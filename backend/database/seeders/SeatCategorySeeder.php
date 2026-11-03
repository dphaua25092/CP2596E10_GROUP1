<?php

namespace Database\Seeders;

use App\Models\SeatCategory;
use Illuminate\Database\Seeder;

class SeatCategorySeeder extends Seeder
{
    public function run(): void
    {
        SeatCategory::create(['code' => 'GOLD', 'name' => 'Gold', 'color' => '#F2B705']);
        SeatCategory::create(['code' => 'PLATINUM', 'name' => 'Platinum', 'color' => '#8E9AAF']);
        SeatCategory::create(['code' => 'BOX', 'name' => 'Box', 'color' => '#C0392B']);
    }
}
