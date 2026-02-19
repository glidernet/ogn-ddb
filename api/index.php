<?php

require_once __DIR__ . '/../sql.php';
require_once __DIR__ . '/../lib/device_service.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/handlers/devices.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Parse path: strip /api prefix and leading slash
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('#^/api#', '', $path);
$path = rtrim($path, '/');

$method = $_SERVER['REQUEST_METHOD'];
$dbh    = Database::connect();

// Route: /devices or /devices/{id}
if (preg_match('#^/devices/([A-Fa-f0-9]{6})$#', $path, $m)) {
    $devid = strtoupper($m[1]);
    switch ($method) {
        case 'GET':
            handle_device_get($dbh, $devid);
            break;
        case 'PUT':
            handle_device_put($dbh, $devid);
            break;
        case 'DELETE':
            handle_device_delete($dbh, $devid);
            break;
        default:
            api_error(405, 'Method not allowed');
    }
} elseif ($path === '/devices') {
    switch ($method) {
        case 'GET':
            handle_devices_list($dbh);
            break;
        case 'POST':
            handle_device_post($dbh);
            break;
        default:
            api_error(405, 'Method not allowed');
    }
} else {
    api_error(404, 'Not found');
}
