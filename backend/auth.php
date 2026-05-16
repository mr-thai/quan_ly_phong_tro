<?php
/**
 * API xác thực: login, register, logout, forgot password
 */

require_once __DIR__ . '/config.php';

$method = getRequestMethod();
$action = $_GET['action'] ?? '';

if ($method === 'POST') {
    if ($action === 'login') {
        handleLogin();
    } elseif ($action === 'register') {
        handleRegister();
    } elseif ($action === 'logout') {
        handleLogout();
    } elseif ($action === 'forgot') {
        handleForgotPassword();
    } else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}

function handleLogin() {
    global $db;
    
    $input = getJsonInput();
    $loginId = sanitize($input['loginId'] ?? '');
    $password = $input['password'] ?? '';
    
    // Validate
    if (empty($loginId) || empty($password)) {
        return json_response(['success' => false, 'error' => 'Email/phone và password không được rỗng'], 400);
    }
    
    // Query user
    $query = "SELECT * FROM nguoi_dung WHERE (email = '$loginId' OR so_dien_thoai = '$loginId') AND da_xoa = 0";
    $result = $db->query($query);
    
    if (!$result || $result->num_rows === 0) {
        return json_response(['success' => false, 'error' => 'Thông tin đăng nhập chưa đúng'], 401);
    }
    
    $user = $result->fetch_assoc();
    
    // Verify password (mật khẩu được hash bằng bcrypt)
    if (!verifyPassword($password, $user['mat_khau_hash'])) {
        return json_response(['success' => false, 'error' => 'Thông tin đăng nhập chưa đúng'], 401);
    }
    
    // Check status
    if ($user['trang_thai'] === 'khoa') {
        return json_response(['success' => false, 'error' => 'Tài khoản của bạn đã bị khóa'], 403);
    }
    
    // Save to session
    $_SESSION[SESSION_KEY_AUTH] = [
        'nguoi_dung_id' => $user['nguoi_dung_id'],
        'name' => $user['ten'],
        'email' => $user['email'],
        'vai_tro' => $user['vai_tro']
    ];
    
    json_response([
        'success' => true,
        'message' => 'Đăng nhập thành công',
        'user' => [
            'id' => $user['nguoi_dung_id'],
            'name' => $user['ten'],
            'email' => $user['email'],
            'role' => $user['vai_tro']
        ]
    ]);
}

function handleRegister() {
    global $db;
    
    $input = getJsonInput();
    $name = sanitize($input['name'] ?? '');
    $email = sanitize($input['email'] ?? '');
    $phone = sanitize($input['phone'] ?? '');
    $password = $input['password'] ?? '';
    
    // Validate
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        return json_response(['success' => false, 'error' => 'Vui lòng nhập đầy đủ thông tin'], 400);
    }
    
    if (strlen($password) < 6) {
        return json_response(['success' => false, 'error' => 'Mật khẩu phải ít nhất 6 ký tự'], 400);
    }
    
    // Check email exists
    $checkEmail = $db->query("SELECT nguoi_dung_id FROM nguoi_dung WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        return json_response(['success' => false, 'error' => 'Email đã được đăng ký'], 400);
    }
    
    // Hash password
    $passwordHash = hashPassword($password);
    
    // Insert user
    $query = "INSERT INTO nguoi_dung (ten, email, so_dien_thoai, mat_khau_hash, vai_tro, trang_thai, ngay_tao)
              VALUES ('$name', '$email', '$phone', '$passwordHash', 'nguoi_dung', 'hoat_dong', NOW())";
    
    if (!$db->query($query)) {
        return json_response(['success' => false, 'error' => 'Lỗi database: ' . $db->error], 500);
    }
    
    $userId = $db->insert_id;
    
    json_response([
        'success' => true,
        'message' => 'Đăng ký thành công. Vui lòng đăng nhập',
        'user_id' => $userId
    ]);
}

function handleLogout() {
    session_destroy();
    json_response(['success' => true, 'message' => 'Đã đăng xuất']);
}

function handleForgotPassword() {
    global $db;
    
    $input = getJsonInput();
    $email = sanitize($input['email'] ?? '');
    
    if (empty($email)) {
        return json_response(['success' => false, 'error' => 'Email không được rỗng'], 400);
    }
    
    // TODO: Gửi email reset password link
    // Hiện tại chỉ trả về success message
    
    json_response([
        'success' => true,
        'message' => 'Yêu cầu reset mật khẩu đã được gửi. Vui lòng kiểm tra email'
    ]);
}
?>
