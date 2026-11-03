import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function MoviesPage() {
  return (
    <PagePlaceholder
      title="Danh sách phim"
      owner="Chí Hùng"
      features={[
        'Đang chiếu / sắp chiếu',
        'Tìm kiếm theo tên phim, diễn viên, đạo diễn',
      ]}
    />
  )
}
