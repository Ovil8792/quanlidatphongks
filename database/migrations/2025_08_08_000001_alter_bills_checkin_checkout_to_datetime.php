<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Đổi kiểu cột từ DATE -> DATETIME mà không cần doctrine/dbal
        DB::statement('ALTER TABLE bills MODIFY checkin DATETIME NULL');
        DB::statement('ALTER TABLE bills MODIFY checkout DATETIME NULL');
    }

    public function down(): void
    {
        // Trả về DATE (sẽ làm mất phần giờ)
        DB::statement('ALTER TABLE bills MODIFY checkin DATE NULL');
        DB::statement('ALTER TABLE bills MODIFY checkout DATE NULL');
    }
};


