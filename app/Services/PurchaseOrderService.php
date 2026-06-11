<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\User;
use App\Notifications\ApprovalRequestedNotification;
use App\Notifications\ApprovalGrantedNotification;
use App\Notifications\ApprovalRejectedNotification;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    public function generateNomor(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = PurchaseOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->withTrashed()->count() + 1;
        return "PO/{$year}/{$month}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function create(array $data, array $details): PurchaseOrder
    {
        return DB::transaction(function () use ($data, $details) {
            $data['nomor_po'] = $this->generateNomor();
            $data['dibuat_oleh'] = auth()->id();
            $data['status'] = 'draft';
            $data['total'] = collect($details)->sum(fn($d) => $d['qty'] * $d['harga']);

            $po = PurchaseOrder::create($data);
            foreach ($details as $detail) {
                $detail['subtotal'] = $detail['qty'] * $detail['harga'];
                $po->details()->create($detail);
            }
            return $po;
        });
    }

    public function submit(PurchaseOrder $po): void
    {
        $po->update(['status' => 'menunggu_head_purchasing']);
        $heads = User::role('head_purchasing')->get();
        foreach ($heads as $head) {
            $head->notify(new ApprovalRequestedNotification($po, 'Purchase Order'));
        }
    }

    public function approveHead(PurchaseOrder $po, ?string $catatan = null): void
    {
        $po->update([
            'status' => 'menunggu_finance',
            'approved_head_purchasing' => auth()->id(),
            'approved_head_at' => now(),
            'catatan' => $catatan,
        ]);

        $finance = User::role('finance')->get();
        foreach ($finance as $f) {
            $f->notify(new ApprovalRequestedNotification($po, 'Purchase Order (Verifikasi Anggaran)'));
        }
    }

    public function approveFinance(PurchaseOrder $po, ?string $catatan = null): void
    {
        $po->update([
            'status' => 'approved',
            'approved_finance' => auth()->id(),
            'approved_finance_at' => now(),
        ]);

        $po->dibuatOleh->notify(new ApprovalGrantedNotification($po, 'Purchase Order'));
    }

    public function reject(PurchaseOrder $po, string $alasan): void
    {
        $po->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $alasan,
        ]);

        $po->dibuatOleh->notify(new ApprovalRejectedNotification($po, 'Purchase Order', $alasan));
    }
}
