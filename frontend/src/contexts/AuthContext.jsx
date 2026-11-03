import { useCallback, useEffect, useMemo, useState } from 'react'
import * as authApi from '@/api/auth'
import { TOKEN_KEY } from '@/api/client'
import { AuthContext } from './auth-context'

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null)
  // đang kiểm tra token đã lưu, tránh đá người dùng ra trang login khi F5
  const [loading, setLoading] = useState(() => Boolean(localStorage.getItem(TOKEN_KEY)))

  useEffect(() => {
    if (!localStorage.getItem(TOKEN_KEY)) {
      return
    }
    authApi
      .getCurrentUser()
      .then(setUser)
      .catch(() => localStorage.removeItem(TOKEN_KEY))
      .finally(() => setLoading(false))
  }, [])

  const login = useCallback(async (credentials) => {
    const { token, user } = await authApi.login(credentials)
    localStorage.setItem(TOKEN_KEY, token)
    setUser(user)
    return user
  }, [])

  const logout = useCallback(async () => {
    try {
      await authApi.logout()
    } finally {
      localStorage.removeItem(TOKEN_KEY)
      setUser(null)
    }
  }, [])

  const value = useMemo(
    () => ({ user, loading, isAdmin: user?.role === 'admin', login, logout, setUser }),
    [user, loading, login, logout],
  )

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}
