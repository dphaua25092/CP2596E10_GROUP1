import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function MovieDetailPage() {
  return (
    <PagePlaceholder
      title="Chi tiết phim"
      owner="Chí Hùng"
      features={[
        'Thông tin phim, trailer, diễn viên, đạo diễn',
        'Lịch chiếu theo rạp / ngày (Phát Tài)',
        'Đánh giá & bình luận: xem theo mới nhất / điểm cao nhất, viết đánh giá (Phước Hậu)',
      ]}
    />
  )
}
