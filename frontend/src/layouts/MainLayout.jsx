import { Link, NavLink, Outlet, useNavigate } from 'react-router-dom'
import useAuth from '@/hooks/useAuth'

export default function MainLayout() {
  const { user, isAdmin, logout } = useAuth()
  const navigate = useNavigate()

  const handleLogout = async () => {
    await logout()
    navigate('/')
  }

  return (
    <div className="layout">
      <header className="header">
        <Link to="/" className="header__logo">CineBook</Link>
        <nav className="header__nav">
          <NavLink to="/movies">Phim</NavLink>
          <NavLink to="/theaters">Rạp</NavLink>
          {user ? (
            <>
              {isAdmin && <NavLink to="/admin">Quản trị</NavLink>}
              <NavLink to="/profile">{user.full_name}</NavLink>
              <button type="button" onClick={handleLogout}>Đăng xuất</button>
            </>
          ) : (
            <>
              <NavLink to="/login">Đăng nhập</NavLink>
              <NavLink to="/register">Đăng ký</NavLink>
            </>
          )}
        </nav>
      </header>
      <main className="main">
        <Outlet />
      </main>
      <footer className="footer">© CineBook</footer>
    </div>
  )
}
