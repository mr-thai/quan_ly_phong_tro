<?php
/**
 * App Configuration & Database Connection
 */

// Database Connection
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'PHONGTRO247');

// Connect to MySQL
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$db->set_charset("utf8mb4");

if ($db->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $db->connect_error]));
}

// Session Configuration
session_start();
define('SESSION_AUTH', 'pt247_auth');
define('SESSION_ADMIN', 'pt247_admin');

// API Response Headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
