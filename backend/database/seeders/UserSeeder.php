<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Mật khẩu mọi tài khoản: password
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Các admin đồng cấp. admin.cu đã có tác vụ nên chỉ bị vô hiệu hóa (xem AdminLogSeeder),
        // admin2 chưa có tác vụ nào nên xóa được.
        User::factory()->admin()->create([
            'full_name' => 'Quản trị viên',
            'email' => 'admin@cinebook.vn',
            'phone' => '0900000001',
            'created_at' => now()->subDays(200),
        ]);
        User::factory()->admin()->create([
            'full_name' => 'Quản trị viên 2',
            'email' => 'admin2@cinebook.vn',
            'phone' => '0900000002',
            'created_at' => now()->subDays(100),
        ]);
        User::factory()->admin()->inactive()->create([
            'full_name' => 'Quản trị viên cũ',
            'email' => 'admin.cu@cinebook.vn',
            'phone' => '0900000003',
            'created_at' => now()->subDays(300),
        ]);

        $cityIds = City::where('is_active', true)->pluck('id')->all();
        $languageIds = Language::pluck('id')->all();

        User::factory()->create([
            'full_name' => 'Nguyễn Văn Khách',
            'email' => 'khachhang@cinebook.vn',
            'phone' => '0911111111',
            'birth_date' => '2000-05-15',
            'preferred_city_id' => City::where('name', 'Cần Thơ')->value('id'),
            'preferred_language_id' => Language::where('code', 'vi')->value('id'),
            'created_at' => now()->subDays(90),
        ]);

        for ($i = 0; $i < 120; $i++) {
            User::factory()->create([
                'preferred_city_id' => mt_rand(0, 4) ? $cityIds[array_rand($cityIds)] : null,
                'preferred_language_id' => mt_rand(0, 4) ? $languageIds[array_rand($languageIds)] : null,
                'created_at' => now()->subDays(mt_rand(20, 180))->subMinutes(mt_rand(0, 1439)),
            ]);
        }

        // Tài khoản bị khóa
        User::factory()->inactive()->create([
            'full_name' => 'Trần Thị Bị Khóa',
            'email' => 'bikhoa@cinebook.vn',
            'phone' => '0922222222',
            'created_at' => now()->subDays(60),
        ]);
    }
}
