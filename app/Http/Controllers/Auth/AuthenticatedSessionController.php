<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Store the previous URL in the session to redirect back after login
        // We check if the previous URL is not the login page itself to avoid loops
        if (url()->previous() !== route('login') && url()->previous() !== '' && url()->previous() !== url()->current()) {
            session(['url.previous_visit' => url()->previous()]);
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $user = $request->user();

        if ($user->role_id === 1 || $user->role_id === 0) { // 1 = Admin, 0 = Owner
            return redirect()->route('admin.dashboard');
        }

        // Redirect to intended URL or the previous visit URL, fallback to dashboard
        $fallbackUrl = session('url.previous_visit', route('customer.dashboard', absolute: false));
        
        return redirect()->intended($fallbackUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
