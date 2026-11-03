<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            'vi' => 'Tiếng Việt',
            'en' => 'Tiếng Anh',
            'ko' => 'Tiếng Hàn',
            'ja' => 'Tiếng Nhật',
            'zh' => 'Tiếng Trung',
            'th' => 'Tiếng Thái',
        ];

        foreach ($languages as $code => $name) {
            Language::create(['code' => $code, 'name' => $name]);
        }
    }
}
