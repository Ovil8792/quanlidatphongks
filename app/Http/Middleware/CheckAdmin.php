<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check()) {
            if (Auth::guard('admin')->user()->role === 'admin' || Auth::guard('admin')->user()->role === 'staff') {
                return $next($request);
            } else {
                return redirect()->route('client.index')->with('error', 'Bạn không có quyền truy cập vào trang này.');
            }
        } else {
            return redirect()->route('admin.login');
        }
    }
}