<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_reservations', function (Blueprint $table) {
            // Cho phép user_id null để khách chưa đăng nhập vẫn lưu được
            $table->unsignedBigInteger('user_id')->nullable()->change();
            // Thêm thông tin khách đặt
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('temp_uid')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('room_reservations', function (Blueprint $table) {
            $table->dropColumn(['guest_name','guest_email','guest_phone','temp_uid']);
            // Không thể dễ dàng revert nullable() trong down an toàn mà không biết dữ liệu, bỏ qua
        });
    }
};


