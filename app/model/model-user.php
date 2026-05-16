<?php
/**
 * Model: Quản lý người dùng
 */

class ModelUser {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Get current user info
     */
    public function getCurrentUser($userId) {
        $userId = (int)$userId;
        
        $query = "SELECT nguoi_dung_id, ten, email, so_dien_thoai, vai_tro, trang_thai, 
                         dia_chi, avatar_url, ngay_tao FROM nguoi_dung 
                  WHERE nguoi_dung_id = $userId AND da_xoa = 0";
        
        $result = $this->db->query($query);
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    /**
     * Update user info
     */
    public function updateInfo($userId, $data) {
        $userId = (int)$userId;
        
        $updates = [];
        if (isset($data['name'])) $updates[] = "ten = '" . sanitize($data['name']) . "'";
        if (isset($data['phone'])) $updates[] = "so_dien_thoai = '" . sanitize($data['phone']) . "'";
        if (isset($data['address'])) $updates[] = "dia_chi = '" . sanitize($data['address']) . "'";
        if (isset($data['avatar'])) $updates[] = "avatar_url = '" . sanitize($data['avatar']) . "'";
        $updates[] = "ngay_cap_nhat = NOW()";
        
        if (empty($updates)) {
            return ['success' => false, 'error' => 'Không có thông tin để cập nhật'];
        }
        
        $updateStr = implode(', ', $updates);
        $query = "UPDATE nguoi_dung SET $updateStr WHERE nguoi_dung_id = $userId";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Cập nhật thành công'];
    }
    
    /**
     * Change password
     */
    public function changePassword($userId, $oldPassword, $newPassword) {
        $userId = (int)$userId;
        
        if (strlen($newPassword) < 6) {
            return ['success' => false, 'error' => 'Mật khẩu mới phải ít nhất 6 ký tự'];
        }
        
        $query = "SELECT mat_khau_hash FROM nguoi_dung WHERE nguoi_dung_id = $userId";
        $result = $this->db->query($query);
        $user = $result->fetch_assoc();
        
        if (!verifyPassword($oldPassword, $user['mat_khau_hash'])) {
            return ['success' => false, 'error' => 'Mật khẩu cũ không đúng'];
        }
        
        $newPasswordHash = hashPassword($newPassword);
        $query = "UPDATE nguoi_dung SET mat_khau_hash = '$newPasswordHash', ngay_cap_nhat = NOW() WHERE nguoi_dung_id = $userId";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
    }
}
