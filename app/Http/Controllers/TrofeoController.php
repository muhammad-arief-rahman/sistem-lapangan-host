<?php

namespace App\Http\Controllers;

use App\Models\Trofeo;
use App\Services\RegionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrofeoController extends Controller
{
    public function index(Request $request)
    {
        $districts = RegionService::getDistricts();
        $villages = RegionService::getVillagesByDistrict($request->input('district'));

        $trofeoQuery = Trofeo::with('booking', 'booking.user', 'matchups')
            // Filter trofeos that are currently open for registration
            ->whereHas('booking', function ($query) {
                $query->whereIn('status', ['pending']);
            })
            // Filter trofeos that have not ended
            ->whereHas('booking.fieldSchedule', function ($query) {
                $query->where('end_datetime', '>', now());
            });

        if ($request->has('search')) {
            $search = $request->input('search');
            $searchTerms = explode(' ', trim($search));

            // Apply search terms to the query
            $trofeoQuery->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->where('match_name', 'like', '%' . $term . '%')
                        ->orWhere('description', 'like', '%' . $term . '%');
                }
            });
        }

        if ($request->has('district')) {
            $districtId = $request->input('district');
            if ($districtId) {
                $trofeoQuery->whereHas('booking.field.village', function ($query) use ($districtId) {
                    $query->where('district_id', $districtId);
                });
            }
        }

        if ($request->has('village')) {
            $villageId = $request->input('village');
            if ($villageId) {
                $trofeoQuery->whereHas('booking.field', function ($query) use ($villageId) {
                    $query->where('village_id', $villageId);
                });
            }
        }

        $trofeos = $trofeoQuery
            ->latest()
            ->get();

        return view('pages.landing.event.trofeo.index', compact('trofeos', 'districts', 'villages'));
    }

    public function show($id)
    {
        $trofeo = Trofeo::with('booking', 'booking.user')->findOrFail($id);

        return view('pages.landing.event.trofeo.show', compact('trofeo'));
    }

    public function store(Request $request, $id)
    {
        $trofeo = Trofeo::findOrFail($id);

        if ($trofeo->booking->user_id === auth()->id()) {
            return redirect()->back()->with('toast', 'Anda tidak dapat mendaftar trofeo anda sendiri.');
        }

        // Check if there are already 2 matchups registered
        if ($trofeo->matchups()->count() >= 2) {
            return redirect()->back()->with('toast', 'Trofeo ini sudah penuh.');
        }

        DB::beginTransaction();

        $trofeo->matchups()->create([
            'user_id' => auth()->id(),
        ]);

        // Create a new payment detail for the trofeo
        $paymentDetail = $trofeo->booking->payment->paymentDetails()->create([
            'user_id' => auth()->id(),
            'amount' => $trofeo->registration_fee,
        ]);

        // Send notification to the user who created the trofeo
        $trofeo->booking->user->notify(new \App\Notifications\Database\NotifyTrofeoCreator($trofeo, auth()->user()));
        $trofeo->booking->user->notify(new \App\Notifications\Mail\NotifyTrofeoCreator($trofeo, auth()->user()));

        DB::commit();

        return redirect()->route('dashboard.payment.index')->with('alert', [
            'title' => 'Berhasil!',
            'message' => 'Anda berhasil mendaftar untuk trofeo tersebut, silahkan lakukan pembayaran untuk mengkonfirmasi pendaftaran anda.',
            'type' => 'success',
        ]);
    }
}
