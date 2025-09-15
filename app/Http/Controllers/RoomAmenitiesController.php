<?php

namespace App\Http\Controllers;

use App\Models\Amenity as Room_amenities;
use Illuminate\Http\Request;

class RoomAmenitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amenities = Room_amenities::all();
        return view('admin.room_amenities.index', compact('amenities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $amenities = Room_amenities::all();
        return view('admin.room_amenities.create', compact('amenities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $errors = [];
        $name = (string) $request->input('name');
        if (trim($name) === '') $errors[] = 'Tên tiện ích là bắt buộc';
        elseif (mb_strlen($name) > 255) $errors[] = 'Tên tiện ích không được quá 255 ký tự';
        if (!empty($errors)) return back()->withInput()->with('form_errors', $errors);

        Room_amenities::create(['name' => $name]);
        return redirect()->route('admin.amenities')->with('success','Thêm tiện ích thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room_amenities $room_amenities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room_amenities $room_amenities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room_amenities $room_amenities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room_amenities $room_amenities)
    {
        $room_amenities->delete();
        return back()->with('success','Đã xóa tiện ích');
    }
}
