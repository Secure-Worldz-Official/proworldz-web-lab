<?php
require_once 'config.php';
if (!is_logged_in()) { header("Location: login.php"); exit; }

include 'templates/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow p-4">
            <h2 class="fw-bold">Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h2>
            <p class="text-muted">Current System Status: <span class="badge bg-success">Production</span></p>
            <hr>
            <p>Your access level is restricted. Only basic analytics are visible.</p>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="p-3 border rounded text-center">
                        <h4 class="fw-bold">124</h4>
                        <small class="text-muted">Active Sessions</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded text-center">
                        <h4 class="fw-bold">99.9%</h4>
                        <small class="text-muted">Uptime</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded text-center">
                        <h4 class="fw-bold">0</h4>
                        <small class="text-muted">Pending Errors</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
