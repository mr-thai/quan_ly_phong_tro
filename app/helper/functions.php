<?php
/**
 * Helper Functions
 */

function json_response($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

function getAuth() {
    return isset($_SESSION[SESSION_AUTH]) ? $_SESSION[SESSION_AUTH] : null;
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

function getPagination($page = 1, $limit = 10) {
    $page = max(1, (int)$page);
    $limit = (int)$limit;
    $offset = ($page - 1) * $limit;
    return ['page' => $page, 'limit' => $limit, 'offset' => $offset];
}

function buildPaginationResponse($data, $total, $page, $limit) {
    return [
        'data' => $data,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => ceil($total / $limit)
        ]
    ];
}
