<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    //     $validator = Validator::make($request->all(), [
    //     'name' => 'required|string|max:255',
    //     'diachi' => 'nullable|string|max:1000',
    //     'phone'=>'nullable|numeric|digits:10|starts_with:03,05,07,08,09'
    // ],[
    //     "name.required"=>"Vui lòng nhập tên để đổi!",
    //     "name.max"=>"Số kí tự tối đa là 255",
    //     "diachi.max"=>"Số kí tự tối đa là 1000",
    //     "phone.numeric"=>"Vui lòng nhập số, không phải chữ hay kí tự",
    //     "phone.starts_with"=>"Vui lòng nhập đầu số điện thoại đúng",
    //     "phone.digits"=>"Vui lòng nhập đúng số điện thoại, bạn đang ghi nhiều hơn rồi",
    // ]);

    // if ($validator->fails()) {
    //     session()->flash('debug_check', 'Validator failed and flash works!');
    //     // dd([session()->all(),$validator]);
    //     return redirect()->route("client.edit")
    //                      ->withErrors($validator)
    //                      ->withInput();
    // }else{
    //     // dd($validator);
    // }
        

        $user->name= $request->name;
        $user->address = $request->diachi;
        $user->phone = $request->phone;
        $user->save();

        return redirect()->route('client.show')->with('success', 'Cập nhật thành công.');
    }
}
