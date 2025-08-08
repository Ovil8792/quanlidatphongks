<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;
use App\Models\khoanh;
use App\Models\Room;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Models\Bill; // Added this import for Bill model

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchId = $request->query('search_id');
        $searchName = $request->query('search_name');

        $query = Room::with(['category:id,name', 'hotel:id,name']);

        if (!empty($searchId)) {
            $query->where('id', (int) $searchId);
        }
        if (!empty($searchName)) {
            $query->where('name', 'like', '%'.$searchName.'%');
        }

        $roomlist = $query->get();

        foreach ($roomlist as $k => $v) {
            $roomlist[$k]["category_name"] = $v->category?->name ?? '';
            $roomlist[$k]["hotel_name"] = $v->hotel?->name ?? '';
        }
        return view("admin.Room.index", compact("roomlist", "searchId", "searchName"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $catelist = Category::get();
        $hotel= Hotel::get();
        return view("admin.Room.add",compact("catelist","hotel"));
    }
    public function totest(){
        return view("test");
    }
    public function uptest(Request $request){
        if($request->hasFile("pimage")){
            $img = $request->file("pimage");
            $filename=time().'_'.$img->getClientOriginalName();
            $img->storeAs("/upload",$filename);
        }else{
            echo "error";
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = $request->input("name");
        $cate = $request->input("category");
        $amenities = $request->input("amenities");
        $hotel = $request->input("hotel");
        $bprice = $request->input("baseprice");
        $filename = "" ;
        $desc = $request->input("desc","");
        $room_area = $request->input("room_area");
        $bathroom_area = $request->input("bathroom_area");
        $max_guests = $request->input("max_guests");
        $bed_count = $request->input("bed_count");
        
        if($request->hasFile("pimage")){
            $img = $request->file("pimage");
            $filename=time().'_'.$img->getClientOriginalName();
            $img->storeAs("/upload",$filename);
        }
        
        Room::create([
            "name" => $name,
            "category_id" => $cate,
            "pimage" => $filename,
            "description" => $desc,
            "amenities" => $amenities,
            "hotel_id" => $hotel,
            "base_price" => $bprice,
            "room_area" => $room_area,
            "bathroom_area" => $bathroom_area,
            "max_guests" => $max_guests,
            "bed_count" => $bed_count
        ]);
        
        $idnewroom = Room::where("name",$name)->get();
        khoanh::create(["imgname"=>$filename,"roomid"=>$idnewroom[0]->id]);
        return redirect(route("admin.roomlist"));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $roominf = Room::with(['category:id,name', 'hotel:id,name'])->findOrFail($id);
        $roominf["hotel_name"] = $roominf->hotel?->name ?? '';
        $roominf["category_name"] = $roominf->category?->name ?? '';
        
        // Lấy thông tin đặt phòng hiện tại từ detailedbills nếu phòng đang được sử dụng
        $activeBooking = null;
        if ($roominf->isInUse == 1) {
            $activeBooking = \App\Models\DetailedBill::where('id_room', $id)
                ->with(['bill.user', 'bill'])
                ->whereHas('bill', function($query) {
                    $query->where('status', '!=', 'cancelled')
                          ->where('checkin', '<=', now())
                          ->where('checkout', '>=', now());
                })
                ->first();
        }
        
        $imglist = khoanh::where("roomid",$roominf->id)->get();
        return view("admin.Room.info",compact("roominf","imglist","id","activeBooking"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $roomdata = Room::findOrFail($id);
        $hotels = Hotel::get();
        $cat = Category::get();
        return view("admin.Room.edit",compact("roomdata","cat","hotels"));
    }
    public function toStorePic($id){
        return view("admin.Room.store",compact("id"));
    }

    public function StorePic($id,Request $request){
        if($request->hasFile("images")){
            foreach($request->file("images") as $file){
                $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();
                $file->storeAs("/upload",$filename);
                khoanh::create([
                    'imgname'=>$filename,
                    'roomid'=>$id
                ]);
            }
        }else{
            return back()->withErrors(["err"=>"lỗi tải file lên"]);
        }
        // khoanh::create([]);
        return redirect(route("admin.showroom",["id"=>$id]));
    }
    /**
     * Update the specified resource in storage.
     */

     
    public function update(Request $request,$id)
    {
        $filename = "";
        $name = $request->input("name");
        $cat = $request->input("category");
        $desc = $request->input("desc");
        $ame = $request->input("amenities");
        $hotel = $request->input("hotel");
        $bprice = $request->input("price");
        $room_area = $request->input("room_area");
        $bathroom_area = $request->input("bathroom_area");
        $max_guests = $request->input("max_guests");
        $bed_count = $request->input("bed_count");
        $oimg = $request->input("old_img");
        
        $updateData = [
            "name" => $name,
            "category_id" => $cat,
            "description" => $desc,
            "amenities" => $ame,
            "hotel_id" => $hotel,
            "base_price" => $bprice,
            "room_area" => $room_area,
            "bathroom_area" => $bathroom_area,
            "max_guests" => $max_guests,
            "bed_count" => $bed_count
        ];
        
        if($request->hasFile("pimage")){
            $img = $request->file("pimage");
            $filename = time()."_".$img->getClientOriginalName();
            $img->storeAs("/upload",$filename);
            $updateData["pimage"] = $filename;
        } else {
            $updateData["pimage"] = $oimg;
        }
        
        Room::where("id",$id)->update($updateData);
        return redirect(route("admin.roomlist"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Room::destroy($id);
        return redirect(route("admin.roomlist"));
    }
}
