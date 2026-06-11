<?php

namespace App\Services;

use App\Models\ReceivingReport;
use App\Models\User;
use App\Services\BarangService;
use App\Notifications\ApprovalRequestedNotification;
use App\Notifications\ApprovalGrantedNotification;
use App\Notifications\ApprovalRejectedNotification;
use App\Notifications\BarangDiterimaNotification;
use Illuminate\Support\Facades\DB;

class ReceivingReportService
{
    public function __construct(protected BarangService $barangService) {}

    public function generateNomor(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = ReceivingReport::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->withTrashed()->count() + 1;
        return "RR/{$year}/{$month}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function create(array $data, array $details): ReceivingReport
    {
        return DB::transaction(function () use ($data, $details) {
            $data['nomor_rr'] = $this->generateNomor();
            $data['penerima_id'] = auth()->id();
            $data['status'] = 'draft';

            $rr = ReceivingReport::create($data);
            foreach ($details as $detail) {
                $rr->details()->create($detail);
            }
            return $rr;
        });
    }

    public function submit(ReceivingReport $rr): void
    {
        $rr->update(['status' => 'menunggu_approval']);
        
        $heads = User::role('head_inventori')->get();
        foreach ($heads as $head) {
            $head->notify(new ApprovalRequestedNotification($rr, 'Receiving Report'));
        }

        $financeUsers = User::role('finance')->get();
        foreach ($financeUsers as $f) {
            $f->notify(new ApprovalRequestedNotification($rr, 'Receiving Report'));
        }
    }

    public function approve(ReceivingReport $rr, ?string $catatan = null): void
    {
        DB::transaction(function () use ($rr, $catatan) {
            $rr->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'catatan' => $catatan,
            ]);

            // Tambah stok berdasarkan qty yang diterima
            foreach ($rr->details as $detail) {
                $this->barangService->tambahStok($detail->barang_id, $detail->qty_diterima);
            }

            // Update PO status to selesai
            if ($rr->purchaseOrder) {
                $rr->purchaseOrder->update(['status' => 'selesai']);
            }

            $rr->penerima->notify(new BarangDiterimaNotification($rr));
        });
    }

    public function reject(ReceivingReport $rr, string $alasan): void
    {
        $rr->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'alasan_penolakan' => $alasan,
        ]);
        $rr->penerima->notify(new ApprovalRejectedNotification($rr, 'Receiving Report', $alasan));
    }
}
