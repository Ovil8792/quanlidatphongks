<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\DetailedBill;
use App\Models\Room_reservation;

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
        $vnp_Returnurl = route('payment.vnpay.return');
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
        $targetId = session('vnpay_target_id');
        
        if (!$targetId) {
            return redirect()->route('client.index')->with('error', 'Không tìm thấy thông tin đơn hàng.');
        }
        
        // Có thể là reservation hoặc bill
        $reservation = Room_reservation::find($targetId);
        $bill = $reservation ? null : Bill::findOrFail($targetId);
        
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
                
                return redirect()->route('dathang.success')->with('success', 'Thanh toán thành công! Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.');
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
}