<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\view;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchId = $request->query('search_id');
        $searchName = $request->query('search_name');

        $query = User::query();
        if (!empty($searchId)) {
            $query->where('id', (int) $searchId);
        }
        if (!empty($searchName)) {
            $query->where('name', 'like', '%'.$searchName.'%');
        }

        $user = $query->get();
        return view("admin.account.account", compact('user', 'searchId', 'searchName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view("admin.account.add");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
        User::create([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
        ]);
        return redirect(route("admin.account"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        return view("admin.account.edit", compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $name = $request->input("name");
    $email = $request->input("email");
    $password = $request->input("password");
    $role = $request->input("role");

    $user = User::find($id);
    $user->update([
        "name" => $name,
        "email" => $email,
        "password" => Hash::make($password),
        "role" => $role
    ]);
    return redirect(route("admin.account"))->with('message', 'Cập nhật thành công');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        User::destroy($id);
        return redirect(route("admin.account"))->with('message', 'Xóa tài khoản thành công');
    }
}
