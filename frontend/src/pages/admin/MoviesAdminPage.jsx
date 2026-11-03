import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function MoviesAdminPage() {
  return (
    <PagePlaceholder
      title="Quản lý phim"
      owner="Chí Hùng"
      features={[
        'Thêm, sửa, xóa phim; upload poster, trailer',
        'Gán thể loại, diễn viên, đạo diễn (nhiều)',
      ]}
    />
  )
}
