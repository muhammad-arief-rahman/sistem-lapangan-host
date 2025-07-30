<?php

namespace App\Notifications\Database;

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
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pendaftaran Trofeo',
            'message' => "Komunitas '" . $this->registeredUser->name
                . "' mendaftar untuk trofeo dengan judul pertandingan '" . $this->trofeo->match_name
                . "' yang telah anda buat.",
            'action_url' => route('dashboard.events.index'),
        ];
    }
}
