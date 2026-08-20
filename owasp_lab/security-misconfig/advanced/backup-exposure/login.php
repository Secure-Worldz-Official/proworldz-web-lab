<?php
require_once 'config.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    header("Location: dashboard.php"); exit;
}
include 'templates/header.php';
?>
<div class="row justify-content-center"><div class="col-md-4">
    <div class="card bg-secondary p-4 shadow-lg text-start">
        <h3 class="mb-3">Login</h3>
        <form method="POST">
            <input name="u" class="form-control mb-3" placeholder="Username">
            <input type="password" name="p" class="form-control mb-3" placeholder="Password">
            <button class="btn btn-primary w-100">Sign In</button>
        </form>
    </div>
</div></div>
<?php include 'templates/footer.php'; ?>
