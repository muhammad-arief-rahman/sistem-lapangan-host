<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PaymentCheckingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payment-checking-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan pembayaran yang belum dibayar 1 jam sebelum waktu pertandingan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get bookings with pending payments that are scheduled to start within the next hour
        $bookings = Booking::whereHas('payment', function ($query) {
            $query->where('status', 'pending');
        })->whereHas('fieldSchedule', function ($query) {
            $query->where('start_datetime', '<=', now()->addHour());
        })->get();

        DB::beginTransaction();

        try {
            $bookings->each(function ($booking) {
                $payment = $booking->payment;

                if ($payment) {
                    $payment->update([
                        'status' => 'failed',
                    ]);

                    // Reimburs the payment back to the user if partially paid
                    $paymentDetails = $payment->paymentDetails()->where('status', 'completed')->get();

                    $paymentDetails->each(function ($detail) use ($booking) {
                        // Create mutation to the user for the amount paid
                        $detail->user->mutations()->create([
                            'source' => 'Pengembalian Pembayaran',
                            'amount' => $detail->amount_paid,
                            'description' => 'Pengembalian pembayaran untuk booking #' . $booking->id,
                        ]);
                    });

                    // Fail all pending payment details
                    $payment->paymentDetails()->where('status', 'pending')->update(['status' => 'failed']);

                    $booking->update([
                        'status' => 'cancelled',
                    ]);
                }
            });

            $totalCancelled = $bookings->count();

            $this->info("$totalCancelled Pembayaran yang belum dibayar telah dibatalkan.");

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
