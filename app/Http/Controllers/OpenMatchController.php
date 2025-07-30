<?php

namespace App\Http\Controllers;

use App\Models\OpenMatch;
use App\Services\RegionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpenMatchController extends Controller
{
    public function openMatches(Request $request)
    {
        $districts = RegionService::getDistricts();
        $villages = RegionService::getVillagesByDistrict($request->input('district'));

        $openMatchesQuery = OpenMatch::with('booking', 'booking.user')
            // Filter open matches that are currently pending
            ->whereHas('booking', function ($query) {
                $query->whereIn('status', ['pending']);
            })
            // Filter open matches that have not ended
            ->whereHas('booking.fieldSchedule', function ($query) {
                $query->where('end_datetime', '>', now());
            });

        /// Filter by search terms if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $searchTerms = explode(' ', trim($search));

            // Apply search terms to the query
            $openMatchesQuery->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->where('match_name', 'like', '%' . $term . '%');
                    $query->orWhere('description', 'like', '%' . $term . '%');
                }
            });
        }

        // Filter by district if provided
        if ($request->has('district')) {
            $districtId = $request->input('district');
            if ($districtId) {
                $openMatchesQuery->whereHas('booking.field.village', function ($query) use ($districtId) {
                    $query->where('district_id', $districtId);
                });
            }
        }

        // Filter by village if provided
        if ($request->has('village')) {
            $villageId = $request->input('village');
            if ($villageId) {
                $openMatchesQuery->whereHas('booking.field', function ($query) use ($villageId) {
                    $query->where('village_id', $villageId);
                });
            }
        }

        $openMatches = $openMatchesQuery->latest()
            ->get();


        return view('pages.landing.event.open-matches.index', compact('openMatches', 'districts', 'villages'));
    }

    public function openMatchDetail($id)
    {
        $openMatch = OpenMatch::with('booking', 'booking.user')->findOrFail($id);

        return view('pages.landing.event.open-matches.show', compact('openMatch'));
    }

    public function register(Request $request, $id)
    {
        $openMatch = OpenMatch::findOrFail($id);

        if ($openMatch->booking->user_id === auth()->id()) {
            return redirect()->back()->with('toast', 'Anda tidak dapat mendaftar untuk pertandingan anda sendiri.');
        }

        // Check if there is already an opponent registered for this open match
        if ($openMatch->opponent_id) {
            return redirect()->back()->with('toast', 'Pertandingan ini sudah memiliki lawan.');
        }

        DB::beginTransaction();

        $openMatch->opponent_id = auth()->id();
        $openMatch->save();

        // Create a new payment detail for the open match
        $paymentDetail = $openMatch->booking->payment->paymentDetails()->create([
            'user_id' => auth()->id(),
            'amount' => $openMatch->registration_fee,
        ]);

        // Send notification to the user who registered the open match
        $openMatch->booking->user->notify(new \App\Notifications\Mail\NotifyOpenMatchCreator($openMatch, auth()->user()));
        $openMatch->booking->user->notify(new \App\Notifications\Database\NotifyOpenMatchCreator($openMatch, auth()->user()));

        DB::commit();

        return redirect()->route('dashboard.payment.index')->with('alert', [
            'title' => 'Berhasil!',
            'message' => 'Anda berhasil mendaftar untuk open match tersebut, silahkan lakukan pembayaran untuk mengkonfirmasi pendaftaran anda.',
            'type' => 'success',
        ]);
    }
}
