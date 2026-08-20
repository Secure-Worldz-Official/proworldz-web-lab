<?php
require_once 'config.php';
include 'templates/header.php';

$id = $_GET['id'] ?? null;

// VULNERABILITY: Verbose PHP errors revealing file structure and flag in the error message
if ($id === 'admin') {
    // Intentionally triggering a fatal error or a customized error that displays the flag
    trigger_error("Fatal Error: Request handler for record 'admin' failed at " . __FILE__ . ". Accessing private constant SYSTEM_FLAG: " . SYSTEM_FLAG, E_USER_ERROR);
}

if ($id) {
    echo "<div class='alert alert-info'>Record for ID " . htmlspecialchars($id) . " not found in the local cache.</div>";
} else {
    echo "<div class='alert alert-warning'>Error: Record ID missing. Check system logs at /var/www/internal/logs/error.log</div>";
}

include 'templates/footer.php';
?>
