<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!Auth::check() || !$user->role) {
            return redirect('login');
        }
        if ($user->hasRole($role)) {
            return $next($request);
        }

        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return redirect('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
