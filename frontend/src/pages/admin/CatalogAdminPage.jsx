import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function CatalogAdminPage() {
  return (
    <PagePlaceholder
      title="Thể loại, ngôn ngữ, diễn viên & đạo diễn"
      owner="Chí Hùng"
      features={[
        'Quản lý thể loại',
        'Quản lý ngôn ngữ phim',
        'Quản lý diễn viên, đạo diễn',
      ]}
    />
  )
}
