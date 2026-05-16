<?php
/**
 * API quản lý phòng trọ
 */

require_once __DIR__ . '/config.php';

$method = getRequestMethod();
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($method === 'GET') {
    if ($action === 'list') {
        getPhongTroList();
    } elseif ($action === 'detail' && $id) {
        getPhongTroDetail($id);
    } elseif ($action === 'my-posts') {
        requireAuth();
        getMyPosts();
    } elseif ($action === 'search') {
        searchPhongTro();
    } else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} elseif ($method === 'POST') {
    if ($action === 'create') {
        requireAuth();
        createPhongTro();
    } else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} elseif ($method === 'PUT') {
    if ($action === 'update' && $id) {
        requireAuth();
        updatePhongTro($id);
    } else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} elseif ($method === 'DELETE') {
    if ($action === 'delete' && $id) {
        requireAuth();
        deletePhongTro($id);
    } else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}

function getPhongTroList() {
    global $db;
    
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    
    // Chỉ lấy phòng đã được duyệt và chưa bị xóa
    $query = "SELECT * FROM phong_tro 
              WHERE trang_thai = 'da_duyet' AND da_xoa = 0
              ORDER BY ngay_tao DESC
              LIMIT $offset, $limit";
    
    $result = $db->query($query);
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $row['utilities'] = getPhongTroUtilities($row['phong_tro_id']);
        $rooms[] = $row;
    }
    
    // Total count
    $countResult = $db->query("SELECT COUNT(*) as total FROM phong_tro WHERE trang_thai = 'da_duyet' AND da_xoa = 0");
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

function getPhongTroDetail($id) {
    global $db;
    
    $id = (int)$id;
    $query = "SELECT * FROM phong_tro WHERE phong_tro_id = $id AND da_xoa = 0";
    $result = $db->query($query);
    
    if ($result->num_rows === 0) {
        return json_response(['success' => false, 'error' => 'Phòng trọ không tồn tại'], 404);
    }
    
    $room = $result->fetch_assoc();
    
    // Increment views
    $db->query("UPDATE phong_tro SET luot_xem = luot_xem + 1 WHERE phong_tro_id = $id");
    
    // Get utilities
    $room['utilities'] = getPhongTroUtilities($id);
    
    // Get images
    $room['images'] = getPhongTroImages($id);
    
    json_response(['success' => true, 'data' => $room]);
}

function getMyPosts() {
    global $db;
    
    $auth = getAuth();
    $userId = (int)$auth['nguoi_dung_id'];
    
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    
    $query = "SELECT * FROM phong_tro 
              WHERE nguoi_dung_chu_so_huu_id = $userId AND da_xoa = 0
              ORDER BY ngay_tao DESC
              LIMIT $offset, $limit";
    
    $result = $db->query($query);
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $row['utilities'] = getPhongTroUtilities($row['phong_tro_id']);
        $rooms[] = $row;
    }
    
    $countResult = $db->query("SELECT COUNT(*) as total FROM phong_tro WHERE nguoi_dung_chu_so_huu_id = $userId AND da_xoa = 0");
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

function searchPhongTro() {
    global $db;
    
    $keyword = sanitize($_GET['keyword'] ?? '');
    $location = sanitize($_GET['location'] ?? '');
    $priceMin = isset($_GET['priceMin']) ? (int)$_GET['priceMin'] : 0;
    $priceMax = isset($_GET['priceMax']) ? (int)$_GET['priceMax'] : 999999999;
    $areaMin = isset($_GET['areaMin']) ? (float)$_GET['areaMin'] : 0;
    $areaMax = isset($_GET['areaMax']) ? (float)$_GET['areaMax'] : 9999;
    
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    
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
    
    $query = "SELECT * FROM phong_tro $where ORDER BY ngay_tao DESC LIMIT $offset, $limit";
    $result = $db->query($query);
    
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $row['utilities'] = getPhongTroUtilities($row['phong_tro_id']);
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

function createPhongTro() {
    global $db;
    
    $auth = getAuth();
    $input = getJsonInput();
    
    $title = sanitize($input['title'] ?? '');
    $address = sanitize($input['address'] ?? '');
    $location = sanitize($input['location'] ?? '');
    $price = isset($input['price']) ? (int)$input['price'] : 0;
    $area = isset($input['area']) ? (float)$input['area'] : 0;
    $description = sanitize($input['description'] ?? '');
    $phone = sanitize($input['phone'] ?? '');
    $image = sanitize($input['image'] ?? '');
    $utilities = $input['utilities'] ?? [];
    
    // Validate
    if (empty($title) || $price <= 0 || $area <= 0) {
        return json_response(['success' => false, 'error' => 'Thông tin không hợp lệ'], 400);
    }
    
    // Insert room
    $query = "INSERT INTO phong_tro 
              (tieu_de, mo_ta, dia_chi, khu_vuc, gia, dien_tich, anh_chinh_url, 
               nguoi_dung_chu_so_huu_id, so_dien_thoai_chu, trang_thai, ngay_tao)
              VALUES ('$title', '$description', '$address', '$location', $price, $area, '$image',
                      {$auth['nguoi_dung_id']}, '$phone', 'cho_duyet', NOW())";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    $roomId = $db->insert_id;
    
    // Insert utilities
    if (!empty($utilities)) {
        foreach ($utilities as $utilityName) {
            $utilityName = sanitize($utilityName);
            $utilResult = $db->query("SELECT tien_ich_id FROM tien_ich WHERE ten = '$utilityName'");
            if ($utilResult && $utilResult->num_rows > 0) {
                $utilId = $utilResult->fetch_assoc()['tien_ich_id'];
                $db->query("INSERT INTO phong_tro_tien_ich (phong_tro_id, tien_ich_id) VALUES ($roomId, $utilId)");
            }
        }
    }
    
    json_response([
        'success' => true,
        'message' => 'Đăng tin thành công. Tin sẽ được duyệt trong thời gian sớm nhất',
        'phong_tro_id' => $roomId
    ]);
}

function updatePhongTro($id) {
    global $db;
    
    $auth = getAuth();
    $id = (int)$id;
    $input = getJsonInput();
    
    // Check ownership
    $checkResult = $db->query("SELECT nguoi_dung_chu_so_huu_id FROM phong_tro WHERE phong_tro_id = $id");
    if ($checkResult->num_rows === 0) {
        return json_response(['success' => false, 'error' => 'Phòng trọ không tồn tại'], 404);
    }
    
    $owner = $checkResult->fetch_assoc()['nguoi_dung_chu_so_huu_id'];
    if ($owner != $auth['nguoi_dung_id'] && !isAdmin()) {
        return json_response(['success' => false, 'error' => 'Bạn không có quyền sửa tin này'], 403);
    }
    
    $title = sanitize($input['title'] ?? '');
    $address = sanitize($input['address'] ?? '');
    $location = sanitize($input['location'] ?? '');
    $price = isset($input['price']) ? (int)$input['price'] : null;
    $area = isset($input['area']) ? (float)$input['area'] : null;
    $description = sanitize($input['description'] ?? '');
    $phone = sanitize($input['phone'] ?? '');
    $image = sanitize($input['image'] ?? '');
    
    $updates = [];
    if ($title) $updates[] = "tieu_de = '$title'";
    if ($address) $updates[] = "dia_chi = '$address'";
    if ($location) $updates[] = "khu_vuc = '$location'";
    if ($price !== null) $updates[] = "gia = $price";
    if ($area !== null) $updates[] = "dien_tich = $area";
    if ($description) $updates[] = "mo_ta = '$description'";
    if ($phone) $updates[] = "so_dien_thoai_chu = '$phone'";
    if ($image) $updates[] = "anh_chinh_url = '$image'";
    $updates[] = "ngay_cap_nhat = NOW()";
    
    $updateStr = implode(', ', $updates);
    $query = "UPDATE phong_tro SET $updateStr WHERE phong_tro_id = $id";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    json_response(['success' => true, 'message' => 'Cập nhật tin đăng thành công']);
}

function deletePhongTro($id) {
    global $db;
    
    $auth = getAuth();
    $id = (int)$id;
    
    // Check ownership
    $checkResult = $db->query("SELECT nguoi_dung_chu_so_huu_id FROM phong_tro WHERE phong_tro_id = $id");
    if ($checkResult->num_rows === 0) {
        return json_response(['success' => false, 'error' => 'Phòng trọ không tồn tại'], 404);
    }
    
    $owner = $checkResult->fetch_assoc()['nguoi_dung_chu_so_huu_id'];
    if ($owner != $auth['nguoi_dung_id'] && !isAdmin()) {
        return json_response(['success' => false, 'error' => 'Bạn không có quyền xóa tin này'], 403);
    }
    
    // Soft delete
    $query = "UPDATE phong_tro SET da_xoa = 1, ngay_cap_nhat = NOW() WHERE phong_tro_id = $id";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    json_response(['success' => true, 'message' => 'Đã xóa tin đăng']);
}

function getPhongTroUtilities($roomId) {
    global $db;
    
    $roomId = (int)$roomId;
    $query = "SELECT t.tien_ich_id, t.ten FROM tien_ich t
              JOIN phong_tro_tien_ich pt ON t.tien_ich_id = pt.tien_ich_id
              WHERE pt.phong_tro_id = $roomId";
    
    $result = $db->query($query);
    $utilities = [];
    while ($row = $result->fetch_assoc()) {
        $utilities[] = $row;
    }
    return $utilities;
}

function getPhongTroImages($roomId) {
    global $db;
    
    $roomId = (int)$roomId;
    $query = "SELECT * FROM phong_tro_anh WHERE phong_tro_id = $roomId ORDER BY thu_tu";
    
    $result = $db->query($query);
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
    return $images;
}
?>
