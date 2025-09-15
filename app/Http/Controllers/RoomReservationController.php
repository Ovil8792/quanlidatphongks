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
        $errors = [];
        $roomId = $request->input('room_id');
        if (!$roomId) $errors[] = 'Phòng là bắt buộc';
        elseif (!\App\Models\Room::where('id', $roomId)->exists()) $errors[] = 'Phòng không tồn tại';

        $userId = $request->input('user_id');
        if ($userId && !\App\Models\User::where('id', $userId)->exists()) $errors[] = 'Người dùng không tồn tại';

        $start = $request->input('start_time');
        $end = $request->input('end_time');
        if (!$start) $errors[] = 'Ngày nhận là bắt buộc';
        if (!$end) $errors[] = 'Ngày trả là bắt buộc';
        if ($start && !preg_match('/^\d{4}-\d{2}-\d{2}/', $start)) $errors[] = 'Ngày nhận không hợp lệ';
        if ($end && !preg_match('/^\d{4}-\d{2}-\d{2}/', $end)) $errors[] = 'Ngày trả không hợp lệ';
        if ($start && $end) { if (!((strtotime($end) - strtotime($start)) > 0)) $errors[] = 'Ngày trả phải sau ngày nhận'; }

        $qty = (int) ($request->input('reserved_quantity'));
        if ($qty < 1) $errors[] = 'Số lượng phòng phải từ 1 trở lên';
        $total = $request->input('total_price');
        if (!is_numeric($total) || $total < 0) $errors[] = 'Tổng tiền không hợp lệ';
        $status = (string) $request->input('status');
        if (trim($status) === '') $errors[] = 'Trạng thái là bắt buộc';

        if (!empty($errors)) return back()->withInput()->with('form_errors', $errors);

        Room_reservation::create([
            'room_id' => $roomId,
            'user_id' => $userId,
            'start_time' => $start,
            'end_time' => $end,
            'reserved_quantity' => $qty,
            'total_price' => $total,
            'status' => $status,
            'special_requests' => $request->input('special_requests')
        ]);
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
        $errors = [];
        $payload = [];
        if ($request->has('start_time')) {
            $start = $request->input('start_time');
            if ($start && !preg_match('/^\d{4}-\d{2}-\d{2}/', $start)) $errors[] = 'Ngày nhận không hợp lệ';
            else $payload['start_time'] = $start;
        }
        if ($request->has('end_time')) {
            $end = $request->input('end_time');
            if ($end && !preg_match('/^\d{4}-\d{2}-\d{2}/', $end)) $errors[] = 'Ngày trả không hợp lệ';
            else $payload['end_time'] = $end;
        }
        if (isset($payload['start_time']) && isset($payload['end_time'])) {
            if (!((strtotime($payload['end_time']) - strtotime($payload['start_time'])) > 0)) $errors[] = 'Ngày trả phải sau ngày nhận';
        }
        if ($request->has('reserved_quantity')) {
            $qty = (int) $request->input('reserved_quantity');
            if ($qty < 1) $errors[] = 'Số lượng phòng phải từ 1 trở lên'; else $payload['reserved_quantity'] = $qty;
        }
        if ($request->has('total_price')) {
            $total = $request->input('total_price');
            if (!is_numeric($total) || $total < 0) $errors[] = 'Tổng tiền không hợp lệ'; else $payload['total_price'] = $total;
        }
        if ($request->has('status')) {
            $status = (string) $request->input('status');
            if (trim($status) === '') $errors[] = 'Trạng thái là bắt buộc'; else $payload['status'] = $status;
        }
        if ($request->has('special_requests')) $payload['special_requests'] = $request->input('special_requests');

        if (!empty($errors)) return back()->withInput()->with('form_errors', $errors);

        $room_reservation->update($payload);
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
