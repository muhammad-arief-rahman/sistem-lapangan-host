<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function show(Request $request, $id)
    {
        $service = Service::with('user', 'schedules')->find($id);

        if (!$service) {
            // TODO: Arahkan ke halaman jasa
            return redirect()->route('home')->with('toast', 'Jasa tidak ditemukan');
        }

        return view('pages.landing.service.show', compact('service'));
    }
}
