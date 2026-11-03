import { Navigate, Outlet, useLocation } from 'react-router-dom'
import Loading from '@/components/common/Loading'
import useAuth from '@/hooks/useAuth'

// Bắt buộc đăng nhập; đăng nhập xong quay lại đúng trang đang mở
export default function ProtectedRoute() {
  const { user, loading } = useAuth()
  const location = useLocation()

  if (loading) {
    return <Loading />
  }
  if (!user) {
    return <Navigate to="/login" replace state={{ from: location }} />
  }
  return <Outlet />
}
