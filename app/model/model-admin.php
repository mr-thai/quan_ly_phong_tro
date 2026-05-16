<?php
/**
 * Model: Admin - Quản lý user, phòng, báo cáo
 */

class ModelAdmin {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Get users list (admin)
     */
    public function getUsers($page = 1, $limit = 20, $search = '') {
        $pagination = getPagination($page, $limit);
        $search = sanitize($search);
        
        $where = "WHERE da_xoa = 0";
        if ($search) {
            $where .= " AND (ten LIKE '%$search%' OR email LIKE '%$search%' OR so_dien_thoai LIKE '%$search%')";
        }
        
        $query = "SELECT * FROM nguoi_dung $where ORDER BY ngay_tao DESC LIMIT {$pagination['offset']}, {$pagination['limit']}";
        $result = $this->db->query($query);
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            unset($row['mat_khau_hash']);
            $users[] = $row;
        }
        
        $countResult = $this->db->query("SELECT COUNT(*) as total FROM nguoi_dung $where");
        $total = $countResult->fetch_assoc()['total'];
        
        return buildPaginationResponse($users, $total, $pagination['page'], $pagination['limit']);
    }
    
    /**
     * Get rooms list (admin)
     */
    public function getRooms($page = 1, $limit = 20, $status = '') {
        $pagination = getPagination($page, $limit);
        $status = sanitize($status);
        
        $where = "WHERE pt.da_xoa = 0";
        if ($status) {
            $where .= " AND pt.trang_thai = '$status'";
        }
        
        $query = "SELECT pt.*, nd.ten as nguoi_dang 
                  FROM phong_tro pt
                  LEFT JOIN nguoi_dung nd ON pt.nguoi_dung_chu_so_huu_id = nd.nguoi_dung_id
                  $where
                  ORDER BY pt.ngay_tao DESC
                  LIMIT {$pagination['offset']}, {$pagination['limit']}";
        
        $result = $this->db->query($query);
        $rooms = [];
        
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        
        $countResult = $this->db->query("SELECT COUNT(*) as total FROM phong_tro pt $where");
        $total = $countResult->fetch_assoc()['total'];
        
        return buildPaginationResponse($rooms, $total, $pagination['page'], $pagination['limit']);
    }
    
    /**
     * Get reports list (admin)
     */
    public function getReports($page = 1, $limit = 20, $resolved = null) {
        $pagination = getPagination($page, $limit);
        
        $where = "";
        if ($resolved !== null) {
            $where = "WHERE da_xu_ly = " . (int)$resolved;
        }
        
        $query = "SELECT bc.*, pt.tieu_de as phong_tro_title 
                  FROM bao_cao bc
                  LEFT JOIN phong_tro pt ON bc.phong_tro_id = pt.phong_tro_id
                  $where
                  ORDER BY ngay_tao DESC
                  LIMIT {$pagination['offset']}, {$pagination['limit']}";
        
        $result = $this->db->query($query);
        $reports = [];
        
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
        
        $countQuery = "SELECT COUNT(*) as total FROM bao_cao";
        if ($where) {
            $countQuery .= " $where";
        }
        $countResult = $this->db->query($countQuery);
        $total = $countResult->fetch_assoc()['total'];
        
        return buildPaginationResponse($reports, $total, $pagination['page'], $pagination['limit']);
    }
    
    /**
     * Get dashboard stats
     */
    public function getStats() {
        $totalUsers = $this->db->query("SELECT COUNT(*) as count FROM nguoi_dung WHERE da_xoa = 0")->fetch_assoc()['count'];
        $totalRooms = $this->db->query("SELECT COUNT(*) as count FROM phong_tro WHERE da_xoa = 0")->fetch_assoc()['count'];
        $pendingRooms = $this->db->query("SELECT COUNT(*) as count FROM phong_tro WHERE trang_thai = 'cho_duyet' AND da_xoa = 0")->fetch_assoc()['count'];
        $unresolvedReports = $this->db->query("SELECT COUNT(*) as count FROM bao_cao WHERE da_xu_ly = 0")->fetch_assoc()['count'];
        
        return [
            'total_users' => $totalUsers,
            'total_rooms' => $totalRooms,
            'pending_rooms' => $pendingRooms,
            'unresolved_reports' => $unresolvedReports
        ];
    }
    
    /**
     * Create user (admin)
     */
    public function createUser($data) {
        $name = sanitize($data['name'] ?? '');
        $email = sanitize($data['email'] ?? '');
        $phone = sanitize($data['phone'] ?? '');
        $role = sanitize($data['role'] ?? 'nguoi_dung');
        $status = sanitize($data['status'] ?? 'hoat_dong');
        
        if (empty($name) || empty($email)) {
            return ['success' => false, 'error' => 'Name và email bắt buộc'];
        }
        
        $checkEmail = $this->db->query("SELECT nguoi_dung_id FROM nguoi_dung WHERE email = '$email'");
        if ($checkEmail->num_rows > 0) {
            return ['success' => false, 'error' => 'Email đã tồn tại'];
        }
        
        $defaultPassword = hashPassword('123456');
        
        $query = "INSERT INTO nguoi_dung (ten, email, so_dien_thoai, mat_khau_hash, vai_tro, trang_thai, ngay_tao)
                  VALUES ('$name', '$email', '$phone', '$defaultPassword', '$role', '$status', NOW())";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Tạo tài khoản thành công. Mật khẩu: 123456', 'user_id' => $this->db->insert_id];
    }
    
    /**
     * Update user (admin)
     */
    public function updateUser($id, $data) {
        $id = (int)$id;
        
        $updates = [];
        if (isset($data['name'])) $updates[] = "ten = '" . sanitize($data['name']) . "'";
        if (isset($data['phone'])) $updates[] = "so_dien_thoai = '" . sanitize($data['phone']) . "'";
        if (isset($data['role'])) $updates[] = "vai_tro = '" . sanitize($data['role']) . "'";
        if (isset($data['status'])) $updates[] = "trang_thai = '" . sanitize($data['status']) . "'";
        $updates[] = "ngay_cap_nhat = NOW()";
        
        if (count($updates) < 2) {
            return ['success' => false, 'error' => 'Không có thông tin để cập nhật'];
        }
        
        $updateStr = implode(', ', $updates);
        $query = "UPDATE nguoi_dung SET $updateStr WHERE nguoi_dung_id = $id";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Cập nhật thành công'];
    }
    
    /**
     * Approve/reject room (admin)
     */
    public function updateRoomStatus($id, $status) {
        $id = (int)$id;
        $status = sanitize($status);
        
        $query = "UPDATE phong_tro SET trang_thai = '$status', ngay_cap_nhat = NOW() WHERE phong_tro_id = $id";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Cập nhật trạng thái thành công'];
    }
    
    /**
     * Mark report as resolved (admin)
     */
    public function resolveReport($id) {
        $id = (int)$id;
        
        $query = "UPDATE bao_cao SET da_xu_ly = 1 WHERE bao_cao_id = $id";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Đã đánh dấu báo cáo là đã xử lý'];
    }
}
