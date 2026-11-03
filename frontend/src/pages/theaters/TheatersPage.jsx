import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function TheatersPage() {
  return (
    <PagePlaceholder
      title="Rạp chiếu"
      owner="Phát Tài"
      features={[
        'Chọn thành phố → danh sách rạp',
        'Lịch chiếu của rạp',
      ]}
    />
  )
}
