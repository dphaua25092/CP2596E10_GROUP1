import axios from 'axios'

export const TOKEN_KEY = 'cinebook_token'

// Mọi request tới backend đều đi qua instance này
const client = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost:8000/api',
  headers: { Accept: 'application/json' },
})

client.interceptors.request.use((config) => {
  const token = localStorage.getItem(TOKEN_KEY)
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

client.interceptors.response.use(
  (response) => response,
  (error) => {
    // token hết hạn / bị thu hồi: xóa để AuthContext coi như chưa đăng nhập
    if (error.response?.status === 401) {
      localStorage.removeItem(TOKEN_KEY)
    }
    return Promise.reject(error)
  },
)

// Lấy thông báo lỗi để hiển thị: lỗi validate 422 của Laravel trả về { message, errors }
export function getErrorMessage(error) {
  return error.response?.data?.message ?? 'Không kết nối được máy chủ'
}

export default client
