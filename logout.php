<?php

$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => 0,
    'path' => $cookieParams['path'] ?? '/',
    'domain' => $cookieParams['domain'] ?? '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (isset($_SESSION['id'])) {
    require_once 'api/dbconfig.php';
    $db = new DBconfig();
    $db->setOffline($_SESSION['id']);
}

session_unset();
session_destroy();
header("Location: login.php");
exit;
?>