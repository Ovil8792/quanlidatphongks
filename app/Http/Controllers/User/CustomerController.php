<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function about(){
        return view("client.about");
    }
    public function contact(){
        return view("client.contact");
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $room= Room::where("isInUse",0)->get();
        $cat = Category::get();
        $checkactive = ["home","about","contact","news","other","rooms"];
        session::put(["active"=>$checkactive]);
        // session::put(["locale"=>"vi"]);
        session::put(["category"=>$cat]);
        return view("client.index",compact("cat","room"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function privacyPolicy()
    {
        return view("client.privacy-policy");
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
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
