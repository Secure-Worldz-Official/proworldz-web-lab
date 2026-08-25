<?php
setcookie('IAM_SID', '', time() - 3600, '/');
header("Location: index.php");
?>
