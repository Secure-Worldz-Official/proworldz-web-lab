<?php
// a02x06
// production mode enabled
// debug disabled
session_start();

define('SYSTEM_FLAG', 'FLAG{6d6973636f6e6669675f3036}');

function check_auth() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}
?>
