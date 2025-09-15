<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();
        return view('admin.contact.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $errors = [];
        $name = (string) $request->input('name');
        $email = (string) $request->input('email');
        $message = (string) $request->input('message');
        if (trim($name) === '') $errors[] = 'Họ tên là bắt buộc';
        elseif (mb_strlen($name) > 255) $errors[] = 'Họ tên không được quá 255 ký tự';
        if (trim($email) === '') $errors[] = 'Email là bắt buộc';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ';
        if (trim($message) === '') $errors[] = 'Nội dung là bắt buộc';
        elseif (mb_strlen($message) > 1000) $errors[] = 'Nội dung không được quá 1000 ký tự';
        if (!empty($errors)) return back()->withInput()->with('form_errors', $errors);

        Contact::create($request->only(['name','email','message']));

        return redirect(route("client.contact"))->with('success', 'Tin nhắn của bạn đã được gửi đi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
