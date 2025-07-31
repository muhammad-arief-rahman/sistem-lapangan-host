<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Models\Payment;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match ($user->role) {
            'super_admin' => $this->admin(),
            'field_manager' => $this->fieldManager(),
            'photographer', 'referee' => $this->service(),
            'community' => $this->community(),
            default => $this->community(),
        };
    }

    private function admin()
    {
        $bookings = Booking::getBookingData();
        $withdrawals = Withdrawal::getWithdrawalData();

        $cardData = (object) [
            'totalUsers' => User::where("role", "!=", "super_admin")->count(),
            'totalFields' => Field::count(),
            'totalPhotographers' => User::where('role', 'photographer')->count(),
            'totalReferees' => User::where('role', 'referee')->count(),
            'totalManagers' => User::where('role', 'field_manager')->count(),

            // Get payment that is still pending for 1 day before the match
            'totalPendingPayments' => Payment::where('status', 'pending')
                ->whereHas('booking.fieldSchedule', function ($query) {
                    $query->where('start_datetime', '>', now()->subDay());
                })->count(),
            'totalIncome' => $bookings->sum('payment.amount_paid'),
            'totalBookings' => $bookings->count(),
            'totalPendingWithdrawals' => $withdrawals->where('status', 'pending')->count(),
            'totalWithdrawals' => $withdrawals->count(),
        ];

        return view('pages.dashboard.index', compact('bookings', 'withdrawals', 'cardData'));
    }

    private function fieldManager()
    {
        $bookings = Booking::getBookingData();

        $cardData = (object) [
            'totalFields' => auth()->user()->fields()->count(),
            'balance' => auth()->user()->balance,
            'totalPendingWithdrawals' => auth()->user()->withdrawals()->where('status', 'pending')->count(),
            'totalBookings' => Booking::whereHas('field', function ($query) {
                $query->where('manager_id', auth()->id());
            })->count(),
        ];

        return view('pages.dashboard.index', compact('cardData', 'bookings'));
    }

    private function service()
    {

        $serviceSchedules = ServiceScheduleController::toCalendar(ServiceScheduleController::getServiceScheduleData());

        $cardData = (object) [
            'totalIncome' => auth()->user()->mutations()->where('amount', '>', 0)->sum('amount'),
            'balance' => auth()->user()->balance,
            'activeSchedules' => auth()->user()->service
                ->schedules()
                ->where('status', 'active')
                ->where(function ($query) {
                    // Check if the schedule is active and the end datetime is in the future
                    $query->where('end_datetime', '>', now())
                        ->orWhere('end_datetime', null);
                })
                ->count(),
            'pendingWithdrawals' => auth()->user()->withdrawals()->where('status', 'pending')->count()
        ];

        return view('pages.dashboard.index', compact('cardData', 'serviceSchedules'));
    }

    private function community()
    {
        $bookings = auth()->user()->bookings()
            ->with('user', 'payment', 'field', 'fieldSchedule', 'bookedServices', 'bookedServices.service', 'bookedServices.service.user')
            ->latest()
            ->get();

        $paymentDetails = auth()->user()->paymentDetails()
            ->with('payment')
            ->latest()
            ->get();

        $cardData = (object) [
            'totalBookings' => $bookings->count(),
            'totalOpenMatches' => $bookings->where('type', 'open_match')->count(),
            'totalTrofeos' => $bookings->where('type', 'trofeo')->count(),
            'totalRegularBookings' => $bookings->where('type', 'regular')->count(),
        ];

        return view('pages.dashboard.index', compact('bookings', 'paymentDetails', 'cardData'));
    }
}
