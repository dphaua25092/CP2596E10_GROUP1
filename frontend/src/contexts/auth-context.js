import { createContext } from 'react'

// Tách riêng khỏi AuthProvider để Vite fast refresh hoạt động
export const AuthContext = createContext(null)
