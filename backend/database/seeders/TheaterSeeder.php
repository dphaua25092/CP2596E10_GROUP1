<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\SeatCategory;
use App\Models\Theater;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TheaterSeeder extends Seeder
{
    // Sơ đồ ghế mỗi phòng: hàng => [hạng ghế, số ghế]. Sức chứa 90 ghế.
    private const LAYOUT = [
        'A' => ['GOLD', 12], 'B' => ['GOLD', 12], 'C' => ['GOLD', 12], 'D' => ['GOLD', 12], 'E' => ['GOLD', 12],
        'F' => ['PLATINUM', 12], 'G' => ['PLATINUM', 12],
        'H' => ['BOX', 6],
    ];

    public function run(): void
    {
        $cityIds = City::pluck('id', 'name');
        $categoryIds = SeatCategory::pluck('id', 'code');

        // [thành phố, tên rạp, địa chỉ, điện thoại, hoạt động]
        $theaters = [
            ['Cần Thơ', 'CineBook Ninh Kiều', 'Số 2 Đại lộ Hòa Bình, P. Tân An, Q. Ninh Kiều', '02923000001', true],
            ['Cần Thơ', 'CineBook Cái Răng', 'Số 15 Võ Nguyên Giáp, P. Phú Thứ, Q. Cái Răng', '02923000002', true],
            ['Cần Thơ', 'CineBook Bình Thủy', 'Số 8 Lê Hồng Phong, P. Bình Thủy, Q. Bình Thủy', '02923000003', false],
            ['Vĩnh Long', 'CineBook Vĩnh Long', 'Số 20 Phạm Thái Bường, P. 4, TP. Vĩnh Long', '02703000001', true],
            ['An Giang', 'CineBook Long Xuyên', 'Số 5 Trần Hưng Đạo, P. Mỹ Bình, TP. Long Xuyên', '02963000001', true],
            ['TP. Hồ Chí Minh', 'CineBook Quận 1', 'Số 72 Lê Thánh Tôn, P. Bến Nghé, Quận 1', '02838000001', true],
            ['TP. Hồ Chí Minh', 'CineBook Thủ Đức', 'Số 216 Võ Văn Ngân, P. Bình Thọ, TP. Thủ Đức', '02838000002', true],
            ['Hà Nội', 'CineBook Cầu Giấy', 'Số 91 Trần Duy Hưng, P. Trung Hòa, Q. Cầu Giấy', '02438000001', true],
        ];

        foreach ($theaters as [$city, $name, $address, $phone, $active]) {
            $theater = Theater::create([
                'city_id' => $cityIds[$city],
                'name' => $name,
                'address' => $address,
                'phone' => $phone,
                'is_active' => $active,
                'created_at' => now()->subDays(180),
                'updated_at' => now()->subDays(180),
            ]);

            foreach ([['P01', 'Tầng 3'], ['P02', 'Tầng 3'], ['P03', 'Tầng 4']] as $i => [$code, $location]) {
                $screen = $theater->screens()->create(['code' => $code, 'location' => $location]);

                $seats = [];
                foreach (self::LAYOUT as $row => [$category, $count]) {
                    for ($number = 1; $number <= $count; $number++) {
                        $seats[] = [
                            'screen_id' => $screen->id,
                            'row_label' => $row,
                            'seat_number' => $number,
                            'seat_category_id' => $categoryIds[$category],
                            // phòng P02 có 2 ghế hỏng ở đầu và cuối hàng A
                            'is_active' => ! ($i === 1 && $row === 'A' && in_array($number, [1, 12])),
                        ];
                    }
                }
                DB::table('seats')->insert($seats);
            }
        }
    }
}
