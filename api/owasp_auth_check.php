<?php




if (session_status() === PHP_SESSION_NONE) {
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => $cookieParams['path'] ?? '/',
        'domain'   => $cookieParams['domain'] ?? '',
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

$dbConfigPath = __DIR__ . '/dbconfig.php';
if (!file_exists($dbConfigPath)) {
    $dbConfigPath = dirname(__DIR__) . '/api/dbconfig.php';
}
require_once $dbConfigPath;

$db = new DBconfig();

if (!isset($_SESSION['id'])) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Authentication required.']);
        exit;
    }
    header('Location: ' . (file_exists('owasp-2026-landing.php') ? 'owasp-2026-landing.php' : '../../owasp-2026-landing.php'));
    exit;
}

$userId = $_SESSION['id'];
$hasAccess = $db->hasOwasp2026Access($userId);


if (!$hasAccess && isset($_SESSION['admin_username'])) {
    $hasAccess = true;
}

if (!$hasAccess) {
    $_SESSION['owasp_2026_authenticated'] = false;
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Access denied. OWASP 2026 Lab requires verified access.']);
        exit;
    }
    $landingUrl = file_exists('owasp-2026-landing.php') ? 'owasp-2026-landing.php' : '../../owasp-2026-landing.php';
    header("Location: $landingUrl");
    exit;
}

$_SESSION['owasp_2026_authenticated'] = true;
?>
