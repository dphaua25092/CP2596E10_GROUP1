import { Link, NavLink, Outlet } from 'react-router-dom'

const MENU = [
  { to: '/admin', label: 'Báo cáo doanh thu', end: true },
  { to: '/admin/movies', label: 'Phim' },
  { to: '/admin/catalog', label: 'Thể loại, ngôn ngữ, diễn viên' },
  { to: '/admin/promotions', label: 'Khuyến mãi' },
  { to: '/admin/cities', label: 'Thành phố' },
  { to: '/admin/theaters', label: 'Rạp & phòng chiếu' },
  { to: '/admin/seat-categories', label: 'Hạng ghế' },
  { to: '/admin/showtimes', label: 'Lịch chiếu' },
  { to: '/admin/bookings', label: 'Đặt vé & hóa đơn' },
  { to: '/admin/reviews', label: 'Đánh giá' },
  { to: '/admin/users', label: 'Người dùng & admin' },
]

export default function AdminLayout() {
  return (
    <div className="admin">
      <aside className="admin__sidebar">
        <Link to="/admin" className="header__logo">CineBook Admin</Link>
        <nav>
          {MENU.map((item) => (
            <NavLink key={item.to} to={item.to} end={item.end}>{item.label}</NavLink>
          ))}
        </nav>
        <Link to="/">← Về trang khách</Link>
      </aside>
      <main className="admin__content">
        <Outlet />
      </main>
    </div>
  )
}
