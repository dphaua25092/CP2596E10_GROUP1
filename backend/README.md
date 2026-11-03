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

```bash
php artisan migrate --seed
php artisan serve             # http://localhost:8000
```

Muốn làm lại dữ liệu từ đầu: `php artisan migrate:fresh --seed`.

## Tài khoản mẫu

Mật khẩu tất cả: `password`

| Email | Vai trò | Ghi chú |
| --- | --- | --- |
| admin@cinebook.vn | admin | Có nhật ký tác vụ |
| admin2@cinebook.vn | admin | Chưa có tác vụ → xóa được |
| admin.cu@cinebook.vn | admin | Đã vô hiệu hóa, có tác vụ → không xóa được |
| khachhang@cinebook.vn | customer | Có lịch sử đặt vé đã qua và sắp tới |
| bikhoa@cinebook.vn | customer | Tài khoản bị khóa |

## Gọi API từ React

```js
// đăng nhập xong backend trả về token, lưu lại và gửi kèm mỗi request
axios.get('http://localhost:8000/api/user', {
  headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
});
```

Tạo token cho user: `$user->createToken('react')->plainTextToken`. Lỗi luôn trả JSON, chưa đăng nhập trả `401`.
