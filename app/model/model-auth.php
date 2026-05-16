<?php
/**
 * Model: Xác thực người dùng
 */

class ModelAuth {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Login user
     */
    public function login($loginId, $password) {
        $loginId = sanitize($loginId);
        
        $query = "SELECT * FROM nguoi_dung 
                  WHERE (email = '$loginId' OR so_dien_thoai = '$loginId') 
                  AND da_xoa = 0";
        
        $result = $this->db->query($query);
        
        if (!$result || $result->num_rows === 0) {
            return ['success' => false, 'error' => 'Thông tin đăng nhập chưa đúng'];
        }
        
        $user = $result->fetch_assoc();
        
        if (!verifyPassword($password, $user['mat_khau_hash'])) {
            return ['success' => false, 'error' => 'Thông tin đăng nhập chưa đúng'];
        }
        
        if ($user['trang_thai'] === 'khoa') {
            return ['success' => false, 'error' => 'Tài khoản của bạn đã bị khóa'];
        }
        
        return [
            'success' => true,
            'user' => [
                'id' => $user['nguoi_dung_id'],
                'name' => $user['ten'],
                'email' => $user['email'],
                'role' => $user['vai_tro']
            ]
        ];
    }
    
    /**
     * Register user
     */
    public function register($name, $email, $phone, $password) {
        $name = sanitize($name);
        $email = sanitize($email);
        $phone = sanitize($phone);
        
        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            return ['success' => false, 'error' => 'Vui lòng nhập đầy đủ thông tin'];
        }
        
        if (strlen($password) < 6) {
            return ['success' => false, 'error' => 'Mật khẩu phải ít nhất 6 ký tự'];
        }
        
        // Check email exists
        $checkEmail = $this->db->query("SELECT nguoi_dung_id FROM nguoi_dung WHERE email = '$email'");
        if ($checkEmail->num_rows > 0) {
            return ['success' => false, 'error' => 'Email đã được đăng ký'];
        }
        
        $passwordHash = hashPassword($password);
        
        $query = "INSERT INTO nguoi_dung (ten, email, so_dien_thoai, mat_khau_hash, vai_tro, trang_thai, ngay_tao)
                  VALUES ('$name', '$email', '$phone', '$passwordHash', 'nguoi_dung', 'hoat_dong', NOW())";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database: ' . $this->db->error];
        }
        
        return [
            'success' => true,
            'message' => 'Đăng ký thành công. Vui lòng đăng nhập',
            'user_id' => $this->db->insert_id
        ];
    }
    
    /**
     * Check if user exists by email
     */
    public function getUserByEmail($email) {
        $email = sanitize($email);
        $query = "SELECT * FROM nguoi_dung WHERE email = '$email' AND da_xoa = 0";
        $result = $this->db->query($query);
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }
}
