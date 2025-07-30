<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = PaymentDetail::with('payment');

        if ($user->role !== 'super_admin') {
            $query->where('user_id', $user->id);
        }

        $paymentDetails = $query->latest()->get();

        $cardData = (object) [
            'totalPayments' => $paymentDetails->count(),
            'totalPendingPayments' => $paymentDetails->where('status', 'pending')->count(),
            'totalCompletedPayments' => $paymentDetails->where('status', 'completed')->count(),
            'totalFailedPayments' => $paymentDetails->where('status', 'failed')->count(),
        ];

        return view('pages.dashboard.payment.index', compact('paymentDetails', 'cardData'));
    }

    public function show($id)
    {
        $paymentDetail = PaymentDetail::with('payment', 'user')->findOrFail($id);

        if ($paymentDetail->status === "pending") {
            $snapId = $this->encodeOrderId($paymentDetail->id);

            $snapRequestBody = [
                'transaction_details' => [
                    'order_id' => $snapId,
                    'gross_amount' => $paymentDetail->amount,
                ],
                'customer_details' => [
                    'first_name' => $paymentDetail->user->name,
                    'email' => $paymentDetail->user->email,
                    'phone' => $paymentDetail->user->phone,
                ],
                'item_details' => [
                    [
                        "id" => $paymentDetail->payment->booking_id,
                        "price" => $paymentDetail->amount,
                        "quantity" => 1,
                        "name" => "Pembayaran #" . $paymentDetail->payment->booking_id
                    ]
                ]
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($snapRequestBody);

            } catch (\Exception $e) {

                return redirect()->route('dashboard.payment.index')->with(['toast' => 'Gagal memuat pembayaran, silakan coba lagi.']);
            }

            return view('pages.dashboard.payment.show', compact('paymentDetail', 'snapToken'));
        }

        return view('pages.dashboard.payment.show', compact('paymentDetail'));
    }

    public function callback(Request $request)
    {
        $transactionStatus = $request->transaction_status;
        $paymentDetailId = $this->decodeOrderId($request->order_id);

        if (!$paymentDetailId) {
            return redirect()->route('dashboard.payment.index')->with(['toast' => 'Invalid order ID format.']);
        }

        $paymentDetail = PaymentDetail::with('payment', 'payment.booking', 'payment.booking.bookedServices', 'payment.booking.fieldSchedule')
            ->findOrFail($paymentDetailId);

        if ($paymentDetail->status !== 'pending') {
            return redirect()->route('dashboard.payment.index')->with(['toast' => 'Pembayaran sudah diproses sebelumnya.']);
        }

        if ($transactionStatus === "expire") {
            return redirect()->route('dashboard.payment.show', $paymentDetail->id)->with('toast', 'Pembayaran telah kadaluarsa, silakan coba lagi.');
        } else if ($transactionStatus === "settlement") {
            DB::beginTransaction();

            try {
                $totalAmount = $paymentDetail->payment->total_amount;

                // Update payment detail
                $paymentDetail->update([
                    'status' => 'completed',
                    'amount_paid' => $paymentDetail->amount,
                ]);

                $totalAmountPaid = $paymentDetail->payment->amount_paid + $paymentDetail->amount;
                $updatedPaymentStatus = $totalAmountPaid >= $totalAmount ? 'completed' : 'partial';

                // Update payment amount
                $paymentDetail->payment->update([
                    'amount_paid' => $totalAmountPaid,
                    'status' => $updatedPaymentStatus,
                ]);


                // Update booking status if payment is completed
                if ($updatedPaymentStatus === 'completed') {
                    $paymentDetail->payment->booking->update(['status' => 'confirmed']);

                    // Disemburse to services and field owner
                    foreach ($paymentDetail->payment->booking->bookedServices as $bookedService) {
                        $bookedService->service->user->mutations()->create([
                            'source' => 'Pembayaran Booking',
                            'amount' => $bookedService->price,
                            'description' => 'Pembayaran untuk layanan ' . $bookedService->service->name . ' pada booking #' . $paymentDetail->payment->booking_id,
                        ]);
                    }

                    // Disemburse to field owner
                    $paymentDetail->payment->booking->field->manager->mutations()->create([
                        'source' => 'Pembayaran Booking',
                        'amount' => $paymentDetail->payment->total_field_price,
                        'description' => 'Pembayaran untuk lapangan pada booking #' . $paymentDetail->payment->booking_id,
                    ]);


                    // Active the field schedule
                    $fieldSchedule = $paymentDetail->payment->booking->fieldSchedule;
                    $fieldSchedule->update(['status' => 'active']);

                    // Activate the booked services
                    foreach ($paymentDetail->payment->booking->bookedServices as $bookedService) {
                        $bookedService->serviceSchedule()->update(['status' => 'active']);

                        // Notify the service
                        $bookedService->service->user->notify(new \App\Notifications\Database\BookedServiceConfirmed($paymentDetail));
                        $bookedService->service->user->notify(new \App\Notifications\Mail\BookedServiceConfirmed($paymentDetail));
                    }
                }

                DB::commit();

                if ($updatedPaymentStatus === 'partial') {
                    return redirect()->route('dashboard.payment.show', $paymentDetail->id)->with(['toast' => 'Pembayaran anda berhasil, silahkan tunggu pembayaran oleh tim lain.']);
                }

                return redirect()->route('dashboard.payment.show', $paymentDetail->id)->with(['toast' => 'Pembayaran berhasil!']);
            } catch (\Exception $e) {
                dd($e->getMessage());

                DB::rollBack();
                return redirect()->route('dashboard.payment.index')->with(['toast' => 'Pembayaran gagal, silakan coba lagi.']);
            }
        }

        // Fallback
        return redirect()->route('dashboard.payment.index')->with(['toast' => 'Pembayaran gagal, silakan coba lagi.']);
    }



    // Encode as 16 character base64 string
    private function encodeOrderId($id)
    {
        return base64_encode(sprintf('%016d', $id)) . '_' . Str::random(8);
    }

    private function decodeOrderId($encodedId)
    {
        $parts = explode('_', $encodedId);
        if (count($parts) !== 2) {
            return null;
        }

        $decoded = base64_decode($parts[0]);
        if ($decoded === false || strlen($decoded) !== 16) {
            return null;
        }

        return (int) $decoded;
    }
}
