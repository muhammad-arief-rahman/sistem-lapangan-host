<?php

namespace App\Notifications\Database;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalApproved extends Notification
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
            'title' => 'Penarikan Selesai',
            'message' => 'Penarikan Anda telah selesai diproses pada '
                . now()->translatedFormat('d M Y H:i'),
            'type' => 'success',
            'action_url' => route('dashboard.withdrawal.index'),
        ];
    }
}
