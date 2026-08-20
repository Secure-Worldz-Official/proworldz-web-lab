<!DOCTYPE html><html><head><title>Secure Assets</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><nav class="navbar navbar-dark bg-primary mb-4"><div class="container"><a class="navbar-brand" href="index.php">AssetPortal</a>
<div class="navbar-nav ms-auto"><?php if(isset($_SESSION['user'])): ?><a class="nav-link" href="login.php?logout=1">Logout</a><?php endif; ?></div></div></nav><div class="container">
