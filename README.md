# CineBook

Hệ thống đặt vé xem phim trực tuyến.

| Thư mục                 | Công nghệ                                             | Chạy tại                  |
| ----------------------- | ----------------------------------------------------- | ------------------------- |
| [`backend/`](backend)   | Laravel 12 – REST API, Sanctum, MariaDB/MySQL (XAMPP) | http://localhost:8000/api |
| [`frontend/`](frontend) | ReactJS 19 + Vite, React Router, axios                | http://localhost:5173     |

## Yêu cầu

- XAMPP có **PHP 8.2 trở lên** (MySQL/MariaDB đi kèm)
- Composer
- Node.js 20.19+ (khuyên dùng 22 hoặc 24)

## Cài đặt sau khi clone

### Backend

```bash
cd backend
composer install
cp .env.example .env              # Windows: copy .env.example .env
php artisan key:generate
```

Bật MySQL trong XAMPP, tạo database `cinebook` (collation `utf8mb4_unicode_ci`) trong phpMyAdmin, rồi:

```bash
php artisan migrate --seed
php artisan serve
```

### Frontend

```bash
cd frontend
npm install
cp .env.example .env              # Windows: copy .env.example .env
npm run dev
```

Mở http://localhost:5173. Frontend phải chạy ở cổng 5173 vì backend chỉ cho phép CORS từ địa chỉ này (`FRONTEND_URL` trong `backend/.env`).

Tài khoản mẫu, cấu hình và ghi chú về CSDL: xem [backend/README.md](backend/README.md). Mật khẩu mọi tài khoản mẫu là `password`.

## Cấu trúc frontend

```
frontend/src/
├── api/            client.js (axios + token), auth.js; mỗi module thêm 1 file: movies.js, bookings.js...
├── components/     component dùng chung (common/)
├── contexts/       AuthContext: user đang đăng nhập, login(), logout()
├── hooks/          useAuth()
├── layouts/        MainLayout (trang khách), AdminLayout (trang quản trị)
├── pages/          mỗi trang 1 file, chia theo nhóm chức năng
│   ├── auth/  movies/  theaters/  booking/  profile/
│   └── admin/
├── routes/         index.jsx (khai báo route), ProtectedRoute, AdminRoute
└── utils/          format.js: tiền VNĐ, ngày giờ theo giờ Việt Nam
```

Import theo alias `@` = `src`, ví dụ `import client from '@/api/client'`.

Mỗi trang hiện là khung tạm (`PagePlaceholder`) ghi người phụ trách và danh sách tính năng; người phụ trách thay bằng giao diện thật.

## Phân công theo thư mục

| Thành viên                                  | Frontend (`frontend/src/pages`)                                                                                                                                                               | Backend                                                                          |
| ------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- |
| Phước Hậu – Người dùng, đánh giá, thông báo | `auth/*`, `profile/ProfilePage`, `profile/NotificationsPage`, `admin/UsersAdminPage`, `admin/ReviewsAdminPage`, `admin/SeatCategoriesAdminPage`, phần đánh giá trong `movies/MovieDetailPage` | Đăng ký / đăng nhập / quên mật khẩu, hồ sơ, admin, đánh giá, thông báo, hạng ghế |
| Nguyễn Chí Hùng – Phim & trang chủ          | `HomePage`, `movies/*`, `admin/MoviesAdminPage`, `admin/CatalogAdminPage`, `admin/PromotionsAdminPage`                                                                                        | Phim, thể loại, ngôn ngữ, diễn viên / đạo diễn, khuyến mãi, tìm kiếm & lọc       |
| Nguyễn Minh Lý Nhật Quỳnh – Rạp, lịch chiếu | `admin/TheatersAdminPage`, `admin/ShowtimesAdminPage`                                                                                                                                         | Rạp, phòng chiếu, sơ đồ ghế, suất chiếu, giá theo hạng ghế, giá động             |
| Đỗ Nguyễn Phát Tài – Đặt vé, hóa đơn        | `theaters/*`, `booking/*`, `profile/BookingHistoryPage`, `admin/DashboardPage`, `admin/BookingsAdminPage`, `admin/CitiesAdminPage`                                                            | Đặt vé, thanh toán giả lập, vé điện tử, hóa đơn, báo cáo, thành phố              |

## Quy ước API

Quy ước chung cho mọi API:

- Tiền tố `/api`, route quản trị đặt dưới `/api/admin/...`.
- Request gửi header `Authorization: Bearer <token>` (axios client tự gắn).
- Lỗi luôn trả JSON `{ "message": "..." }`, lỗi validate trả 422 kèm `errors`, chưa đăng nhập trả 401.
- Ngày trả dạng `YYYY-MM-DD`, ngày giờ trả ISO 8601 (UTC), frontend hiển thị bằng `utils/format.js`.

Frontend đã viết sẵn phần xác thực (`api/auth.js`, `AuthContext`, `LoginPage`) theo quy ước dưới đây, backend cần làm đúng như vậy:

| Method | URL             | Body                                                           | Trả về            |
| ------ | --------------- | -------------------------------------------------------------- | ----------------- |
| POST   | `/api/login`    | `{ email, password }`                                          | `{ token, user }` |
| POST   | `/api/register` | `{ full_name, email, phone, password, password_confirmation }` | `{ token, user }` |
| POST   | `/api/logout`   | –                                                              | 204               |
| GET    | `/api/user`     | –                                                              | `user`            |

`user` có trường `role` (`customer` / `admin`); frontend dựa vào đó để hiện trang quản trị.

## Làm việc với git

- Không commit thẳng lên `main`, mỗi người làm trên nhánh riêng (`feature/dat-ve`, `feature/quan-ly-phim`...) rồi tạo Pull Request.
- Đổi CSDL: tạo migration mới (`php artisan make:migration ...`), không sửa migration cũ.
