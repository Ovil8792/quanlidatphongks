<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotel = Hotel::get();
        return view('admin.Hotel.index', compact('hotel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Hotel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'address' => 'required|string|max:255',
        //     'pimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'description' => 'nullable|string',
        // ]);

        $hotel = new Hotel();
        $hotel->name = $request->name;
        $hotel->address = $request->address;
        $hotel->description = $request->descr;
        $hotel->rooms= $request->rooms;
        $hotel->pimage = "";
        if ($request->hasFile('pimage')) {
            $image = $request->file('pimage');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('/upload', $filename);
            $hotel->pimage = $filename;
        }
        
        $hotel->save();

        return redirect()->route('admin.hotel')->with('success', 'Hotel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('admin.Hotel.edit', compact('hotel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $name = $request->name;
        $address=$request->address;
        $rooms = $request->rooms;
        $description = $request->descr;
        $pimage = "";
        if ($request->hasFile('pimage')) {
            $image = $request->file('pimage');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('/upload', $filename);
            $pimage = $filename;
        }
        Hotel::update([
            "name"=>$name,
            "address"=>$address,
            "rooms"=>$rooms,
            "pimage"=>$pimage,
            "description"=>$description,
        ]);
        return redirect(route("admin.hotel"))->with("success","Sửa thành công!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
