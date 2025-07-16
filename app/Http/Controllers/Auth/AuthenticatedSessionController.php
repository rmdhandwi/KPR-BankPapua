<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response|RedirectResponse
    {
        // Jika user sudah login
        if (Auth::check()) {
            $role = Auth::user()->role;
            $user = Auth::user();
            // Redirect berdasarkan role
            return redirect()->route(match ($role) {
                1 => 'admin.dashboard', 
                2 => 'developer.dashboard',
                default => 'home',
            })->with('success', 'Sudah Login sebagai ' . $user->name);
        }

        // Jika belum login, tampilkan halaman login
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        switch ($user->role) {
            case 1:
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, ' . $user->name);
            case 2:
                return redirect()->route('developer.dashboard')->with('success', 'Selamat datang, ' . $user->name);
            default:
                Auth::logout();
                return redirect()->route('home')->with('error', 'Role tidak dikenali.');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil Logout.');
    }
}
