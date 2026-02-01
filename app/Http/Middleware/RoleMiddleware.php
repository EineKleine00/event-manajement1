<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $isMaintenance = Setting::where('key', 'maintenance_mode')->value('value');

        // Jika Maintenance ON, dan User BUKAN Admin -> Tendang
        try {
            $isMaintenance = Setting::where('key', 'maintenance_mode')->value('value');
        } catch (\Exception $e) {
            $isMaintenance = '0';
        }

        if ($isMaintenance == '1') {
            // KITA HARUS IZINKAN AKSES UNTUK:
            // a. Admin (biar bisa benerin sistem)
            // b. Route Logout (biar user bisa keluar)
            
            $isAdmin = Auth::check() && Auth::user()?->user_role === 'admin';
            $isLogoutRoute = $request->routeIs('logout');

            // Jika BUKAN Admin DAN BUKAN mau Logout -> Tampilkan Halaman Maintenance
            if (!$isAdmin && !$isLogoutRoute) {
                // Return view maintenance dengan status code 503 (Service Unavailable)
                return response()->view('maintenance', [], 503);
            }
        }
        if (!Auth::check()) {
            return redirect('/login');
        }

        // GANTI DI SINI: dari 'role' ke 'user_role'
        $userRole = Auth::user()->user_role; 

        if ($userRole == $role) {
            return $next($request);
        }

        if ($userRole == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($userRole == 'user') {
            return redirect()->route('dashboard');
        }

        return abort(403, 'Unauthorized');
    }
}