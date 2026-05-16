<?php
/**
 * Controller: Người dùng
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helper/functions.php';
require_once __DIR__ . '/../model/model-user.php';

$method = getRequestMethod();
$action = $_GET['action'] ?? '';

requireAuth();

$auth = getAuth();
$modelUser = new ModelUser($db);

if ($method === 'GET') {
    if ($action === 'me') {
        $user = $modelUser->getCurrentUser($auth['id']);
        if ($user) {
            json_response(['success' => true, 'data' => $user]);
        } else {
            json_response(['success' => false, 'error' => 'User not found'], 404);
        }
    } 
    else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} 
elseif ($method === 'PUT') {
    if ($action === 'me') {
        $input = getJsonInput();
        $result = $modelUser->updateInfo($auth['id'], $input);
        json_response($result, $result['success'] ? 200 : 400);
    } 
    elseif ($action === 'change-password') {
        $input = getJsonInput();
        $result = $modelUser->changePassword(
            $auth['id'],
            $input['oldPassword'] ?? '',
            $input['newPassword'] ?? ''
        );
        json_response($result, $result['success'] ? 200 : 400);
    }
    else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
}
else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}
