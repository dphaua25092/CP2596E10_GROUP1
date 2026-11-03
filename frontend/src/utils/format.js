// API trả ngày giờ dạng ISO (UTC); luôn hiển thị theo giờ Việt Nam
const TIME_ZONE = 'Asia/Ho_Chi_Minh'

export const formatCurrency = (amount) =>
  new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount)

// '2026-11-03' (cột ngày) hoặc '2026-11-03T12:30:00.000000Z' (cột ngày giờ) -> 03/11/2026
export const formatDate = (value) =>
  /^\d{4}-\d{2}-\d{2}$/.test(value)
    ? value.split('-').reverse().join('/')
    : new Intl.DateTimeFormat('vi-VN', { timeZone: TIME_ZONE, dateStyle: 'short' }).format(new Date(value))

export const formatTime = (value) =>
  new Intl.DateTimeFormat('vi-VN', { timeZone: TIME_ZONE, hour: '2-digit', minute: '2-digit' }).format(new Date(value))

export const formatDateTime = (value) => `${formatTime(value)} ${formatDate(value)}`
