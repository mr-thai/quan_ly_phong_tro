<?php
/**
 * Model: Quản lý phòng trọ
 */

class ModelPhongTro {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Get list of rooms (published only)
     */
    public function getList($page = 1, $limit = 10) {
        $pagination = getPagination($page, $limit);
        
        $query = "SELECT * FROM phong_tro 
                  WHERE trang_thai = 'da_duyet' AND da_xoa = 0
                  ORDER BY ngay_tao DESC
                  LIMIT {$pagination['offset']}, {$pagination['limit']}";
        
        $result = $this->db->query($query);
        $rooms = [];
        
        while ($row = $result->fetch_assoc()) {
            $row['utilities'] = $this->getUtilities($row['phong_tro_id']);
            $rooms[] = $row;
        }
        
        $countResult = $this->db->query("SELECT COUNT(*) as total FROM phong_tro WHERE trang_thai = 'da_duyet' AND da_xoa = 0");
        $total = $countResult->fetch_assoc()['total'];
        
        return buildPaginationResponse($rooms, $total, $pagination['page'], $pagination['limit']);
    }
    
    /**
     * Get room detail
     */
    public function getDetail($id) {
        $id = (int)$id;
        
        $query = "SELECT * FROM phong_tro WHERE phong_tro_id = $id AND da_xoa = 0";
        $result = $this->db->query($query);
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        $room = $result->fetch_assoc();
        
        // Increment views
        $this->db->query("UPDATE phong_tro SET luot_xem = luot_xem + 1 WHERE phong_tro_id = $id");
        
        // Get utilities & images
        $room['utilities'] = $this->getUtilities($id);
        $room['images'] = $this->getImages($id);
        
        return $room;
    }
    
    /**
     * Search & filter rooms
     */
    public function search($keyword = '', $location = '', $priceMin = 0, $priceMax = 999999999, $areaMin = 0, $areaMax = 9999, $page = 1, $limit = 10) {
        $pagination = getPagination($page, $limit);
        
        $keyword = sanitize($keyword);
        $location = sanitize($location);
        
        $where = "WHERE trang_thai = 'da_duyet' AND da_xoa = 0";
        
        if ($keyword) {
            $where .= " AND (tieu_de LIKE '%$keyword%' OR mo_ta LIKE '%$keyword%' OR dia_chi LIKE '%$keyword%')";
        }
        if ($location) {
            $where .= " AND (khu_vuc LIKE '%$location%' OR dia_chi LIKE '%$location%')";
        }
        if ($priceMin > 0 || $priceMax < 999999999) {
            $where .= " AND gia BETWEEN $priceMin AND $priceMax";
        }
        if ($areaMin > 0 || $areaMax < 9999) {
            $where .= " AND dien_tich BETWEEN $areaMin AND $areaMax";
        }
        
        $query = "SELECT * FROM phong_tro $where ORDER BY ngay_tao DESC LIMIT {$pagination['offset']}, {$pagination['limit']}";
        $result = $this->db->query($query);
        
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $row['utilities'] = $this->getUtilities($row['phong_tro_id']);
            $rooms[] = $row;
        }
        
        $countResult = $this->db->query("SELECT COUNT(*) as total FROM phong_tro $where");
        $total = $countResult->fetch_assoc()['total'];
        
        return buildPaginationResponse($rooms, $total, $pagination['page'], $pagination['limit']);
    }
    
    /**
     * Get user's rooms
     */
    public function getUserRooms($userId, $page = 1, $limit = 10) {
        $pagination = getPagination($page, $limit);
        $userId = (int)$userId;
        
        $query = "SELECT * FROM phong_tro 
                  WHERE nguoi_dung_chu_so_huu_id = $userId AND da_xoa = 0
                  ORDER BY ngay_tao DESC
                  LIMIT {$pagination['offset']}, {$pagination['limit']}";
        
        $result = $this->db->query($query);
        $rooms = [];
        
        while ($row = $result->fetch_assoc()) {
            $row['utilities'] = $this->getUtilities($row['phong_tro_id']);
            $rooms[] = $row;
        }
        
        $countResult = $this->db->query("SELECT COUNT(*) as total FROM phong_tro WHERE nguoi_dung_chu_so_huu_id = $userId AND da_xoa = 0");
        $total = $countResult->fetch_assoc()['total'];
        
        return buildPaginationResponse($rooms, $total, $pagination['page'], $pagination['limit']);
    }
    
    /**
     * Create new room
     */
    public function create($data, $userId) {
        $title = sanitize($data['title'] ?? '');
        $address = sanitize($data['address'] ?? '');
        $location = sanitize($data['location'] ?? '');
        $price = (int)($data['price'] ?? 0);
        $area = (float)($data['area'] ?? 0);
        $description = sanitize($data['description'] ?? '');
        $phone = sanitize($data['phone'] ?? '');
        $image = sanitize($data['image'] ?? '');
        
        if (empty($title) || $price <= 0 || $area <= 0) {
            return ['success' => false, 'error' => 'Thông tin không hợp lệ'];
        }
        
        $query = "INSERT INTO phong_tro 
                  (tieu_de, mo_ta, dia_chi, khu_vuc, gia, dien_tich, anh_chinh_url, 
                   nguoi_dung_chu_so_huu_id, so_dien_thoai_chu, trang_thai, ngay_tao)
                  VALUES ('$title', '$description', '$address', '$location', $price, $area, '$image',
                          $userId, '$phone', 'cho_duyet', NOW())";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        $roomId = $this->db->insert_id;
        
        // Insert utilities
        if (isset($data['utilities']) && is_array($data['utilities'])) {
            foreach ($data['utilities'] as $utilityName) {
                $utilityName = sanitize($utilityName);
                $utilResult = $this->db->query("SELECT tien_ich_id FROM tien_ich WHERE ten = '$utilityName'");
                if ($utilResult && $utilResult->num_rows > 0) {
                    $utilId = $utilResult->fetch_assoc()['tien_ich_id'];
                    $this->db->query("INSERT INTO phong_tro_tien_ich (phong_tro_id, tien_ich_id) VALUES ($roomId, $utilId)");
                }
            }
        }
        
        return ['success' => true, 'message' => 'Đăng tin thành công', 'phong_tro_id' => $roomId];
    }
    
    /**
     * Update room
     */
    public function update($id, $data, $userId) {
        $id = (int)$id;
        
        // Check ownership
        $checkResult = $this->db->query("SELECT nguoi_dung_chu_so_huu_id FROM phong_tro WHERE phong_tro_id = $id");
        if ($checkResult->num_rows === 0) {
            return ['success' => false, 'error' => 'Phòng trọ không tồn tại'];
        }
        
        $owner = $checkResult->fetch_assoc()['nguoi_dung_chu_so_huu_id'];
        if ($owner != $userId && !isAdmin()) {
            return ['success' => false, 'error' => 'Bạn không có quyền sửa'];
        }
        
        $updates = [];
        if (isset($data['title'])) $updates[] = "tieu_de = '" . sanitize($data['title']) . "'";
        if (isset($data['price'])) $updates[] = "gia = " . (int)$data['price'];
        if (isset($data['area'])) $updates[] = "dien_tich = " . (float)$data['area'];
        if (isset($data['description'])) $updates[] = "mo_ta = '" . sanitize($data['description']) . "'";
        $updates[] = "ngay_cap_nhat = NOW()";
        
        $updateStr = implode(', ', $updates);
        $query = "UPDATE phong_tro SET $updateStr WHERE phong_tro_id = $id";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Cập nhật thành công'];
    }
    
    /**
     * Delete room (soft delete)
     */
    public function delete($id, $userId) {
        $id = (int)$id;
        
        $checkResult = $this->db->query("SELECT nguoi_dung_chu_so_huu_id FROM phong_tro WHERE phong_tro_id = $id");
        if ($checkResult->num_rows === 0) {
            return ['success' => false, 'error' => 'Phòng trọ không tồn tại'];
        }
        
        $owner = $checkResult->fetch_assoc()['nguoi_dung_chu_so_huu_id'];
        if ($owner != $userId && !isAdmin()) {
            return ['success' => false, 'error' => 'Bạn không có quyền xóa'];
        }
        
        $query = "UPDATE phong_tro SET da_xoa = 1, ngay_cap_nhat = NOW() WHERE phong_tro_id = $id";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Đã xóa tin đăng'];
    }
    
    /**
     * Get room utilities
     */
    private function getUtilities($roomId) {
        $roomId = (int)$roomId;
        $query = "SELECT t.tien_ich_id, t.ten FROM tien_ich t
                  JOIN phong_tro_tien_ich pt ON t.tien_ich_id = pt.tien_ich_id
                  WHERE pt.phong_tro_id = $roomId";
        
        $result = $this->db->query($query);
        $utilities = [];
        while ($row = $result->fetch_assoc()) {
            $utilities[] = $row;
        }
        return $utilities;
    }
    
    /**
     * Get room images
     */
    private function getImages($roomId) {
        $roomId = (int)$roomId;
        $query = "SELECT * FROM phong_tro_anh WHERE phong_tro_id = $roomId ORDER BY thu_tu";
        
        $result = $this->db->query($query);
        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }
        return $images;
    }
}
