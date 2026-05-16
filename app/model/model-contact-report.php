<?php
/**
 * Model: Liên hệ & Báo cáo
 */

class ModelContactReport {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Create contact submission
     */
    public function createContact($data) {
        $name = sanitize($data['name'] ?? '');
        $email = sanitize($data['email'] ?? '');
        $phone = sanitize($data['phone'] ?? '');
        $message = sanitize($data['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($phone) || empty($message)) {
            return ['success' => false, 'error' => 'Vui lòng nhập đầy đủ thông tin'];
        }
        
        $query = "INSERT INTO lien_he (ten, email, so_dien_thoai, noi_dung, ngay_tao)
                  VALUES ('$name', '$email', '$phone', '$message', NOW())";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Cảm ơn bạn đã liên hệ'];
    }
    
    /**
     * Create report
     */
    public function createReport($data) {
        $roomId = isset($data['room_id']) ? (int)$data['room_id'] : null;
        $name = sanitize($data['name'] ?? '');
        $email = sanitize($data['email'] ?? '');
        $phone = sanitize($data['phone'] ?? '');
        $message = sanitize($data['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($phone) || empty($message)) {
            return ['success' => false, 'error' => 'Vui lòng nhập đầy đủ thông tin'];
        }
        
        $query = "INSERT INTO bao_cao (phong_tro_id, nguoi_gui_ten, nguoi_gui_email, 
                                       nguoi_gui_so_dien_thoai, noi_dung, ngay_tao, da_xu_ly)
                  VALUES ($roomId, '$name', '$email', '$phone', '$message', NOW(), 0)";
        
        if (!$this->db->query($query)) {
            return ['success' => false, 'error' => 'Lỗi database'];
        }
        
        return ['success' => true, 'message' => 'Cảm ơn bạn đã gửi báo cáo'];
    }
}
