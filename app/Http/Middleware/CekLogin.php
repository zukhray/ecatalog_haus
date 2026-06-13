<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kalau nggak ada session 'role' (berarti belum login), tendang balik ke /login
        if (!session()->has('role')) {
            return redirect('/login')->with('error_admin', 'Lu harus login dulu bro!');
        }

        return $next($request);
    }
}