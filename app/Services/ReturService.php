<?php

namespace App\Services;

use App\Models\Retur;
use App\Models\User;
use App\Notifications\ApprovalRequestedNotification;
use App\Notifications\ApprovalGrantedNotification;
use App\Notifications\ApprovalRejectedNotification;
use App\Notifications\ReturDisetujuiNotification;
use Illuminate\Support\Facades\DB;

class ReturService
{
    public function generateNomor(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = Retur::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->withTrashed()->count() + 1;
        return "RTR/{$year}/{$month}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function create(array $data, array $details): Retur
    {
        return DB::transaction(function () use ($data, $details) {
            $data['nomor_retur'] = $this->generateNomor();
            $data['dibuat_oleh'] = auth()->id();
            $data['status'] = 'draft';

            $retur = Retur::create($data);
            foreach ($details as $detail) {
                $retur->details()->create($detail);
            }
            return $retur;
        });
    }

    public function submit(Retur $retur): void
    {
        $retur->update(['status' => 'menunggu_approval']);
        $heads = User::role('head_inventori')->get();
        foreach ($heads as $head) {
            $head->notify(new ApprovalRequestedNotification($retur, 'Retur Barang'));
        }
    }

    public function approve(Retur $retur, ?string $catatan = null): void
    {
        $retur->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan' => $catatan,
        ]);

        $retur->dibuatOleh->notify(new ReturDisetujuiNotification($retur));

        // Notify supplier about the return
        $suppliers = User::role('supplier')->get();
        foreach ($suppliers as $sup) {
            $sup->notify(new ApprovalRequestedNotification($retur, 'Retur Barang'));
        }
    }

    public function reject(Retur $retur, string $alasan): void
    {
        $retur->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'alasan_penolakan' => $alasan,
        ]);
        $retur->dibuatOleh->notify(new ApprovalRejectedNotification($retur, 'Retur Barang', $alasan));
    }

    public function selesai(Retur $retur): void
    {
        $retur->update(['status' => 'selesai']);
    }
}
