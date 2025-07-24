<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Room;

class DathangController extends Controller
{
    public function showForm($id)
    {
        $room = Room::findOrFail($id);
        return view('client.room.datphong', compact('room'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^0[0-9]{9,10}$/',
            'email' => 'required|email',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'total' => 'required|numeric',
        ]);

        // Lưu thông tin vào session
        session([
            'booking' => [
                'room_id' => $request->room_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'checkin' => $request->checkin,
                'checkout' => $request->checkout,
                'total' => $request->total,
            ]
        ]);

        return redirect()->route('testr');
    }
    public function testr()
    {
        $booking = session('booking');

        $room = Room::findOrFail($booking['room_id']);
        // dd(1);
        return view('client.room.confirm', compact("booking", "room")); // Trả về view xác nhận
    }



    public function xacNhan1()
    {
        $booking = session('booking');

        if (!$booking) {
            return redirect()->route('client.index')->with('error', 'Không có dữ liệu đặt phòng.');
        }

        $room = Room::findOrFail($booking['room_id']);

        return view('client.room.confirm', compact('booking', 'room'));
    }
}
