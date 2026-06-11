<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApprovalRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $model,
        public string $type
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'approval_requested',
            'document_type' => $this->type,
            'title' => "Permintaan Approval: {$this->type}",
            'message' => "Ada {$this->type} baru yang memerlukan persetujuan Anda.",
            'model_id' => $this->model->id,
            'url' => $this->getUrl(),
        ];
    }

    private function getUrl(): string
    {
        return match (true) {
            $this->model instanceof \App\Models\StoreRequisition => route('sr.show', $this->model->id),
            $this->model instanceof \App\Models\DeliveryRequisition => route('dr.show', $this->model->id),
            $this->model instanceof \App\Models\PurchaseRequisition => route('pr.show', $this->model->id),
            $this->model instanceof \App\Models\PurchaseOrder => route('po.show', $this->model->id),
            $this->model instanceof \App\Models\ReceivingReport => route('rr.show', $this->model->id),
            $this->model instanceof \App\Models\Retur => route('retur.show', $this->model->id),
            default => '/',
        };
    }
}
