<?php

namespace App\Http\Controllers;

use App\Models\Room_reservation;
use Illuminate\Http\Request;

class RoomReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Room_reservation::with(['room','user'])->latest()->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'nullable|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reserved_quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'special_requests' => 'nullable|string',
        ]);
        Room_reservation::create($data);
        return back()->with('success','Tạo đặt phòng thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room_reservation $room_reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room_reservation $room_reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room_reservation $room_reservation)
    {
        $data = $request->validate([
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'reserved_quantity' => 'sometimes|integer|min:1',
            'total_price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string',
            'special_requests' => 'nullable|string',
        ]);
        $room_reservation->update($data);
        return back()->with('success','Cập nhật đặt phòng thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room_reservation $room_reservation)
    {
        $room_reservation->delete();
        return back()->with('success','Xóa đặt phòng thành công');
    }
}
