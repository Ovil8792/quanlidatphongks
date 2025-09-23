<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\DetailedBill;
use App\Models\Room_reservation;
use App\Models\Room;

class PaymentController extends Controller
{
    public function showPayment($billId)
    {
        $bill = Bill::with(['details.room'])->findOrFail($billId);
        
        if ($bill->status !== 'pending') {
            return redirect()->route('client.index')->with('error', 'Đơn hàng này không thể thanh toán.');
        }

        $checkIn = Carbon::parse($bill->checkin);
        $checkOut = Carbon::parse($bill->checkout);
        $nights = $checkOut->diffInDays($checkIn);

        return view('client.payment', compact('bill', 'checkIn', 'checkOut', 'nights'));
    }

    public function processVNPay(Request $request, $billId)
    {
        // Ở luồng mới, $billId có thể là ID reservation khi chưa có bill
        $reservation = Room_reservation::find($billId);
        $amount = null;
        $displayName = '';
        if ($reservation) {
            if ($reservation->status !== 'pending') {
                return redirect()->route('client.index')->with('error', 'Đơn đặt này không thể thanh toán.');
            }
            $amount = $reservation->total_price;
            $displayName = $reservation->guest_name;
        } else {
            $bill = Bill::findOrFail($billId);
            if ($bill->status !== 'pending') {
                return redirect()->route('client.index')->with('error', 'Đơn hàng này không thể thanh toán.');
            }
            $amount = $bill->total;
            $displayName = $bill->guest_name;
        }

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        // Xây dựng Return URL dựa trên base URL hiện tại để hỗ trợ deploy trong subfolder (WAMP/XAMPP)
        $relativeReturn = route('payment.vnpay.return', [], false); // "/payment/vnpay/return"
        $origin = $request->getSchemeAndHttpHost();                 // "http://localhost"
        $baseUrl = $request->getBaseUrl();                          // ví dụ: "/quanlidatphongks/public" nếu chạy trong subfolder
        $vnp_Returnurl = rtrim($origin . $baseUrl, '/') . $relativeReturn;
        \Log::info('VNPay Return URL:', ['return_url' => $vnp_Returnurl, 'origin' => $origin, 'baseUrl' => $baseUrl, 'relative' => $relativeReturn]);
        $vnp_TmnCode = "C5QKNTNE"; // Mã website tại VNPAY 
        $vnp_HashSecret = "T3KLE7F6DEYS5NTFJ06U5SM5TOWZHSHF"; // Chuỗi bí mật

        $vnp_TxnRef = $billId; // Dùng ID reservation/bill làm mã giao dịch
        $vnp_OrderInfo = 'Thanh toán đặt phòng - ' . $displayName;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100; // VNPay yêu cầu số tiền nhân với 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Lưu id vào session để xử lý khi VNPay trả về
        session(['vnpay_target_id' => $billId]);

        return redirect()->to($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        // Ưu tiên dùng mã tham chiếu do VNPay trả về để không phụ thuộc session
        $targetId = $request->get('vnp_TxnRef') ?: session('vnpay_target_id');

        // Có thể là reservation hoặc bill
        $reservation = $targetId ? Room_reservation::find($targetId) : null;
        $bill = (!$reservation && $targetId) ? Bill::find($targetId) : null;
        if (!$reservation && !$bill) {
            return redirect()->route('client.index')->with('error', 'Không tìm thấy đơn hàng/hóa đơn tương ứng.');
        }
        
        // Kiểm tra response từ VNPay
        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        $vnp_TxnRef = $request->get('vnp_TxnRef');
        $vnp_Amount = $request->get('vnp_Amount');
        $vnp_SecureHash = $request->get('vnp_SecureHash');

        // Xác thực hash
        $vnp_HashSecret = "T3KLE7F6DEYS5NTFJ06U5SM5TOWZHSHF";
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData["vnp_SecureHash"]);
        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            if ($vnp_ResponseCode == "00") {
                // Thanh toán thành công
                if ($reservation) {
                    // Double-check overlap before confirming (phòng có thể vừa được mở/khóa)
                    $overlapPaid = Bill::where('room_id', $reservation->room_id)
                        ->where('status', 'paid')
                        ->whereDate('checkin', '<', Carbon::parse($reservation->end_time)->toDateString())
                        ->whereDate('checkout', '>', Carbon::parse($reservation->start_time)->toDateString())
                        ->exists();
                    // Bổ sung kiểm tra paid qua detailedbills nếu bill không có room_id
                    $overlapPaidViaDetails = DetailedBill::where('id_room', $reservation->room_id)
                        ->whereHas('bill', function($q) use ($reservation) {
                            $q->whereIn('status', ['paid','Paid','PAID'])
                              ->whereDate('checkin', '<', Carbon::parse($reservation->end_time)->toDateString())
                              ->whereDate('checkout', '>', Carbon::parse($reservation->start_time)->toDateString());
                        })
                        ->exists();
                    $overlapConfirmed = Room_reservation::where('room_id', $reservation->room_id)
                        ->where('id', '!=', $reservation->id)
                        ->whereIn('status', ['confirmed'])
                        ->whereDate('start_time', '<', Carbon::parse($reservation->end_time)->toDateString())
                        ->whereDate('end_time', '>', Carbon::parse($reservation->start_time)->toDateString())
                        ->exists();
                    if ($overlapPaid || $overlapPaidViaDetails || $overlapConfirmed) {
                        // Trả về failed nếu vừa phát hiện chồng lấn
                        $reservation->update(['status' => 'failed']);
                        session()->forget(['vnpay_target_id']);
                        return redirect()->route('dathang.cancel')->with('error', 'Khoảng thời gian vừa không còn khả dụng. Vui lòng chọn ngày khác hoặc phòng khác.');
                    }
                    // Tạo bill từ reservation
                    $bill = Bill::create([
                        'user_id' => $reservation->user_id,
                        'room_id' => $reservation->room_id,
                        'total' => $reservation->total_price,
                        'status' => 'paid',
                        'checkin' => $reservation->start_time,
                        'checkout' => $reservation->end_time,
                        'guest_name' => $reservation->guest_name,
                        'guest_email' => $reservation->guest_email,
                        'guest_phone' => $reservation->guest_phone,
                        'booking_date' => now(),
                        'payment_date' => now(),
                    ]);
                    DetailedBill::create([
                        'id_bill' => $bill->id,
                        'id_room' => $reservation->room_id,
                        'room_rate' => optional($reservation->room)->base_price ?? 0,
                        'quantity' => Carbon::parse($reservation->end_time)->diffInDays(Carbon::parse($reservation->start_time)),
                    ]);
                    $reservation->update(['status' => 'confirmed']);
                } else if ($bill) {
                    $bill->update([
                        'status' => 'paid',
                        'payment_date' => now(),
                    ]);
                }

                session()->forget(['vnpay_target_id', 'booking', 'current_bill_id', 'current_reservation_id']);

                // Điều hướng về trang hóa đơn để người dùng thấy chi tiết thanh toán
                $redirectBillId = isset($bill) && $bill ? $bill->id : (isset($reservation) && $reservation ? ($bill->id ?? null) : null);
                // Nếu vừa tạo bill từ reservation thì $bill đã được gán ở trên
                if (isset($bill) && $bill) {
                    return redirect()->route('payment.invoice', ['bill_id' => $bill->id])
                        ->with('success', 'Thanh toán thành công!');
                }
                // Trường hợp dự phòng nếu vì lý do nào đó chưa có bill
                return redirect()->route('client.index')->with('success', 'Thanh toán thành công!');
            } else {
                // Thanh toán thất bại
                if ($reservation) {
                    $reservation->update(['status' => 'failed']);
                } else if ($bill) {
                    $bill->update(['status' => 'failed']);
                }
                session()->forget(['vnpay_target_id', 'booking', 'current_bill_id', 'current_reservation_id']);
                
                return redirect()->route('dathang.cancel')->with('error', 'Thanh toán thất bại. Mã lỗi: ' . $vnp_ResponseCode);
            }
        } else {
            // Hash không khớp
            return redirect()->route('client.index')->with('error', 'Chữ ký không hợp lệ.');
        }
    }

    public function showPaymentHistory()
    {
        $bills = Bill::with(['details.room'])
            ->where('guest_email', session('guest_email'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.payment.history', compact('bills'));
    }

    public function showInvoice($billId)
    {
        $bill = Bill::with(['details.room.category'])->findOrFail($billId);
        
        // Kiểm tra xem bill đã được thanh toán chưa
        if ($bill->status !== 'paid') {
            return redirect()->route('client.index')->with('error', 'Hóa đơn này chưa được thanh toán hoặc không tồn tại.');
        }

        return view('client.invoice', compact('bill'));
    }

    public function testPayment()
    {
        // Hiển thị trang test thanh toán
        return view('client.test-payment');
    }

    public function testCreatePayment(Request $request)
    {
        // Lấy 1 phòng bất kỳ để test
        $room = Room::orderBy('id')->first();
        if (!$room) {
            return redirect()->route('client.index')->with('error', 'Không có phòng để test thanh toán.');
        }

        // 2 đêm từ ngày mai
        $checkin = Carbon::now()->addDay()->startOfDay();
        $checkout = (clone $checkin)->addDays(2);
        $nights = $checkout->diffInDays($checkin);

        $pricePerNight = $room->base_price ?? 500000;
        $totalAmount = $pricePerNight * $nights; // 1 phòng

        // Tạo reservation pending để thanh toán
        $reservation = Room_reservation::create([
            'room_id' => $room->id,
            'user_id' => auth()->id(),
            'start_time' => $checkin->toDateString(),
            'end_time' => $checkout->toDateString(),
            'reserved_quantity' => 1,
            'total_price' => $totalAmount,
            'status' => 'pending',
            'guest_name' => 'Nguyen Van Test',
            'guest_email' => 'test@example.com',
            'guest_phone' => '0900000000',
            'temp_uid' => $request->cookie('temp_uid'),
        ]);

        // Lưu để tham chiếu sau
        session(['current_reservation_id' => $reservation->id]);

        // Chuyển hướng qua VNPay sử dụng id reservation
        return redirect()->route('payment.vnpay', ['bill_id' => $reservation->id]);
    }
}