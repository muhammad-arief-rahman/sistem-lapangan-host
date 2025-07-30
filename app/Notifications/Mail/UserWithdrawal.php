<?php

namespace App\Notifications\Mail;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserWithdrawal extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct(
        public Withdrawal $withdrawal,
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Penarikan Baru')
            ->view('mails.contents.user-withdrawal', [
                    'withdrawal' => $this->withdrawal,
                ]);
    }
}
