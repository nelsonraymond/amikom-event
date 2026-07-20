<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePartnerIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (auth('partner')->check() && auth('partner')->user()->status !== 'active') {
            auth('partner')->logout();
            return redirect()->route('partner.login')
                ->withErrors(['email' => 'Akun Kepanitiaan Anda tidak aktif.']);
        }

        return $next($request);
    }
}