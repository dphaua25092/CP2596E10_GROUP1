import { createBrowserRouter } from 'react-router-dom'
import AdminLayout from '@/layouts/AdminLayout'
import MainLayout from '@/layouts/MainLayout'
import BookingsAdminPage from '@/pages/admin/BookingsAdminPage'
import CatalogAdminPage from '@/pages/admin/CatalogAdminPage'
import CitiesAdminPage from '@/pages/admin/CitiesAdminPage'
import DashboardPage from '@/pages/admin/DashboardPage'
import MoviesAdminPage from '@/pages/admin/MoviesAdminPage'
import PromotionsAdminPage from '@/pages/admin/PromotionsAdminPage'
import ReviewsAdminPage from '@/pages/admin/ReviewsAdminPage'
import SeatCategoriesAdminPage from '@/pages/admin/SeatCategoriesAdminPage'
import ShowtimesAdminPage from '@/pages/admin/ShowtimesAdminPage'
import TheatersAdminPage from '@/pages/admin/TheatersAdminPage'
import UsersAdminPage from '@/pages/admin/UsersAdminPage'
import ForgotPasswordPage from '@/pages/auth/ForgotPasswordPage'
import LoginPage from '@/pages/auth/LoginPage'
import RegisterPage from '@/pages/auth/RegisterPage'
import BookingPage from '@/pages/booking/BookingPage'
import TicketPage from '@/pages/booking/TicketPage'
import HomePage from '@/pages/HomePage'
import MovieDetailPage from '@/pages/movies/MovieDetailPage'
import MoviesPage from '@/pages/movies/MoviesPage'
import NotFoundPage from '@/pages/NotFoundPage'
import BookingHistoryPage from '@/pages/profile/BookingHistoryPage'
import NotificationsPage from '@/pages/profile/NotificationsPage'
import ProfilePage from '@/pages/profile/ProfilePage'
import TheatersPage from '@/pages/theaters/TheatersPage'
import AdminRoute from './AdminRoute'
import ProtectedRoute from './ProtectedRoute'

const router = createBrowserRouter([
  {
    element: <MainLayout />,
    children: [
      { path: '/', element: <HomePage /> },
      { path: '/movies', element: <MoviesPage /> },
      { path: '/movies/:movieId', element: <MovieDetailPage /> },
      { path: '/theaters', element: <TheatersPage /> },
      { path: '/login', element: <LoginPage /> },
      { path: '/register', element: <RegisterPage /> },
      { path: '/forgot-password', element: <ForgotPasswordPage /> },
      {
        // cần đăng nhập
        element: <ProtectedRoute />,
        children: [
          { path: '/booking/:showtimeId', element: <BookingPage /> },
          { path: '/tickets/:bookingCode', element: <TicketPage /> },
          { path: '/profile', element: <ProfilePage /> },
          { path: '/profile/bookings', element: <BookingHistoryPage /> },
          { path: '/profile/notifications', element: <NotificationsPage /> },
        ],
      },
      { path: '*', element: <NotFoundPage /> },
    ],
  },
  {
    path: '/admin',
    element: <AdminRoute />,
    children: [
      {
        element: <AdminLayout />,
        children: [
          { index: true, element: <DashboardPage /> },
          { path: 'movies', element: <MoviesAdminPage /> },
          { path: 'catalog', element: <CatalogAdminPage /> },
          { path: 'promotions', element: <PromotionsAdminPage /> },
          { path: 'cities', element: <CitiesAdminPage /> },
          { path: 'theaters', element: <TheatersAdminPage /> },
          { path: 'seat-categories', element: <SeatCategoriesAdminPage /> },
          { path: 'showtimes', element: <ShowtimesAdminPage /> },
          { path: 'bookings', element: <BookingsAdminPage /> },
          { path: 'reviews', element: <ReviewsAdminPage /> },
          { path: 'users', element: <UsersAdminPage /> },
        ],
      },
    ],
  },
])

export default router
