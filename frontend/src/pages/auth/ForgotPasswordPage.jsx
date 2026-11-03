import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function ForgotPasswordPage() {
  return (
    <PagePlaceholder
      title="Quên mật khẩu"
      owner="Phước Hậu"
      features={[
        'Gửi email đặt lại mật khẩu',
        'Đặt mật khẩu mới',
      ]}
    />
  )
}
