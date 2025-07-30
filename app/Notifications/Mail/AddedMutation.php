<?php

namespace App\Notifications\Mail;

use App\Models\Mutation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddedMutation extends Notification implements ShouldQueue
{
    use Queueable;


    public function __construct(
        public Mutation $mutation,
        public string $action,
        public string $type,
    )
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Perubahan Saldo')
            ->view('mails.contents.added-mutation', [
                'mutation' => $this->mutation,
                'action' => $this->action,
                'type' => $this->type,
            ]);
    }
}
