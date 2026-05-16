# Backend PHP - Hướng dẫn sử dụng

## Cấu trúc thư mục

```
backend/
├── config.php           # Cấu hình DB + helper functions
├── auth.php             # Login, register, logout, forgot password
├── rooms.php            # CRUD phòng trọ, search, filter
├── users.php            # Xem/chỉnh sửa tài khoản
├── utilities.php        # Lấy danh sách tiện ích
├── contact_report.php   # Gửi liên hệ, báo cáo đã thuê
├── admin.php            # Admin: quản lý user, phòng, báo cáo, stats
└── upload.php           # Upload ảnh (chưa tạo)
```

## Cài đặt

### 1. Cài đặt MySQL/MariaDB
```bash
# Import database
mysql -u root -p < ../PHONGTRO247.sql
```

### 2. Cập nhật config.php
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');       // Nhập password của bạn
define('DB_NAME', 'PHONGTRO247');
```

### 3. Đặt thư mục backend trên server
- Nếu dùng Apache: đặt trong `public_html/` hoặc thư mục web
- Nếu dùng local: chạy PHP built-in server
```bash
cd backend
php -S localhost:8000
```

## Các Endpoint API

### Authentication - `backend/auth.php`

#### Login
```
POST /backend/auth.php?action=login
Content-Type: application/json

{
  "loginId": "email@example.com",  // email hoặc số điện thoại
  "password": "123456"
}

Response:
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Người dùng",
    "email": "email@example.com",
    "role": "nguoi_dung"
  }
}
```

#### Register
```
POST /backend/auth.php?action=register
Content-Type: application/json

{
  "name": "Tên người dùng",
  "email": "email@example.com",
  "phone": "0901234567",
  "password": "123456"
}
```

#### Logout
```
POST /backend/auth.php?action=logout
```

### Rooms - `backend/rooms.php`

#### Lấy danh sách phòng
```
GET /backend/rooms.php?action=list&page=1&limit=10
```

#### Tìm kiếm phòng
```
GET /backend/rooms.php?action=search&keyword=phong&location=Ha%20Noi&priceMin=1000000&priceMax=3000000
```

#### Xem chi tiết phòng
```
GET /backend/rooms.php?action=detail&id=1
```

#### Tạo phòng (yêu cầu auth)
```
POST /backend/rooms.php?action=create
Content-Type: application/json

{
  "title": "Phòng trọ sinh viên",
  "address": "123 Ngõ 20, Đống Đa",
  "location": "Hà Nội",
  "price": 1500000,
  "area": 20.5,
  "description": "Mô tả chi tiết",
  "phone": "0901234567",
  "image": "https://example.com/image.jpg",
  "utilities": ["Wifi", "Máy lạnh"]
}
```

#### Sửa phòng (yêu cầu auth)
```
PUT /backend/rooms.php?action=update&id=1
Content-Type: application/json

{
  "title": "Phòng trọ mới",
  "price": 2000000,
  ...
}
```

#### Xóa phòng (soft delete)
```
DELETE /backend/rooms.php?action=delete&id=1
```

#### Danh sách phòng của tôi (yêu cầu auth)
```
GET /backend/rooms.php?action=my-posts&page=1&limit=10
```

### Users - `backend/users.php`

#### Lấy thông tin tài khoản (yêu cầu auth)
```
GET /backend/users.php?action=me
```

#### Cập nhật tài khoản (yêu cầu auth)
```
PUT /backend/users.php?action=me
Content-Type: application/json

{
  "name": "Tên mới",
  "phone": "0987654321",
  "address": "Địa chỉ mới",
  "avatar": "https://example.com/avatar.jpg"
}
```

### Contact & Report - `backend/contact_report.php`

#### Gửi liên hệ
```
POST /backend/contact_report.php?type=contact
Content-Type: application/json

{
  "name": "Tên khách hàng",
  "email": "customer@example.com",
  "phone": "0901234567",
  "message": "Nội dung liên hệ"
}
```

#### Gửi báo cáo đã thuê
```
POST /backend/contact_report.php?type=report
Content-Type: application/json

{
  "room_id": 1,
  "name": "Người báo cáo",
  "email": "reporter@example.com",
  "phone": "0901234567",
  "message": "Nội dung báo cáo"
}
```

### Utilities - `backend/utilities.php`

#### Lấy danh sách tiện ích
```
GET /backend/utilities.php
```

### Admin - `backend/admin.php` (yêu cầu role = admin)

#### Danh sách người dùng
```
GET /backend/admin.php?resource=users&page=1&limit=20&search=keyword
```

#### Danh sách phòng toàn site
```
GET /backend/admin.php?resource=rooms&page=1&limit=20&status=cho_duyet
```

#### Danh sách báo cáo
```
GET /backend/admin.php?resource=reports&page=1&limit=20&resolved=0
```

#### Thống kê dashboard
```
GET /backend/admin.php?resource=stats
```

#### Tạo tài khoản (Admin)
```
POST /backend/admin.php?resource=users
Content-Type: application/json

{
  "name": "Admin tạo",
  "email": "new@example.com",
  "phone": "0901234567",
  "role": "chu_tro",
  "status": "hoat_dong"
}
```

#### Sửa người dùng (Admin)
```
PUT /backend/admin.php?resource=users&id=1
Content-Type: application/json

{
  "name": "Tên mới",
  "role": "quan_tri",
  "status": "khoa"
}
```

#### Duyệt phòng (Admin)
```
PATCH /backend/admin.php?resource=rooms&id=1
Content-Type: application/json

{
  "action": "approve",
  "status": "da_duyet"
}
```

#### Ẩn phòng (Admin)
```
PATCH /backend/admin.php?resource=rooms&id=1
Content-Type: application/json

{
  "action": "hide",
  "status": "da_an"
}
```

#### Xử lý báo cáo (Admin)
```
PATCH /backend/admin.php?resource=reports&id=1
```

## Sử dụng từ Frontend (JavaScript)

### Login
```javascript
const response = await fetch('backend/auth.php?action=login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    loginId: 'email@example.com',
    password: '123456'
  })
});
const data = await response.json();
```

### Lấy danh sách phòng
```javascript
const response = await fetch('backend/rooms.php?action=list&page=1&limit=10');
const data = await response.json();
```

### Tạo phòng (với auth)
```javascript
const response = await fetch('backend/rooms.php?action=create', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    title: 'Phòng trọ',
    price: 1500000,
    ...
  })
});
```

## Ghi chú

- **Session**: Sử dụng PHP native session. Mỗi lần login sẽ lưu thông tin vào `$_SESSION`
- **Password**: Được hash bằng `password_hash()` (bcrypt)
- **CORS**: Đã setup để frontend có thể truy cập từ các origin khác
- **Soft delete**: Phòng/người dùng không bị xóa thực sự mà chỉ set `da_xoa = 1`

## TODO

- [ ] Upload API (`backend/upload.php`)
- [ ] Email notifications (liên hệ, báo cáo)
- [ ] Password reset email link
- [ ] Pagination links
- [ ] Input validation nâng cao
- [ ] Rate limiting
- [ ] Logging
