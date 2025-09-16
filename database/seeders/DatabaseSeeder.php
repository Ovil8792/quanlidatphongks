<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Amenity;
use App\Models\User;
use App\Models\Statistical;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert([
            "name"=>"test",
            "email"=>"test@test.test",
            "password"=>bcrypt("test"),
            "role"=>"admin",
            "created_at"=>now(),
        ]);
        Category::Insert([
            ["name" => "Phòng view đẹp","created_at"=>now()],
            ["name" => "Phòng gia đình","created_at"=>now()],
            ["name" => "Phòng Tổng Thống","created_at"=>now()],
            ["name" => "Phòng bình dân","created_at"=>now()],
            ["name" => "Phòng Vip","created_at"=>now()],
        ]);
        
        Amenity::Insert([[
            "name"=> "Bữa sáng miễn phí",
            "created_at" => now(),
        ],[
            "name"=> "Có bãi đỗ xe",
            "created_at" => now(),
        ],[
            "name"=> "Wifi miễn phí",
            "created_at" => now(),
        ],[
            "name"=> "Máy lạnh",
            "created_at" => now(),
        ],[
            "name"=> "Bồn tắm",
            "created_at" => now(),
        ],[
            "name"=>"Có phòng gym",
            "created_at"=>now(),
        ]]);
        Statistical::insert([
            [
                'date' => now()->subDays(7)->toDateString(),
                'total_bookings' => 15,
                'total_customers' => 12,
                'total_revenue' => 25000000,
                'rooms_occupied' => 8,
                'rooms_available' => 32,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'date' => now()->subDays(6)->toDateString(),
                'total_bookings' => 22,
                'total_customers' => 18,
                'total_revenue' => 32000000,
                'rooms_occupied' => 12,
                'rooms_available' => 28,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'date' => now()->subDays(5)->toDateString(),
                'total_bookings' => 18,
                'total_customers' => 15,
                'total_revenue' => 28000000,
                'rooms_occupied' => 10,
                'rooms_available' => 30,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'date' => now()->subDays(4)->toDateString(),
                'total_bookings' => 25,
                'total_customers' => 20,
                'total_revenue' => 38000000,
                'rooms_occupied' => 15,
                'rooms_available' => 25,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'date' => now()->subDays(3)->toDateString(),
                'total_bookings' => 20,
                'total_customers' => 16,
                'total_revenue' => 30000000,
                'rooms_occupied' => 12,
                'rooms_available' => 28,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'date' => now()->subDays(2)->toDateString(),
                'total_bookings' => 28,
                'total_customers' => 22,
                'total_revenue' => 42000000,
                'rooms_occupied' => 18,
                'rooms_available' => 22,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'date' => now()->subDays(1)->toDateString(),
                'total_bookings' => 24,
                'total_customers' => 19,
                'total_revenue' => 36000000,
                'rooms_occupied' => 14,
                'rooms_available' => 26,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'date' => now()->toDateString(),
                'total_bookings' => 16,
                'total_customers' => 13,
                'total_revenue' => 24000000,
                'rooms_occupied' => 9,
                'rooms_available' => 31,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
