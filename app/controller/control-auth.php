<?php
/**
 * Controller: Xác thực
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helper/functions.php';
require_once __DIR__ . '/../model/model-auth.php';

$method = getRequestMethod();
$action = $_GET['action'] ?? '';

$modelAuth = new ModelAuth($db);

if ($method === 'POST') {
    if ($action === 'login') {
        $input = getJsonInput();
        $result = $modelAuth->login($input['loginId'] ?? '', $input['password'] ?? '');
        
        if ($result['success']) {
            $_SESSION[SESSION_AUTH] = $result['user'];
            json_response([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'user' => $result['user']
            ]);
        } else {
            json_response($result, 401);
        }
    } elseif ($action === 'register') {
        $input = getJsonInput();
        $result = $modelAuth->register(
            $input['name'] ?? '',
            $input['email'] ?? '',
            $input['phone'] ?? '',
            $input['password'] ?? ''
        );
        json_response($result, $result['success'] ? 200 : 400);
    } elseif ($action === 'logout') {
        session_destroy();
        json_response(['success' => true, 'message' => 'Đã đăng xuất']);
    } else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}
