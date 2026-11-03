import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function ProfilePage() {
  return (
    <PagePlaceholder
      title="Hồ sơ cá nhân"
      owner="Phước Hậu"
      features={[
        'Cập nhật tên, ngày sinh, ngôn ngữ, thành phố',
        'Đổi mật khẩu',
      ]}
    />
  )
}
