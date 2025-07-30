<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncompleteService
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guest()) {
            return redirect()->route('login')->with('toast', 'Silahkan login terlebih dahulu!');
        }

        $user = auth()->user();

        if (!in_array($user->role, ['referee', 'photographer'])) {
            return redirect()->route('home');
        }

        if ($user->service) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
