<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\OpenMatch;
use App\Models\Trofeo;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $openMatches = OpenMatch::getOpenMatchData();
        $trofeos = Trofeo::getTrofeoData();
        $eventData = [...$openMatches, ...$trofeos];

        $cardData = (object) [
            'totalOpenMatches' => $openMatches->count(),
            'totalTrofeos' => $trofeos->count(),
        ];

        return view('pages.dashboard.event.index', compact('eventData', 'cardData'));
    }

}
