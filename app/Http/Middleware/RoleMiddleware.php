<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles (support multiple roles)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $user = Auth::user();

        // Cek apakah role user ada dalam daftar role yang diizinkan
        if (!in_array($user->role, $roles)) {
            // Redirect ke dashboard masing-masing dengan pesan error
            $dashboardRoutes = [
                'pemilik' => 'admin.dashboard',
                'karyawan' => 'karyawan.dashboard',
                'kasir' => 'kasir.dashboard',
            ];

            $route = $dashboardRoutes[$user->role] ?? 'login';
            
            return redirect()->route($route)
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return $next($request);
    }
}