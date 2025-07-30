<?php

namespace App\Notifications\Database;

use App\Models\PaymentDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookedServiceConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PaymentDetail $paymentDetail
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
            'title' => 'Booking Dikonfirmasi',
            'message' => 'Booking #' . $this->paymentDetail->payment->booking_id
                . ' oleh ' . $this->paymentDetail->payment->booking->user->name
                . ' telah dikonfirmasi. Silakan cek jadwal anda.',
            'type' => 'info',
            'action_url' => route('dashboard.service-schedule.index'),
        ];
    }
}
