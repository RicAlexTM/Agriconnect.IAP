<?php
// public/mpesa_callback.php
require_once __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/../config.php';

use AgriApp\Payments\MPesaClient;

$body = file_get_contents('php://input');
@file_put_contents(__DIR__ . '/../logs/mpesa_raw_callbacks.log', date('c') . " - " . $body . PHP_EOL, FILE_APPEND);

try {
    $payload = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo 'Invalid JSON';
        exit;
    }

    //$mpesa = new MPesaClient($config);
    $ok = $mpesa->handleCallback($payload);

    // Daraja expects HTTP 200; content doesn't matter much.
    http_response_code(200);
    echo json_encode(['received' => true, 'ok' => (bool)$ok]);

} catch (Throwable $e) {
    http_response_code(500);
    @file_put_contents(__DIR__ . '/../logs/mpesa_errors.log', date('c') . ' - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['error' => $e->getMessage()]);
}
