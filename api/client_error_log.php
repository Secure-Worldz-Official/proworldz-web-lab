<?php
// Best-effort client diagnostics for intermittent loading failures. No response
// body is returned so this endpoint cannot interfere with the requesting page.
header('Content-Type: application/json; charset=utf-8');
http_response_code(204);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Avoid log floods from a broken browser extension or network proxy.
$now = time();
if (!empty($_SESSION['client_error_log_at']) && ($now - (int) $_SESSION['client_error_log_at']) < 5) {
    exit;
}
$_SESSION['client_error_log_at'] = $now;

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody, true);
if (!is_array($payload)) {
    exit;
}

$entry = [
    'event' => 'client_loading_error',
    'user_id' => isset($_SESSION['id']) ? (string) $_SESSION['id'] : null,
    'type' => substr((string) ($payload['type'] ?? 'unknown'), 0, 80),
    'message' => substr((string) ($payload['message'] ?? ''), 0, 500),
    'detail' => substr((string) ($payload['detail'] ?? ''), 0, 1000),
    'path' => substr((string) ($payload['path'] ?? ''), 0, 250),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
];

error_log(json_encode($entry, JSON_UNESCAPED_SLASHES));
