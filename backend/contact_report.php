<?php
/**
 * API quản lý liên hệ và báo cáo
 */

require_once __DIR__ . '/config.php';

$method = getRequestMethod();
$type = $_GET['type'] ?? ''; // 'contact' hoặc 'report'

if ($method === 'POST') {
    if ($type === 'contact') {
        createContact();
    } elseif ($type === 'report') {
        createReport();
    } else {
        json_response(['success' => false, 'error' => 'Invalid type'], 400);
    }
} else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}

function createContact() {
    global $db;
    
    $input = getJsonInput();
    
    $name = sanitize($input['name'] ?? '');
    $email = sanitize($input['email'] ?? '');
    $phone = sanitize($input['phone'] ?? '');
    $message = sanitize($input['message'] ?? '');
    
    // Validate
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        return json_response(['success' => false, 'error' => 'Vui lòng nhập đầy đủ thông tin'], 400);
    }
    
    // Insert contact
    $query = "INSERT INTO lien_he (ten, email, so_dien_thoai, noi_dung, ngay_tao)
              VALUES ('$name', '$email', '$phone', '$message', NOW())";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    // TODO: Send email to admin
    
    json_response([
        'success' => true,
        'message' => 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất'
    ]);
}

function createReport() {
    global $db;
    
    $input = getJsonInput();
    
    $roomId = isset($input['room_id']) ? (int)$input['room_id'] : null;
    $name = sanitize($input['name'] ?? '');
    $email = sanitize($input['email'] ?? '');
    $phone = sanitize($input['phone'] ?? '');
    $message = sanitize($input['message'] ?? '');
    
    // Validate
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        return json_response(['success' => false, 'error' => 'Vui lòng nhập đầy đủ thông tin'], 400);
    }
    
    // Insert report
    $query = "INSERT INTO bao_cao (phong_tro_id, nguoi_gui_ten, nguoi_gui_email, 
                                   nguoi_gui_so_dien_thoai, noi_dung, ngay_tao, da_xu_ly)
              VALUES ($roomId, '$name', '$email', '$phone', '$message', NOW(), 0)";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    // TODO: Send email to admin
    
    json_response([
        'success' => true,
        'message' => 'Cảm ơn bạn đã gửi báo cáo. Chúng tôi sẽ kiểm tra thông tin'
    ]);
}
?>
