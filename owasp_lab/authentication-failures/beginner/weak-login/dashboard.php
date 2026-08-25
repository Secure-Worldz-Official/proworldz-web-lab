<?php
session_start();
if (!isset($_SESSION['iam_user'])) { header("Location: index.php"); exit; }

require_once dirname(__DIR__, 3) . '/db.php';
$query = "SELECT * FROM users_owasp WHERE username = '" . $con->real_escape_string($_SESSION['iam_user']) . "'";
$result = $con->query($query);
$user = $result ? $result->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora IAM | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="card border-0 shadow-sm p-5 rounded-5 bg-white">
            <h2 class="fw-bold mb-4">Enterprise Access Overview</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 bg-primary text-white rounded-4">
                        <h6 class="fw-bold mb-1 opacity-75">IDENTITY</h6>
                        <h4><?= htmlspecialchars($user['username'] ?? 'N/A') ?></h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-dark text-white rounded-4">
                        <h6 class="fw-bold mb-1 opacity-75">ROLE</h6>
                        <h4><?= htmlspecialchars($user['role'] ?? 'N/A') ?></h4>
                    </div>
                </div>
            </div>
            
            <?php if ($user && $user['role'] === 'superadmin'): ?>
                <div class="mt-5 p-4 border border-warning bg-warning bg-opacity-10 rounded-4">
                    <h5 class="fw-bold text-dark">SECURE FLAG REVEALED</h5>
                    <p class="mb-0 text-danger fw-bold">FLAG: <?= $user['secret_flag'] ?></p>
                </div>
            <?php endif; ?>

            <hr class="my-5">
            <a href="logout.php" class="btn btn-outline-dark">Terminate Session</a>
        </div>
    </div>
</body>
</html>
