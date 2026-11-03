import react from '@vitejs/plugin-react'
import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  resolve: {
    // import '@/api/client' thay cho '../../api/client'
    alias: { '@': fileURLToPath(new URL('./src', import.meta.url)) },
  },
  server: {
    // backend chỉ cho phép CORS từ cổng này (FRONTEND_URL trong backend/.env)
    port: 5173,
    strictPort: true,
  },
})
