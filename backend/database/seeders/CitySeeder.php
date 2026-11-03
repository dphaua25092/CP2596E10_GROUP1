<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Cần Thơ', 'Vĩnh Long', 'An Giang', 'TP. Hồ Chí Minh', 'Hà Nội'] as $name) {
            City::create(['name' => $name]);
        }

        // Thành phố đã tắt, chưa có rạp: dùng để thử chức năng xóa / ẩn thành phố
        City::create(['name' => 'Sóc Trăng', 'is_active' => false]);
    }
}
