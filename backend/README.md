# CineBook – Backend API (Laravel 12)

Backend chỉ cung cấp REST API tại `/api`. Giao diện nằm ở `../frontend` (ReactJS + Vite, cổng 5173), gọi API bằng token Sanctum.

## Yêu cầu

- XAMPP có PHP 8.2+ và MariaDB 10.4+ (bật Apache không bắt buộc, chỉ cần MySQL)
- Composer

## Cài đặt lần đầu

```bash
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
```

Tạo database `cinebook` (utf8mb4_unicode_ci) trong phpMyAdmin, hoặc:

```sql
CREATE DATABASE cinebook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Sau đó tạo bảng và dữ liệu mẫu:

```bash
php artisan migrate --seed
php artisan serve             # http://localhost:8000
```

Muốn làm lại dữ liệu từ đầu: `php artisan migrate:fresh --seed`.

Dữ liệu mẫu tính theo ngày chạy seed (phim đang chiếu / sắp chiếu, suất chiếu 14 ngày trước đến 6 ngày tới), nên khi demo nên seed lại cho dữ liệu mới.

## Tài khoản mẫu

Mật khẩu tất cả: `password`

| Email | Vai trò | Ghi chú |
| --- | --- | --- |
| admin@cinebook.vn | admin | Có nhật ký tác vụ |
| admin2@cinebook.vn | admin | Chưa có tác vụ → xóa được |
| admin.cu@cinebook.vn | admin | Đã vô hiệu hóa, có tác vụ → không xóa được |
| khachhang@cinebook.vn | customer | Có lịch sử đặt vé đã qua và sắp tới |
| bikhoa@cinebook.vn | customer | Tài khoản bị khóa |

## Cấu hình chính (.env)

| Biến | Giá trị | Ý nghĩa |
| --- | --- | --- |
| `DB_CONNECTION` | `mariadb` | MariaDB của XAMPP |
| `DB_TIMEZONE` | `+07:00` | `NOW()` trong SQL / trigger khớp giờ Việt Nam |
| `APP_TIMEZONE` | `Asia/Ho_Chi_Minh` | |
| `FRONTEND_URL` | `http://localhost:5173` | Origin React được phép gọi API (CORS); nhiều origin cách nhau dấu phẩy |

## Gọi API từ React

```js
// đăng nhập xong backend trả về token, lưu lại và gửi kèm mỗi request
axios.get('http://localhost:8000/api/user', {
  headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
});
```

Tạo token cho user: `$user->createToken('react')->plainTextToken`. Lỗi luôn trả JSON; chưa đăng nhập trả `401`.

## Cơ sở dữ liệu

- Migration trong `database/migrations` là nguồn chuẩn của lược đồ; muốn đổi bảng thì tạo migration mới, không sửa migration đã có trên nhánh `main`.
- Ràng buộc nằm ở CSDL: CHECK, khóa ngoại, trigger chống trùng suất chiếu và kiểm tra ghế hợp lệ (`create_triggers`), view báo cáo (`create_views`).
- Báo cáo đọc từ view: `DB::table('v_ticket_sales_daily')`, `v_revenue_by_theater`, `v_popular_movies`, `v_user_booking_stats`, `v_booking_totals` (hóa đơn), `v_movie_ratings`, `v_screen_capacity`, `v_theater_capacity`.
- Bảng `notifications` là bảng riêng của CineBook (model `App\Models\Notification`), không dùng database notification mặc định của Laravel.
- Tìm kiếm phim / diễn viên dùng `LIKE '%từ khóa%'`; collation bỏ qua dấu nên "bo gia" tìm ra "Bố già".

### Quy tắc code phải tự đảm bảo

1. Mỗi thao tác thêm / sửa / xóa / ẩn của admin phải ghi `admin_logs` (để chặn xóa admin đã có tác vụ).
2. Trước khi giữ ghế, chuyển đơn `pending` quá `hold_expires_at` sang `expired` và xóa `booking_seats` của đơn `expired` / `failed` / `cancelled`.
3. Chỉ cho đánh giá khi user có đơn `confirmed` của phim đó và suất đã chiếu xong.
4. Giá ghế = giá gốc trong `showtime_prices` → áp `pricing_rules` (`PricingRule::priceFor()`) → trừ khuyến mãi đang chạy, chốt vào `booking_seats.price` và `bookings.discount_amount`.
