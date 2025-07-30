<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth()->guest()) {
            return redirect()->route('login')->with('toast', 'Silahkan login terlebih dahulu!');
        }

        $user = auth()->user();

        if (count($roles) > 0 && !in_array($user->role, $roles)) {
            // Reroute based on user role
            if ($user->role === "community") {
                return redirect()->route('home')->with('toast', 'Anda tidak memiliki akses ke halaman ini!');
            }

            return redirect()->route('dashboard.index')->with('toast', 'Anda tidak memiliki akses ke halaman ini!');
        }

        // Check if the user is a community and has no service set
        if (in_array($user->role, ['referee', 'photographer']) && !$user->service) {
            return redirect()->route('complete-profile')->with('toast', 'Silahkan lengkapi data diri anda!');
        }

        return $next($request);
    }
}
