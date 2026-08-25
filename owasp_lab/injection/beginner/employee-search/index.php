<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once dirname(__DIR__, 4) . '/owasp_lab/db.php';
include 'templates/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-5 search-box border-0">
            <h3 class="fw-bold mb-4">Internal Employee Directory</h3>
            <p class="text-muted small mb-4">Secure lookup for enterprise personnel across all global intelligence sectors.</p>
            <form action="search.php" method="GET" class="mb-0">
                <div class="input-group">
                    <input type="text" name="name" class="form-control form-control-lg bg-light" placeholder="Search by name (e.g. John)" required>
                    <button class="btn btn-primary px-5 fw-bold">Query Identity</button>
                </div>
            </form>
            <div class="mt-4 p-3 bg-light rounded text-center x-small text-muted border border-dashed">
                <i class="bi bi-shield-lock me-1"></i> Input validated successfully by Intelligence Gateway
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
