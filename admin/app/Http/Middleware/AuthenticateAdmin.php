<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;
use Illuminate\Http\Request;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $adminId = $request->session()->get('admin_user_id');

        if (! $adminId) {
            return redirect()->route('login')->withErrors([
                'email' => 'Iniciá sesión para acceder al administrador.',
            ]);
        }

        $adminUser = AdminUser::find($adminId);

        if (! $adminUser) {
            $request->session()->forget(['admin_user_id', 'admin_user_name']);

            return redirect()->route('login')->withErrors([
                'email' => 'Tu sesión ya no es válida. Volvé a ingresar.',
            ]);
        }

        $request->attributes->set('adminUser', $adminUser);
        view()->share('currentAdminUser', $adminUser);

        return $next($request);
    }
}
