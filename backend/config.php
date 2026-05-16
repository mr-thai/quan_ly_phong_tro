<?php
/**
 * Cấu hình kết nối MySQL
 */

// MySQL Connection
define('DB_HOST', 'localhost');      // Địa chỉ MySQL server
define('DB_USER', 'root');           // Username MySQL
define('DB_PASS', '');               // Password MySQL (để trống nếu không có)
define('DB_NAME', 'PHONGTRO247');    // Tên database

// Kết nối MySQL
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Set charset UTF8
$db->set_charset("utf8mb4");

// Kiểm tra kết nối
if ($db->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $db->connect_error]));
}

// Session config
session_start();
define('SESSION_KEY_AUTH', 'pt247_auth');
define('SESSION_KEY_ADMIN', 'pt247_admin');

// CORS header (cho frontend truy cập)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Helper functions
function json_response($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

function getAuth() {
    return isset($_SESSION[SESSION_KEY_AUTH]) ? $_SESSION[SESSION_KEY_AUTH] : null;
}

function isAdmin() {
    $auth = getAuth();
    return $auth && $auth['vai_tro'] === 'quan_tri';
}

function requireAuth() {
    if (!getAuth()) {
        json_response(['success' => false, 'error' => 'Unauthorized'], 401);
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        json_response(['success' => false, 'error' => 'Admin access required'], 403);
    }
}

function getRequestMethod() {
    return $_SERVER['REQUEST_METHOD'];
}

function getJsonInput() {
    return json_decode(file_get_contents('php://input'), true);
}

function sanitize($str) {
    global $db;
    return $db->real_escape_string(trim($str));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?>
