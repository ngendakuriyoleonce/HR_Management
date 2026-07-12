<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectToPanel();
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->employee && $user->employee->status !== 'active') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact HR.',
                ])->onlyInput('email');
            }

            return $this->redirectToPanel();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function redirectToPanel(): RedirectResponse
    {
        $user = Auth::user();

        return match ($user->role) {
            'hr' => redirect('/admin'),
            'manager' => redirect()->route('hr.manager-panel.index'),
            default => redirect()->route('hr.check-in.index'),
        };
    }
}
