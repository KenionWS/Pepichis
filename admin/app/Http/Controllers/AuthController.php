<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('admin_user_id')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $adminUser = AdminUser::where('email', $credentials['email'])->first();

        if (! $adminUser || ! Hash::check($credentials['password'], $adminUser->password)) {
            return back()
                ->withErrors(['email' => 'Credenciales inválidas.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put([
            'admin_user_id' => $adminUser->id,
            'admin_user_name' => $adminUser->name,
        ]);

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget(['admin_user_id', 'admin_user_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
