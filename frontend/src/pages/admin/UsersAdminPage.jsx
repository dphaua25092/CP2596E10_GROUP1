import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function UsersAdminPage() {
  return (
    <PagePlaceholder
      title="Người dùng & admin"
      owner="Phước Hậu"
      features={[
        'Danh sách người dùng, khóa tài khoản',
        'Thêm admin; admin có tác vụ chỉ vô hiệu hóa, chưa có mới được xóa',
      ]}
    />
  )
}
