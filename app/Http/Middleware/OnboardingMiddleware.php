<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnboardingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->cookie('laundry_onboarding_done')) {
            if (! $request->routeIs('onboarding')) {
                return redirect()->route('onboarding');
            }
        }

        return $next($request);
    }
}