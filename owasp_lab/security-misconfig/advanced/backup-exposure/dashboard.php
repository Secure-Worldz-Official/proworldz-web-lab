<?php
require_once 'config.php';
include 'templates/header.php';
?>
<div class="alert alert-info">Dashboard Access Granted. Current Env: <?= SYSTEM_ENV ?></div>
<p>Sensitive operations are locked. System backup scheduled for tomorrow.</p>
<p class="small text-muted">Maintenance Note: System logs archived at /system/backups/</p>
<?php include 'templates/footer.php'; ?>
