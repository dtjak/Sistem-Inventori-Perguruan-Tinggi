<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\PurchaseOrder;

class POShippedNotification extends Notification
{
    use Queueable;

    public function __construct(public PurchaseOrder $po) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'po_shipped',
            'title' => 'Purchase Order Dikirim',
            'message' => "Supplier telah mengirimkan barang untuk {$this->po->nomor_po} dengan nomor resi {$this->po->nomor_resi}.",
            'model_id' => $this->po->id,
            'url' => route('po.show', $this->po->id),
        ];
    }
}
