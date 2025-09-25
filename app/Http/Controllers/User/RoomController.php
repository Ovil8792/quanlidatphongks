<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\khoanh;
use App\Models\Room;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::limit(4)->get();
        return view("client.room.list",compact("rooms"));
    }
    public function CateRoomList(int $id){
        $catname = Category::findOrFail($id);
        $listbycat = Room::where("category_id",$id)->get();
                // dd($listbycat);
        foreach($listbycat as $k=>$v){
            $categoryname = Category::findOrFail($id)->name;
            $listbycat[$k]->categoryname = $categoryname;
            
        }
        session::put("curcat", $catname->name);

        return view("client.room.list",compact("listbycat","catname"));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $room = Room::findOrFail($id);
        $imglist = khoanh::where("roomid", $id)->get();
        $reviews = Review::with('user')->where('roomid', $id)->latest()->get();
        
        // Lấy dữ liệu đặt phòng từ session
        $bookingData = session('booking_data', []);
        
        return view("client.room.detail", compact("room", "imglist", "bookingData", "reviews"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function RV(Request $request, int $roomid){
        // Chỉ cho phép người dùng đã đăng nhập đánh giá
        if (!Auth::check()) {
            return redirect()->back()
                ->with('error', 'Vui lòng đăng nhập để đánh giá phòng.')
                ->with('auth_modal', 'login');
        }

        $cmt = $request->comment;
        $rtype = $request->rate;
        if($rtype == "number"){
            // dd($request->rating_number);
            $rating = $request->rating_number;
        }elseif($rtype == "star"){
            // dd($request->rating);
            $rating = $request->rating;

        }

        Review::create([
            "userid" => Auth::id(),
            "roomid" => $roomid,
            "rating" => $rating,
            "comment" => $cmt,
            "created_at" => now(),
        ]);
        return redirect()->back()->with("success","Đánh giá thành công");
    }
}
