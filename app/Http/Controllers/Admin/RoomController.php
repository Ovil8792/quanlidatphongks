<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;
use App\Models\khoanh;
use App\Models\Room;

use App\Http\Controllers\Controller;
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

        $query = Room::with(['category:id,name']);

        if (!empty($searchId)) {
            $query->where('id', (int) $searchId);
        }
        if (!empty($searchName)) {
            $query->where('name', 'like', '%'.$searchName.'%');
        }

        $roomlist = $query->get();

        foreach ($roomlist as $k => $v) {
            $roomlist[$k]["category_name"] = $v->category?->name ?? '';
            $roomlist[$k]["isInUse"] = 0;
        }
        return view("admin.Room.index", compact("roomlist", "searchId", "searchName"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $catelist = Category::get();
        return view("admin.Room.add",compact("catelist"));
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
        $code = $request->input("code");
        $floor = $request->input("floor");
        $require = $request->input("requirements");
        $status = $request->input("status","available");
        $cate = $request->input("category");
        
        // Xử lý amenities từ checkbox và text tùy chỉnh
        $amenitiesCheckbox = $request->input("amenities_checkbox", []);
        $amenitiesCustom = $request->input("amenities_custom", "");
        
        $amenityText = "";
        if (!empty($amenitiesCheckbox)) {
            $amenityText = implode(", ", $amenitiesCheckbox);
        }
        if (!empty($amenitiesCustom)) {
            if (!empty($amenityText)) {
                $amenityText .= ", " . $amenitiesCustom;
            } else {
                $amenityText = $amenitiesCustom;
            }
        }
        
        $bprice = $request->input("price");
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
        
           $room =  Room::create([
        "name" => $name,
        "code" => $code,
        "floor" => $floor,
        "requirements" => $require,
        "status" => $status,
        "category_id" => $cate,
        "pimage" => $filename,
        "description" => $desc,
        "base_price" => $bprice,
        "room_area" => $room_area,
            "bathroom_area" => $bathroom_area,
            "max_guests" => $max_guests,
            "bed_count" => $bed_count,
            "amenities" => $amenityText,
    ]);
            // dd($idnewroom);
            khoanh::create(["imgname" => $filename, "roomid" => $room->id]);
            return redirect(route("admin.roomlist"));
        // } catch (\Throwable $th) {
        //     throw $th;
        // }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $roominf = Room::findOrFail($id);
        $imglist = khoanh::where("roomid",$roominf->id)->get();

        // helper fields for view
        $roominf->category_name = optional($roominf->category)->name;
        $roominf->isInUse = 0;
        $activeBooking = null;

        try {
            $activeBooking = \App\Models\DetailedBill::with(['bill.user'])
                ->where('id_room', $id)
                ->whereHas('bill', function($q){
                    $q->whereIn('status', ['pending','paid'])
                      ->whereDate('checkin', '<=', now())
                      ->whereDate('checkout', '>=', now());
                })
                ->first();
            if ($activeBooking) {
                $roominf->isInUse = 1;
            }
        } catch (\Throwable $e) {}

        return view("admin.Room.info",compact("roominf","imglist","id","activeBooking"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $roomdata = Room::findOrFail($id);
        $cat = Category::get();
        // Không còn sử dụng relationship amenities nữa
        // $ame = Amenity::all();
        // $roomAmenityIds = $roomdata->amenities->pluck('id')->toArray();
        return view("admin.Room.edit",compact("roomdata","cat"));
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
        $room = Room::findOrFail($id);
        $name = $request->input("name");
        $code = $request->input("code");
        $floor = $request->input("floor");
        $require = $request->input("requirements");
        $status = $request->input("status","available");
        $cate = $request->input("category");
        
        // Xử lý amenities từ checkbox và text tùy chỉnh
        $amenitiesCheckbox = $request->input("amenities_checkbox", []);
        $amenitiesCustom = $request->input("amenities_custom", "");
        
        $amenityText = "";
        if (!empty($amenitiesCheckbox)) {
            $amenityText = implode(", ", $amenitiesCheckbox);
        }
        if (!empty($amenitiesCustom)) {
            if (!empty($amenityText)) {
                $amenityText .= ", " . $amenitiesCustom;
            } else {
                $amenityText = $amenitiesCustom;
            }
        }
        
        $bprice = $request->input("price");
        $filename = "" ;
        $desc = $request->input("desc","");
        $room_area = $request->input("room_area");
        $bathroom_area = $request->input("bathroom_area");
        $max_guests = $request->input("max_guests");
        $bed_count = $request->input("bed_count");
        
        if($request->hasFile("pimage")){
            $room->update([
        "name" => $name,
        "code" => $code,
        "floor" => $floor,
        "requirements" => $require,
        "status" => $status,
        "category_id" => $cate,
        "pimage" => $filename,
        "description" => $desc,
        "base_price" => $bprice,
        "room_area" => $room_area,
            "bathroom_area" => $bathroom_area,
            "max_guests" => $max_guests,
            "bed_count" => $bed_count,
            "amenities" => $amenityText
    ]);
            // dd($idnewroom);
           
                
            $img = $request->file("pimage");
            $filename=time().'_'.$img->getClientOriginalName();
            $img->storeAs("/upload",$filename);
             khoanh::where("roomid",$id)->update(["imgname" => $filename, "roomid" => $room->id]);
        }else{
            $room->update([
        "name" => $name,
        "code" => $code,
        "floor" => $floor,
        "requirements" => $require,
        "status" => $status,
        "category_id" => $cate,
        "pimage" => $room->pimage,
        "description" => $desc,
        "base_price" => $bprice,
        "room_area" => $room_area,
            "bathroom_area" => $bathroom_area,
            "max_guests" => $max_guests,
            "bed_count" => $bed_count,
            "amenities" => $amenityText
    ]);
            // dd($idnewroom);
        }
        return redirect(route('admin.roomlist'));
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
