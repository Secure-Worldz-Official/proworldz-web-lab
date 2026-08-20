<?php
require_once 'config.php';
if(isset($_GET['logout'])){ session_destroy(); header("Location: index.php"); exit; }
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    header("Location: dashboard.php"); exit;
}
include 'templates/header.php';
?>
<div class="row justify-content-center"><div class="col-md-4 card p-4 shadow">
<form method="POST"><h3>Login</h3><input name="u" class="form-control mb-2" placeholder="Admin"><input type="password" name="p" class="form-control mb-2" placeholder="Password"><button class="btn btn-primary w-100">Login</button></form>
</div></div>
<?php include 'templates/footer.php'; ?>
