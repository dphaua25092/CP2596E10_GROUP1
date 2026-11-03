import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function HomePage() {
  return (
    <PagePlaceholder
      title="Trang chủ"
      owner="Chí Hùng"
      features={[
        'Banner phim nổi bật, khuyến mãi',
        'Phim đang chiếu / sắp chiếu, xem trailer',
        'Lọc nhanh: thành phố, thể loại, ngôn ngữ, ngày chiếu, đánh giá',
      ]}
    />
  )
}
