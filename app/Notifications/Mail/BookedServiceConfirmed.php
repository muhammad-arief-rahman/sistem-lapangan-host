<?php

namespace App\Notifications\Mail;

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
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Dikonfirmasi')
            ->view('mails.contents.booked-service-confirmed', [
                'paymentDetail' => $this->paymentDetail,
            ]);

    }
}
