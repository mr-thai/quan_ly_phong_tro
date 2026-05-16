<?php
/**
 * API quản trị viên: quản lý user, phòng, báo cáo
 */

require_once __DIR__ . '/config.php';

requireAdmin();

$method = getRequestMethod();
$resource = $_GET['resource'] ?? ''; // 'users', 'rooms', 'reports'
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($method === 'GET') {
    if ($resource === 'users') {
        getAdminUsers();
    } elseif ($resource === 'rooms') {
        getAdminRooms();
    } elseif ($resource === 'reports') {
        getAdminReports();
    } elseif ($resource === 'stats') {
        getAdminStats();
    } else {
        json_response(['success' => false, 'error' => 'Invalid resource'], 400);
    }
} elseif ($method === 'POST') {
    if ($resource === 'users') {
        createAdminUser();
    } else {
        json_response(['success' => false, 'error' => 'Invalid resource'], 400);
    }
} elseif ($method === 'PUT') {
    if ($resource === 'users' && $id) {
        updateAdminUser($id);
    } else {
        json_response(['success' => false, 'error' => 'Invalid resource'], 400);
    }
} elseif ($method === 'PATCH') {
    if ($resource === 'users' && $id) {
        toggleUserStatus($id);
    } elseif ($resource === 'rooms' && $id) {
        handleRoomAction($id);
    } elseif ($resource === 'reports' && $id) {
        markReportResolved($id);
    } else {
        json_response(['success' => false, 'error' => 'Invalid resource'], 400);
    }
} else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}

function getAdminUsers() {
    global $db;
    
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $offset = ($page - 1) * $limit;
    $search = sanitize($_GET['search'] ?? '');
    
    $where = "WHERE da_xoa = 0";
    if ($search) {
        $where .= " AND (ten LIKE '%$search%' OR email LIKE '%$search%' OR so_dien_thoai LIKE '%$search%')";
    }
    
    $query = "SELECT * FROM nguoi_dung $where ORDER BY ngay_tao DESC LIMIT $offset, $limit";
    $result = $db->query($query);
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        unset($row['mat_khau_hash']);
        $users[] = $row;
    }
    
    $countResult = $db->query("SELECT COUNT(*) as total FROM nguoi_dung $where");
    $total = $countResult->fetch_assoc()['total'];
    
    json_response([
        'success' => true,
        'data' => $users,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

function getAdminRooms() {
    global $db;
    
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $offset = ($page - 1) * $limit;
    $status = sanitize($_GET['status'] ?? '');
    
    $where = "WHERE da_xoa = 0";
    if ($status) {
        $where .= " AND trang_thai = '$status'";
    }
    
    $query = "SELECT pt.*, nd.ten as nguoi_dang 
              FROM phong_tro pt
              LEFT JOIN nguoi_dung nd ON pt.nguoi_dung_chu_so_huu_id = nd.nguoi_dung_id
              $where
              ORDER BY ngay_tao DESC
              LIMIT $offset, $limit";
    
    $result = $db->query($query);
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    
    $countResult = $db->query("SELECT COUNT(*) as total FROM phong_tro $where");
    $total = $countResult->fetch_assoc()['total'];
    
    json_response([
        'success' => true,
        'data' => $rooms,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

function getAdminReports() {
    global $db;
    
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $offset = ($page - 1) * $limit;
    $resolved = isset($_GET['resolved']) ? (int)$_GET['resolved'] : null;
    
    $where = "";
    if ($resolved !== null) {
        $where = "WHERE da_xu_ly = $resolved";
    }
    
    $query = "SELECT bc.*, pt.tieu_de as phong_tro_title 
              FROM bao_cao bc
              LEFT JOIN phong_tro pt ON bc.phong_tro_id = pt.phong_tro_id
              $where
              ORDER BY ngay_tao DESC
              LIMIT $offset, $limit";
    
    $result = $db->query($query);
    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
    
    $countQuery = "SELECT COUNT(*) as total FROM bao_cao";
    if ($where) {
        $countQuery .= " $where";
    }
    $countResult = $db->query($countQuery);
    $total = $countResult->fetch_assoc()['total'];
    
    json_response([
        'success' => true,
        'data' => $reports,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

function getAdminStats() {
    global $db;
    
    $totalUsers = $db->query("SELECT COUNT(*) as count FROM nguoi_dung WHERE da_xoa = 0")->fetch_assoc()['count'];
    $totalRooms = $db->query("SELECT COUNT(*) as count FROM phong_tro WHERE da_xoa = 0")->fetch_assoc()['count'];
    $pendingRooms = $db->query("SELECT COUNT(*) as count FROM phong_tro WHERE trang_thai = 'cho_duyet' AND da_xoa = 0")->fetch_assoc()['count'];
    $unresolvedReports = $db->query("SELECT COUNT(*) as count FROM bao_cao WHERE da_xu_ly = 0")->fetch_assoc()['count'];
    
    json_response([
        'success' => true,
        'stats' => [
            'total_users' => $totalUsers,
            'total_rooms' => $totalRooms,
            'pending_rooms' => $pendingRooms,
            'unresolved_reports' => $unresolvedReports
        ]
    ]);
}

function createAdminUser() {
    global $db;
    
    $input = getJsonInput();
    
    $name = sanitize($input['name'] ?? '');
    $email = sanitize($input['email'] ?? '');
    $phone = sanitize($input['phone'] ?? '');
    $role = sanitize($input['role'] ?? 'nguoi_dung');
    $status = sanitize($input['status'] ?? 'hoat_dong');
    
    if (empty($name) || empty($email)) {
        return json_response(['success' => false, 'error' => 'Name và email bắt buộc'], 400);
    }
    
    // Check email exists
    $checkEmail = $db->query("SELECT nguoi_dung_id FROM nguoi_dung WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        return json_response(['success' => false, 'error' => 'Email đã tồn tại'], 400);
    }
    
    $defaultPassword = hashPassword('123456'); // Default password
    
    $query = "INSERT INTO nguoi_dung (ten, email, so_dien_thoai, mat_khau_hash, vai_tro, trang_thai, ngay_tao)
              VALUES ('$name', '$email', '$phone', '$defaultPassword', '$role', '$status', NOW())";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    json_response([
        'success' => true,
        'message' => 'Tạo tài khoản thành công. Mật khẩu mặc định: 123456',
        'user_id' => $db->insert_id
    ]);
}

function updateAdminUser($id) {
    global $db;
    
    $id = (int)$id;
    $input = getJsonInput();
    
    $name = isset($input['name']) ? sanitize($input['name']) : null;
    $phone = isset($input['phone']) ? sanitize($input['phone']) : null;
    $role = isset($input['role']) ? sanitize($input['role']) : null;
    $status = isset($input['status']) ? sanitize($input['status']) : null;
    
    $updates = [];
    if ($name) $updates[] = "ten = '$name'";
    if ($phone) $updates[] = "so_dien_thoai = '$phone'";
    if ($role) $updates[] = "vai_tro = '$role'";
    if ($status) $updates[] = "trang_thai = '$status'";
    $updates[] = "ngay_cap_nhat = NOW()";
    
    if (empty($updates)) {
        return json_response(['success' => false, 'error' => 'Không có thông tin để cập nhật'], 400);
    }
    
    $updateStr = implode(', ', $updates);
    $query = "UPDATE nguoi_dung SET $updateStr WHERE nguoi_dung_id = $id";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    json_response(['success' => true, 'message' => 'Cập nhật thành công']);
}

function toggleUserStatus($id) {
    global $db;
    
    $id = (int)$id;
    $input = getJsonInput();
    $newStatus = sanitize($input['status'] ?? 'hoat_dong');
    
    $query = "UPDATE nguoi_dung SET trang_thai = '$newStatus', ngay_cap_nhat = NOW() WHERE nguoi_dung_id = $id";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    json_response(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
}

function handleRoomAction($id) {
    global $db;
    
    $id = (int)$id;
    $input = getJsonInput();
    $action = sanitize($input['action'] ?? '');
    $newStatus = sanitize($input['status'] ?? '');
    
    if (empty($newStatus)) {
        return json_response(['success' => false, 'error' => 'Status bắt buộc'], 400);
    }
    
    $query = "UPDATE phong_tro SET trang_thai = '$newStatus', ngay_cap_nhat = NOW() WHERE phong_tro_id = $id";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    $message = $action === 'approve' ? 'Đã duyệt tin đăng' : 'Đã cập nhật trạng thái';
    
    json_response(['success' => true, 'message' => $message]);
}

function markReportResolved($id) {
    global $db;
    
    $id = (int)$id;
    
    $query = "UPDATE bao_cao SET da_xu_ly = 1 WHERE bao_cao_id = $id";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    json_response(['success' => true, 'message' => 'Đã đánh dấu báo cáo là đã xử lý']);
}
?>
