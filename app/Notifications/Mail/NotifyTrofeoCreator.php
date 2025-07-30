<?php

namespace App\Notifications\Mail;

use App\Models\OpenMatch;
use App\Models\Trofeo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyTrofeoCreator extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Trofeo $trofeo,
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
            ->subject('Pendaftaran Trofeo')
            ->view('mails.contents.notify-trofeo-creator', [
                'trofeo' => $this->trofeo,
                'registeredUser' => $this->registeredUser,
            ]);
    }
}
