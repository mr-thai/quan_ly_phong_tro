<?php
/**
 * API quản lý người dùng
 */

require_once __DIR__ . '/config.php';

$method = getRequestMethod();
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($method === 'GET') {
    if ($action === 'me') {
        requireAuth();
        getCurrentUser();
    } else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} elseif ($method === 'PUT') {
    if ($action === 'me') {
        requireAuth();
        updateCurrentUser();
    } else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}

function getCurrentUser() {
    global $db;
    
    $auth = getAuth();
    $userId = (int)$auth['nguoi_dung_id'];
    
    $query = "SELECT nguoi_dung_id, ten, email, so_dien_thoai, vai_tro, trang_thai, 
                     dia_chi, avatar_url, ngay_tao FROM nguoi_dung WHERE nguoi_dung_id = $userId";
    
    $result = $db->query($query);
    if ($result->num_rows === 0) {
        return json_response(['success' => false, 'error' => 'User not found'], 404);
    }
    
    $user = $result->fetch_assoc();
    json_response(['success' => true, 'data' => $user]);
}

function updateCurrentUser() {
    global $db;
    
    $auth = getAuth();
    $userId = (int)$auth['nguoi_dung_id'];
    $input = getJsonInput();
    
    $name = isset($input['name']) ? sanitize($input['name']) : null;
    $phone = isset($input['phone']) ? sanitize($input['phone']) : null;
    $address = isset($input['address']) ? sanitize($input['address']) : null;
    $avatar = isset($input['avatar']) ? sanitize($input['avatar']) : null;
    
    $updates = [];
    if ($name) $updates[] = "ten = '$name'";
    if ($phone) $updates[] = "so_dien_thoai = '$phone'";
    if ($address) $updates[] = "dia_chi = '$address'";
    if ($avatar) $updates[] = "avatar_url = '$avatar'";
    $updates[] = "ngay_cap_nhat = NOW()";
    
    if (empty($updates)) {
        return json_response(['success' => false, 'error' => 'Không có thông tin nào để cập nhật'], 400);
    }
    
    $updateStr = implode(', ', $updates);
    $query = "UPDATE nguoi_dung SET $updateStr WHERE nguoi_dung_id = $userId";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    json_response(['success' => true, 'message' => 'Cập nhật thông tin thành công']);
}
?>
