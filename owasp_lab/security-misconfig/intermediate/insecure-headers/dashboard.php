<?php
require_once 'config.php';
include 'templates/header.php';

$headers = headers_list();
?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow p-4">
            <h3 class="fw-bold">Security Audit Results</h3>
            <hr>
            <div class="alert alert-warning">
                <strong>Warning:</strong> Several critical security headers are missing from the response.
            </div>
            
            <h5 class="mt-4">Detected Headers:</h5>
            <ul class="list-group mb-4">
                <?php foreach ($headers as $h): ?>
                    <li class="list-group-item"><code><?= htmlspecialchars($h) ?></code></li>
                <?php endforeach; ?>
            </ul>

            <h5 class="text-danger mt-4">Security Recommendations:</h5>
            <div class="p-3 bg-light border rounded">
                <ul>
                    <li>Set <strong>X-Frame-Options: DENY</strong> to prevent Clickjacking.</li>
                    <li>Set <strong>Content-Security-Policy</strong> to prevent XSS.</li>
                    <li>Verify: <code>FLAG{6d6973636f6e6669675f3034}</code> in audit logs.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- DEBUG: Flag verify: FLAG{6d6973636f6e6669675f3034} -->
<?php include 'templates/footer.php'; ?>
