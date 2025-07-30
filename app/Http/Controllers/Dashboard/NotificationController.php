<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->latest()->get();

        $cardData = (object) [
            'totalNotifications' => $notifications->count(),
            'unreadNotifications' => $notifications->where('read_at', null)->count(),
        ];

        return view('pages.dashboard.notification.index', compact('notifications', 'cardData'));
    }

    public function read(Request $request, $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return redirect()->route('dashboard.notification.index')->with(['toast' => 'Notifikasi telah ditandai sebagai dibaca.']);
    }

    public function readAll(Request $request)
    {
        $user = auth()->user();
        $user->unreadNotifications->each(function ($notification) {
            $notification->update(['read_at' => now()]);
        });

        return redirect()->route('dashboard.notification.index')->with(['toast' => 'Semua notifikasi telah ditandai sebagai dibaca.']);
    }
}
