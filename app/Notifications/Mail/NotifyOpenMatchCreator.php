<?php

namespace App\Notifications\Mail;

use App\Models\OpenMatch;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyOpenMatchCreator extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public OpenMatch $openMatch,
        public User $registeredUser
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
            ->subject('Pendaftaran Open Match')
            ->view('mails.contents.notify-open-match-creator', [
                'openMatch' => $this->openMatch,
                'registeredUser' => $this->registeredUser,
            ]);
    }
}
