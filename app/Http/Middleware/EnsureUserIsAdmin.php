<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Jika User belum login (Jaga-jaga, walaupun biasanya udah dicegat middleware 'auth')
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // 2. Jika User sudah login TAPI bukan Admin
        if ($request->user()->user_role !== 'admin') {
            // JANGAN abort(403), tapi REDIRECT ke dashboard user
            return redirect()->route('dashboard');
        }

        // 3. Jika Admin, silakan lanjut
        return $next($request);
    }
}