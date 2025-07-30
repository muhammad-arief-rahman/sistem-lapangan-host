<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function getFieldAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
            'field_id' => 'required|exists:fields,id',
        ], [
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'time.required' => 'Waktu harus diisi.',
            'time.date_format' => 'Format waktu tidak valid. Gunakan format H:i.',
            'duration.required' => 'Durasi harus diisi.',
            'duration.integer' => 'Durasi harus berupa angka.',
            'duration.min' => 'Durasi minimal 1 jam.',
            'field_id.required' => 'ID lapangan harus diisi.',
            'field_id.exists' => 'Lapangan tidak ditemukan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Logic to get available fields based on date, time, and duration
        $start_datetime = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time));
        $end_datetime = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time) + ($request->duration * 3600));

        // Get all fields that do not have schedules overlapping with the requested date and time
        $isAvailable = Field::where('id', $request->field_id)
            ->whereDoesntHave('schedules', function ($query) use ($start_datetime, $end_datetime) {
                $query->where(function ($q) use ($start_datetime, $end_datetime) {
                    $q->where('start_datetime', '<', $end_datetime);
                    $q->where('end_datetime', '>', $start_datetime);
                });
            })
            ->exists();

        if (!$isAvailable) {
            return response()->json([
                'code' => 404,
                'message' => 'Lapangan tidak tersedia pada waktu yang dipilih.',
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Fields available successfully retrieved.',
            'data' => [
                'field_id' => $request->field_id,
                'date' => $request->date,
                'time' => $request->time,
                'duration' => $request->duration,
            ],
        ], 200);
    }

    public function getAvailableServices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
        ], [
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'time.required' => 'Waktu harus diisi.',
            'time.date_format' => 'Format waktu tidak valid. Gunakan format H:i.',
            'duration.required' => 'Durasi harus diisi.',
            'duration.integer' => 'Durasi harus berupa angka.',
            'duration.min' => 'Durasi minimal 1 jam.',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $start_datetime = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time));
        $end_datetime = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time) + ($request->duration * 3600));

        // Get all services that do not have schedules overlapping with the requested date and time
        $services = Service::with('user')->whereDoesntHave('schedules', function ($query) use ($start_datetime, $end_datetime) {
            $query->where(function ($q) use ($start_datetime, $end_datetime) {
                $q->where('start_datetime', '<', $end_datetime);
                $q->where('end_datetime', '>', $start_datetime);
            });
        })->get();


        // Group into photographers and referees
        $services = $services->groupBy(function ($service) {
            return $service->user->role; // Assuming 'role' is a field in the User model
        });

        if ($request->has('html')) {
            // If the request has 'html', return the services as HTML
            $html = view('partials.booking.service-list', compact('services'))->render();

            return response()->json([
                'code' => 200,
                'message' => 'Jasa yang tersedia berhasil diambil.',
                'data' => $html,
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Jasa yang tersedia berhasil diambil.',
            'data' => $services,
        ], 200);
    }
}
