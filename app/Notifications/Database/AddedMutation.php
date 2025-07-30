<?php

namespace App\Notifications\Database;

use App\Models\Mutation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddedMutation extends Notification
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
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Saldo ' . ucfirst($this->action),
            'message' => 'Saldo anda telah ' . $this->action
                . ' sebesar Rp ' . number_format(abs($this->mutation->amount))
                . ' untuk ' . $this->mutation->source . '.',
            'type' => $this->type,
        ];
    }
}
