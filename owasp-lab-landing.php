<?php
require_once 'api/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OWASP Lab | Secure Worldz Academy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
    <script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
    <style>
        :root {
            --primary: #ff2a2f;
            --muted-fg: #94a3b8;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --bg-card: rgba(16, 21, 31, 0.85);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #07090e;
            color: #ffffff;
            font-family: 'Roboto Mono', monospace;
        }

        .owasp-landing-main { gap: 1.5rem; }
        .owasp-landing-header { flex-shrink: 0; }

        .owasp-header-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
        }

        .owasp-header-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            flex-shrink: 0;
            border-radius: 0.375rem;
            background: var(--primary);
            color: #ffffff;
            font-size: 1.125rem;
        }

        .owasp-header-title {
            margin: 0;
            color: #ffffff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.875rem;
            line-height: 2.25rem;
            letter-spacing: -0.02em;
        }

        .owasp-header-subtitle {
            margin: 0.125rem 0 0;
            color: var(--muted-fg);
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .owasp-version-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem;
            align-items: stretch;
        }

        .owasp-version-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.5);
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .owasp-version-card::before {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            width: 100%;
            height: 3px;
            content: '';
            background: linear-gradient(90deg, #ff2a2f 0%, #c0151a 50%, #ff2a2f 100%);
        }

        .owasp-version-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 42, 47, 0.35);
            box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.7), 0 0 20px -4px rgba(255, 42, 47, 0.18);
        }

        .owasp-version-visual {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.875rem;
            min-height: 150px;
            padding: 1.5rem;
            background: linear-gradient(135deg, #0f0f14 0%, #07090e 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--primary);
        }

        .owasp-version-visual i { font-size: 2.25rem; }

        .owasp-version-year {
            color: #ffffff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.25rem;
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        .owasp-version-content {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 1.5rem;
        }

        .owasp-version-title {
            margin: 0 0 0.75rem;
            color: #ffffff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .owasp-version-description {
            flex: 1;
            margin: 0 0 1.5rem;
            color: var(--muted-fg);
            font-size: 0.875rem;
            line-height: 1.6;
        }

        .owasp-entry-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            background: linear-gradient(135deg, #ff2a2f 0%, #b81419 100%);
            box-shadow: 0 4px 14px rgba(255, 42, 47, 0.3);
            color: #ffffff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-decoration: none;
            text-transform: uppercase;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .owasp-entry-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 42, 47, 0.5);
            filter: brightness(1.08);
        }

        .owasp-entry-button:active { transform: translateY(0); }

        @media (max-width: 1023px) {
            .owasp-version-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 640px) {
            .owasp-header-content { padding: 0.875rem; }
            .owasp-header-title { font-size: 1.5rem; line-height: 2rem; }
            .owasp-version-content { padding: 1.25rem; }
        }
    </style>
</head>
<body>
    <div class="desktop-container">
        <?php include 'sidebar.php'; ?>

        <main class="desktop-main owasp-landing-main">
            <header class="card owasp-landing-header">
                <div class="owasp-header-content">
                    <div class="owasp-header-icon" aria-hidden="true">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h1 class="owasp-header-title">OWASP Lab</h1>
                        <p class="owasp-header-subtitle">Choose a lab version to practice secure application development and vulnerability analysis.</p>
                    </div>
                </div>
            </header>

            <section class="owasp-version-grid" aria-label="OWASP lab versions">
                <article class="owasp-version-card">
                    <div class="owasp-version-visual" aria-hidden="true">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span class="owasp-version-year">2025</span>
                    </div>
                    <div class="owasp-version-content">
                        <h2 class="owasp-version-title">OWASP 2025</h2>
                        <p class="owasp-version-description">Explore hands-on OWASP Top 10 2025 vulnerability scenarios and strengthen your practical application-security skills.</p>
                        <a class="owasp-entry-button" href="owasp-2025.php">Enter Lab</a>
                    </div>
                </article>

                <article class="owasp-version-card">
                    <div class="owasp-version-visual" aria-hidden="true">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span class="owasp-version-year">2026</span>
                    </div>
                    <div class="owasp-version-content">
                        <h2 class="owasp-version-title">OWASP 2026</h2>
                        <p class="owasp-version-description">Train with the OWASP Top 10 2026 lab environment through guided, realistic security challenges and analysis.</p>
                        <a class="owasp-entry-button" href="owasp-2026-lab.php">Enter Lab</a>
                    </div>
                </article>
            </section>
        </main>
    </div>
</body>
</html>
