<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container">
        <div class="alert alert-success border-0 shadow-sm p-4 rounded-4">
            <h5 class="fw-bold"><i class="bi bi-shield-check"></i> Welcome, <?= $_SESSION['user_id'] ?></h5>
            <p class="mb-0">Your identity has been verified via Nexora Secure Hashing.</p>
        </div>
        
        <div class="card border-0 shadow mt-4">
            <div class="card-body">
                <h6 class="fw-bold text-muted small mb-3">SYSTEM FLAG [ENCRYPTED]</h6>
                <div class="bg-light p-3 rounded font-monospace">
                    FLAG{a04_weak_hash_01}
                </div>
            </div>
        </div>
        <a href="logout.php" class="btn btn-link mt-4 text-muted small">Terminate Session</a>
    </div>
</body>
</html>
