<?php

require_once 'api/auth_check.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Secure Worldz Academy'; ?></title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Roboto+Mono:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="design-system.css?v=20260413d">
    <link rel="stylesheet" href="th-theme.css?v=20260413d">
    <script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
</head>
<body class="animate-fade">
    <div class="app-wrapper">
        <?php include 'sidebar.php'; ?>
        <main class="main-container" style="grid-template-columns: 1fr;">
            <div class="main-content">
