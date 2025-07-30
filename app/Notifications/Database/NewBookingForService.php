<?php

namespace App\Notifications\Database;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingForService extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking,
    ) {
        //
    }
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Booking Baru',
            'message' => 'Ada booking baru untuk layanan anda'
                . ' pada tanggal ' . $this->booking->start_datetime->format('Y-m-d')
                . ' pukul ' . $this->booking->start_datetime->format('H:i')
                . '. Silahkan cek jadwal di dashboard.',
            'type' => 'info',
            'action_url' => route('dashboard.service-schedule.index'),
        ];
    }
}
