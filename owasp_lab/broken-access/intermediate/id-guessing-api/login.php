<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $con->prepare("SELECT * FROM users_owasp WHERE username=? AND password=?");
    $stmt->bind_param("ss", $_POST['username'], $_POST['password']);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    if ($u) { $_SESSION['user_id'] = $u['id']; header("Location: dashboard.php"); exit; }
}
include 'templates/header.php';
?>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-md-4">
<form method="POST"><h3>Login</h3><input name="username" class="form-control mb-2" placeholder="Username"><input type="password" name="password" class="form-control mb-2" placeholder="Password"><button class="btn btn-primary w-100">Login</button></form>
</div></div></div>
<?php include 'templates/footer.php'; ?>
