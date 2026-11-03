import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function CitiesAdminPage() {
  return (
    <PagePlaceholder
      title="Quản lý thành phố"
      owner="Phát Tài"
      features={[
        'Thêm, sửa, xóa thành phố, trạng thái hoạt động',
        'Không cho xóa thành phố đang có rạp',
      ]}
    />
  )
}
