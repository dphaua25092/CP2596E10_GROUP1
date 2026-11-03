import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function BookingsAdminPage() {
  return (
    <PagePlaceholder
      title="Đặt vé & hóa đơn"
      owner="Phát Tài"
      features={[
        'Danh sách đơn đặt vé, xác nhận thông tin',
        'Hóa đơn',
      ]}
    />
  )
}
