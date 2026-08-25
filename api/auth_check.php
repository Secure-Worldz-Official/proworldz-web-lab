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

// Authenticated pages contain user-specific state. Never allow a stale cached
// response to leave a user behind a loading overlay or display another session.
header('Cache-Control: private, no-store, max-age=0, must-revalidate');
header('Pragma: no-cache');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

require_once 'api/dbconfig.php';
require_once 'api/avatar_helper.php';

$db = new DBconfig();
$userId = $_SESSION['id'];

$db->updateLastActive($userId);

$userInfo = $db->getUserInfo($userId, ['name', 'course', 'eagle_coins', 'assigns_complete', 'access']);

$userAccess  = $userInfo['access'] ?? 'false';
$currentPage = basename($_SERVER['PHP_SELF']);

if ($userAccess !== 'true' && $currentPage !== 'maintanance.php' && $currentPage !== 'pay.php') {
    header("Location: pay.php");
    exit();
}

$userName        = $userInfo['name'] ?? 'User';
$course          = $userInfo['course'] ?? 'Not Enrolled';
$userCoins       = $userInfo['eagle_coins'] ?? 0;
$userAssignments = $userInfo['assigns_complete'] ?? 0;

$activeAvatarImage = getActiveAvatarImage($db, $userId);

$userRank = $db->getUserRank($userId);

$_SESSION['c-user']   = $userName;
$_SESSION['c-course'] = $course;
?>
