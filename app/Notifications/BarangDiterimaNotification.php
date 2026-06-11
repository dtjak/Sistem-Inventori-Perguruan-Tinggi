<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BarangDiterimaNotification extends Notification
{
    use Queueable;

    public function __construct(public $receivingReport) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'barang_diterima',
            'title' => 'Barang Diterima',
            'message' => "Receiving Report {$this->receivingReport->nomor_rr} telah disetujui. Stok telah diperbarui.",
            'model_id' => $this->receivingReport->id,
        ];
    }
}
