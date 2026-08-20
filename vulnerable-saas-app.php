<?php
require_once 'api/auth_check.php';

$courseKey = strtolower(preg_replace("/[^a-z0-9]/i", "", (string)($course ?? "")));
if ($courseKey !== "securex") {
    header("Location: dashboard.php");
    exit();
}

$saasApps = [
    [
        'title' => 'jtech-ems',
        'file' => 'jtech-ems.zip',
        'subtitle' => 'Employee management system (vulnerable training build).',
    ],
    [
        'title' => 'vultrum ecommerce',
        'file' => 'ecommerce_project.zip',
        'subtitle' => 'Ecommerce lab app with intentionally vulnerable flows.',
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable SaaS App | Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --gap: 1.5rem;
            --sides: 1.5rem;
            --radius: 0.625rem;
            --background: #000000;
            --foreground: #ffffff;
            --card: #080808;
            --primary: #ff2a2f;
            --primary-light: #8183f4;
            --muted-foreground: #a0a0a0;
            --border: rgba(139, 12, 16, 0.1);
            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        body {
            font-family: 'Roboto Mono', monospace;
            background: var(--background);
            color: var(--foreground);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 999px;
            filter: blur(100px);
            opacity: 0.22;
            pointer-events: none;
            z-index: 0;
            animation: ambient-float 16s ease-in-out infinite alternate;
        }

        body::before {
            width: 360px;
            height: 360px;
            background: rgba(255, 42, 47, 0.4);
            top: -140px;
            right: -120px;
        }

        body::after {
            width: 330px;
            height: 330px;
            background: rgba(129, 131, 244, 0.4);
            left: -140px;
            bottom: -120px;
            animation-delay: 0.9s;
        }

        .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap);
            min-height: 100vh;
            padding: var(--sides);
            background: var(--background);
            position: relative;
            z-index: 1;
        }

        .desktop-main {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            overflow-y: auto;
            max-height: calc(100vh - 3rem);
            padding-right: 0.5rem;
            position: relative;
        }

        .desktop-main::-webkit-scrollbar {
            width: 6px;
        }

        .desktop-main::-webkit-scrollbar-track {
            background: transparent;
        }

        .desktop-main::-webkit-scrollbar-thumb {
            background: rgba(160, 160, 160, 0.45);
            border-radius: 999px;
        }

        .page-header {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(145deg, rgba(8, 8, 8, 0.95) 0%, rgba(8, 8, 8, 0.75) 100%);
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 14px 40px rgba(0, 0, 0, 0.38);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .page-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-primary);
            opacity: 0.85;
        }

        .page-header::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 85% 18%, rgba(255, 42, 47, 0.14) 0%, transparent 42%),
                radial-gradient(circle at 15% 82%, rgba(129, 131, 244, 0.15) 0%, transparent 40%);
            pointer-events: none;
        }

        .page-header:hover {
            border-color: rgba(255, 42, 47, 0.28);
            box-shadow: 0 18px 48px rgba(0, 0, 0, 0.45);
        }

        .page-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            z-index: 1;
            animation: heading-glow 3.2s ease-in-out infinite alternate;
        }

        .page-header p {
            color: var(--muted-foreground);
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .apps-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            perspective: 1100px;
        }

        .app-card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(145deg, rgba(8, 8, 8, 0.95) 0%, rgba(8, 8, 8, 0.75) 100%);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.28s ease, border-color 0.28s ease, box-shadow 0.28s ease;
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.34);
        }

        .app-card::before {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 2px;
            background: var(--gradient-primary);
            opacity: 0.82;
        }

        .app-card::after {
            content: "";
            position: absolute;
            top: -140%;
            left: -55%;
            width: 58%;
            height: 300%;
            background: linear-gradient(120deg, transparent 0%, rgba(255, 255, 255, 0.08) 45%, transparent 100%);
            transform: rotate(22deg);
            transition: transform 0.75s ease;
            pointer-events: none;
        }

        .app-card:hover {
            border-color: rgba(255, 42, 47, 0.3);
            box-shadow: 0 22px 46px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 42, 47, 0.13);
            transform: translateY(-4px);
        }

        .app-card:hover::after {
            transform: translateX(220px) rotate(22deg);
        }

        .app-title {
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            text-shadow: 0 0 12px rgba(255, 42, 47, 0.18);
            position: relative;
            z-index: 1;
        }

        .app-subtitle {
            color: var(--muted-foreground);
            font-size: 0.9rem;
            line-height: 1.5;
            min-height: 2.6rem;
            position: relative;
            z-index: 1;
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            width: fit-content;
            padding: 0.7rem 1.15rem;
            border-radius: 0.55rem;
            text-decoration: none;
            font-weight: 600;
            color: #fff;
            border: 1px solid rgba(255, 42, 47, 0.4);
            background: linear-gradient(135deg, rgba(255, 42, 47, 0.95) 0%, rgba(129, 131, 244, 0.95) 100%);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            background-size: 200% 200%;
            animation: gradient-flow 4.2s ease infinite;
            position: relative;
            z-index: 1;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(255, 42, 47, 0.26);
            filter: saturate(1.1);
        }

        .download-btn i {
            transition: transform 0.2s ease;
        }

        .download-btn:hover i {
            transform: translateY(1px);
        }

        .download-btn:focus-visible {
            outline: 2px solid rgba(129, 131, 244, 0.75);
            outline-offset: 2px;
        }

        .reveal-item {
            opacity: 0;
            transform: translateY(16px);
            filter: blur(4px);
            transition: opacity 0.55s ease, transform 0.55s ease, filter 0.55s ease;
            transition-delay: var(--reveal-delay, 0s);
        }

        .reveal-item.is-visible {
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
        }

        @keyframes ambient-float {
            0% { transform: translate3d(0, 0, 0) scale(1); }
            100% { transform: translate3d(14px, -12px, 0) scale(1.08); }
        }

        @keyframes gradient-flow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes heading-glow {
            0% { text-shadow: 0 0 0 rgba(255, 42, 47, 0); }
            100% { text-shadow: 0 0 16px rgba(255, 42, 47, 0.22); }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }

            .reveal-item {
                opacity: 1;
                transform: none;
                filter: none;
            }
        }

        @media (max-width: 1024px) {
            .desktop-container {
                grid-template-columns: 1fr;
            }

            .desktop-main {
                max-height: none;
                overflow: visible;
                padding-right: 0;
            }

            .apps-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
<div class="desktop-container">
    <?php include 'sidebar.php'; ?>

    <main class="desktop-main">
        <section class="page-header reveal-item">
            <h1>Vulnerable SaaS App</h1>
            <p>Download the vulnerable training projects used in SecureX practical labs.</p>
        </section>

        <section class="apps-grid">
            <?php foreach ($saasApps as $app): ?>
                <?php $downloadPath = 'saas-apps/' . $app['file']; ?>
                <article class="app-card reveal-item">
                    <h2 class="app-title"><?php echo htmlspecialchars($app['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="app-subtitle"><?php echo htmlspecialchars($app['subtitle'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <a class="download-btn" href="<?php echo htmlspecialchars($downloadPath, ENT_QUOTES, 'UTF-8'); ?>" download>
                        <i class="fas fa-download"></i>
                        Download
                    </a>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
</div>

<script>
    setInterval(() => {
        fetch('api/heartbeat.php').catch(() => {});
    }, 60000);

    const revealItems = Array.from(document.querySelectorAll('.reveal-item'));
    revealItems.forEach((item, index) => {
        item.style.setProperty('--reveal-delay', `${Math.min(index * 90, 260)}ms`);
    });

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    revealItems.forEach((item) => revealObserver.observe(item));
</script>
</body>
</html>
