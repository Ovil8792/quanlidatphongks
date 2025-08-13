<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\DetailedBill;

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
        $bill = Bill::findOrFail($billId);
        
        if ($bill->status !== 'pending') {
            return redirect()->route('client.index')->with('error', 'Đơn hàng này không thể thanh toán.');
        }

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('payment.vnpay.return');
        $vnp_TmnCode = "C5QKNTNE"; // Mã website tại VNPAY 
        $vnp_HashSecret = "T3KLE7F6DEYS5NTFJ06U5SM5TOWZHSHF"; // Chuỗi bí mật

        $vnp_TxnRef = $billId; // Sử dụng bill ID làm mã đơn hàng
        $vnp_OrderInfo = 'Thanh toán đặt phòng - ' . $bill->guest_name;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $bill->total * 100; // VNPay yêu cầu số tiền nhân với 100
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

        // Lưu bill_id vào session để xử lý khi VNPay trả về
        session(['vnpay_bill_id' => $billId]);

        return redirect()->to($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $billId = session('vnpay_bill_id');
        
        if (!$billId) {
            return redirect()->route('client.index')->with('error', 'Không tìm thấy thông tin đơn hàng.');
        }

        $bill = Bill::findOrFail($billId);
        
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
                $bill->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                ]);

                session()->forget(['vnpay_bill_id', 'booking', 'current_bill_id']);
                
                return redirect()->route('dathang.success')->with('success', 'Thanh toán thành công! Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.');
            } else {
                // Thanh toán thất bại
                $bill->update(['status' => 'failed']);
                session()->forget(['vnpay_bill_id', 'booking', 'current_bill_id']);
                
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