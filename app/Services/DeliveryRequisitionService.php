<?php

namespace App\Services;

use App\Models\DeliveryRequisition;
use App\Models\StoreRequisition;
use App\Models\User;
use App\Notifications\ApprovalRequestedNotification;
use App\Notifications\ApprovalGrantedNotification;
use App\Notifications\ApprovalRejectedNotification;
use App\Services\BarangService;
use Illuminate\Support\Facades\DB;

class DeliveryRequisitionService
{
    public function __construct(protected BarangService $barangService) {}

    public function generateNomor(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = DeliveryRequisition::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->withTrashed()->count() + 1;
        return "DR/{$year}/{$month}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function create(StoreRequisition $sr, array $data, array $details): DeliveryRequisition
    {
        return DB::transaction(function () use ($sr, $data, $details) {
            $data['nomor_dr'] = $this->generateNomor();
            $data['store_requisition_id'] = $sr->id;
            $data['dibuat_oleh'] = auth()->id();
            $data['status'] = 'draft';

            $dr = DeliveryRequisition::create($data);
            foreach ($details as $detail) {
                $dr->details()->create($detail);
            }

            return $dr;
        });
    }

    public function submit(DeliveryRequisition $dr): void
    {
        $dr->update(['status' => 'menunggu_approval']);

        $headInventori = User::role('head_inventori')->get();
        foreach ($headInventori as $head) {
            $head->notify(new ApprovalRequestedNotification($dr, 'Delivery Requisition'));
        }
    }

    public function approve(DeliveryRequisition $dr, ?string $catatan = null): void
    {
        DB::transaction(function () use ($dr, $catatan) {
            $dr->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'catatan' => $catatan,
            ]);

            // Kurangi stok untuk setiap item
            foreach ($dr->details as $detail) {
                $this->barangService->kurangiStok($detail->barang_id, $detail->qty_distribusi);
            }

            $dr->dibuatOleh->notify(new ApprovalGrantedNotification($dr, 'Delivery Requisition'));
        });
    }

    public function reject(DeliveryRequisition $dr, string $alasan): void
    {
        $dr->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'alasan_penolakan' => $alasan,
        ]);

        $dr->dibuatOleh->notify(new ApprovalRejectedNotification($dr, 'Delivery Requisition', $alasan));
    }

    public function selesai(DeliveryRequisition $dr): void
    {
        $dr->update(['status' => 'selesai']);
    }
}
