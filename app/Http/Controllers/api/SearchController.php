<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SearchController extends Controller
{
    public function filter(Request $request)
    {
        $cin = $request->input("date_in");
        $cout = $request->input("date_out");
        $people = $request->input("guest");
        $rooms = $request->input("rooms");
        $customGuest = $request->input("customGuest");
        // $loc = $request->input("location");

        $query = Room::query();
        if (!empty($request->input("room_type", []))) {
            $query->where("category_id", $request->room_type)->get();
        }
        if(!empty($request->input("price_range",[]))){
            $query->where(function ($q) use ($request) {
            foreach ($request->price_range as $range) {
                if ($range === 'low') {
                    $q->orWhere('price', '<', 1000000);
                } elseif ($range === 'medium') {
                    $q->orWhereBetween('price', [1000000, 3000000]);
                } elseif ($range === 'high') {
                    $q->orWhere('price', '>', 3000000);
                }
            }
        });
        if (!empty($request->amenities)) {
        foreach ($request->amenities as $amenity) {
            $query->where('amenities', 'LIKE', "%$amenity%");
        }
    }
        }

        $results = $query->limit(20)->paginate(10);

    return response()->json([
        'results' => $results
    ]);
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
    public function search(Request $request)
    {
        $keyword = trim($request->input('keyword'));
        // dd($keyword);
        $selectedLocation = $request->input('selected_location');

        if (!empty($selectedLocation)) {
            session(['location_selected' => $selectedLocation]);
        }

        if (empty($keyword)) {
            return redirect()->route('client.rooms');
        }
        $co = [];
        $checkin = $request->input('date_in');
        $checkout = $request->input('date_out');
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



        $results = Room::with('hotel')
            ->where(function ($q) use ($selectedLocation) {
                $q->where('name', 'LIKE', "%$selectedLocation%")
                    ->orWhereHas('hotel', function ($q) use ($selectedLocation) {
                        $q->where('name', 'LIKE', "%$selectedLocation%")
                            ->orWhere('address', 'LIKE', "%$selectedLocation%");
                    });
            })
            ->get();

        
        foreach($results as $k=>$v){
            $htname= Hotel::findOrFail($v->id)["name"];
            // dd($htname);
            $results[$k]["hotel_name"]=$htname;
            // dd($results);
        }
        $notFound = $results->isEmpty();
        // dd($results);
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

        $rooms = Room::with('hotel')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%");
            })
            ->orWhereHas('hotel', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('address', 'LIKE', "%$query%")
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
                'hotel_name' => $room->hotel->name ?? '',
                'hotel_address' => $room->hotel->address ?? ''
            ];
        });

        return response()->json($results);
    }
}
