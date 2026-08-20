<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


unset(
    $_SESSION['owasp_2026_authenticated'],
    $_SESSION['owasp_2026_user_name'],
    $_SESSION['owasp_2026_user_email']
);

header('Location: owasp-2026-landing.php');
exit;
