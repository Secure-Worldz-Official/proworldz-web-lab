<?php
require_once dirname(__DIR__, 4) . '/owasp_lab/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$result = $con->query("SELECT * FROM documents_owasp WHERE is_private=0");
$docs = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
include 'templates/header.php';
?>
<h3>Available Documents</h3>
<div class="list-group">
    <?php foreach($docs as $d): ?>
        <a href="view.php?doc_id=<?= $d['id'] ?>" class="list-group-item list-group-item-action"><?= htmlspecialchars($d['title']) ?></a>
    <?php endforeach; ?>
</div>
<div class="mt-4 alert alert-warning">Only public documents are listed here.</div>
<?php include 'templates/footer.php'; ?>
