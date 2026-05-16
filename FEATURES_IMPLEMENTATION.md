# Danh sách Chức năng Cần Hoàn Thiện - PHONGTRO247

## Tổng quan
Dự án PHONGTRO247 là website tìm kiếm phòng trọ. Hiện tại là trang tĩnh (static site) với JS chỉ xử lý UI. Các chức năng cần hoàn thiện bao gồm: kết nối backend API, xác thực người dùng, quản lý dữ liệu, và các tính năng quản trị.

**Cơ sở dữ liệu**: SQL Server với schema PHONGTRO247 (các bảng: `nguoi_dung`, `phong_tro`, `tien_ich`, `bao_cao`, `lien_he`, `phong_tro_anh`, `phong_tro_tien_ich`)

---

## Bảng Danh sách Chức năng

| # | Trang | Tên Chức năng | Mô tả Kết quả | Hoàn thành |
|---|-------|--------------|--------------|-----------|
| 1 | index.html | Load danh sách phòng từ API | Lấy dữ liệu từ `/api/phong_tro`, hiển thị danh sách phòng trên homepage; hỗ trợ phân trang 6 tin/trang; sắp xếp mới nhất, phổ biến, gần ĐH Vinh | ❌ Chưa |
| 2 | index.html | Tìm kiếm nhanh | Form tìm kiếm nhanh trên hero section; gửi query đến search.html với các tham số location, priceRange, areaRange | ❌ Chưa |
| 3 | search.html | Lọc phòng nâng cao | Bộ lọc gồm: từ khóa, địa điểm, giá, diện tích, tiện ích; gửi POST tới `/api/phong_tro/search` và nhận kết quả | ❌ Chưa |
| 4 | search.html | Sắp xếp kết quả | Sắp xếp theo: mới nhất, giá thấp→cao, giá cao→thấp, diện tích lớn nhất; gọi lại filter với sort parameter | ❌ Chưa |
| 5 | room-detail.html | Xem chi tiết phòng | GET `/api/phong_tro/{id}` → hiển thị: ảnh chính, gallery ảnh, thông tin phòng, người đăng, tiện ích, mô tả; cập nhật `luot_xem` | ❌ Chưa |
| 6 | room-detail.html | Danh sách phòng liên quan | GET `/api/phong_tro` với filter khu_vuc = phòng hiện tại; hiển thị 3 phòng gợi ý | ❌ Chưa |
| 7 | room-detail.html | Tính năng liên hệ chủ phòng | Nút gọi điện (tel:); link báo cáo đã thuê | ✓ UI sẵn |
| 8 | login.html | Đăng nhập người dùng | POST `/api/auth/login` {loginId, password} → nhận token/session; lưu auth state vào localStorage; redirect trang chủ | ❌ Chưa |
| 9 | login.html | Xác thực Captcha | Tạo mã Captcha ngẫu nhiên; hiển thị trên form; check khớp trước khi submit | ✓ UI sẵn |
| 10 | register.html | Đăng ký tài khoản | POST `/api/auth/register` {name, email, phone, password} → tạo người dùng; validate email, phone format; hash password phía server | ❌ Chưa |
| 11 | forgot-password.html | Quên mật khẩu | Nhập email → POST `/api/auth/forgot-password`; gửi email reset link; người dùng set mật khẩu mới | ❌ Chưa |
| 12 | change-password.html | Đổi mật khẩu | POST `/api/auth/change-password` {currentPassword, newPassword}; verify password hiện tại; update hash mới | ❌ Chưa |
| 13 | account.html | Xem/chỉnh sửa tài khoản | GET `/api/nguoi_dung/me` → load thông tin; PUT `/api/nguoi_dung/me` {name, phone, address, avatar} → update | ❌ Chưa |
| 14 | account.html | Tải ảnh đại diện | File input; upload tới `/api/upload`; lưu URL vào DB; hiển thị preview | ❌ Chưa |
| 15 | post-create.html | Đăng tin phòng trọ | POST `/api/phong_tro` {title, address, location, price, area, description, utilities, phone}; tạo record mới; set status = 'cho_duyet' | ❌ Chưa |
| 16 | post-create.html | Upload ảnh phòng | File input; POST `/api/upload`; nhận URL; gán vào `anh_chinh_url` của phòng | ❌ Chưa |
| 17 | post-create.html | Validate form | Check: title ≠ rỗng, price > 0, area > 0, phone hợp lệ, description ≠ rỗng; hiển thị toast lỗi | ✓ Partial |
| 18 | post-manage.html | Danh sách tin của tôi | GET `/api/phong_tro/my-posts` → bảng: STT, ảnh thumbnail, tiêu đề, giá, diện tích, địa điểm, trạng thái, hành động | ❌ Chưa |
| 19 | post-manage.html | Sửa tin đăng | Link "Sửa" → post-edit.html?id=X; GET `/api/phong_tro/{id}` → fill form; PUT `/api/phong_tro/{id}` update dữ liệu | ❌ Chưa |
| 20 | post-manage.html | Xóa tin đăng | Nút "Xóa" → confirm dialog → DELETE `/api/phong_tro/{id}`; refresh table | ❌ Chưa |
| 21 | post-manage.html | Ẩn/hiện tin | Toggle button "Ẩn/Hiện" → PATCH `/api/phong_tro/{id}` {status: 'da_an' \| 'da_duyet'}; cập nhật hàng | ❌ Chưa |
| 22 | post-edit.html | Sửa thông tin phòng | GET `/api/phong_tro/{id}` → fill form; PUT `/api/phong_tro/{id}` {title, price, area, ...}; toast "Cập nhật thành công" | ❌ Chưa |
| 23 | contact.html | Gửi form liên hệ | POST `/api/lien_he` {name, email, phone, message}; lưu vào DB; gửi email admin; toast "Cảm ơn, chúng tôi sẽ phản hồi" | ❌ Chưa |
| 24 | contact.html | Hiển thị bản đồ | Iframe Google Maps embed; hiển thị vị trí TP Vinh | ✓ UI sẵn |
| 25 | report.html | Báo cáo đã thuê | Dropdown list phòng từ API; POST `/api/bao_cao` {phong_tro_id, name, phone, email, noi_dung}; save tới DB; gửi email admin | ❌ Chưa |
| 26 | admin/index.html | Dashboard Admin | GET `/api/admin/stats` → hiển thị: tổng tài khoản, tổng tin đăng, tin chờ duyệt, báo cáo chưa xử lý | ❌ Chưa |
| 27 | admin/index.html | Bảng trạng thái tin | Bảng: status, số lượng tin → thống kê phân bổ trạng thái (cho_duyet, da_duyet, da_an, da_thue) | ❌ Chưa |
| 28 | admin/accounts.html | Danh sách người dùng | GET `/api/admin/nguoi_dung` → bảng: STT, họ tên, email, phone, vai_tro, trang_thai; hỗ trợ phân trang | ❌ Chưa |
| 29 | admin/accounts.html | Thêm tài khoản (Admin) | Modal form; POST `/api/admin/nguoi_dung` {name, email, phone, role, status}; add user → refresh table | ❌ Chưa |
| 30 | admin/accounts.html | Sửa tài khoản (Admin) | Nút "Sửa" → modal pre-fill; PUT `/api/admin/nguoi_dung/{id}` → update; refresh table | ❌ Chưa |
| 31 | admin/accounts.html | Khóa/mở khóa tài khoản | Toggle button → PATCH `/api/admin/nguoi_dung/{id}` {trang_thai: 'hoat_dong' \| 'khoa'}; cập nhật hàng | ❌ Chưa |
| 32 | admin/posts.html | Danh sách tin (Admin) | GET `/api/admin/phong_tro` → bảng: STT, tiêu đề, người đăng, giá, địa điểm, trạng thái, ngày đăng, hành động | ❌ Chưa |
| 33 | admin/posts.html | Duyệt tin đăng | Nút "Duyệt" → PATCH `/api/admin/phong_tro/{id}` {status: 'da_duyet'}; cập nhật; toast "Đã duyệt" | ❌ Chưa |
| 34 | admin/posts.html | Từ chối tin | Nút "Từ chối" → modal nhập lý do → POST `/api/admin/phong_tro/{id}/reject` {reason}; gửi email người đăng | ❌ Chưa |
| 35 | admin/posts.html | Ẩn tin vi phạm | Nút "Ẩn" → PATCH `/api/admin/phong_tro/{id}` {status: 'da_an'}; có thể nhập lý do | ❌ Chưa |
| 36 | admin/posts.html | Xem chi tiết tin | Link "Chi tiết" → modal/popup hiển thị đầy đủ thông tin tin đăng + ảnh | ❌ Chưa |
| 37 | admin/stats.html | Thống kê báo cáo | Bảng: bao_cao_id, phong_tro_id, người gửi, nội dung, ngày gửi, trang_thai (da_xu_ly), hành động | ❌ Chưa |
| 38 | admin/stats.html | Xử lý báo cáo | Nút "Xử lý" → check tin; mark `da_xu_ly = 1` → PATCH `/api/admin/bao_cao/{id}` {da_xu_ly: true} | ❌ Chưa |
| 39 | admin/stats.html | Biểu đồ thống kê | Chart.js hoặc library tương tự; vẽ: số tin/tháng, phân bổ giá, top khu vực | ❌ Chưa |
| 40 | Toàn site | Xác thực JWT/Session | Gửi token trong header Authorization; kiểm tra expire time; auto redirect login nếu không hợp lệ | ❌ Chưa |
| 41 | Toàn site | Quyền truy cập (Authorization) | Admin pages: kiểm tra role = 'admin'; user pages: kiểm tra auth != null; deny access nếu không đủ quyền | ❌ Chưa |
| 42 | Toàn site | Xử lý lỗi API | Catch network/server errors; hiển thị toast/alert; retry logic cho failed requests | ❌ Chưa |
| 43 | Toàn site | Loading state | Hiển thị spinner/skeleton khi fetch dữ liệu; disable button trong quá trình submit | ❌ Chưa |
| 44 | Toàn site | Lưu ưu tiên người dùng | localStorage: preferred location, price range, utilities; đổ vào form search lần tới | ❌ Chưa |
| 45 | Backend API | POST /api/auth/login | Request: {loginId, password} → Response: {token, user: {id, name, email, role}} | ❌ Chưa |
| 46 | Backend API | POST /api/auth/register | Request: {name, email, phone, password} → validate, hash, save; Response: {success, message} | ❌ Chưa |
| 47 | Backend API | GET /api/phong_tro | Trả về paginated list phòng; query: page, limit, location, priceRange, areaRange, sort | ❌ Chưa |
| 48 | Backend API | GET /api/phong_tro/:id | Trả về chi tiết phòng; increment luot_xem; join tien_ich và images | ❌ Chưa |
| 49 | Backend API | POST /api/phong_tro (Auth) | Tạo phòng mới; set nguoi_dung_chu_so_huu_id từ token; status = 'cho_duyet' | ❌ Chưa |
| 50 | Backend API | PUT /api/phong_tro/:id (Auth) | Update phòng; check owner hoặc admin; set ngay_cap_nhat | ❌ Chưa |
| 51 | Backend API | DELETE /api/phong_tro/:id (Auth) | Xóa soft (set da_xoa = 1) hoặc hard; check owner hoặc admin | ❌ Chưa |
| 52 | Backend API | POST /api/phong_tro/search | Lọc nâng cao phòng; query: keyword, location, priceMin/Max, areaMin/Max, utilities (array) | ❌ Chưa |
| 53 | Backend API | GET /api/phong_tro/my-posts (Auth) | Trả về các phòng của người dùng hiện tại; include status, views, edit/delete links | ❌ Chưa |
| 54 | Backend API | GET /api/nguoi_dung/me (Auth) | Trả về thông tin người dùng hiện tại từ token | ❌ Chưa |
| 55 | Backend API | PUT /api/nguoi_dung/me (Auth) | Update thông tin cá nhân: name, phone, address, avatar_url | ❌ Chưa |
| 56 | Backend API | POST /api/upload | Upload file (image); kiểm tra mime type; lưu vào server/cloud; trả về URL | ❌ Chưa |
| 57 | Backend API | POST /api/lien_he | Tạo contact form submission; lưu vào DB; gửi email admin | ❌ Chưa |
| 58 | Backend API | POST /api/bao_cao | Tạo báo cáo đã thuê; lưu vào DB; gửi email admin | ❌ Chưa |
| 59 | Backend API | GET /api/admin/stats (Admin) | Trả về: totalUsers, totalPosts, pendingPosts, unresolvedReports | ❌ Chưa |
| 60 | Backend API | GET /api/admin/nguoi_dung (Admin, Paginated) | Danh sách người dùng; query: page, limit, search by name/email/phone | ❌ Chưa |
| 61 | Backend API | POST /api/admin/nguoi_dung (Admin) | Tạo người dùng mới (admin only) | ❌ Chưa |
| 62 | Backend API | PUT /api/admin/nguoi_dung/:id (Admin) | Update tài khoản (admin only) | ❌ Chưa |
| 63 | Backend API | PATCH /api/admin/nguoi_dung/:id (Admin) | Toggle status (hoat_dong ↔ khoa) | ❌ Chưa |
| 64 | Backend API | GET /api/admin/phong_tro (Admin, Paginated) | Danh sách phòng toàn site; query: page, limit, status, search | ❌ Chưa |
| 65 | Backend API | PATCH /api/admin/phong_tro/:id (Admin) | Duyệt (status = 'da_duyet') hoặc ẩn (status = 'da_an') | ❌ Chưa |
| 66 | Backend API | POST /api/admin/phong_tro/:id/reject (Admin) | Từ chối tin; lưu reason; gửi email người đăng | ❌ Chưa |
| 67 | Backend API | GET /api/admin/bao_cao (Admin, Paginated) | Danh sách báo cáo; query: page, limit, status (da_xu_ly), sort | ❌ Chưa |
| 68 | Backend API | PATCH /api/admin/bao_cao/:id (Admin) | Mark báo cáo là da_xu_ly = true | ❌ Chưa |
| 69 | Backend API | GET /api/tien_ich | Lấy danh sách tiện ích; response: [{id, name}, ...] | ❌ Chưa |
| 70 | Email Service | Send verification email | Khi đăng ký; verify link (optional hoặc bắt buộc) | ❌ Chưa |
| 71 | Email Service | Send reset password email | POST /api/auth/forgot-password → gửi link reset | ❌ Chưa |
| 72 | Email Service | Send approval/rejection email | Admin duyệt/từ chối tin → email người đăng | ❌ Chưa |
| 73 | Email Service | Send report notification email | Người dùng báo cáo → email admin thông báo | ❌ Chưa |
| 74 | Email Service | Send contact form email | Khách hàng gửi liên hệ → email admin + confirm email khách | ❌ Chưa |

---

## Tóm tắt Tiến độ

- **Tổng chức năng**: 74
- **Đã hoàn thành**: 2 (UI sẵn: 1, Partial: 1)
- **Chưa hoàn thành**: 72 (❌)

### Ưu tiên triển khai

1. **Giai đoạn 1 (Cơ bản)**: Auth (login, register, logout), API endpoints cơ bản (GET phòng, POST phòng), ✅ cho phép người dùng search & đăng tin
2. **Giai đoạn 2 (Quản lý)**: CRUD phần quản lý tin, chỉnh sửa tài khoản, upload ảnh
3. **Giai đoạn 3 (Admin)**: Dashboard, duyệt tin, quản lý user, báo cáo
4. **Giai đoạn 4 (Polish)**: Email notifications, thống kê/chart, tối ưu performance

---

## Ghi chú kỹ thuật

- **Database**: MySQL/MariaDB; schema `PHONGTRO247` với 7 bảng chính (nguoi_dung, phong_tro, tien_ich, phong_tro_anh, lien_he, bao_cao, phong_tro_tien_ich)
- **Frontend**: HTML5 + Bootstrap 5 + Vanilla JS (UI-only)
- **Backend**: Truy vấn **trực tiếp** từ MySQL (không qua REST API)
  - Lựa chọn 1: **PHP** - tạo file PHP để query MySQL, trả về JSON
  - Lựa chọn 2: **Node.js + Express** - server backend kết nối MySQL, xử lý request từ frontend
- **Authentication**: Session PHP hoặc JWT
- **File Upload**: Lưu local (`/uploads`) hoặc cloud (AWS S3, Azure Blob, etc.)
- **Email**: SMTP service (Gmail, SendGrid, etc.)

