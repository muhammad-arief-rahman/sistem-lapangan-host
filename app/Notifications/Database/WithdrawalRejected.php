<?php

namespace App\Notifications\Database;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalRejected extends Notification
{
    use Queueable;

    public function __construct(
        public Withdrawal $withdrawal,
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Penarikan Ditolak',
            'message' => 'Penarikan Anda telah ditolak pada '
                . now()->translatedFormat('d M Y H:i')
                . '. Alasan: ' . $this->withdrawal->notes,
            'type' => 'error',
            'action_url' => route('dashboard.withdrawal.index'),
        ];
    }
}
