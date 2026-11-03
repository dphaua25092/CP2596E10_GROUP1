import client from './client'

// Quy ước API xác thực, xem mục "Quy ước API" trong README gốc
export const login = (credentials) => client.post('/login', credentials).then((res) => res.data)

export const register = (data) => client.post('/register', data).then((res) => res.data)

export const logout = () => client.post('/logout')

export const getCurrentUser = () => client.get('/user').then((res) => res.data)
