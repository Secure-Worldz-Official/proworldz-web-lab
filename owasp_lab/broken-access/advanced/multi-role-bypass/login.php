<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $con->prepare("SELECT * FROM users_owasp WHERE username=? AND password=?");
    $stmt->bind_param("ss", $_POST['u'], $_POST['p']);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    if ($u) { $_SESSION['uid'] = $u['id']; $_SESSION['role'] = $u['role']; $_SESSION['user'] = $u['username']; header("Location: dashboard.php"); exit; }
}
include 'templates/header.php';
?>
<form method="POST"><input name="u" placeholder="Username" class="form-control mb-2"><input type="password" name="p" placeholder="Password" class="form-control mb-2"><button class="btn btn-dark w-100">Login</button></form>
<?php include 'templates/footer.php'; ?>
