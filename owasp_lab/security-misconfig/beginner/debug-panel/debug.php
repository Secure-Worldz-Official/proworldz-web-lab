<?php
require_once 'config.php';
// VULNERABILITY: Publicly accessible debug panel leaking session and flag
include 'templates/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow p-4 border-danger">
            <h2 class="text-danger fw-bold">Internal System Debug</h2>
            <p class="text-muted">Warning: This page contains sensitive system state information.</p>
            <hr>
            
            <h5 class="fw-bold mt-3 text-primary">Global Constants</h5>
            <div class="bg-dark text-white p-3 rounded">
                <pre>
DEBUG_MODE: ENABLED
PLATFORM_VERSION: 1.0.4-staging
SYSTEM_FLAG: <?= FLAG_01 ?>
                </pre>
            </div>

            <h5 class="fw-bold mt-4 text-primary">Session Variable Dump</h5>
            <div class="bg-light p-3 rounded border">
                <pre><?php print_r($_SESSION); ?></pre>
            </div>

            <h5 class="fw-bold mt-4 text-primary">Server Environment Overview</h5>
            <div class="table-responsive">
                <table class="table table-sm text-muted">
                    <?php foreach ($_SERVER as $key => $value): ?>
                        <?php if (strpos($key, 'HTTP') === 0 || strpos($key, 'REMOTE') === 0): ?>
                        <tr>
                            <td class="fw-bold"><?= $key ?></td>
                            <td class="text-break"><?= $value ?></td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
