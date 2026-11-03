import { useState } from 'react'
import { Link, useLocation, useNavigate } from 'react-router-dom'
import { getErrorMessage } from '@/api/client'
import useAuth from '@/hooks/useAuth'

// Phụ trách: Phước Hậu. Form mẫu đã nối AuthContext, chờ API POST /api/login
export default function LoginPage() {
  const { login } = useAuth()
  const navigate = useNavigate()
  const location = useLocation()
  const [form, setForm] = useState({ email: '', password: '' })
  const [error, setError] = useState('')
  const [submitting, setSubmitting] = useState(false)

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value })

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError('')
    setSubmitting(true)
    try {
      const user = await login(form)
      const fallback = user.role === 'admin' ? '/admin' : '/'
      navigate(location.state?.from?.pathname ?? fallback, { replace: true })
    } catch (err) {
      setError(getErrorMessage(err))
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <section className="auth">
      <h1>Đăng nhập</h1>
      <form onSubmit={handleSubmit}>
        <label>
          Email
          <input type="email" name="email" value={form.email} onChange={handleChange} required />
        </label>
        <label>
          Mật khẩu
          <input type="password" name="password" value={form.password} onChange={handleChange} required />
        </label>
        {error && <p className="error">{error}</p>}
        <button type="submit" disabled={submitting}>{submitting ? 'Đang đăng nhập...' : 'Đăng nhập'}</button>
      </form>
      <p>
        <Link to="/forgot-password">Quên mật khẩu?</Link> · <Link to="/register">Tạo tài khoản</Link>
      </p>
    </section>
  )
}
