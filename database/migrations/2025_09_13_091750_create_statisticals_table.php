<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('statisticals', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // The date for which the statistics are recorded
            $table->integer('total_bookings')->default(0); // Total number of bookings
            $table->integer('total_customers')->default(0); // Total number of customers
            $table->decimal('total_revenue', 15, 2)->default(0); // Total revenue for the day
            $table->integer('rooms_occupied')->default(0); // Number of rooms occupied
            $table->integer('rooms_available')->default(0); // Number of rooms available
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statisticals');
    }
};
