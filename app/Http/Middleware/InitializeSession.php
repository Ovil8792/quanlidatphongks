<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Locale: chỉ set nếu chưa tồn tại
        if (!Session::has('locale')) {
            Session::put('locale', 'vi');
        }

        // Categories: luôn set lại mỗi request
        $cat = Category::get();
        $checkactive = ["home","about","contact","news","other","rooms"];
        session::put(["active"=>$checkactive]);
        session::put(["category"=>$cat]);
        return $next($request);
    }
}
