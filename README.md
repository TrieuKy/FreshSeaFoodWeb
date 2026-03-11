# 🦞 Dự Án Website Bán Hải Sản Tươi (Fresh Seafood Web)

Dự án website thương mại điện tử chuyên cung cấp hải sản tươi sống. Được xây dựng dựa trên mô hình MVC (Model-View-Controller) thuần bằng PHP và MySQL, mang đến trải nghiệm mua sắm nhanh chóng cho khách hàng và bộ công cụ quản trị mạnh mẽ cho Admin.

![Mô tả hình ảnh](https://placehold.co/1000x400/0a7075/ffffff?text=Hai+San+Tuoi+Store)

---

## ✨ Tính năng nổi bật

### 🛒 Dành cho Khách hàng (Customer)
* **Đăng ký & Đăng nhập:** Hệ thống User cơ bản.
* **Danh mục Sản phẩm:** Phân chia theo Tôm, Cua, Cá, Ốc, Đặc sản.
* **Giỏ hàng (Cart) dùng Session:** Thêm, xóa, thay đổi số lượng bằng AJAX mượt mà không cần tải lại trang.
* **Mã giảm giá (Voucher):** Kiểm tra và áp dụng mã giảm giá trực tiếp ở trang Thanh toán (Checkout) bằng AJAX.
* **Quản lý Tài khoản (Profile):** Xem lịch sử đơn hàng, theo dõi trạng thái đơn hàng (Thanh tiến trình 4 bước).

### ⚙️ Dành cho Quản trị viên (Admin)
* **Dashboard:** Thống kê tóm tắt đầu ngày (Doanh thu, Đơn mới, Lượng khách).
* **Quản lý Sản phẩm (CRUD):** Thêm, sửa, xóa các loại hải sản, upload ảnh sản phẩm.
* **Quản lý Đơn hàng:** Kiểm tra chi tiết đơn đặt hàng, đổi trạng thái (Xác nhận, Đang giao, Đã giao, Hủy).
* **Quản lý Mã giảm giá (Voucher):** Tạo mã giảm giá (Theo % hoặc số tiền cố định).
* **Quản lý Khách hàng:** Xem danh sách khách hàng, thống kê tổng số đơn và chi tiêu của từng người.
* **Thống kê Doanh thu nâng cao:**
  * Lọc theo Tháng / Năm.
  * Xem biểu đồ phân bổ doanh thu theo từng ngày.
  * Xuất báo cáo dạng file Excel/CSV.

---

## 💻 Công nghệ sử dụng
* **Ngôn ngữ:** Backend xử lý bằng PHP Thuần (Vanilla PHP).
* **Kiến trúc:** Mô hình MVC (Model - View - Controller).
* **Cơ sở dữ liệu:** MySQL (Sử dụng PDO Abstract Layer).
* **Giao diện (Frontend):** HTML5, Vanilla CSS (Thiết kế tập trung vào biến --var, bảng màu Gradient, Bo góc tròn chuẩn UI Web App), Vanilla JavaScript (Sử dụng Fetch API cho thao tác AJAX).

---

## 🚀 Hướng dẫn Cài đặt & Chạy trên máy tính (Localhost)

Yêu cầu: Máy tính cần cài đặt **XAMPP / Laragon / WAMP**.

### Bước 1: Clone dự án tải về
Mở Terminal và gõ:
```bash
git clone https://github.com/TrieuKy/FreshSeaFoodWeb.git
```
Di chuyển toàn bộ thư mục vừa tải vào `htdocs` (nếu dùng XAMPP) hoặc thư mục `www` (nếu dùng Laragon). Đổi tên thư mục thành `banhaisan`.

### Bước 2: Thiết lập Cơ sở dữ liệu (Database)
1. Mở phpMyAdmin (thường là `http://localhost/phpmyadmin`).
2. Tạo một database mới có tên là `banhaisan_db` với Encoding là `utf8mb4_general_ci`.
3. Nhập (Import) file `banhaisan_db.sql` được đính kèm sẵn trong thư mục gốc dự án vào database vừa tạo.

### Bước 3: Cài đặt kết nối
Mở file `app/config/database.php` và điều chỉnh lại thông tin nếu máy tính của bạn cấu hình mật khẩu MySQL khác:
```php
class Database {
    private $host = "localhost";
    private $db_name = "banhaisan_db";
    private $username = "root";       // Thay đổi nếu của bạn khác
    private $password = "";           // Điền mật khẩu nếu có
    // ...
}
```

### Bước 4: Chạy Website
Mở trình duyệt Web và truy cập:
* Trang phía Khách: `http://localhost/banhaisan/`
* Trang phía Admin: `http://localhost/banhaisan/admin/`

*(Tài khoản dùng thử: Hãy tìm trong bảng `users` ở database sau khi Import vào để thử quyền tài khoản Admin).*

---

## 📞 Liên hệ
Dự án được tạo bởi [Triệu Đoàn Kỳ]. Mọi đóng góp xin vui lòng gửi Pull Request hoặc gửi Issue thông qua Github!
