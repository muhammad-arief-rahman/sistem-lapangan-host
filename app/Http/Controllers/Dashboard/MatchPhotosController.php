<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MatchPhoto;
use Illuminate\Http\Request;

class MatchPhotosController extends Controller
{
    public function index(Request $request)
    {
        $bookingQuery = Booking::where('status', 'confirmed');

        // Get bookings where the schedule has finished
        $bookingQuery->whereHas('fieldSchedule', function ($query) use ($request) {
            // TESTING: use params for easier testing
            if (!$request->has('skip-finished')) {
                $query->where('end_datetime', '<=', now());
            }
        });

        if (auth()->user()->role === "photographer") {
            // If the user is a photographer, check if they are the photographer for the booking
            $bookingQuery->whereHas('bookedServices', function ($query) {
                $query->where('service_id', auth()->user()->service->id);
            });
        } else if (auth()->user()->role === 'community') {
            // If the user is a community, check if they are the ones who booked the match
            $bookingQuery->where('user_id', auth()->id());
        }

        $bookings = $bookingQuery->latest()->get();

        $cardData = (object) [
            'totalMatches' => $bookings->count(),
        ];

        return view('pages.dashboard.match-photos.index', compact('cardData', 'bookings'));
    }

    public function show(Request $request, $id)
    {
        $booking = Booking::with('photos')->findOrFail($id);

        return view('pages.dashboard.match-photos.show', compact('booking'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each photo
        ]);

        $booking = Booking::findOrFail($id);

        $photos = $request->file('photos');

        foreach ($photos as $photo) {
            $imagePath = store_image($photo, 'images/match-photos');

            $booking->photos()->create([
                'path' => $imagePath,
            ]);
        }

        return redirect()->route('dashboard.match-photos.show', $id)
            ->with('toast', 'Foto pertandingan berhasil diunggah!');

    }

    public function destroy($id)
    {
        $photo = MatchPhoto::findOrFail($id);

        delete_image($photo->path); // Delete the image file from storage

        $photo->delete();

        return redirect()->back()->with('toast', 'Foto pertandingan berhasil dihapus!');
    }

    public function updateLink(Request $request, $id)
    {
        $request->validate([
            'match_photo_link' => 'nullable|max:255',
        ], [
            'match_photo_link.max' => 'Link tambahan tidak boleh lebih dari 255 karakter.',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->match_photo_link = $request->input('match_photo_link');
        $booking->save();

        return redirect()->route('dashboard.match-photos.show', $id)
            ->with('toast', 'Link foto pertandingan berhasil diperbarui!');
    }
}
