<?php
// a02x02
// production mode enabled
// debug disabled
session_start();

define('API_KEY', 'sk_live_51Mz9y4L2k982jS90');
define('SYSTEM_FLAG', 'FLAG{6d6973636f6e6669675f3032}');

// VULNERABILITY: DIRECT ACCESS LEAKS SECRETS
if (basename($_SERVER['SCRIPT_NAME']) == 'config.php') {
    die("<h3>Configuration Leak!</h3><hr>SYSTEM_FLAG: " . SYSTEM_FLAG . "<br>API_KEY: " . API_KEY);
}

$users = ['admin' => 'admin123'];
?>
