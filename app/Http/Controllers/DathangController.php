<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Room;
use App\Models\DetailedBill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        // Tính toán số đêm
        $checkin = Carbon::parse($request->checkin);
        $checkout = Carbon::parse($request->checkout);
        $nights = $checkout->diffInDays($checkin);
        
        // Lấy thông tin phòng
        $room = Room::findOrFail($request->room_id);
        
        // Tính toán giá thực sự
        $pricePerNight = $room->base_price;
        $totalAmount = $pricePerNight * $nights;

        try {
            DB::beginTransaction();

            // Tạo bill mới
            $bill = Bill::create([
                'room_id' => $request->room_id,
                'total' => $totalAmount,
                'status' => 'pending',
                'checkin' => $request->checkin,
                'checkout' => $request->checkout,
                'guest_name' => $request->name,
                'guest_email' => $request->email,
                'guest_phone' => $request->phone,
                'booking_date' => now(),
            ]);

            // Tạo detailed bill
            DetailedBill::create([
                'id_bill' => $bill->id,
                'id_room' => $request->room_id,
                'room_rate' => $pricePerNight,
                'quantity' => $nights,
            ]);

            DB::commit();

            // Lưu thông tin vào session để sử dụng cho VNPay
            session([
                'current_bill_id' => $bill->id,
                'booking_success' => [
                    'bill_id' => $bill->id,
                    'room_name' => $room->name,
                    'guest_name' => $request->name,
                    'checkin' => $request->checkin,
                    'checkout' => $request->checkout,
                    'nights' => $nights,
                    'total' => $totalAmount,
                    'price_per_night' => $pricePerNight,
                ]
            ]);

            // Trả về view thành công với thông tin đặt phòng
            return view('client.room.booking_success', [
                'bill_id' => $bill->id,
                'room_name' => $room->name,
                'guest_name' => $request->name,
                'checkin' => $request->checkin,
                'checkout' => $request->checkout,
                'nights' => $nights,
                'total' => $totalAmount,
                'price_per_night' => $pricePerNight,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi đặt phòng: ' . $e->getMessage());
        }
    }

    public function processPayment(Request $request)
    {
        $billId = session('current_bill_id');
        
        if (!$billId) {
            return redirect()->route('client.index')->with('error', 'Không có dữ liệu đặt phòng.');
        }

        // Chuyển hướng đến trang thanh toán VNPay
        return redirect()->route('payment.vnpay', ['bill_id' => $billId]);
    }

    public function paymentSuccess(Request $request)
    {
        $billId = session('current_bill_id');
        
        if (!$billId) {
            return redirect()->route('client.index')->with('error', 'Không tìm thấy thông tin đặt phòng.');
        }

        $bill = Bill::findOrFail($billId);
        
        // Cập nhật trạng thái thanh toán
        $bill->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        // Xóa session
        session()->forget(['current_bill_id', 'booking_success']);

        return redirect()->route('client.index')->with('success', 'Đặt phòng thành công! Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.');
    }

    public function paymentCancel()
    {
        $billId = session('current_bill_id');
        
        if ($billId) {
            $bill = Bill::findOrFail($billId);
            $bill->update(['status' => 'cancelled']);
            session()->forget(['current_bill_id', 'booking_success']);
        }

        return redirect()->route('client.index')->with('error', 'Thanh toán đã bị hủy.');
    }
}
