<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class ShareData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bookingData = json_decode($request->cookie('bookingdata'), true) ?? [];
        \Log::debug('bookingData:', $bookingData);
        
        View::share('bookingData', $bookingData);
        return $next($request);
    }
}
