<?php
require_once 'config.php';
// VULNERABILITY: This OLD admin panel has a misconfigured auth check (only checks if 'user' is set, not the role)
// AND it's a hidden route
if (!isset($_SESSION['user'])) {
    die("Access Denied. Only authenticated users (admins) can view this legacy panel.");
}

include 'templates/header.php';
?>
<div class="card p-4 border-danger shadow">
    <h3 class="text-danger">LEGACY ADMIN PANEL</h3>
    <hr>
    <div class="alert alert-warning">
        <strong>Warning:</strong> This panel is pending deletion. Please migrate all data to the new dashboard immediately.
    </div>
    
    <h5>System Flag Retrieval:</h5>
    <div class="bg-dark text-white p-3 rounded mt-2">
        <code>SYSTEM_FLAG: <?= SYSTEM_FLAG ?></code>
    </div>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-outline-danger btn-sm">Return to Main Portal</a>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
