<?php

namespace App\Services;

use App\Models\PurchaseRequisition;
use App\Models\User;
use App\Notifications\ApprovalRequestedNotification;
use App\Notifications\ApprovalGrantedNotification;
use App\Notifications\ApprovalRejectedNotification;
use Illuminate\Support\Facades\DB;

class PurchaseRequisitionService
{
    public function generateNomor(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = PurchaseRequisition::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->withTrashed()->count() + 1;
        return "PR/{$year}/{$month}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function create(array $data, array $details): PurchaseRequisition
    {
        return DB::transaction(function () use ($data, $details) {
            $data['nomor_pr'] = $this->generateNomor();
            $data['dibuat_oleh'] = auth()->id();
            $data['status'] = 'draft';

            $pr = PurchaseRequisition::create($data);
            foreach ($details as $detail) {
                $pr->details()->create($detail);
            }
            return $pr;
        });
    }

    public function update(PurchaseRequisition $pr, array $data, array $details): PurchaseRequisition
    {
        return DB::transaction(function () use ($pr, $data, $details) {
            $pr->update($data);
            $pr->details()->delete();
            foreach ($details as $detail) {
                $pr->details()->create($detail);
            }
            return $pr->fresh();
        });
    }

    public function submit(PurchaseRequisition $pr): void
    {
        $pr->update(['status' => 'menunggu_approval']);
        $heads = User::role('head_inventori')->get();
        foreach ($heads as $head) {
            $head->notify(new ApprovalRequestedNotification($pr, 'Purchase Requisition'));
        }
    }

    public function approve(PurchaseRequisition $pr, ?string $catatan = null): void
    {
        $pr->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan' => $catatan,
        ]);

        $pr->dibuatOleh->notify(new ApprovalGrantedNotification($pr, 'Purchase Requisition'));

        // Notify staff purchasing
        $staffPurchasing = User::role('staff_purchasing')->get();
        foreach ($staffPurchasing as $staff) {
            $staff->notify(new ApprovalGrantedNotification($pr, 'Purchase Requisition (PR baru)'));
        }
    }

    public function reject(PurchaseRequisition $pr, string $alasan): void
    {
        $pr->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'alasan_penolakan' => $alasan,
        ]);
        $pr->dibuatOleh->notify(new ApprovalRejectedNotification($pr, 'Purchase Requisition', $alasan));
    }
}
