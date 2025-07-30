<?php

namespace App\Notifications\Database;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingForFieldManager extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
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
            'title' => 'Booking Baru',
            'message' => 'Ada booking baru untuk lapangan ' . $this->booking->field->name
                . ' pada tanggal ' . $this->booking->created_at->format('d-m-Y')
                . ' pukul ' . $this->booking->created_at->format('H:i')
                . '. Silahkan cek detail booking di dashboard.',
            'type' => 'info',
            'action_url' => route('dashboard.booking.index'),
        ];
    }
}
