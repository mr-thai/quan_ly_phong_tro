# PHONGTRO247 🏠

> Nền tảng tìm kiếm và quản lý phòng trọ trực tuyến, kết nối người thuê và chủ nhà một cách nhanh chóng và tiện lợi.

## 📑 Mục lục
- [Giới thiệu](#-giới-thiệu)
- [Tính năng nổi bật](#-tính-năng-nổi-bật)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)
- [Lộ trình phát triển](#-lộ-trình-phát-triển)

## 💡 Giới thiệu
**PHONGTRO247** là một ứng dụng web giúp người dùng dễ dàng tìm kiếm phòng trọ, căn hộ cho thuê theo khu vực, mức giá, và diện tích mong muốn. Đồng thời, nền tảng cũng cung cấp các công cụ đắc lực cho các chủ nhà/môi giới để đăng tin cho thuê và quản lý hệ thống phòng trọ của mình. Dự án bao gồm cả một phân hệ Quản trị (Admin Dashboard) giúp kiểm duyệt tin đăng và theo dõi hệ thống.

## ✨ Tính năng nổi bật

### Dành cho Khách thuê:
- 🔍 **Tìm kiếm & Lọc:** Tìm phòng theo từ khóa, khu vực, khoảng giá, diện tích và các tiện ích đi kèm.
- 📋 **Danh sách phòng:** Xem các phòng mới nhất, phòng phổ biến, phân trang thân thiện.
- ℹ️ **Chi tiết phòng:** Xem thông tin đầy đủ, bộ sưu tập hình ảnh, tiện ích, và mô tả chi tiết.
- 📞 **Liên hệ nhanh:** Dễ dàng lấy thông tin liên hệ của chủ phòng.
- ⚠️ **Báo cáo:** Chức năng gửi phản hồi nếu phòng đã được thuê hoặc thông tin sai lệch.

### Dành cho Chủ phòng:
- ✍️ **Đăng tin cho thuê:** Giao diện trực quan để tạo mới tin đăng, upload hình ảnh và mô tả chi tiết.
- 📊 **Quản lý tin đăng:** Quản lý danh sách các bài đã đăng, cập nhật trạng thái (ẩn/hiện) và theo dõi tình trạng phê duyệt.
- 👤 **Quản lý tài khoản:** Chỉnh sửa thông tin cá nhân, cập nhật mật khẩu, ảnh đại diện.

### Dành cho Quản trị viên (Admin):
- 📈 **Dashboard:** Biểu đồ thống kê tổng quan về người dùng, số lượng tin bài và báo cáo.
- 🛡️ **Kiểm duyệt nội dung:** Phê duyệt tin mới, từ chối hoặc ẩn các tin đăng vi phạm quy định.
- 👥 **Quản lý thành viên:** Theo dõi danh sách, phân quyền và khóa/mở khóa tài khoản khi cần.
- 📨 **Quản lý báo cáo:** Xử lý các báo cáo từ cộng đồng.

## 🛠 Công nghệ sử dụng
- **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JavaScript.
- **Backend (Dự kiến):** Node.js (Express) hoặc PHP.
- **Cơ sở dữ liệu:** SQL Server / MySQL (Sử dụng cấu trúc có sẵn).
- **Khác:** Xác thực với Session hoặc JWT.

## 📂 Cấu trúc dự án
```text
quan_ly_phong_tro/
├── admin/                  # Giao diện phân hệ dành cho Admin
├── app/                    # Cấu trúc mã nguồn Backend/App
├── assets/                 # Các tệp tĩnh (CSS, JS, Images) cho Frontend
├── backend/                # Các thư mục chứa mã nguồn API xử lý
├── index.html              # Trang chủ ứng dụng
├── *.html                  # Các trang tĩnh giao diện (login, search, detail...)
├── PHONGTRO247.sql         # Script tạo bảng và dữ liệu mẫu cơ sở dữ liệu
└── FEATURES_IMPLEMENTATION.md # Bảng theo dõi tiến độ các chức năng dự án
```

## 🚀 Hướng dẫn cài đặt (Môi trường Phát triển)

Hiện tại dự án đã hoàn thành phần lớn giao diện tĩnh (Frontend) và đang trong quá trình tích hợp Backend.

1. **Tải mã nguồn:**
```bash
git clone <link-repo-cua-ban>
cd quan_ly_phong_tro
```

2. **Thiết lập Cơ sở dữ liệu:**
   - Mở hệ quản trị CSDL của bạn (SQL Server Management Studio hoặc MySQL Workbench).
   - Thực thi file `PHONGTRO247.sql` để khởi tạo schema, các bảng (`nguoi_dung`, `phong_tro`, `tien_ich`...) và dữ liệu cần thiết.

3. **Chạy giao diện tĩnh:**
   - Bạn có thể chạy trực tiếp các file `.html` hoặc sử dụng tiện ích như **Live Server** trên VSCode để có trải nghiệm tốt nhất với cấu trúc đường dẫn tĩnh.

## 🗺 Lộ trình phát triển
Dự án được phân chia theo các giai đoạn ưu tiên (Tham khảo chi tiết tại `FEATURES_IMPLEMENTATION.md`):
- [x] **Giai đoạn 1:** Hoàn thiện giao diện UI/UX (HTML/CSS/JS thuần).
- [ ] **Giai đoạn 2:** Khởi tạo Backend (API) và kết nối Cơ sở dữ liệu.
- [ ] **Giai đoạn 3:** Tích hợp luồng Xác thực (Login/Register/Quên mật khẩu).
- [ ] **Giai đoạn 4:** Hoàn thành các tính năng cốt lõi: Đăng tin, tìm kiếm, lọc.
- [ ] **Giai đoạn 5:** Xây dựng các chức năng Quản trị (Duyệt tin, Dashboard thống kê).
- [ ] **Giai đoạn 6:** Tối ưu hóa hệ thống (Gửi Email thông báo, SEO, Performance).
