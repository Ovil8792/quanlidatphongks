<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
 

class ProfileController extends Controller
{
    public function show()
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập.');
    }
    // dd($user);
    return view('client.user.show', compact('user'));
}

public function edit()
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập.');
    }
    return view('client.user.edit', compact('user'));
}

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'diachi' => 'nullable|string|max:1000',
            // Cho phép số điện thoại với bất kỳ độ dài hợp lý, tối đa 50 ký tự
            'phone'  => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'phone.max' => 'Số điện thoại quá dài (tối đa 50 ký tự).',
            'avatar.image' => 'File tải lên phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, webp, gif.',
            'avatar.max' => 'Kích thước ảnh tối đa 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('client.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $user->name   = $request->name;
        $user->email  = $request->email;
        $user->address = $request->diachi;
        $user->phone   = $request->phone;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                $uploadPath = public_path('upload');
                if (!File::isDirectory($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Delete old avatar if exists
                if (!empty($user->avatar)) {
                    $old = $uploadPath . DIRECTORY_SEPARATOR . $user->avatar;
                    if (File::exists($old)) {
                        @File::delete($old);
                    }
                }

                $file->move($uploadPath, $filename);
                $user->avatar = $filename;
            }
        }

        // Nếu người dùng yêu cầu đổi mật khẩu
        if ($request->filled('current_password') || $request->filled('new_password') || $request->filled('new_password_confirmation')) {
            $pwValidator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ], [
                'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
                'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
                'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
                'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            ]);

            if ($pwValidator->fails()) {
                return redirect()->route('client.edit')
                    ->withErrors($pwValidator)
                    ->withInput();
            }

            if (!Hash::check($request->input('current_password'), $user->password)) {
                return redirect()->route('client.edit')
                    ->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])
                    ->withInput();
            }

            // Gán mật khẩu mới (model sẽ tự hash do casts)
            $user->password = $request->input('new_password');
        }

        $user->save();

        $msg = $request->filled('new_password') ? 'Cập nhật thông tin và đổi mật khẩu thành công.' : 'Cập nhật thông tin thành công.';
        return redirect()->route('client.show')->with('success', $msg);
    }
}
