<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MutationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $mutations = $user->mutations()->latest()->get();

        $cardData = (object) [
            'balance' => $user->balance,
            'mutations' => $mutations->count(),
            'income' => $mutations->where('amount', '>', 0)->sum('amount'),
            'monthly_income' => $mutations->where('amount', '>', 0)
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('amount'),
        ];

        return view('pages.dashboard.mutation.index', compact('mutations', 'cardData'));
    }
}
