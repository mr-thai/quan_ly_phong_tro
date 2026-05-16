-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2026 at 05:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `PHONGTRO247`
--
CREATE DATABASE IF NOT EXISTS PHONGTRO247 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE PHONGTRO247;
-- --------------------------------------------------------

--
-- Table structure for table `bao_cao`
--

CREATE TABLE `bao_cao` (
  `bao_cao_id` int(11) NOT NULL,
  `phong_tro_id` int(11) DEFAULT NULL,
  `nguoi_gui_ten` varchar(200) DEFAULT NULL,
  `nguoi_gui_so_dien_thoai` varchar(30) DEFAULT NULL,
  `nguoi_gui_email` varchar(255) DEFAULT NULL,
  `noi_dung` text DEFAULT NULL,
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp(),
  `da_xu_ly` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bao_cao`
--

INSERT INTO `bao_cao` (`bao_cao_id`, `phong_tro_id`, `nguoi_gui_ten`, `nguoi_gui_so_dien_thoai`, `nguoi_gui_email`, `noi_dung`, `ngay_tao`, `da_xu_ly`) VALUES
(1, 1, 'Nguyễn Văn A', '0901234567', NULL, 'Giá không đúng như tin đăng.', '2026-05-16 22:07:38', 0),
(2, 4, 'Lê Văn C', '0923456789', NULL, 'Phòng đã được cho thuê nhưng chưa gỡ tin.', '2026-05-16 22:07:38', 1),
(3, 7, 'Đỗ Thị F', '0956789012', NULL, 'Thông tin liên hệ sai.', '2026-05-16 22:07:38', 0),
(4, 2, 'Bùi Văn I', '0989012345', NULL, 'Hình ảnh không giống thực tế.', '2026-05-16 22:07:38', 0),
(5, 9, 'Nguyễn Văn A', '0901234567', NULL, 'Nghi ngờ lừa đảo cọc tiền.', '2026-05-16 22:07:38', 0),
(6, 3, 'Vũ Văn G', '0967890123', NULL, 'Mô tả không chính xác về diện tích.', '2026-05-16 22:07:38', 1),
(7, 6, 'Lý Thị K', '0990123456', NULL, 'Chủ trọ thái độ không tốt.', '2026-05-16 22:07:38', 0),
(8, 8, 'Lê Văn C', '0923456789', NULL, 'Phòng có mùi ẩm mốc, không như quảng cáo.', '2026-05-16 22:07:38', 0),
(9, 5, 'Đỗ Thị F', '0956789012', NULL, 'Tin đăng spam.', '2026-05-16 22:07:38', 1),
(10, 10, 'Bùi Văn I', '0989012345', NULL, 'Không liên lạc được với chủ trọ.', '2026-05-16 22:07:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lien_he`
--

CREATE TABLE `lien_he` (
  `lien_he_id` int(11) NOT NULL,
  `ten` varchar(200) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(30) DEFAULT NULL,
  `noi_dung` text DEFAULT NULL,
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lien_he`
--

INSERT INTO `lien_he` (`lien_he_id`, `ten`, `email`, `so_dien_thoai`, `noi_dung`, `ngay_tao`) VALUES
(1, 'Khách hàng 1', 'kh1@gmail.com', '0123456789', 'Cần hỗ trợ tìm phòng gấp ở Quận 1.', '2026-05-16 22:07:38'),
(2, 'Khách hàng 2', 'kh2@gmail.com', '0234567890', 'Tôi muốn đăng tin cho thuê nhưng không biết cách.', '2026-05-16 22:07:38'),
(3, 'Khách hàng 3', 'kh3@gmail.com', '0345678901', 'Lỗi hiển thị hình ảnh trên website.', '2026-05-16 22:07:38'),
(4, 'Khách hàng 4', 'kh4@gmail.com', '0456789012', 'Cho tôi hỏi về gói đăng tin VIP.', '2026-05-16 22:07:38'),
(5, 'Khách hàng 5', 'kh5@gmail.com', '0567890123', 'Báo cáo lỗi nạp tiền vào tài khoản.', '2026-05-16 22:07:38'),
(6, 'Khách hàng 6', 'kh6@gmail.com', '0678901234', 'Cần tư vấn hợp đồng thuê nhà.', '2026-05-16 22:07:38'),
(7, 'Khách hàng 7', 'kh7@gmail.com', '0789012345', 'Quên mật khẩu tài khoản nhưng không nhận được email khôi phục.', '2026-05-16 22:07:38'),
(8, 'Khách hàng 8', 'kh8@gmail.com', '0890123456', 'Tôi muốn xóa tài khoản.', '2026-05-16 22:07:38'),
(9, 'Khách hàng 9', 'kh9@gmail.com', '0901234567', 'Góp ý về giao diện ứng dụng.', '2026-05-16 22:07:38'),
(10, 'Khách hàng 10', 'kh10@gmail.com', '0112233445', 'Hợp tác quảng cáo.', '2026-05-16 22:07:38');

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `nguoi_dung_id` int(11) NOT NULL,
  `ten` varchar(200) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(30) DEFAULT NULL,
  `mat_khau_hash` varchar(512) DEFAULT NULL,
  `vai_tro` varchar(50) NOT NULL DEFAULT 'nguoi_dung',
  `trang_thai` varchar(50) NOT NULL DEFAULT 'hoat_dong',
  `dia_chi` varchar(400) DEFAULT NULL,
  `avatar_url` varchar(1000) DEFAULT NULL,
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp(),
  `ngay_cap_nhat` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `da_xoa` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`nguoi_dung_id`, `ten`, `email`, `so_dien_thoai`, `mat_khau_hash`, `vai_tro`, `trang_thai`, `dia_chi`, `avatar_url`, `ngay_tao`, `ngay_cap_nhat`, `da_xoa`) VALUES
(1, 'Nguyễn Văn A', 'nguyenvana@gmail.com', '0901234567', NULL, 'nguoi_dung', 'hoat_dong', 'Hà Nội', NULL, '2026-05-16 22:07:38', NULL, 0),
(2, 'Trần Thị B', 'tranthib@gmail.com', '0912345678', NULL, 'chu_tro', 'hoat_dong', 'Hồ Chí Minh', NULL, '2026-05-16 22:07:38', NULL, 0),
(3, 'Lê Văn C', 'levanc@gmail.com', '0923456789', NULL, 'nguoi_dung', 'hoat_dong', 'Đà Nẵng', NULL, '2026-05-16 22:07:38', NULL, 0),
(4, 'Phạm Thị D', 'phamthid@gmail.com', '0934567890', NULL, 'quan_tri', 'hoat_dong', 'Cần Thơ', NULL, '2026-05-16 22:07:38', NULL, 0),
(5, 'Hoàng Văn E', 'hoangvane@gmail.com', '0945678901', NULL, 'chu_tro', 'hoat_dong', 'Hải Phòng', NULL, '2026-05-16 22:07:38', NULL, 0),
(6, 'Đỗ Thị F', 'dothif@gmail.com', '0956789012', NULL, 'nguoi_dung', 'hoat_dong', 'Nha Trang', NULL, '2026-05-16 22:07:38', NULL, 0),
(7, 'Vũ Văn G', 'vuvang@gmail.com', '0967890123', NULL, 'nguoi_dung', 'hoat_dong', 'Huế', NULL, '2026-05-16 22:07:38', NULL, 0),
(8, 'Đặng Thị H', 'dangthih@gmail.com', '0978901234', NULL, 'chu_tro', 'khoa', 'Vũng Tàu', NULL, '2026-05-16 22:07:38', NULL, 0),
(9, 'Bùi Văn I', 'buivani@gmail.com', '0989012345', NULL, 'nguoi_dung', 'hoat_dong', 'Biên Hòa', NULL, '2026-05-16 22:07:38', NULL, 0),
(10, 'Lý Thị K', 'lythik@gmail.com', '0990123456', NULL, 'nguoi_dung', 'hoat_dong', 'Bình Dương', NULL, '2026-05-16 22:07:38', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `phong_tro`
--

CREATE TABLE `phong_tro` (
  `phong_tro_id` int(11) NOT NULL,
  `tieu_de` varchar(500) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `dia_chi` varchar(500) DEFAULT NULL,
  `khu_vuc` varchar(200) DEFAULT NULL,
  `gia` bigint(20) DEFAULT NULL,
  `dien_tich` decimal(8,2) DEFAULT NULL,
  `anh_chinh_url` varchar(1000) DEFAULT NULL,
  `nguoi_dung_chu_so_huu_id` int(11) DEFAULT NULL,
  `ten_chu_so_huu` varchar(200) DEFAULT NULL,
  `so_dien_thoai_chu` varchar(30) DEFAULT NULL,
  `luot_xem` int(11) NOT NULL DEFAULT 0,
  `trang_thai` varchar(50) NOT NULL DEFAULT 'cho_duyet',
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp(),
  `ngay_cap_nhat` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `da_xoa` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phong_tro`
--

INSERT INTO `phong_tro` (`phong_tro_id`, `tieu_de`, `mo_ta`, `dia_chi`, `khu_vuc`, `gia`, `dien_tich`, `anh_chinh_url`, `nguoi_dung_chu_so_huu_id`, `ten_chu_so_huu`, `so_dien_thoai_chu`, `luot_xem`, `trang_thai`, `ngay_tao`, `ngay_cap_nhat`, `da_xoa`) VALUES
(1, 'Phòng trọ sinh viên giá rẻ', 'Phòng rộng rãi, thoáng mát, gần trường học.', 'Số 10, Ngõ 20, Quận Đống Đa', 'Hà Nội', 1500000, 20.50, NULL, 2, 'Trần Thị B', '0912345678', 0, 'da_duyet', '2026-05-16 22:07:38', NULL, 0),
(2, 'Căn hộ mini cao cấp', 'Đầy đủ nội thất, an ninh 24/7.', 'Đường Nguyễn Hữu Thọ, Quận 7', 'Hồ Chí Minh', 5000000, 35.00, NULL, 5, 'Hoàng Văn E', '0945678901', 0, 'da_duyet', '2026-05-16 22:07:38', NULL, 0),
(3, 'Phòng trọ cho người đi làm', 'Khu vực yên tĩnh, giờ giấc tự do.', 'Đường Cầu Giấy', 'Hà Nội', 2500000, 25.00, NULL, 2, 'Trần Thị B', '0912345678', 0, 'da_duyet', '2026-05-16 22:07:38', NULL, 0),
(4, 'Ký túc xá sinh viên', 'Giường tầng, có tủ đồ riêng.', 'Quận Thủ Đức', 'Hồ Chí Minh', 800000, 15.00, NULL, 8, 'Đặng Thị H', '0978901234', 0, 'cho_duyet', '2026-05-16 22:07:38', NULL, 0),
(5, 'Phòng trọ mới xây', 'Sạch sẽ, wc khép kín.', 'Quận Bình Thạnh', 'Hồ Chí Minh', 3000000, 22.00, NULL, 5, 'Hoàng Văn E', '0945678901', 0, 'da_duyet', '2026-05-16 22:07:38', NULL, 0),
(6, 'Nhà nguyên căn cho thuê', 'Thích hợp ở gia đình hoặc nhóm bạn.', 'Quận Tân Phú', 'Hồ Chí Minh', 7000000, 60.00, NULL, 2, 'Trần Thị B', '0912345678', 0, 'cho_duyet', '2026-05-16 22:07:38', NULL, 0),
(7, 'Phòng trọ mặt tiền', 'Tiện kinh doanh nhỏ.', 'Quận 10', 'Hồ Chí Minh', 4500000, 30.00, NULL, 5, 'Hoàng Văn E', '0945678901', 0, 'da_duyet', '2026-05-16 22:07:38', NULL, 0),
(8, 'Phòng trọ gần bến xe', 'Thuận tiện đi lại.', 'Bến xe Mỹ Đình', 'Hà Nội', 2000000, 18.00, NULL, 2, 'Trần Thị B', '0912345678', 0, 'da_duyet', '2026-05-16 22:07:38', NULL, 0),
(9, 'Studio đầy đủ tiện nghi', 'Xách vali vào ở ngay.', 'Quận 1', 'Hồ Chí Minh', 8000000, 40.00, NULL, 5, 'Hoàng Văn E', '0945678901', 0, 'cho_duyet', '2026-05-16 22:07:38', NULL, 0),
(10, 'Phòng trọ gác lửng', 'Tối ưu diện tích sử dụng.', 'Quận Gò Vấp', 'Hồ Chí Minh', 2800000, 25.00, NULL, 8, 'Đặng Thị H', '0978901234', 0, 'da_duyet', '2026-05-16 22:07:38', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `phong_tro_anh`
--

CREATE TABLE `phong_tro_anh` (
  `phong_tro_anh_id` int(11) NOT NULL,
  `phong_tro_id` int(11) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `thu_tu` int(11) NOT NULL DEFAULT 0,
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phong_tro_anh`
--

INSERT INTO `phong_tro_anh` (`phong_tro_anh_id`, `phong_tro_id`, `url`, `thu_tu`, `ngay_tao`) VALUES
(1, 1, 'https://example.com/images/phong1_1.jpg', 1, '2026-05-16 22:07:38'),
(2, 1, 'https://example.com/images/phong1_2.jpg', 2, '2026-05-16 22:07:38'),
(3, 2, 'https://example.com/images/phong2_1.jpg', 1, '2026-05-16 22:07:38'),
(4, 2, 'https://example.com/images/phong2_2.jpg', 2, '2026-05-16 22:07:38'),
(5, 3, 'https://example.com/images/phong3_1.jpg', 1, '2026-05-16 22:07:38'),
(6, 4, 'https://example.com/images/phong4_1.jpg', 1, '2026-05-16 22:07:38'),
(7, 5, 'https://example.com/images/phong5_1.jpg', 1, '2026-05-16 22:07:38'),
(8, 6, 'https://example.com/images/phong6_1.jpg', 1, '2026-05-16 22:07:38'),
(9, 9, 'https://example.com/images/phong9_1.jpg', 1, '2026-05-16 22:07:38'),
(10, 9, 'https://example.com/images/phong9_2.jpg', 2, '2026-05-16 22:07:38');

-- --------------------------------------------------------

--
-- Table structure for table `phong_tro_tien_ich`
--

CREATE TABLE `phong_tro_tien_ich` (
  `phong_tro_id` int(11) NOT NULL,
  `tien_ich_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phong_tro_tien_ich`
--

INSERT INTO `phong_tro_tien_ich` (`phong_tro_id`, `tien_ich_id`) VALUES
(1, 1),
(1, 3),
(1, 5),
(2, 1),
(2, 2),
(2, 9),
(3, 1),
(3, 6),
(3, 7),
(4, 1),
(4, 3),
(5, 5),
(5, 6),
(6, 1),
(6, 9),
(6, 10),
(7, 1),
(7, 8),
(8, 1),
(8, 5),
(8, 10),
(9, 1),
(9, 2),
(9, 8),
(9, 9),
(10, 4),
(10, 5),
(10, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tien_ich`
--

CREATE TABLE `tien_ich` (
  `tien_ich_id` int(11) NOT NULL,
  `ten` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tien_ich`
--

INSERT INTO `tien_ich` (`tien_ich_id`, `ten`) VALUES
(8, 'Camera an ninh'),
(3, 'Chỗ để xe'),
(4, 'Gác lửng'),
(10, 'Gần trạm xe buýt'),
(7, 'Giờ giấc tự do'),
(6, 'Không chung chủ'),
(2, 'Máy lạnh'),
(5, 'Nhà vệ sinh riêng'),
(9, 'Nội thất đầy đủ'),
(1, 'Wifi miễn phí');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bao_cao`
--
ALTER TABLE `bao_cao`
  ADD PRIMARY KEY (`bao_cao_id`),
  ADD KEY `IX_bao_cao_phong_tro_id` (`phong_tro_id`);

--
-- Indexes for table `lien_he`
--
ALTER TABLE `lien_he`
  ADD PRIMARY KEY (`lien_he_id`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`nguoi_dung_id`),
  ADD KEY `IX_nguoi_dung_email` (`email`),
  ADD KEY `IX_nguoi_dung_so_dien_thoai` (`so_dien_thoai`);

--
-- Indexes for table `phong_tro`
--
ALTER TABLE `phong_tro`
  ADD PRIMARY KEY (`phong_tro_id`),
  ADD KEY `FK_phong_tro_nguoi_dung_chu_so_huu` (`nguoi_dung_chu_so_huu_id`),
  ADD KEY `IX_phong_tro_trang_thai` (`trang_thai`),
  ADD KEY `IX_phong_tro_khu_vuc` (`khu_vuc`),
  ADD KEY `IX_phong_tro_gia` (`gia`);

--
-- Indexes for table `phong_tro_anh`
--
ALTER TABLE `phong_tro_anh`
  ADD PRIMARY KEY (`phong_tro_anh_id`),
  ADD KEY `IX_phong_tro_anh_phong_tro_thu_tu` (`phong_tro_id`,`thu_tu`);

--
-- Indexes for table `phong_tro_tien_ich`
--
ALTER TABLE `phong_tro_tien_ich`
  ADD PRIMARY KEY (`phong_tro_id`,`tien_ich_id`),
  ADD KEY `FK_phong_tro_tien_ich_tien_ich` (`tien_ich_id`);

--
-- Indexes for table `tien_ich`
--
ALTER TABLE `tien_ich`
  ADD PRIMARY KEY (`tien_ich_id`),
  ADD UNIQUE KEY `ten` (`ten`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bao_cao`
--
ALTER TABLE `bao_cao`
  MODIFY `bao_cao_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lien_he`
--
ALTER TABLE `lien_he`
  MODIFY `lien_he_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `nguoi_dung_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `phong_tro`
--
ALTER TABLE `phong_tro`
  MODIFY `phong_tro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `phong_tro_anh`
--
ALTER TABLE `phong_tro_anh`
  MODIFY `phong_tro_anh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tien_ich`
--
ALTER TABLE `tien_ich`
  MODIFY `tien_ich_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bao_cao`
--
ALTER TABLE `bao_cao`
  ADD CONSTRAINT `FK_bao_cao_phong_tro` FOREIGN KEY (`phong_tro_id`) REFERENCES `phong_tro` (`phong_tro_id`);

--
-- Constraints for table `phong_tro`
--
ALTER TABLE `phong_tro`
  ADD CONSTRAINT `FK_phong_tro_nguoi_dung_chu_so_huu` FOREIGN KEY (`nguoi_dung_chu_so_huu_id`) REFERENCES `nguoi_dung` (`nguoi_dung_id`);

--
-- Constraints for table `phong_tro_anh`
--
ALTER TABLE `phong_tro_anh`
  ADD CONSTRAINT `FK_phong_tro_anh_phong_tro` FOREIGN KEY (`phong_tro_id`) REFERENCES `phong_tro` (`phong_tro_id`) ON DELETE CASCADE;

--
-- Constraints for table `phong_tro_tien_ich`
--
ALTER TABLE `phong_tro_tien_ich`
  ADD CONSTRAINT `FK_phong_tro_tien_ich_phong_tro` FOREIGN KEY (`phong_tro_id`) REFERENCES `phong_tro` (`phong_tro_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_phong_tro_tien_ich_tien_ich` FOREIGN KEY (`tien_ich_id`) REFERENCES `tien_ich` (`tien_ich_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
