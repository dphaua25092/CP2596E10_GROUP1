<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = ['Hành động', 'Hài', 'Tình cảm', 'Kinh dị', 'Hoạt hình',
            'Khoa học viễn tưởng', 'Tâm lý', 'Phiêu lưu', 'Gia đình', 'Hình sự'];

        foreach ($genres as $name) {
            Genre::create(['name' => $name]);
        }
    }
}
