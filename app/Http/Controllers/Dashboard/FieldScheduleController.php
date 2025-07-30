<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldScheduleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'duration' => 'required|integer',
        ], [
            'field_id.required' => 'Lapangan harus dipilih.',
            'field_id.exists' => 'Lapangan yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'jam.required' => 'Jam harus diisi.',
            'jam.date_format' => 'Format jam tidak valid. Gunakan format HH:MM.',
            'duration.required' => 'Durasi harus diisi.',
            'duration.integer' => 'Durasi harus berupa angka.',
        ]);

        $startDatetime = \Carbon\Carbon::parse($validated['tanggal'] . ' ' . $validated['jam']);
        $endDatetime = $startDatetime->copy()->addHours((int) $validated['duration']);

        $field = Field::where('id', $validated['field_id'])->first();

        // Check if the schedule overlaps with existing schedules
        $isOverlapping = !Field::where('id', $request->field_id)
            ->whereDoesntHave('schedules', function ($query) use ($startDatetime, $endDatetime) {
                $query->where(function ($q) use ($startDatetime, $endDatetime) {
                    $q->where('start_datetime', '<', $endDatetime);
                    $q->where('end_datetime', '>', $startDatetime);
                });
            })
            ->exists();

        if ($isOverlapping) {
            return redirect()->route('dashboard.field.index')->with('toast', 'Jadwal lapangan ini sudah ada pada waktu yang dipilih.');
        }

        $field->schedules()->create([
            'start_datetime' => $startDatetime,
            'end_datetime' => $endDatetime,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard.field.index')->with('toast', 'Jadwal lapangan berhasil dibuat.');
    }
}
