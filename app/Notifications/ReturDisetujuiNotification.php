<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReturDisetujuiNotification extends Notification
{
    use Queueable;

    public function __construct(public $retur) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'retur_disetujui',
            'title' => 'Retur Barang Disetujui',
            'message' => "Retur {$this->retur->nomor_retur} telah disetujui dan akan diproses ke supplier.",
            'model_id' => $this->retur->id,
        ];
    }
}
