<?php
require_once 'config.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_SESSION['user'] = 'guest';
    header("Location: dashboard.php"); exit;
}
include 'templates/header.php';
?>
<div class="row justify-content-center"><div class="col-md-4 card p-4 shadow">
<h3>HR Login</h3>
<form method="POST">
    <button class="btn btn-secondary w-100">Guest Pass</button>
</form>
</div></div>
<?php include 'templates/footer.php'; ?>
