<?php

namespace App\Services;

use App\Models\StoreRequisition;
use App\Models\User;
use App\Notifications\ApprovalRequestedNotification;
use App\Notifications\ApprovalGrantedNotification;
use App\Notifications\ApprovalRejectedNotification;
use Illuminate\Support\Facades\DB;

class StoreRequisitionService
{
    public function generateNomor(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = StoreRequisition::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->withTrashed()->count() + 1;
        return "SR/{$year}/{$month}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function create(array $data, array $details): StoreRequisition
    {
        return DB::transaction(function () use ($data, $details) {
            $data['nomor_sr'] = $this->generateNomor();
            $data['status'] = 'draft';
            $data['pemohon_id'] = auth()->id();

            $sr = StoreRequisition::create($data);

            foreach ($details as $detail) {
                $sr->details()->create($detail);
            }

            return $sr;
        });
    }

    public function update(StoreRequisition $sr, array $data, array $details): StoreRequisition
    {
        return DB::transaction(function () use ($sr, $data, $details) {
            $sr->update($data);
            $sr->details()->delete();
            foreach ($details as $detail) {
                $sr->details()->create($detail);
            }
            return $sr->fresh();
        });
    }

    public function submit(StoreRequisition $sr): void
    {
        $sr->update(['status' => 'menunggu_approval']);

        // Notify head unit
        $headUnits = User::role('head_unit')->get();
        foreach ($headUnits as $head) {
            $head->notify(new ApprovalRequestedNotification($sr, 'Store Requisition'));
        }
    }

    public function approve(StoreRequisition $sr, ?string $catatan = null): void
    {
        $sr->update([
            'status' => 'disetujui',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan' => $catatan,
        ]);

        $sr->pemohon->notify(new ApprovalGrantedNotification($sr, 'Store Requisition'));

        // Notify staff inventori
        $staffInventori = User::role('staff_inventori')->get();
        foreach ($staffInventori as $staff) {
            $staff->notify(new ApprovalGrantedNotification($sr, 'Store Requisition (perlu diproses)'));
        }
    }

    public function reject(StoreRequisition $sr, string $alasan): void
    {
        $sr->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'alasan_penolakan' => $alasan,
        ]);

        $sr->pemohon->notify(new ApprovalRejectedNotification($sr, 'Store Requisition', $alasan));
    }

    public function revisi(StoreRequisition $sr, string $catatan): void
    {
        $sr->update([
            'status' => 'revisi',
            'catatan' => $catatan,
        ]);
    }
}
