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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
     public function register()
    {
        // Mở popup đăng ký trên trang chủ
        return redirect()->route('client.index')->with('auth_modal', 'register');
    }
    public function postRegister(Request $request)
    {
        // Validate dữ liệu đăng ký và giữ popup đăng ký mở khi có lỗi
        // $validator = Validator::make($request->all(), [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        //     'password' => ['required', 'string', 'min:6'],
        //     'phone' => ['nullable', 'string', 'max:50'],
        //     'address' => ['nullable', 'string', 'max:255'],
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->route('client.index')
        //         ->withErrors($validator)
        //         ->withInput()
        //         ->with('auth_modal', 'register');
        // }

        // $validated = $validator->validated();

        try {
            $avatarFilename = null;
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                if ($file->isValid()) {
                    $uploadPath = public_path('upload');
                    if (!File::isDirectory($uploadPath)) {
                        File::makeDirectory($uploadPath, 0755, true);
                    }
                    $avatarFilename = 'avatar_reg_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $avatarFilename);
                }
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                // Không tự hash ở đây; model đã cast 'password' => 'hashed'
                'password' => $request->password,
                'phone' => $request->phone ?? null,
                'address' => $request->address ?? null,
                'avatar' => $avatarFilename ?? null,
                'role' => 'user',
            ]);
        } catch (\Throwable $e) {
            // Ghi log để chẩn đoán nhanh nguyên nhân (VD: Unknown column, DB error, ...)
            Log::error('Register failed: '.$e->getMessage());
            // Ví dụ: chưa migrate cột phone/address sẽ gây lỗi Unknown column
            return redirect()->route('client.index')
                ->withErrors(['register_error' => 'Không thể tạo tài khoản. Vui lòng thử lại hoặc liên hệ quản trị viên.'])
                ->withInput()
                ->with('auth_modal', 'register');
        }
        // Tự động đăng nhập sau khi tạo tài khoản
        Auth::login($user);
        $request->session()->regenerate();

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
        // Sau khi đăng ký xong, chuyển về trang chủ với thông báo thành công
        return redirect()->route('client.index')->with('success', 'Đăng ký và đăng nhập thành công.');
    }
    public function login()
    {
        // Mở popup đăng nhập trên trang chủ
        return redirect()->route('client.index')->with('auth_modal', 'login');
    }
    public function postLogin(Request $request)
    {
        \Log::info('postLogin called', ['email' => $request->input('email')]);
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
            \Log::info('Login success', ['email' => $request->input('email')]);
            return redirect()->route('client.index');
        }
        // Thông báo chung cho cả trường hợp sai tài khoản hoặc sai mật khẩu
        \Log::warning('Login failed', [
            'email' => $request->input('email'),
            'has_errors' => true,
        ]);
        return redirect()->route('client.index')
            ->withErrors(['wrong' => 'Tài khoản hoặc mật khẩu không đúng.'])
            ->with('error', 'Tài khoản hoặc mật khẩu không đúng.')
            ->withInput()
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
    return view('admin.login');
}

public function postAdminLogin(Request $request)
{
    // Đăng nhập Admin với tài khoản cố định
    $email = $request->input('email');
    $password = $request->input('password');

    if ($email === 'test@test.com' && $password === 'test') {
        // Tạo hoặc cập nhật tài khoản admin cố định
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Administrator',
                'email' => $email,
                'password' => $password, // sẽ được hash qua casts
                'role' => 'admin',
            ]);
        } else {
            // Đảm bảo role và mật khẩu đúng
            $user->role = 'admin';
            if (!\Hash::check($password, $user->password)) {
                $user->password = $password; // sẽ được hash qua casts
            }
            $user->save();
        }

        Auth::guard('admin')->login($user, true);
        $request->session()->regenerate();
        return redirect()->route('admin.statistical.index');
    }

    // Fallback: Cho phép các tài khoản admin khác đăng nhập qua guard 'admin'
    if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {
        $request->session()->regenerate();
        return redirect()->route('admin.statistical.index');
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
