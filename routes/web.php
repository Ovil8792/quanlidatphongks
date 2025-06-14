<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ImageStorageController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, [ 'vi','en'])) {
        session(['locale' => $locale]);
    }
    return redirect(route("client.index"));
});
Route::middleware( 'auth')->prefix("/")->group(function(){
    Route::get('/', [CustomerController::class,"index"])->name("client.index");
});

Route::prefix("/administrator")->group(function(){
    Route::get("/",[AdminController::class,"index"])->name("admin.index");
    Route::prefix("/category")->group(function () {
        Route::get("/",[CategoryController::class,"index"])->name("admin.category");
        Route::get("/create",[CategoryController::class,"create"])->name("admin.createcat");
        Route::get("/edit/{id}",[CategoryController::class,"edit"])->name("admin.editcat");
        Route::get("/delete/{id}",[CategoryController::class,"destroy"])->name("admin.delcat");
        Route::post("/add",[CategoryController::class,"store"])->name("admin.addcat");
        Route::post("/update/{id}",[CategoryController::class,"update"])->name("admin.updcat");
    });
    Route::prefix("/room")->group(function(){
        Route::get("/list",[RoomController::class,"index"])->name("admin.roomlist");
        Route::get("/add",[RoomController::class,"create"])->name("admin.addroom");
        Route::get("/edit/{id}",[RoomController::class,"edit"])->name("admin.editroom");
        Route::get("/del/{id}",[RoomController::class,"destroy"])->name("admin.delroom");
        Route::put("/update/{id}",[RoomController::class,"update"])->name("admin.updroom");
        Route::post("/store",[RoomController::class,"store"])->name("admin.storeroom");
    });
    Route::prefix("/storage")->group(function(){
        Route::prefix("/image")->group(function(){
            Route::get("/",[ImageStorageController::class,"index"])->name("storage.image");
        });
    });
    // Route::get("/tup",[RoomController::class,"totest"])->name("totest");
    // Route::post("/testupload",[RoomController::class,"uptest"])->name("testing");
});



    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'postRegister'])->name('postRegister');

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'postLogin'])->name('postLogin');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
