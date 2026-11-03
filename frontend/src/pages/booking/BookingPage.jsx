import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function BookingPage() {
  return (
    <PagePlaceholder
      title="Đặt vé"
      owner="Phát Tài"
      features={[
        'Sơ đồ ghế: trống / đã đặt / đang chọn',
        'Chọn nhiều ghế, giá theo hạng ghế và ngày',
        'Thanh toán giả lập',
      ]}
    />
  )
}
