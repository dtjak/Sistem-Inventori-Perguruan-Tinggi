<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApprovalRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $model,
        public string $type,
        public string $alasan
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'approval_rejected',
            'document_type' => $this->type,
            'title' => "Ditolak: {$this->type}",
            'message' => "{$this->type} Anda ditolak. Alasan: {$this->alasan}",
            'model_id' => $this->model->id,
        ];
    }
}
