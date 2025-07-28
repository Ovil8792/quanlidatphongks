<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function cookieRequest( Request $request){
        $data = $request->only(['cin', 'cout', 'pple', 'rms', 'cppl']);

    return response('OK')->cookie(
        'bookingdata',
        json_encode($data),
        60 * 24 // 1 ngày
    );
    }
}
