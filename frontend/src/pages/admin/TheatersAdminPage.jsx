import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function TheatersAdminPage() {
  return (
    <PagePlaceholder
      title="Quản lý rạp & phòng chiếu"
      owner="Nhật Quỳnh"
      features={[
        'Thêm, sửa, xóa rạp: thành phố, địa chỉ',
        'Phòng chiếu: mã phòng, vị trí, sức chứa',
        'Sơ đồ ghế, ghế không sử dụng được',
      ]}
    />
  )
}
