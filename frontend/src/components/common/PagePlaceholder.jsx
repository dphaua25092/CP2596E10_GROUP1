// Khung tạm cho trang chưa làm; người phụ trách thay bằng giao diện thật
export default function PagePlaceholder({ title, owner, features = [] }) {
  return (
    <section className="placeholder">
      <h1>{title}</h1>
      <p className="placeholder__owner">Phụ trách: {owner}</p>
      {features.length > 0 && (
        <ul>
          {features.map((feature) => (
            <li key={feature}>{feature}</li>
          ))}
        </ul>
      )}
    </section>
  )
}
