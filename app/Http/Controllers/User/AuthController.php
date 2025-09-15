<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\TempUser;

class AuthController extends Controller
{
     public function register()
    {
        // Mở popup đăng ký trên trang chủ
        return redirect()->route('client.index')->with('auth_modal', 'register');
    }
    public function postRegister(Request $request)
    {
        //thêm logic kiểm tra xem email đã có trên hệ thống chưa, nếu có thì thông báo
        
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        // Merge temp user booking data nếu có
        $tempUid = $request->cookie('temp_uid');
        if ($tempUid) {
            $temp = TempUser::where('temp_uid', $tempUid)->first();
            if ($temp && !empty($temp->booking_data)) {
                // Tùy theo nghiệp vụ, có thể lưu vào session hoặc bills nháp
                session(['booking_data' => $temp->booking_data]);
                $temp->delete();
                Cookie::queue(Cookie::forget('temp_uid'));
            }
        }
        // Sau khi đăng ký xong, mở popup đăng nhập
        return redirect()->route('client.index')->with('auth_modal', 'login');
    }
    public function login()
    {
        // Mở popup đăng nhập trên trang chủ
        return redirect()->route('client.index')->with('auth_modal', 'login');
    }
    public function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Merge temp user booking data nếu có
            $tempUid = $request->cookie('temp_uid');
            if ($tempUid) {
                $temp = TempUser::where('temp_uid', $tempUid)->first();
                if ($temp && !empty($temp->booking_data)) {
                    session(['booking_data' => $temp->booking_data]);
                    $temp->delete();
                    Cookie::queue(Cookie::forget('temp_uid'));
                }
            }
            return redirect()->route('client.index');
        }
        return redirect()->route('client.index')
            ->withErrors(['wrong' => 'The provided credentials do not match our records.'])
            ->with('auth_modal', 'login');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

// ...existing code...

public function adminLogin()
{
    return view('admin');
}

public function postAdminLogin(Request $request)
{
        // dd(session()->all());
    $credentials = $request->only('email', 'password');
    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('admin.index');
    }
    return back()->withErrors([
        'wrong' => 'Tài khoản hoặc mật khẩu không đúng.',
    ]);
}

public function adminLogout(Request $request)
{
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('admin.login');
}
// ...existing code...



}
