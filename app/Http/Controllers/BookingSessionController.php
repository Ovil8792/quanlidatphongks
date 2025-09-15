<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\TempUser;

class BookingSessionController extends Controller
{
    /**
     * Lưu dữ liệu đặt phòng vào session và cookie
     */
    public function saveBookingData(Request $request)
    {
        $data = $request->only([
            'date_in', 'date_out', 'guest', 'room', 'custom_guest',
            'room_id', 'guest_name', 'guest_phone', 'guest_email'
        ]);

        // Lọc bỏ các giá trị null/empty
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });

        // Lưu vào session
        Session::put('booking_data', $data);

        // Lưu vào cookie (7 ngày)
        Cookie::queue('booking_data', json_encode($data), 60 * 24 * 7);

        return response()->json([
            'success' => true,
            'message' => 'Dữ liệu đặt phòng đã được lưu',
            'data' => $data
        ]);
    }

    /**
     * Lấy dữ liệu đặt phòng từ session hoặc cookie
     */
    public function getBookingData()
    {
        // Ưu tiên session trước
        $data = Session::get('booking_data', []);

        // Nếu session trống, lấy từ cookie
        if (empty($data)) {
            $cookieData = Cookie::get('booking_data');
            if ($cookieData) {
                $data = json_decode($cookieData, true) ?: [];
                // Cập nhật lại session
                Session::put('booking_data', $data);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Cập nhật một phần dữ liệu đặt phòng
     */
    public function updateBookingData(Request $request)
    {
        $key = $request->input('key');
        $value = $request->input('value');

        if (!$key) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu key cập nhật'
            ], 400);
        }

        // Lấy dữ liệu hiện tại
        $data = Session::get('booking_data', []);
        $data[$key] = $value;

        // Cập nhật session
        Session::put('booking_data', $data);

        // Cập nhật cookie
        Cookie::queue('booking_data', json_encode($data), 60 * 24 * 7);

        return response()->json([
            'success' => true,
            'message' => 'Dữ liệu đã được cập nhật',
            'data' => $data
        ]);
    }

    /**
     * Xóa dữ liệu đặt phòng
     */
    public function clearBookingData()
    {
        Session::forget('booking_data');
        Cookie::queue(Cookie::forget('booking_data'));

        return response()->json([
            'success' => true,
            'message' => 'Dữ liệu đặt phòng đã được xóa'
        ]);
    }

    /**
     * Lấy dữ liệu đặt phòng để hiển thị trong form
     */
    public function getFormData()
    {
        $data = Session::get('booking_data', []);
        
        if (empty($data)) {
            $cookieData = Cookie::get('booking_data');
            if ($cookieData) {
                $data = json_decode($cookieData, true) ?: [];
            }
        }

        return $data;
    }

    /**
     * Kiểm tra dữ liệu đặt phòng có đầy đủ không
     */
    public function validateBookingData()
    {
        $data = $this->getFormData();
        
        $required = ['date_in', 'date_out', 'room'];
        $missing = [];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $missing[] = $field;
            }
        }

        // Kiểm tra guest hoặc custom_guest
        if (empty($data['guest']) && empty($data['custom_guest'])) {
            $missing[] = 'guest';
        }

        return [
            'is_valid' => empty($missing),
            'missing_fields' => $missing,
            'data' => $data
        ];
    }

    /**
     * Lưu temp user theo user agent và gắn booking_data nếu có
     */
    public function saveTemp(Request $request)
    {
        $uid = $request->cookie('temp_uid') ?: (string) Str::uuid();

        $payload = Session::get('booking_data', []);
        if (empty($payload)) {
            $cookieData = Cookie::get('booking_data');
            if ($cookieData) {
                $payload = json_decode($cookieData, true) ?: [];
            }
        }

        $temp = TempUser::updateOrCreate(
            ['temp_uid' => $uid],
            [
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
                'ip_address' => $request->ip(),
                'booking_data' => $payload,
            ]
        );

        return response()->json(['success' => true, 'temp_uid' => $uid])
            ->cookie('temp_uid', $uid, 60 * 24 * 30);
    }
}
