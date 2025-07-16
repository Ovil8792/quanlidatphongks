<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReviewController extends Controller
{

    public function listReview($id){
        $room = Room::get();
        $rn = $room->where("id",$id)->first();
        $user = User::get();
        $rv = Review::where("roomid",$id)->get();
        foreach($rv as $r=>$v){
            $rv[$r]->usern = $user->where("id",$v->userid)->first()->name ?? "Unknown User";
            $rv[$r]->roomn = $room->where("id",$v->roomid)->first()->name ?? "Unknown Room";
        }

        return view("admin.Room.review",compact("rv","rn","id"));
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
    public function show(string $id)
    {
        //
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
}
