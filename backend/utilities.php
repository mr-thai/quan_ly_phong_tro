<?php
/**
 * API tiện ích
 */

require_once __DIR__ . '/config.php';

$method = getRequestMethod();

if ($method === 'GET') {
    getUtilities();
} else {
    json_response(['success' => false, 'error' => 'Method not allowed'], 405);
}

function getUtilities() {
    global $db;
    
    $query = "SELECT * FROM tien_ich ORDER BY ten";
    $result = $db->query($query);
    
    $utilities = [];
    while ($row = $result->fetch_assoc()) {
        $utilities[] = $row;
    }
    
    json_response(['success' => true, 'data' => $utilities]);
}
?>
