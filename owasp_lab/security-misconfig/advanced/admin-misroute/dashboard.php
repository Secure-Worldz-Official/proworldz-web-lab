<?php
require_once 'config.php';
check_auth();
include 'templates/header.php';
?>
<h3>Employee Records</h3>
<div class="alert alert-secondary">Viewing guest records... (Admin access required for sensitive data)</div>
<div class="mt-4 border p-3 rounded bg-white">
    <p class="text-muted">No records found for current user role.</p>
</div>
<?php include 'templates/footer.php'; ?>
