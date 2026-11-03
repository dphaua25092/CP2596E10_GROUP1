import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function DashboardPage() {
  return (
    <PagePlaceholder
      title="Báo cáo doanh thu"
      owner="Phát Tài"
      features={[
        'Doanh thu vé theo ngày / tuần / tháng',
        'Doanh thu từng rạp, phim phổ biến nhất',
        'Người dùng đăng ký & lượt đặt vé',
        'Bảng và biểu đồ',
      ]}
    />
  )
}
