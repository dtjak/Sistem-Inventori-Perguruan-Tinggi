<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApprovalGrantedNotification extends Notification
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
            'type' => 'approval_granted',
            'document_type' => $this->type,
            'title' => "Disetujui: {$this->type}",
            'message' => "{$this->type} Anda telah disetujui.",
            'model_id' => $this->model->id,
        ];
    }
}
