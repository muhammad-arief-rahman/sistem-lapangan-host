<?php

namespace App\Notifications\Database;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserWithdrawal extends Notification
{
    use Queueable;
    public function __construct(
        public Withdrawal $withdrawal,
    )
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Penarikan Baru',
            'message' => 'Pengguna "' . ($this->withdrawal->user->name ?? $this->withdrawal->user->email)
            . '" telah membuat penarikan baru sebesar ' . format_rp($this->withdrawal->amount)
            . '. Silakan verifikasi.',
            'type' => 'info',
            'action_url' => route('dashboard.withdrawal.index'),
        ];
    }
}
