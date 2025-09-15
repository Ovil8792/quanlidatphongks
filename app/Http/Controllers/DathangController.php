<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Room;
use App\Models\DetailedBill;
use App\Models\Room_reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Models\TempUser;

class DathangController extends Controller
{
    public function showForm($id)
    {
        $room = Room::findOrFail($id);
        
        // Lấy dữ liệu đặt phòng từ session
        $bookingData = session('booking_data', []);

        // Nếu session trống thì lấy từ TempUser theo cookie temp_uid
        if (empty($bookingData)) {
            $tempUid = request()->cookie('temp_uid');
            if ($tempUid) {
                $temp = TempUser::where('temp_uid', $tempUid)->first();
                if ($temp && is_array($temp->booking_data)) {
                    $bookingData = $temp->booking_data;
                    // Lưu lại vào session để dùng tiếp
                    session(['booking_data' => $bookingData]);
                }
            }
        }
        
        // Debug: Kiểm tra dữ liệu session
        \Log::info('DathangController - Raw Session Data:', $bookingData);
        
        // Chuẩn hóa dữ liệu từ session
        $normalizedData = [];
        
        // Xử lý ngày tháng
        if (isset($bookingData['date_in'])) {
            $normalizedData['date_in'] = $bookingData['date_in'];
        }
        if (isset($bookingData['date_out'])) {
            $normalizedData['date_out'] = $bookingData['date_out'];
        }
        
        // Xử lý thông tin khách
        if (isset($bookingData['guest'])) {
            $normalizedData['guest'] = $bookingData['guest'];
        }
        if (isset($bookingData['custom_guest']) && $bookingData['guest'] == 'custom') {
            $normalizedData['custom_guest'] = $bookingData['custom_guest'];
        }
        
        // Xử lý loại phòng
        if (isset($bookingData['category_id'])) {
            $normalizedData['category_id'] = $bookingData['category_id'];
        }
        
        // Thêm room_id
        $normalizedData['room_id'] = $id;
        
        // Debug: Kiểm tra dữ liệu đã chuẩn hóa
        \Log::info('DathangController - Normalized Data:', $normalizedData);
        
        return view('client.room.datphong', compact('room', 'bookingData'));
    }

    public function store(Request $request)
    {
        // Lấy dữ liệu từ session nếu có
        $sessionData = session('booking_data', []);
        // Lấy dữ liệu từ TempUser nếu có
        $tempData = [];
        $tempUid = $request->cookie('temp_uid');
        if ($tempUid) {
            $temp = TempUser::where('temp_uid', $tempUid)->first();
            if ($temp && is_array($temp->booking_data)) {
                $tempData = $temp->booking_data;
            }
        }
        
        // Merge dữ liệu từ DB (TempUser) -> session -> request (ưu tiên request cao nhất)
        $data = array_merge($tempData, $sessionData, $request->all());
        
        // Debug: Log dữ liệu nhận được
        \Log::info('DathangController - Request Data:', $request->all());
        \Log::info('DathangController - Session Data:', $sessionData);
        \Log::info('DathangController - Merged Data:', $data);
        
        // Custom validate (không dùng Validator)
        $errors = [];
        if (!$request->filled('room_id')) {
            $errors[] = 'ID phòng là bắt buộc';
        } elseif (!Room::where('id', $request->input('room_id'))->exists()) {
            $errors[] = 'Phòng không tồn tại';
        }
        if (!trim((string) $request->input('name'))) {
            $errors[] = 'Họ tên là bắt buộc';
        } elseif (mb_strlen($request->input('name')) > 255) {
            $errors[] = 'Họ tên không được quá 255 ký tự';
        }
        if (!trim((string) $request->input('phone'))) {
            $errors[] = 'Số điện thoại là bắt buộc';
        } elseif (!preg_match('/^0[0-9]{9,10}$/', (string) $request->input('phone'))) {
            $errors[] = 'Số điện thoại không hợp lệ (định dạng: 0xxxxxxxxx)';
        }
        if (!trim((string) $request->input('email'))) {
            $errors[] = 'Email là bắt buộc';
        } elseif (!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }
        $dateInRaw = $request->input('date_in');
        $dateOutRaw = $request->input('date_out');
        if (!$dateInRaw) { $errors[] = 'Ngày nhận phòng là bắt buộc'; }
        if (!$dateOutRaw) { $errors[] = 'Ngày trả phòng là bắt buộc'; }
        $dateInOk = $dateInRaw && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateInRaw);
        $dateOutOk = $dateOutRaw && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateOutRaw);
        if ($dateInRaw && !$dateInOk) { $errors[] = 'Ngày nhận phòng không hợp lệ'; }
        if ($dateOutRaw && !$dateOutOk) { $errors[] = 'Ngày trả phòng không hợp lệ'; }
        if ($dateInOk && $dateOutOk) {
            $cin = Carbon::createFromFormat('Y-m-d', $dateInRaw)->startOfDay();
            $cout = Carbon::createFromFormat('Y-m-d', $dateOutRaw)->startOfDay();
            if ($cout->lessThanOrEqualTo($cin)) {
                $errors[] = 'Ngày trả phòng phải sau ngày nhận phòng';
            }
        }
        $roomCount = $request->input('room_count');
        if ($roomCount === null || $roomCount === '') {
            $errors[] = 'Số phòng là bắt buộc';
        } elseif (!is_numeric($roomCount) || (int)$roomCount < 1 || (int)$roomCount > 10) {
            $errors[] = 'Số phòng phải từ 1 đến 10';
        }
        $total = $request->input('total');
        if ($total === null || $total === '') {
            $errors[] = 'Tổng tiền là bắt buộc';
        } elseif (!is_numeric($total) || $total < 0) {
            $errors[] = 'Tổng tiền không hợp lệ';
        }
        if (!empty($errors)) {
            return back()->withInput()->with('form_errors', $errors);
        }

        // Tính toán số đêm
        $checkin = Carbon::createFromFormat('Y-m-d', $data['date_in'])->startOfDay();
        $checkout = Carbon::createFromFormat('Y-m-d', $data['date_out'])->startOfDay();
        $nights = $checkout->diffInDays($checkin);
        
        // Đảm bảo số đêm không âm và tối thiểu là 1
        $nights = max(1, $nights);
        
        // Debug: Log thông tin tính toán
        \Log::info('DathangController - Date Calculation:', [
            'checkin' => $data['date_in'],
            'checkout' => $data['date_out'],
            'nights' => $nights,
            'checkin_carbon' => $checkin->toDateTimeString(),
            'checkout_carbon' => $checkout->toDateTimeString(),
            'diff_in_days' => $checkout->diffInDays($checkin)
        ]);
        
        // Lấy thông tin phòng
        $room = Room::findOrFail($data['room_id']);
        
        // Tính toán giá thực sự
        $pricePerNight = $room->base_price;
        $roomCount = intval($data['room_count']);
        $totalAmount = $pricePerNight * $nights * $roomCount;

        // Kiểm tra trùng lịch: nếu cùng phòng đã có đơn paid (bill) hoặc reservation confirmed trùng khoảng ngày thì chặn
        // Quy tắc overlap (nửa mở): [checkin, checkout) -> overlap nếu (existing.checkin < checkout) AND (existing.checkout > checkin)
        $hasPaidBillOverlap = Bill::where('room_id', $data['room_id'])
            ->where('status', 'paid')
            ->whereDate('checkin', '<', $checkout->toDateString())
            ->whereDate('checkout', '>', $checkin->toDateString())
            ->exists();

        // Trường hợp bill không có room_id (các bản ghi cũ), kiểm tra qua detailedbills
        $hasPaidBillOverlapViaDetails = DetailedBill::where('id_room', $data['room_id'])
            ->whereHas('bill', function($q) use ($checkin, $checkout) {
                $q->whereIn('status', ['paid','Paid','PAID'])
                  ->whereDate('checkin', '<', $checkout->toDateString())
                  ->whereDate('checkout', '>', $checkin->toDateString());
            })
            ->exists();

        $hasConfirmedReservationOverlap = Room_reservation::where('room_id', $data['room_id'])
            ->whereIn('status', ['confirmed'])
            ->whereDate('start_time', '<', $checkout->toDateString())
            ->whereDate('end_time', '>', $checkin->toDateString())
            ->exists();

        if ($hasPaidBillOverlap || $hasPaidBillOverlapViaDetails || $hasConfirmedReservationOverlap) {
            return back()->withInput()->with('error', 'Phòng này đã được đặt trong khoảng thời gian chọn. Vui lòng chọn ngày khác hoặc phòng khác.');
        }

        try {
            DB::beginTransaction();

            // Tạo reservation trước, chưa tạo bill
            $reservation = Room_reservation::create([
                'room_id' => $data['room_id'],
                'user_id' => auth()->id(),
                'start_time' => $checkin->toDateString(),
                'end_time' => $checkout->toDateString(),
                'reserved_quantity' => $roomCount,
                'total_price' => $totalAmount,
                'status' => 'pending',
                'guest_name' => $data['name'],
                'guest_email' => $data['email'],
                'guest_phone' => $data['phone'],
                'temp_uid' => request()->cookie('temp_uid'),
            ]);

            DB::commit();

            // Lưu thông tin vào session để sử dụng cho VNPay
            session([
                'current_reservation_id' => $reservation->id,
                'booking_success' => [
                    'reservation_id' => $reservation->id,
                    'room_name' => $room->name,
                    'guest_name' => $data['name'],
                    'checkin' => $data['date_in'],
                    'checkout' => $data['date_out'],
                    'nights' => $nights,
                    'room_count' => $roomCount,
                    'total' => $totalAmount,
                    'price_per_night' => $pricePerNight,
                ]
            ]);

            // Xóa dữ liệu đặt phòng cũ
            session()->forget('booking_data');

            // Trả về view thành công với thông tin đặt phòng
            return view('client.room.booking_success', [
                'bill_id' => $reservation->id,
                'room_name' => $room->name,
                'guest_name' => $data['name'],
                'checkin' => $data['date_in'],
                'checkout' => $data['date_out'],
                'nights' => $nights,
                'total' => $totalAmount,
                'price_per_night' => $pricePerNight,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi đặt phòng: ' . $e->getMessage());
        }
    }

    /**
     * API: Kiểm tra khả năng đặt phòng theo khoảng ngày cho 1 phòng
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'date_in' => 'required|date_format:Y-m-d',
            'date_out' => 'required|date_format:Y-m-d|after:date_in',
        ]);

        $roomId = (int) $request->input('room_id');
        $checkin = Carbon::createFromFormat('Y-m-d', $request->input('date_in'))->startOfDay();
        $checkout = Carbon::createFromFormat('Y-m-d', $request->input('date_out'))->startOfDay();

        // dd($checkin, $checkout, $roomId);
        // Bill đã paid
        $hasPaidBillOverlap = Bill::where('room_id', $roomId)
            ->whereIn('status', ['paid','Paid','PAID'])
            ->whereDate('checkin', '<', $checkout->toDateString())
            ->whereDate('checkout', '>', $checkin->toDateString())
            ->exists();

        // Bill paid qua detailedbills
        $hasPaidBillOverlapViaDetails = DetailedBill::where('id_room', $roomId)
            ->whereHas('bill', function($q) use ($checkin, $checkout) {
                $q->whereIn('status', ['paid','Paid','PAID'])
                  ->whereDate('checkin', '<', $checkout->toDateString())
                  ->whereDate('checkout', '>', $checkin->toDateString());
            })
            ->exists();

        // Reservation đã confirmed
        $hasConfirmedReservationOverlap = Room_reservation::where('room_id', $roomId)
            ->whereIn('status', ['confirmed'])
            ->whereDate('start_time', '<', $checkout->toDateString())
            ->whereDate('end_time', '>', $checkin->toDateString())
            ->exists();

        $available = !($hasPaidBillOverlap || $hasPaidBillOverlapViaDetails || $hasConfirmedReservationOverlap);

        return response()->json([
            'success' => true,
            'available' => $available,
            'message' => $available ? 'Phòng có thể đặt trong khoảng thời gian này.' : 'Phòng đã được đặt trong khoảng thời gian này.',
        ]);
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
