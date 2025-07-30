<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::getBookingData();

        $cardData = (object) [
            'totalBookings' => $bookings->count(),
            'totalOpenMatches' => $bookings->where('type', 'open_match')->count(),
            'totalTrofeos' => $bookings->where('type', 'trofeo')->count(),
            'totalRegularBookings' => $bookings->where('type', 'regular')->count(),
        ];

        return view('pages.dashboard.booking.index', compact('bookings', 'cardData'));
    }

}
