<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            // Cho phép null user
            $table->unsignedBigInteger('user_id')->nullable();

            $table->unsignedBigInteger('room_id')->nullable();

            $table->decimal('total', 10, 2);
            $table->string('status')->default('pending');
            $table->text('note')->nullable();

            $table->date('checkin')->nullable();
            $table->date('checkout')->nullable();

            $table->dateTime('booking_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('payment_date')->nullable();

            // Thông tin khách (nếu không có user)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();

            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
