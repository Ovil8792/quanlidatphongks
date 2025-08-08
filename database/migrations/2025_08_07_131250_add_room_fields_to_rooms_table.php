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
        Schema::table('rooms', function (Blueprint $table) {
            $table->float('room_area')->nullable()->after('isInUse');
            $table->float('bathroom_area')->nullable()->after('room_area');
            $table->integer('max_guests')->nullable()->after('bathroom_area');
            $table->integer('bed_count')->nullable()->after('max_guests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['room_area', 'bathroom_area', 'max_guests', 'bed_count']);
        });
    }
};
