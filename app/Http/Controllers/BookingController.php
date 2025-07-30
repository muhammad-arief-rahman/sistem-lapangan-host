<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function show($field_id)
    {
        if (!$field = Field::find($field_id)) {
            return redirect()->route('home')->with('toast', 'Lapangan tidak ditemukan!');
        }

        $fieldSchedules = $field->schedules()->get();

        // Format the schedules to a more usable format
        $schedules = [];

        foreach ($fieldSchedules as $fieldSchedule) {
            $startTime = Carbon::parse($fieldSchedule->start_datetime);
            $endTime = Carbon::parse($fieldSchedule->end_datetime);

            $startDate = $startTime->format('Y-m-d');
            $endDate = $endTime->format('Y-m-d');

            $schedules[] = [
                'title' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i'),
                'start' => $startDate,
                'end' => $endDate,
                'status' => $fieldSchedule->status,
            ];
        }

        // Get all available services and doesn't overlap with the field's schedules

        return view('pages.landing.booking.show', compact('field', 'schedules'));
    }

    public function store(Request $request, $field_id)
    {
        if (!$field = Field::find($field_id)) {
            return redirect()->route('home')->with('toast', 'Lapangan tidak ditemukan!');
        }

        $rules = [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
            'type' => 'required|in:trofeo,open_match,regular',
            'referee_id' => 'nullable|exists:services,id',
            'photographer_id' => 'nullable|exists:services,id',
        ];

        if (in_array($type = $request->type, ['trofeo', 'open_match'])) {
            $rules['title'] = 'required|string|max:255';
            $rules['description'] = 'nullable|string|max:1000';
        }

        $request->validate($rules, [
            'date.required' => 'Tanggal booking harus diisi.',
            'time.required' => 'Waktu booking harus diisi.',
            'duration.required' => 'Durasi booking harus diisi.',
            'type.required' => 'Jenis booking harus dipilih.',
            'referee_id.exists' => 'Referee yang dipilih tidak valid.',
            'photographer_id.exists' => 'Fotografer yang dipilih tidak valid.',
            'title.required_if' => 'Judul pertandingan harus diisi untuk jenis booking trofeo atau open match.',
            'description.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
            'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'date.date' => 'Format tanggal tidak valid.',
            'time.date_format' => 'Format waktu harus dalam format HH:MM.',
            'duration.integer' => 'Durasi harus berupa angka.',
            'duration.min' => 'Durasi minimal adalah 1 jam.',
            'type.in' => 'Jenis booking tidak valid. Pilih antara trofeo, open match, atau regular.',
        ]);

        // Get the start and end time
        $startDateTime = Carbon::parse($request->date . ' ' . $request->time);
        $endDateTime = $startDateTime->copy()->addHours((int) $request->duration);

        DB::beginTransaction();

        try {
            //  Check if the field is available for the selected date and time
            $field = Field::lockForUpdate()->findOrFail($field_id);

            $isAvailable = !Field::where('id', $request->field_id)
                ->whereDoesntHave('schedules', function ($query) use ($startDateTime, $endDateTime) {
                    $query->where(function ($q) use ($startDateTime, $endDateTime) {
                        $q->where('start_datetime', '<', $endDateTime);
                        $q->where('end_datetime', '>', $startDateTime);
                    });
                })
                ->exists();

            if (!$isAvailable) {
                DB::rollBack();
                return redirect()->back()->with('toast', 'Lapangan tidak tersedia pada waktu yang dipilih!');
            }

            // Create the schedule
            $fieldSchedule = $field->schedules()->create([
                'date' => $request->date,
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'duration' => $request->duration,
                'status' => 'inactive',
            ]);

            // Create the booking
            $booking = auth()->user()->bookings()->create([
                'field_id' => $field->id,
                'field_schedule_id' => $fieldSchedule->id,
                'type' => $request->type,
            ]);

            // Initialize the services array
            $services = [];

            // Create the referee schedule
            if ($request->referee_id)
                $services[] = Service::find($request->referee_id);

            // Create the photographer schedule
            if ($request->photographer_id)
                $services[] = Service::find($request->photographer_id);

            $services = collect($services);

            // Create the service schedule for the booking
            $services->each(function ($service) use ($booking, $startDateTime, $endDateTime) {
                $serviceSchedule = $service->schedules()->create([
                    'start_datetime' => $startDateTime,
                    'end_datetime' => $endDateTime,
                    'booking_id' => $booking->id,
                    'status' => 'inactive', // Assuming the service schedule starts inactive
                ]);

                // Attach the services to the booking
                $booking->bookedServices()->create([
                    'service_id' => $service->id,
                    'service_schedule_id' => $serviceSchedule->id,
                    'price' => $service->price_per_hour * request()->duration, // Assuming the service has a price_per_hour attribute
                ]);
            });

            // Total the price of the services
            $totalServicePrice = $services->sum(function ($service) use ($request) {
                return $service->price_per_hour * $request->duration;
            });

            // Total the price of the field
            $totalFieldPrice = $field->price_per_hour * $request->duration;
            $totalOrderPrice = $totalServicePrice + $totalFieldPrice;

            // Handle payment creation
            $payment = $booking->payment()->create([
                'total_amount' => $totalOrderPrice,
                'total_field_price' => $totalFieldPrice,
            ]);

            // Split the payment between all associated users
            if ($request->type === 'open_match') {
                $perPersonAmount = $payment->total_amount / 2;
            } else if ($request->type === 'trofeo') {
                $perPersonAmount = $payment->total_amount / 3;
            } else {
                $perPersonAmount = $payment->total_amount;
            }

            // Create payment details for the user booking the field
            $paymentDetails = $payment->paymentDetails()->create([
                'user_id' => auth()->user()->id,
                'amount' => $perPersonAmount,
            ]);

            // Create trofeo or open match if applicable
            if ($request->type === 'open_match') {
                $openMatch = $booking->openMatch()->create([
                    'match_name' => $request->title,
                    'description' => $request->description,
                    'registration_fee' => $perPersonAmount,
                ]);
            } else if ($request->type === 'trofeo') {
                $trofeo = $booking->trofeo()->create([
                    'match_name' => $request->title,
                    'description' => $request->description,
                    'registration_fee' => $perPersonAmount,
                ]);
            }

            // * Notifications

            // Create notification for the user
            auth()->user()->notify(new \App\Notifications\Mail\BookingCreated($booking));
            auth()->user()->notify(new \App\Notifications\Database\BookingCreated($booking));

            // Notify the field manager
            $field->manager->notify(new \App\Notifications\Database\NewBookingForFieldManager($booking));
            $field->manager->notify(new \App\Notifications\Mail\NewBookingForFieldManager($booking));

            // Notify the services
            $services->each(function ($service) use ($booking) {
                $service->user->notify(new \App\Notifications\Database\NewBookingForService($booking));
                $service->user->notify(new \App\Notifications\Mail\NewBookingForService($booking));
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', 'Terjadi kesalahan saat membuat booking: ' . $e->getMessage());
        }

        // dd($request->all(), $fieldSchedule, $booking, $payment, $paymentDetails);
        return redirect()->route('dashboard.payment.index')->with('alert', [
            'title' => 'Berhasil!',
            'message' => 'Booking berhasil dibuat, silahkan lakukan pembayaran untuk mengkonfirmasi booking anda.',
            'type' => 'success',
        ]);
    }
}
