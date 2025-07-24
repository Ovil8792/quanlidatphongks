<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index()
    {
        $booking = [
            'room' => 5,
            'check_in' => '2025-05-11',
            'check_out' => '2025-05-16',
            'base_price' => 200000,
        ];

        $checkIn = Carbon::parse($booking['check_in']);
        $checkOut = Carbon::parse($booking['check_out']);

        // Đảm bảo số đêm không bị âm
        $nights = $checkOut->diffInDays($checkIn, true);


        // Tính tổng tiền
        $total = $nights * $booking['base_price'] * $booking['room'];

        return view('client.payment', compact('booking', 'checkIn', 'checkOut', 'nights', 'total'));
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function processPayment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretkey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán qua MoMo";
        $amount = $request->input('total'); // Lấy tổng tiền từ form
        $orderId = time() . "";
        $redirectUrl = "http://127.0.0.1:8000/payment";
        $ipnUrl = "http://127.0.0.1:8000/payment";

        // Lấy dữ liệu từ form
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone-number');
        $pid = $request->input('personal-identification-number');

        // Gộp thông tin vào extraData
        $extraDataArray = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'pid' => $pid
        ];
        $extraData = base64_encode(json_encode($extraDataArray));

        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . urlencode($extraData) . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretkey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        // return redirect()->to($jsonResult['payUrl']);

        dd($result);
    }
}
