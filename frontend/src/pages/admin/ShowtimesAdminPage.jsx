import PagePlaceholder from '@/components/common/PagePlaceholder'

export default function ShowtimesAdminPage() {
  return (
    <PagePlaceholder
      title="Quản lý lịch chiếu"
      owner="Nhật Quỳnh"
      features={[
        'Thêm, sửa, xóa suất chiếu: ngày, giờ, giá theo hạng ghế',
        'Không trùng suất trong cùng phòng',
        'Quy tắc giá động (cuối tuần, giờ cao điểm)',
      ]}
    />
  )
}
