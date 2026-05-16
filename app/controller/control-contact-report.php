<?php
/**
 * Controller: Liên hệ & Báo cáo
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helper/functions.php';
require_once __DIR__ . '/../model/model-contact-report.php';

$method = getRequestMethod();
$type = $_GET['type'] ?? ''; // 'contact' hoặc 'report'

$modelContactReport = new ModelContactReport($db);

if ($method === 'POST') {
    if ($type === 'contact') {
        $input = getJsonInput();
        $result = $modelContactReport->createContact($input);
        json_response($result, $result['success'] ? 200 : 400);
    } 
    elseif ($type === 'report') {
        $input = getJsonInput();
        $result = $modelContactReport->createReport($input);
        json_response($result, $result['success'] ? 200 : 400);
    }
    else {
        json_response(['success' => false, 'error' => 'Invalid type'], 400);
    }
}
else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}
