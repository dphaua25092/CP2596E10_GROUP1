import { Navigate, Outlet } from 'react-router-dom'
import Loading from '@/components/common/Loading'
import useAuth from '@/hooks/useAuth'

// Chỉ admin; backend vẫn phải tự kiểm tra quyền, đây chỉ là chặn ở giao diện
export default function AdminRoute() {
  const { user, isAdmin, loading } = useAuth()

  if (loading) {
    return <Loading />
  }
  if (!user) {
    return <Navigate to="/login" replace />
  }
  if (!isAdmin) {
    return <Navigate to="/" replace />
  }
  return <Outlet />
}
