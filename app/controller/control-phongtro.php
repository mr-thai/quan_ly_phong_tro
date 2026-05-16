<?php
/**
 * Controller: Phòng trọ (Search, Filter)
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helper/functions.php';
require_once __DIR__ . '/../model/model-phongtro.php';

$method = getRequestMethod();
$action = $_GET['action'] ?? '';

$modelPhongTro = new ModelPhongTro($db);

if ($method === 'GET') {
    if ($action === 'list') {
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 10;
        $result = $modelPhongTro->getList($page, $limit);
        json_response(['success' => true] + $result);
    } 
    elseif ($action === 'detail') {
        $id = $_GET['id'] ?? 0;
        $room = $modelPhongTro->getDetail($id);
        if ($room) {
            json_response(['success' => true, 'data' => $room]);
        } else {
            json_response(['success' => false, 'error' => 'Phòng trọ không tồn tại'], 404);
        }
    } 
    elseif ($action === 'search') {
        $keyword = $_GET['keyword'] ?? '';
        $location = $_GET['location'] ?? '';
        $priceMin = $_GET['priceMin'] ?? 0;
        $priceMax = $_GET['priceMax'] ?? 999999999;
        $areaMin = $_GET['areaMin'] ?? 0;
        $areaMax = $_GET['areaMax'] ?? 9999;
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 10;
        
        $result = $modelPhongTro->search($keyword, $location, $priceMin, $priceMax, $areaMin, $areaMax, $page, $limit);
        json_response(['success' => true] + $result);
    } 
    elseif ($action === 'my-posts') {
        requireAuth();
        $auth = getAuth();
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 10;
        $result = $modelPhongTro->getUserRooms($auth['id'], $page, $limit);
        json_response(['success' => true] + $result);
    }
    else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} 
elseif ($method === 'POST') {
    if ($action === 'create') {
        requireAuth();
        $auth = getAuth();
        $input = getJsonInput();
        $result = $modelPhongTro->create($input, $auth['id']);
        json_response($result, $result['success'] ? 200 : 400);
    } 
    else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} 
elseif ($method === 'PUT') {
    if ($action === 'update') {
        requireAuth();
        $auth = getAuth();
        $id = $_GET['id'] ?? 0;
        $input = getJsonInput();
        $result = $modelPhongTro->update($id, $input, $auth['id']);
        json_response($result, $result['success'] ? 200 : 400);
    } 
    else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
} 
elseif ($method === 'DELETE') {
    if ($action === 'delete') {
        requireAuth();
        $auth = getAuth();
        $id = $_GET['id'] ?? 0;
        $result = $modelPhongTro->delete($id, $auth['id']);
        json_response($result, $result['success'] ? 200 : 400);
    } 
    else {
        json_response(['success' => false, 'error' => 'Invalid action'], 400);
    }
}
else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}
