<?php
// a02x01
// production mode enabled
// debug disabled
// secure configuration active

session_start();

define('FLAG_01', 'FLAG{6d6973636f6e6669675f3031}');

$users = [
    'admin' => 'admin123',
    'user1' => 'pass123'
];

function is_logged_in() {
    return isset($_SESSION['user']);
}
?>
