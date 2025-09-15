<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Admin\ImageStorageController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\api\SearchController;
use App\Http\Controllers\api\CookieController;
use App\Http\Controllers\User\CustomerController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RoomController as UserRoom;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\RoomAmenitiesController;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\DathangController;
// use Faker\Provider\ar_EG\Payment;

Route::get("/privacy-policy", [CustomerController::class, "privacyPolicy"])->name("client.privacy-policy");
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['vi', 'en'])) {
        // Ghi vào session
        session(['locale' => $locale]);

        // Tạo cookie sống 1 năm
        $cookie = Cookie::make('locale', $locale, 60 * 24 * 365); // 1 năm

        // Quay về trang trước và gắn cookie
        return redirect()->back()->withCookie($cookie);
    }

    return response("Invalid locale", 400);
});
//middleware('auth')->
Route::prefix("/")->group(function () {
    Route::get('/', [CustomerController::class, "index"])->name("client.index");
    Route::get("/about-us", [CustomerController::class, "about"])->name("client.about");
    Route::get("/contact", [CustomerController::class, "contact"])->name("client.contact");
    Route::post("/sendcontact", [ContactController::class, "store"])->name("client.postcontact");
    Route::get("/rooms", [UserRoom::class, "index"])->name("client.rooms");
    Route::get("/roomlist/{id}", [UserRoom::class, "CateRoomList"])->name("client.roomlist");
    Route::get("/roomdetail/{id}", [UserRoom::class, "show"])->name("client.roomdetail");
    Route::get("/payment", [PaymentController::class, "index"])->name("client.payment");
     Route::get('/profile', [ProfileController::class, 'show'])->name('client.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('client.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('client.update');
        Route::post("/vnpay-payment",[PaymentController::class,"processPayment"])->name("vnpay-payment");
    Route::post("/review/{id}",[UserRoom::class,"RV"])->name("client.p_review");

});
// Route::get("/sapi", [SearchController::class, "autocompletingSearch"])->name("api.search");
Route::get("/search", [SearchController::class, "search"])->name("search.pending");
// Route::get("/sres", [SearchController::class, "Asearch"])->name("search.result");
Route::get("/api/booking-cookies",[CookieController::class,"cookieRequest"]);
Route::post('/update-booking-cookie', function (Illuminate\Http\Request $request) {
    $loc = $request->input('selected_location');
    return response('OK')->cookie('bookingdata', json_encode(['location' => $loc]), 60 * 24);
});
Route::post("/api/filter-rooms",[SearchController::class,"filter"]);
Route::get("/api/available-rooms", [SearchController::class, "availableRooms"])->name("api.available-rooms");
Route::get("/api/used-room-codes/{floor}", [App\Http\Controllers\Admin\RoomController::class, "getUsedRoomCodes"])->name("api.used-room-codes");

// Booking Session Management Routes
Route::post('/api/booking-session/save', [App\Http\Controllers\BookingSessionController::class, 'saveBookingData'])->name('api.booking-session.save');
Route::get('/api/booking-session/get', [App\Http\Controllers\BookingSessionController::class, 'getBookingData'])->name('api.booking-session.get');
Route::post('/api/booking-session/update', [App\Http\Controllers\BookingSessionController::class, 'updateBookingData'])->name('api.booking-session.update');
Route::post('/api/booking-session/clear', [App\Http\Controllers\BookingSessionController::class, 'clearBookingData'])->name('api.booking-session.clear');
Route::post('/api/booking-session/save-temp', [App\Http\Controllers\BookingSessionController::class, 'saveTemp'])->name('api.booking-session.saveTemp');

Route::prefix("/administrator")->group(function () {
    Route::get("/", [AdminController::class, "index"])->name("admin.index");
    Route::get("/amenities", [RoomAmenitiesController::class, "index"])->name("admin.amenities");
    Route::prefix("contact")->group(function () {
        Route::get("/", [ContactController::class, "index"])->name("admin.contact");
    });
    Route::prefix("/category")->group(function () {
        Route::get("/", [CategoryController::class, "index"])->name("admin.category");
        Route::get("/create", [CategoryController::class, "create"])->name("admin.createcat");
        Route::get("/edit/{id}", [CategoryController::class, "edit"])->name("admin.editcat");
        Route::get("/delete/{id}", [CategoryController::class, "destroy"])->name("admin.delcat");
        Route::post("/add", [CategoryController::class, "store"])->name("admin.addcat");
        Route::post("/update/{id}", [CategoryController::class, "update"])->name("admin.updcat");
    });
    Route::prefix("/account")->group(function () {
        Route::get("/", [UserController::class, "index"])->name("admin.account");
        Route::get("/edit/{id}", [UserController::class, "edit"])->name("admin.edituser");
        Route::post("/update/{id}", [UserController::class, "update"])->name("admin.updateuser");
        Route::get("/delete/{id}", [UserController::class, "destroy"])->name("admin.deleteuser");
    });
    Route::prefix("/room")->group(function () {
        Route::get("/list", [RoomController::class, "index"])->name("admin.roomlist");
        Route::get("/info/{id}", [RoomController::class, "show"])->name("admin.showroom");
        Route::get("/add", [RoomController::class, "create"])->name("admin.addroom");
        Route::get("/addpic/{id}", [RoomController::class, "toStorePic"])->name("admin.tostorepic");
        Route::post("/storepic/{id}", [RoomController::class, "StorePic"])->name("admin.storepic");
        Route::get("/edit/{id}", [RoomController::class, "edit"])->name("admin.editroom");
        Route::get("/del/{id}", [RoomController::class, "destroy"])->name("admin.delroom");
        Route::put("/update/{id}", [RoomController::class, "update"])->name("admin.updroom");
        Route::post("/store", [RoomController::class, "store"])->name("admin.storeroom");
        Route::get("/review/{id}", [ReviewController::class, "listReview"])->name("admin.reviews");
        Route::get("/reserList", [RoomController::class, "reservationList"])->name("admin.reserlist");
    });
    Route::prefix("/storage")->group(function () {
        Route::prefix("/image")->group(function () {
            Route::get("/", [ImageStorageController::class, "index"])->name("storage.image");
            Route::delete("/sdelimg/{id}", [ImageStorageController::class, "destroy"])->name("storage.sdelimg");
            Route::get("/trashed", [ImageStorageController::class, "trash"])->name("storage.trashedimg");
            Route::post("/restore/{id}", [ImageStorageController::class, "restore"])->name("storage.restimg");
            Route::delete("/fdelimg/{id}", [ImageStorageController::class, "fdel"])->name("storage.fdelimg");
            // Route::delete("");
        });
    });
    Route::prefix("/account")->group(function () {
        Route::get("/", [UserController::class, "index"])->name("admin.account");
        Route::get("/add", [UserController::class, "create"])->name("admin.adduser");
        Route::get("/edit/{id}", [UserController::class, "edit"])->name("admin.edituser");
        Route::get("/delete/{id}", [UserController::class, "destroy"])->name("admin.deleteuser");
        Route::put("/update/{id}", [UserController::class, "update"])->name("admin.updateuser");
        Route::post("/store", [UserController::class, "store"])->name("admin.storeuser");
    });
    // 🔻 Route quản lý hóa đơn
    Route::prefix("/bills")->group(function () {
        Route::get("/", [BillController::class, "index"])->name("admin.bills.index");
        Route::get('/show/{bill}', [BillController::class, 'show'])->name('admin.bills.show');
        Route::put("/update-status/{id}", [BillController::class, "updateStatus"])->name("admin.bills.updateStatus");
    });

    // Route::get("/tup",[RoomController::class,"totest"])->name("totest");
    // Route::post("/testupload",[RoomController::class,"uptest"])->name("testing");
});
// Routes đặt phòng
Route::get('/datphong/check-availability', [DathangController::class, 'checkAvailability'])->name('dathang.check');
Route::get('/datphong/{id}', [DathangController::class, 'showForm'])->name('dathang.form');
Route::post('/datphong/store', [DathangController::class, 'store'])->name('dathang.store');

Route::post('/datphong/process-payment', [DathangController::class, 'processPayment'])->name('dathang.process-payment');
Route::get('/datphong/success', [DathangController::class, 'paymentSuccess'])->name('dathang.success');
Route::get('/datphong/cancel', [DathangController::class, 'paymentCancel'])->name('dathang.cancel');

// Routes thanh toán VNPay
Route::get('/payment/{bill_id}', [PaymentController::class, 'showPayment'])->name('payment.show');
Route::get('/payment/vnpay/{bill_id}', [PaymentController::class, 'processVNPay'])->name('payment.vnpay');
Route::get('/payment/vnpay/return', [PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');
Route::get('/payment/history', [PaymentController::class, 'showPaymentHistory'])->name('payment.history');



    Route::get('/administrator/login', [AuthController::class, 'adminLogin'])->name('admin.login');
    Route::post('/administrator/Plogin', [AuthController::class, 'postAdminLogin'])->name('admin.auth');
    Route::get('/administrator/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');



Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister'])->name('postRegister');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin'])->name('postLogin');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/test-auth', function() {
    return [
        'user' => Auth::user(),
        'session' => session()->all(),
        'cookie' => request()->cookie(),
    ];
});