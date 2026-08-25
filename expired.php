<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only accessible to logged-in users (anyone not logged in has nothing to expire)
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Expired | ProWorldz</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg: #0d1015;
            --card: #1a1d24;
            --border: rgba(255,255,255,0.1);
            --warning: #f59e0b;
            --destructive: #ef4444;
            --muted-fg: #94a3b8;
            --fg: #f8fafc;
            --radius: 0.625rem;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--fg);
            font-family: 'Roboto Mono', monospace;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .expired-card {
            background: var(--card);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 1rem;
            padding: 3rem 2.5rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 0 40px rgba(239,68,68,0.08);
            animation: fadeUp 0.5s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .expired-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(239,68,68,0.1);
            border: 2px solid rgba(239,68,68,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--destructive);
            margin: 0 auto 1.75rem;
        }

        .expired-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--fg);
        }

        .expired-msg {
            font-size: 0.875rem;
            color: var(--muted-fg);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .expired-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.4rem;
            border-radius: var(--radius);
            font-family: 'Roboto Mono', monospace;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid rgba(239,68,68,0.35);
            background: rgba(239,68,68,0.08);
            color: var(--destructive);
        }

        .btn-logout:hover {
            background: rgba(239,68,68,0.18);
            border-color: rgba(239,68,68,0.6);
        }

        .badge-expired {
            display: inline-block;
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.3);
            color: var(--destructive);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="expired-card">
        <div class="expired-icon">
            <i class="fas fa-lock"></i>
        </div>
        <div class="badge-expired">Access Expired</div>
        <h1 class="expired-title">Your lab access has expired</h1>
        <p class="expired-msg">
            Your subscription period has ended and your lab access has been automatically revoked.<br><br>
            Please contact your admin to renew your access and continue using the platform.
        </p>
        <div class="expired-actions">
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</body>
</html>
