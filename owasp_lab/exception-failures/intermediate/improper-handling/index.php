<?php
/**
 * Nexora Runtime - Secure Execution Flow
 * // error handled securely with empty catch prevention
 */

session_start();

$auth_state = "GUEST";
$msg = "";

if (isset($_GET['token'])) {
    try {
        $token = $_GET['token'];
        
        // VULNERABILITY (A10): Mishandling of Exceptional Conditions - Improper Handling
        // The system tries to validate the token. If it fails, the exception is CAUGHT but IGNORED.
        // This allows the code to continue execution into dangerous territory (auth_state = ADMIN).
        
        if ($token !== 'SECURE_NEX_9982') {
             throw new Exception("INVALID_TOKEN_EXCEPTION");
        }
        
        // This line only runs if token is valid
        $auth_state = "TRUSTED_EMPLOYEE";

    } catch (Exception $e) {
        // VULNERABLE: Empty or generic catch block that doesn't prevent further execution
        // // silently ignore and continue logic
    }
    
    // Logic continues here even if an exception occurred!
    if (isset($_GET['force_admin'])) {
        $auth_state = "ADMIN";
    }
}

if ($auth_state === 'ADMIN') {
    $msg = "<div class='p-5 bg-dark text-warning border-0 rounded-5 shadow-2xl'>";
    $msg .= "<h2 class='fw-bold mb-3'>NEXORA ROOT CONSOLE</h2>";
    $msg .= "<p class='opacity-50 mb-4'>Exception handled improperly. State contamination detected.</p>";
    $msg .= "<div class='p-3 bg-white bg-opacity-10 border border-white border-opacity-10 rounded text-center'>";
    $msg .= "VAULT_KEY: FLAG{a10_exception_ignore_03}";
    $msg .= "</div></div>";
} else {
    $msg = "<div class='p-5 bg-white border-0 shadow-sm rounded-5'>";
    $msg .= "<h2 class='fw-bold mb-3'>Standard User Portal</h2>";
    $msg .= "<p class='text-muted small'>Auth State: <span class='badge bg-secondary'>$auth_state</span></p>";
    $msg .= "<hr><p class='small text-muted'>Please provide a valid session token for elevation.</p>";
    $msg .= "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Flow Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #fdfdfd; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <?= $msg ?>
        
        <p class="mt-4 text-center x-small text-muted opacity-50">
            Internal Note: Tokens are processed via the <code>token</code> parameter. Use <code>force_admin=1</code> for developer testing.
        </p>
    </div>
</body>
</html>
