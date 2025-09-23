<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Room_reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function filter(Request $request)
    {
        $query = Room::with(['category']);

        // Lọc loại phòng
        if ($request->filled('room_types') && is_array($request->room_types) && count($request->room_types) > 0) {
            $query->whereIn('category_id', $request->room_types);
        }

        // Lọc số người tối đa
        if ($request->filled('max_guests') && is_array($request->max_guests) && count($request->max_guests) > 0) {
            $query->where(function ($q) use ($request) {
                foreach ($request->max_guests as $guestCount) {
                    if ($guestCount == '2') {
                        $q->orWhere('max_guests', '<=', 2);
                    } elseif ($guestCount == '4') {
                        $q->orWhereBetween('max_guests', [3, 4]);
                    } elseif ($guestCount == '6') {
                        $q->orWhere('max_guests', '>=', 5);
                    }
                }
            });
        }

        // Lọc khoảng giá nhập riêng (ưu tiên nếu có)
        $min = $request->input('custom_price_min');
        $max = $request->input('custom_price_max');
        if ($min !== null && $min !== '' && $max !== null && $max !== '') {
            $query->whereBetween('base_price', [(int)$min, (int)$max]);
        } elseif ($min !== null && $min !== '') {
            $query->where('base_price', '>=', (int)$min);
        } elseif ($max !== null && $max !== '') {
            $query->where('base_price', '<=', (int)$max);
        } else if ($request->filled('price_ranges') && is_array($request->price_ranges) && count($request->price_ranges) > 0) {
            $query->where(function ($q) use ($request) {
                foreach ($request->price_ranges as $range) {
                    if ($range === 'low') {
                        $q->orWhere('base_price', '<', 1000000);
                    } elseif ($range === 'medium') {
                        $q->orWhereBetween('base_price', [1000000, 3000000]);
                    } elseif ($range === 'high') {
                        $q->orWhere('base_price', '>', 3000000);
                    }
                }
            });
        }

        // Lọc tiện ích (theo text - không còn sử dụng relationship)
        if ($request->filled('amenities') && is_array($request->amenities) && count($request->amenities) > 0) {
            $query->where(function ($q) use ($request) {
                foreach ($request->amenities as $amenity) {
                    $q->where('amenities', 'LIKE', "%$amenity%");
                }
            });
        }

        // Lọc theo ngày checkin/checkout nếu có
        if ($request->filled('cin') && $request->filled('cout')) {
            $checkin = $request->input('cin');
            $checkout = $request->input('cout');
            
            // Kiểm tra xem phòng có bị đặt trong khoảng thời gian này không
            $query->whereDoesntHave('reservations', function($q) use ($checkin, $checkout) {
                $q->where('status', '!=', 'cancelled')
                   ->where(function($subQ) use ($checkin, $checkout) {
                       $subQ->whereBetween('checkin', [$checkin, $checkout])
                            ->orWhereBetween('checkout', [$checkin, $checkout])
                            ->orWhere(function($innerQ) use ($checkin, $checkout) {
                                $innerQ->where('checkin', '<=', $checkin)
                                      ->where('checkout', '>=', $checkout);
                            });
                   });
            });
        }

        // Sắp xếp theo giá tăng dần
        $query->orderBy('base_price', 'asc');

        // Phân trang: 5 phòng/trang
        $results = $query->paginate(5);

        return response()->json($results);
    }
    public function autocompletingSearch(Request $r)
    {
        $query = $r->get('query');

        $locations = ["Hà Nội", "Hồ Chí Minh", "TP HCM", "HCM", "Đà Nẵng", "Nha Trang", "Hải Phòng", "Hải Dương", "Cần Thơ", "Huế", "Quảng Ninh", "Bình Thuận", "Đồng Nai", "Kiên Giang", "Bà Rịa - Vũng Tàu", "Thanh Hóa", "An Giang", "Bắc Ninh", "Nam Định", "Thái Nguyên", "Vĩnh Phúc", "Hưng Yên", "Phú Thọ", "Bắc Giang", "Hà Nam", "Hòa Bình", "Lào Cai", "Lạng Sơn", "Cao Bằng", "Tuyên Quang", "Yên Bái", "Sơn La", "Điện Biên", "Hà Giang", "Kon Tum", "Gia Lai", "Đắk Lắk", "Đắk Nông", "Lâm Đồng", "Bình Dương", "Bình Phước", "Long An", "Tiền Giang", "Bến Tre", "Trà Vinh", "Vĩnh Long", "Sóc Trăng", "Bạc Liêu", "Cà Mau", "Hậu Giang", "Đồng Tháp", "Ninh Thuận", "Quảng Nam", "Quảng Ngãi", "Bình Định", "Phú Yên", "Khánh Hòa", "Hà Tĩnh", "Nghệ An", "Quảng Bình", "Quảng Trị",];

        // Lọc những tỉnh thành chứa từ khóa người dùng nhập
        $filtered = array_filter($locations, function ($location) use ($query) {
            return stripos($location, $query) !== false;
        });

        return response()->json([
            'locations' => array_values($filtered),
        ]);

    }
    public function Asearch(Request $request)
    {
        $keyword = trim($request->input('keyword'));
        $selectedLocation = $request->input('selected_location');

        if (!empty($selectedLocation)) {
            session(['location_selected' => $selectedLocation]);
        }

        if (empty($keyword)) {
            return redirect()->route('client.rooms');
        }

        // Validation ngày
        $checkin = $request->input('date_in');
        $checkout = $request->input('date_out');
        
        if (empty($checkin) || empty($checkout)) {
            return redirect()->route('client.rooms')->with('error', 'Vui lòng chọn ngày nhận phòng và ngày trả phòng!');
        }
        
        if ($checkout <= $checkin) {
            return redirect()->route('client.rooms')->with('error', 'Ngày trả phòng phải sau ngày nhận phòng!');
        }
        
        // Kiểm tra ngày không được trong quá khứ
        $now = now();
        $checkinDate = Carbon::parse($checkin);
        $checkoutDate = Carbon::parse($checkout);
        
        if ($checkinDate < $now) {
            return redirect()->route('client.rooms')->with('error', 'Ngày nhận phòng không thể trong quá khứ!');
        }
        
        if ($checkoutDate < $now) {
            return redirect()->route('client.rooms')->with('error', 'Ngày trả phòng không thể trong quá khứ!');
        }

        $people = $request->input('guest', "nope");
        $roomCount = $request->input('room');
        $customGuest = $request->input('custom_guest', "nope");
        
        if (empty($customGuest) && !empty($people)) {
            $co = [
                "cin" => $checkin,
                "cout" => $checkout,
                "pple" => $people,
                "rms" => $roomCount,
                "cppl" => "none",
                "sloc" => $selectedLocation
            ];
        } else {
            $co = [
                "cin" => $checkin,
                "cout" => $checkout,
                "pple" => "none",
                "rms" => $roomCount,
                "cppl" => $customGuest,
                "sloc" => $selectedLocation
            ];
        }

        // Lưu thông tin tìm kiếm vào session
        session([
            'search_dates' => [
                'checkin' => $checkin,
                'checkout' => $checkout,
                'people' => $people,
                'roomCount' => $roomCount,
                'customGuest' => $customGuest
            ]
        ]);

        $results = Room::with(['category'])
            ->where(function ($q) use ($selectedLocation) {
                $q->where('name', 'LIKE', "%$selectedLocation%")
                  ->orWhere('description', 'LIKE', "%$selectedLocation%")
                  ->orWhereHas('category', function($cq) use ($selectedLocation){
                      $cq->where('name','LIKE', "%$selectedLocation%");
                  });
            })
            ->get();

        $notFound = $results->isEmpty();
        
        return response()
            ->view('client.SResult', compact(
                'results',
                "customGuest",
                'keyword',
                'checkin',
                'checkout',
                'people',
                'roomCount',
                'notFound',
                'selectedLocation'
            ))->cookie("bookingdata", json_encode($co), 60 * 24);
    }
    public function old_autocompletingSearch(Request $r)
    {
        $query = $r->get('query');

        $rooms = Room::where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%");
            })
            ->take(10)
            ->get();

        // Chuyển dữ liệu thành dạng phù hợp cho JSON
        $results = $rooms->map(function ($room) {
            return [
                'id' => $room->id,
                'room_name' => $room->name,
                'room_description' => $room->description,
                'hotel_name' => 'NT House',
                'hotel_address' => '123 Đường ABC, Quận 1, TP.HCM'
            ];
        });

        return response()->json($results);
    }
    public function availableRooms(Request $request)
    {
        $checkin = $request->input('date_in');
        $checkout = $request->input('date_out');
        // $people = $request->input('guest', "nope");
        // $customGuest = $request->input('custom_guest', "nope");
        $categoryId = $request->input('category_id', null);

        $start = Carbon::parse($checkin);
        $end = Carbon::parse($checkout);
        $rooms = Room::when($categoryId, fn($q) => $q->where('category_id', $categoryId))->get();
        $availableRooms = [];
        foreach ($rooms as $room) {
            $maxReserved = 0;
            $period = CarbonPeriod::create($start, $end->copy()->subDay());

            foreach ($period as $date) {
                $reserved = Room_reservation::where('room_id', $room->id)
                    ->where('status', '!=', 'cancelled')
                    ->whereDate('start_time', '<=', $date)
                    ->whereDate('end_time', '>', $date)
                    ->sum('reserved_quantity');

                $maxReserved = max($maxReserved, $reserved);
            }
            $totalQuantity = method_exists($room, 'getAttribute') && $room->getAttribute('total_quantity') !== null
                ? (int)$room->getAttribute('total_quantity')
                : 1;
            $availableQty = $totalQuantity - $maxReserved;
            if ($availableQty > 0) {
                $room->available_quantity = $availableQty;
                $availableRooms[] = $room;
            }
        }
        // Logic to find available rooms based on the input parameters
        // This is a placeholder, actual implementation will depend on your database structure and business logic


        return response()->json([
            'rooms' => $availableRooms,
        ]);
    }
    public function a(){
        
    }
    public function search(Request $request){
        $checkin = $request->input('date_in');
        $checkout = $request->input('date_out');
        $people = $request->input('guest', "zero");
        $customGuest = $request->input('custom_guest', null);
        $categoryId = $request->input('category_id', null);

        // Lưu dữ liệu vào session để sử dụng ở các trang khác
        $bookingData = [
            'date_in' => $checkin,
            'date_out' => $checkout,
            'guest' => $people,
            'custom_guest' => $customGuest,
            'category_id' => $categoryId
        ];
        
        // Lọc bỏ các giá trị null/empty
        $bookingData = array_filter($bookingData, function($value) {
            return $value !== null && $value !== '';
        });
        
        // Lưu vào session
        session(['booking_data' => $bookingData]);
        
        


        $query = Room::query();
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        // Lọc phòng theo khoảng ngày: loại bỏ phòng trùng ngày với đơn đã thanh toán/đặt trước
        if (!empty($checkin) && !empty($checkout)) {
            $cin = Carbon::parse($checkin)->startOfDay();
            $cout = Carbon::parse($checkout)->startOfDay();

            // loại bỏ phòng có Bill đã thanh toán (bảng bills) trùng khoảng
            $query->whereNotExists(function($q) use ($cin, $cout) {
                $q->select(DB::raw(1))
                  ->from('bills')
                  ->whereColumn('bills.room_id', 'rooms.id')
                  ->whereIn('status', ['paid','Paid','PAID'])
                  ->whereDate('checkin', '<', $cout->toDateString())
                  ->whereDate('checkout', '>', $cin->toDateString());
            });

            // loại bỏ phòng có Bill qua detailedbills đã paid trùng khoảng
            $query->whereNotExists(function($q) use ($cin, $cout) {
                $q->select(DB::raw(1))
                  ->from('detailedbills')
                  ->join('bills', 'bills.id', '=', 'detailedbills.id_bill')
                  ->whereColumn('detailedbills.id_room', 'rooms.id')
                  ->whereIn('bills.status', ['paid','Paid','PAID'])
                  ->whereDate('bills.checkin', '<', $cout->toDateString())
                  ->whereDate('bills.checkout', '>', $cin->toDateString());
            });

            // loại bỏ phòng có reservation đã confirmed trùng khoảng
            $query->whereDoesntHave('reservations', function($q) use ($cin, $cout) {
                $q->whereIn('status', ['confirmed'])
                  ->whereDate('start_time', '<', $cout->toDateString())
                  ->whereDate('end_time', '>', $cin->toDateString());
            });
        }
        $results = $query->get();

        return response()->view('client.SResult',compact('results','checkin','checkout','people'));
    }
}
