<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ServiceSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceScheduleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('view') === 'table') {
            $schedules = self::getServiceScheduleData();
        } else {
            $schedules = self::toCalendar(self::getServiceScheduleData());
        }

        $cardData = (object) [
            'totalSchedules' => $schedules->count(),
            'totalActiveSchedules' => $schedules->where('status', 'active')->count(),
            'totalInactiveSchedules' => $schedules->where('status', 'inactive')->count(),
        ];

        return view('pages.dashboard.service-schedule.index', compact('schedules', 'cardData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
        ]);

        // Parse start and end datetime
        $startDatetime = Carbon::parse($request->tanggal . ' ' . $request->jam);
        $endDatetime = $startDatetime->copy()->addHours((int) $request->duration);

        auth()->user()->service->schedules()->create([
            'start_datetime' => $startDatetime,
            'end_datetime' => $endDatetime,
        ]);

        return redirect()->route('dashboard.service-schedule.index')
            ->with('toast', 'Jadwal layanan berhasil dibuat.');
    }

    public static function getServiceScheduleData()
    {
        $user = auth()->user();
        $scheduleQuery = ServiceSchedule::with('service', 'service.user');

        if ($user->role !== 'super_admin') {
            // If the user is not a super admin, filter schedules by the user's service
            $scheduleQuery->whereHas('service', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $schedules = $scheduleQuery->orderBy('start_datetime', 'asc')
            ->get();

        // Format the schedules to a more usable format
        return $schedules;
    }

    public static function toCalendar($schedules)
    {
        $user = auth()->user();

        return $schedules->map(function ($schedule) use ($user) {
            return [
                'title' => ($user->role === "super_admin" ? $schedule->service->user->name . ', ' : "") . Carbon::parse($schedule->start_datetime)->format('H:i') . ' - ' . Carbon::parse($schedule->end_datetime)->format('H:i'),
                // Format as Y-m-d
                'start' => Carbon::parse($schedule->start_datetime)->format('Y-m-d'),
                'end' => Carbon::parse($schedule->end_datetime)->format('Y-m-d'),
                'status' => $schedule->status,
            ];
        });
    }
}
