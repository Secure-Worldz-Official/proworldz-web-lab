<?php
// a02x04
// production mode enabled
// debug disabled
session_start();

// VULNERABILITY: MISSING SECURITY HEADERS
// No Content-Security-Policy
// No X-Frame-Options
// No X-Content-Type-Options

define('SYSTEM_FLAG', 'FLAG{6230315f3034}'); // Prompt said different flag for intermediate
?>
