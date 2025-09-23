<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (!Schema::hasColumn('bills', 'guest_cccd')) {
                $table->string('guest_cccd')->nullable()->after('guest_phone');
            }
        });

        Schema::table('room_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('room_reservations', 'guest_cccd')) {
                $table->string('guest_cccd')->nullable()->after('guest_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (Schema::hasColumn('bills', 'guest_cccd')) {
                $table->dropColumn('guest_cccd');
            }
        });

        Schema::table('room_reservations', function (Blueprint $table) {
            if (Schema::hasColumn('room_reservations', 'guest_cccd')) {
                $table->dropColumn('guest_cccd');
            }
        });
    }
};
