<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Amenity;
use App\Models\User;
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
    }
}
