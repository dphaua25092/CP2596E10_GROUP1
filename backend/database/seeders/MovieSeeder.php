<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use App\Models\Person;
use Illuminate\Database\Seeder;

/**
 * Phim và người làm phim đều là hư cấu. Ngày chiếu tính theo hôm nay để luôn có
 * phim đang chiếu, sắp chiếu và đã ngừng chiếu. Poster / trailer bổ sung sau.
 */
class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $people = [
            // đạo diễn
            'Lê Minh Khang', 'Trần Hoài Nam', 'Nguyễn Thu Hà', 'Daniel Brooks',
            'Emily Carter', 'Park Ji-won', 'Sato Kenji', 'Phạm Quốc Bảo',
            // diễn viên
            'Võ Thanh Tâm', 'Huỳnh Gia Hân', 'Đặng Khôi Nguyên', 'Bùi Ngọc Trâm',
            'Châu Minh Đức', 'Lâm Bảo Ngọc', 'Tô Gia Huy', 'Ryan Mitchell',
            'Olivia Hayes', 'Marcus Reed', 'Kim Seo-yeon', 'Han Min-jae', 'Tanaka Yui',
        ];
        $personIds = [];
        foreach ($people as $name) {
            $personIds[$name] = Person::create([
                'full_name' => $name,
                'bio' => "{$name} là nghệ sĩ hư cấu dùng cho dữ liệu mẫu của CineBook.",
            ])->id;
        }

        $genreIds = Genre::pluck('id', 'name');
        $languageIds = Language::pluck('id', 'code');

        // [tên, nội dung, phút, ngôn ngữ, độ tuổi, khởi chiếu (+/- ngày), kết thúc (+/- ngày | null), nổi bật, thể loại, đạo diễn, diễn viên]
        $movies = [
            ['Mùa Hè Bên Sông Hậu', 'Một cậu bé thành phố về quê ngoại ở Cần Thơ và trải qua mùa hè thay đổi cả cuộc đời.',
                112, 'vi', 'T13', -25, 20, true, ['Tâm lý', 'Gia đình'], ['Nguyễn Thu Hà'], ['Võ Thanh Tâm', 'Huỳnh Gia Hân', 'Châu Minh Đức']],
            ['Truy Lùng Bóng Đêm', 'Nữ thanh tra trẻ lần theo dấu vết đường dây buôn lậu xuyên biên giới.',
                128, 'vi', 'T16', -18, 25, true, ['Hành động', 'Hình sự'], ['Trần Hoài Nam'], ['Bùi Ngọc Trâm', 'Đặng Khôi Nguyên', 'Tô Gia Huy']],
            ['Ngôi Nhà Số 13', 'Nhóm bạn thuê căn nhà cũ giá rẻ và phát hiện bí mật bị chôn giấu suốt 30 năm.',
                98, 'vi', 'T18', -12, 18, false, ['Kinh dị'], ['Phạm Quốc Bảo'], ['Lâm Bảo Ngọc', 'Tô Gia Huy']],
            ['Biệt Đội Ngân Hà', 'Phi hành đoàn cuối cùng của Trái Đất phải ngăn một hạm đội lạ tiến vào hệ Mặt Trời.',
                142, 'en', 'T13', -20, 30, true, ['Khoa học viễn tưởng', 'Phiêu lưu', 'Hành động'], ['Daniel Brooks'], ['Ryan Mitchell', 'Olivia Hayes', 'Marcus Reed']],
            ['Chú Mèo Phi Hành Gia', 'Chú mèo Mướp vô tình lên tàu vũ trụ và trở thành phi hành gia bất đắc dĩ.',
                94, 'en', 'P', -15, 35, false, ['Hoạt hình', 'Gia đình', 'Hài'], ['Emily Carter'], ['Olivia Hayes', 'Marcus Reed']],
            ['Tình Đầu Ở Seoul', 'Hai du học sinh gặp nhau trong một tiệm sách nhỏ giữa mùa tuyết đầu tiên.',
                118, 'ko', 'T13', -10, 25, false, ['Tình cảm'], ['Park Ji-won'], ['Kim Seo-yeon', 'Han Min-jae']],
            ['Ông Ngoại Đi Chợ Tết', 'Chuyến đi chợ Tết đầy sự cố của ông ngoại và ba đứa cháu nghịch ngợm.',
                105, 'vi', 'K', -8, 40, false, ['Hài', 'Gia đình'], ['Lê Minh Khang'], ['Châu Minh Đức', 'Huỳnh Gia Hân', 'Võ Thanh Tâm']],
            ['Đêm Trăng Rằm', 'Câu chuyện tình lặng lẽ của đôi vợ chồng già bên bến đò miền Tây.',
                100, 'vi', 'T13', -45, -5, false, ['Tình cảm', 'Tâm lý'], ['Nguyễn Thu Hà'], ['Bùi Ngọc Trâm', 'Đặng Khôi Nguyên']],
            ['Cơn Bão Cuối Cùng', 'Đội cứu hộ đặc biệt chạy đua với siêu bão để cứu một thị trấn ven biển.',
                135, 'en', 'T16', 5, 45, true, ['Hành động', 'Phiêu lưu'], ['Daniel Brooks'], ['Marcus Reed', 'Ryan Mitchell']],
            ['Lời Nguyền Kyoto', 'Một nhiếp ảnh gia chụp được bóng người lạ trong mọi bức ảnh ở ngôi đền cổ.',
                101, 'ja', 'T18', 12, null, false, ['Kinh dị'], ['Sato Kenji'], ['Tanaka Yui']],
            ['Hành Trình Về Phương Nam', 'Hai cha con đạp xe từ Hà Nội về Cà Mau để thực hiện lời hứa với mẹ.',
                110, 'vi', 'P', 20, null, false, ['Phiêu lưu', 'Gia đình'], ['Lê Minh Khang', 'Phạm Quốc Bảo'], ['Võ Thanh Tâm', 'Lâm Bảo Ngọc']],
        ];

        foreach ($movies as [$title, $synopsis, $minutes, $lang, $age, $from, $to, $featured, $genres, $directors, $actors]) {
            $movie = Movie::create([
                'title' => $title,
                'synopsis' => $synopsis,
                'duration_minutes' => $minutes,
                'language_id' => $languageIds[$lang],
                'age_rating' => $age,
                'release_date' => today()->addDays($from),
                'end_date' => $to === null ? null : today()->addDays($to),
                'is_featured' => $featured,
                'created_at' => now()->subDays(max(1, 30 - $from)),
                'updated_at' => now()->subDays(max(1, 30 - $from)),
            ]);

            $movie->genres()->attach($genreIds->only($genres)->values());

            $credits = [];
            $order = 0;
            foreach ($directors as $name) {
                $credits[] = ['person_id' => $personIds[$name], 'role' => 'director', 'display_order' => $order++];
            }
            foreach ($actors as $name) {
                $credits[] = ['person_id' => $personIds[$name], 'role' => 'actor', 'display_order' => $order++];
            }
            foreach ($credits as $credit) {
                $movie->people()->attach($credit['person_id'], ['role' => $credit['role'], 'display_order' => $credit['display_order']]);
            }
        }
    }
}
