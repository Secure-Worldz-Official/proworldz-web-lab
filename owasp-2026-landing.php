<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'api/dbconfig.php';
$paymentConfig = require 'api/payment_config.php';

$db = new DBconfig();
$userId = $_SESSION['id'] ?? null;
$hasAccess = false;
$accessInfo = null;
$verification = null;

if ($userId) {
    $hasAccess = $db->hasOwasp2026Access($userId);
    $accessInfo = $db->getOwasp2026AccessInfo($userId);
    $verification = $db->getPaymentVerificationByUser($userId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OWASP 2026 AI Security Lab | Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <meta name="description" content="Access the OWASP 2026 Top 10 AI Security Vulnerability Lab at Secure Worldz Academy. Authenticate to enter hands-on simulations.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --primary-red: #ff2a2f;
            --primary-red-hover: #e6191e;
            --secondary-red: #ff2a2f;
            --accent-red: #ff4d4f;
            --red-glow: rgba(255, 42, 47, 0.35);
            --red-subtle: rgba(255, 42, 47, 0.12);
            --red-border: rgba(255, 42, 47, 0.28);
            --dark-bg: #030406;
            --darker-bg: #000000;
            --card-bg: #080b10;
            --border-color: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(255, 42, 47, 0.45);
            --text-primary: #ffffff;
            --text-secondary: #a3a3a3;
            --text-muted: #666666;
            --shadow-glow: 0 0 35px rgba(255, 42, 47, 0.25);
        }

        @keyframes scanMove {
            0%   { transform: translateY(0); }
            100% { transform: translateY(4px); }
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0; }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
            background-image:
                radial-gradient(circle at 15% 15%, rgba(255, 42, 47, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(255, 42, 47, 0.08) 0%, transparent 40%),
                linear-gradient(180deg, #030406 0%, #000000 100%);
        }

        /* Animated scanline overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background:
                repeating-linear-gradient(
                    0deg,
                    rgba(255,42,47,0.02) 0px,
                    rgba(255,42,47,0.02) 1px,
                    transparent 1px,
                    transparent 4px
                );
            animation: scanMove 8s linear infinite;
        }

        /* Animated grid overlay */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background:
                repeating-linear-gradient(90deg, rgba(255,255,255,0.018) 0px, rgba(255,255,255,0.018) 1px, transparent 1px, transparent 80px),
                repeating-linear-gradient(0deg, rgba(255,255,255,0.018) 0px, rgba(255,255,255,0.018) 1px, transparent 1px, transparent 80px);
        }

        .page-content { position: relative; z-index: 1; }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(3, 4, 6, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            background: rgba(0, 0, 0, 0.97);
            border-bottom: 1px solid var(--border-hover);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: translateY(-2px);
        }

        .logo-img {
            height: 35px;
            width: auto;
            object-fit: contain;
        }

        .logo-accent { color: var(--primary-red); }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 3rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-red);
            transition: width 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #ffffff;
        }

        .nav-links a:hover::before,
        .nav-links a.active::before {
            width: 100%;
        }

        .nav-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            background: var(--primary-red);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            box-shadow: 0 4px 15px rgba(255, 42, 47, 0.25);
        }

        .nav-cta:hover {
            background: var(--primary-red-hover);
            box-shadow: var(--shadow-glow);
            transform: translateY(-2px);
            color: #fff;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 0.5rem;
            background: none;
            border: none;
            z-index: 1001;
        }

        .menu-toggle span {
            width: 24px;
            height: 2px;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: flex;
            }
            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                background: rgba(0, 0, 0, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 100px 2rem 2rem;
                gap: 1.5rem;
                transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                border-left: 1px solid var(--red-border);
                z-index: 1000;
                list-style: none;
            }
            .nav-links.active {
                right: 0;
            }
            .nav-links .nav-cta {
                display: flex !important;
                margin-top: 1rem;
                justify-content: center;
            }
            .nav-container > .nav-cta {
                display: none !important;
            }
        }

        body, h1, h2, h3, h4, h5, h6, p, span, div, li, a {
            font-weight: 700 !important;
        }

        /* Hero & Authentication */
        .main-container {
            flex: 1;
            max-width: 960px;
            margin: 90px auto 0;
            padding: 3rem 1.5rem 5rem;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 3rem;
        }

        @media (max-width: 1024px) {
            .main-container {
                gap: 2.5rem;
                padding: 2rem 1.25rem 4rem;
            }
        }

        .hero-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
            animation: fadeUp 0.5s ease both;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            background: var(--red-subtle);
            border: 1px solid var(--red-border);
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--accent-red);
            margin-bottom: 1.5rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-family: 'JetBrains Mono', monospace;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #ffffff 0%, var(--primary-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
            font-family: 'Space Grotesk', sans-serif;
        }

        .hero-title-sub {
            display: block;
            font-size: 1.85rem;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            letter-spacing: -0.01em;
            margin-bottom: 1.5rem;
            font-family: 'Space Grotesk', sans-serif;
        }

        .cursor {
            display: inline-block;
            width: 3px;
            height: 1em;
            background: var(--primary-red);
            margin-left: 2px;
            vertical-align: text-bottom;
            animation: blink 1.1s step-end infinite;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.2rem; }
            .hero-title-sub { font-size: 1.3rem; }
        }

        .hero-desc {
            font-size: 1.05rem;
            color: var(--text-secondary);
            margin-bottom: 2.2rem;
            line-height: 1.8;
            max-width: 58ch;
        }

        /* How It Works Strip */
        .how-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            margin-bottom: 1rem;
            width: 100%;
            border: 1px solid var(--red-border);
            border-radius: 14px;
            overflow: hidden;
            background: rgba(255, 42, 47, 0.03);
            text-align: left;
        }

        .how-step {
            padding: 1.1rem 1.2rem;
            border-right: 1px solid var(--red-border);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .how-step:last-child { border-right: none; }

        .how-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid var(--primary-red);
            background: var(--red-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--primary-red);
            flex-shrink: 0;
            font-family: 'JetBrains Mono', monospace;
        }

        .how-text strong {
            display: block;
            font-size: 0.85rem;
            color: #fff;
            margin-bottom: 0.2rem;
        }

        .how-text span {
            font-size: 0.76rem;
            color: var(--text-secondary);
        }

        @media (max-width: 768px) {
            .how-strip { grid-template-columns: repeat(2, 1fr); }
            .how-step:nth-child(2) { border-right: none; }
            .how-step:nth-child(1), .how-step:nth-child(2) { border-bottom: 1px solid var(--red-border); }
        }

        @media (max-width: 520px) {
            .how-strip { grid-template-columns: 1fr; }
            .how-step { border-right: none; border-bottom: 1px solid var(--red-border); }
            .how-step:last-child { border-bottom: none; }
        }

        /* Auth Card */
        .auth-card {
            width: 100%;
            max-width: 520px;
            background: var(--card-bg);
            border: 1px solid var(--red-border);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: var(--shadow-glow);
            position: relative;
            overflow: hidden;
            animation: fadeUp 0.6s ease both;
            animation-delay: 0.15s;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-red), var(--accent-red));
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: var(--primary-red);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            letter-spacing: 0.02em;
            box-shadow: 0 4px 20px rgba(255, 42, 47, 0.35);
        }

        .btn-submit:hover {
            background: var(--primary-red-hover);
            box-shadow: 0 6px 30px rgba(255, 42, 47, 0.55);
            transform: translateY(-2px);
        }

        /* Sections on Landing Page */
        .info-section {
            width: 100%;
            background: rgba(18, 18, 20, 0.6);
            border: 1px solid var(--red-border);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: left;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            transition: border-color 0.3s ease;
        }

        .info-section:hover {
            border-color: rgba(255, 42, 47, 0.4);
        }

        .info-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary-red);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 0.5rem;
        }

        .info-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 1rem;
            letter-spacing: -0.01em;
        }

        .info-desc {
            font-size: 1rem;
            color: var(--text-secondary);
            line-height: 1.75;
            margin-bottom: 1rem;
        }

        .info-desc:last-child {
            margin-bottom: 0;
        }

        .doc-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            margin-top: 1rem;
            padding: 0.8rem 1.4rem;
            background: rgba(255, 42, 47, 0.08);
            border: 1px solid var(--red-border);
            border-radius: 10px;
            color: #ffffff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .doc-link-btn:hover {
            background: var(--primary-red);
            border-color: var(--primary-red);
            box-shadow: 0 4px 20px rgba(255, 42, 47, 0.4);
            transform: translateY(-2px);
        }

        .learn-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .learn-grid {
                grid-template-columns: 1fr;
            }
        }

        .learn-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .learn-item:hover {
            border-color: rgba(255, 42, 47, 0.3);
            background: rgba(255, 42, 47, 0.04);
        }

        .learn-item i {
            font-size: 1.25rem;
            color: var(--primary-red);
            margin-top: 0.2rem;
            flex-shrink: 0;
        }

        .learn-item strong {
            display: block;
            font-size: 0.95rem;
            color: #ffffff;
            margin-bottom: 0.35rem;
            font-family: 'Space Grotesk', sans-serif;
        }

        .learn-item p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin: 0;
        }

        .btn-enter-animated {
            padding: 1.1rem 2.75rem;
            background: linear-gradient(135deg, var(--primary-red), #b30005);
            color: #ffffff !important;
            border: 1px solid rgba(255, 42, 47, 0.6);
            border-radius: 50px;
            font-size: 1.05rem;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.85rem;
            letter-spacing: 0.04em;
            box-shadow: 0 4px 25px rgba(255, 42, 47, 0.4);
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .btn-enter-animated:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 35px rgba(255, 42, 47, 0.65);
            background: linear-gradient(135deg, #ff4045, var(--primary-red));
            color: #ffffff !important;
        }

        .btn-enter-animated .btn-arrow {
            transition: transform 0.3s ease;
        }

        .btn-enter-animated:hover .btn-arrow {
            transform: translateX(5px);
        }

        /* Access Status UI */
        .payment-badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.9rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-family: 'JetBrains Mono', monospace;
        }

        .status-pending {
            background: rgba(234, 179, 8, 0.15);
            color: #facc15;
            border: 1px solid rgba(234, 179, 8, 0.4);
            animation: pulseGlow 2s infinite;
        }

        .status-accepted {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.4);
        }

        .status-declined {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.4);
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 rgba(234, 179, 8, 0); }
            50% { box-shadow: 0 0 15px rgba(234, 179, 8, 0.3); }
        }
    </style>
</head>
<body>
<div class="page-content">

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <img src="images/eaglone/p-eaglone.png" alt="Secure Worldz Academy Logo" class="logo-img" loading="lazy">
                <span class="logo-text">
                    Secure<span class="logo-accent"> Worldz Academy</span>
                </span>
            </a>

            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li><a href="about-home.php">About</a></li>
                <li><a href="contact-home.php">Contact</a></li>
                <li><a href="swa-lab.php">Lab</a></li>
                <li><a href="owasp-2026-landing.php" class="active">OWASP 2026 Lab</a></li>
            </ul>

            <?php if ($userId): ?>
                <a href="logout.php" class="nav-cta" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.4);">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            <?php else: ?>
                <a href="login.php?redirect=owasp-2026-landing.php" class="nav-cta">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="main-container">
        <!-- Hero Details -->
        <div class="hero-left">
            <div class="hero-badge">
                <i class="fas fa-shield-halved"></i> OWASP 2026 · AI Security Lab
            </div>
            <h1 class="hero-title">OWASP 2026 Challenges<span class="cursor"></span></h1>
            <span class="hero-title-sub">AI Security Training Lab</span>
            <p class="hero-desc">
               A comprehensive set of ten hands-on labs spanning every OWASP 2026 Agentic AI risk category. Build practical expertise in prompt injection, tool abuse, privilege escalation, and remote code execution through interactive, browser-based exploitation exercises.
            </p>
        </div>

        <!-- SECTION 1: OWASP 2026 -->
        <section class="info-section">
            <div class="info-section-inner">
                <div class="info-label">OWASP 2026</div>
                <h2 class="info-title">The Official Standard</h2>
                <p class="info-desc">
                    OWASP (Open Worldwide Application Security Project) releases the definitive risk framework for AI security each year.
                    The 2026 edition focuses on Agentic AI systems, covering autonomous agents, multi-agent orchestration, and AI supply chain risks.
                </p>
                <p class="info-desc">
                    Our lab features ten purpose-built vulnerable scenarios matching every entry in the OWASP 2026 Top 10 — from ASI-01 Prompt Injection through ASI-10 Rogue Agents.
                </p>
                <a href="owasp-2026-lab-guide.php" class="doc-link-btn">
                    <i class="fas fa-book-open"></i>
                    Read Full Lab Guide & Documentation
                </a>
            </div>
        </section>

        <!-- SECTION 2: ARCHITECTURE -->
        <section class="info-section">
            <div class="info-section-inner">
                <div class="info-label">Lab Architecture</div>
                <h2 class="info-title">How the Labs Work</h2>
                <p class="info-desc">
                    Each challenge runs in an isolated simulated environment with its own mock AI backend, tool dispatcher, memory layer, and flag store.
                </p>
                <p class="info-desc">
                    Interact directly with simulated agents, observe reasoning traces in real-time, craft exploits, and retrieve secret flags upon successful breach.
                </p>
            </div>
        </section>

        <!-- SECTION 3: WHAT YOU'LL LEARN -->
        <section class="info-section">
            <div class="info-section-inner">
                <div class="info-label">What You'll Learn</div>
                <h2 class="info-title">Skills You Take With You</h2>
                <div class="learn-grid">
                    <div class="learn-item">
                        <i class="fas fa-crosshairs"></i>
                        <div>
                            <strong>Prompt Injection Techniques</strong>
                            <p>Craft payloads that redirect an AI agent away from its intended objective by overriding system instructions through user input.</p>
                        </div>
                    </div>
                    <div class="learn-item">
                        <i class="fas fa-wrench"></i>
                        <div>
                            <strong>Tool and API Exploitation</strong>
                            <p>Trigger unintended tool calls at scale, abuse overpermissioned API integrations, and understand the blast radius of unbounded tool access.</p>
                        </div>
                    </div>
                    <div class="learn-item">
                        <i class="fas fa-user-shield"></i>
                        <div>
                            <strong>Privilege and Identity Abuse</strong>
                            <p>Exploit agents that inherit credentials from their orchestrators, escalate from low-privilege contexts, and traverse IAM boundaries.</p>
                        </div>
                    </div>
                    <div class="learn-item">
                        <i class="fas fa-terminal"></i>
                        <div>
                            <strong>Remote Code Execution via AI</strong>
                            <p>Identify unsafe evaluation paths in AI code assistants and escape the application boundary to gain shell access in a controlled lab environment.</p>
                        </div>
                    </div>
                    <div class="learn-item">
                        <i class="fas fa-brain"></i>
                        <div>
                            <strong>Memory and Context Poisoning</strong>
                            <p>Inject malicious data into RAG databases and long-term memory stores to manipulate future agent behavior across sessions.</p>
                        </div>
                    </div>
                    <div class="learn-item">
                        <i class="fas fa-flag"></i>
                        <div>
                            <strong>Capture the Flag Methodology</strong>
                            <p>Apply a structured exploit workflow from reconnaissance through payload delivery and condition trigger to flag capture, just as in professional red team engagements.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 4: LAB ACCESS STATUS -->
        <section id="unlock-lab" class="info-section" style="text-align: center;">
            <?php if ($hasAccess): ?>
                <div style="text-align: center; padding: 1rem 0;">
                    <div class="payment-badge-status status-accepted" style="margin-bottom: 1.25rem;">
                        <i class="fas fa-circle-check"></i> Access Granted
                    </div>
                    <h2 class="info-title" style="margin-bottom: 0.75rem;">You Have Full Lab Access!</h2>
                    <p class="info-desc" style="max-width: 600px; margin: 0 auto 2rem;">
                        Your OWASP 2026 subscription is active.
                        <?php if (!empty($accessInfo['expires_at'])): ?>
                            It expires on <strong><?= htmlspecialchars(date('M d, Y h:i A', strtotime($accessInfo['expires_at']))); ?></strong>. Renew before then to keep your access uninterrupted.
                        <?php endif; ?>
                    </p>
                    <a href="owasp-2026-lab.php" class="btn-enter-animated" style="text-decoration: none;">
                        <i class="fas fa-flask-vial"></i>
                        <span>Launch OWASP 2026 Lab</span>
                        <i class="fas fa-arrow-right btn-arrow"></i>
                    </a>
                </div>

            <?php elseif (!$userId): ?>
                <div style="text-align: center; padding: 1rem 0;">
                    <div class="payment-badge-status status-pending" style="margin-bottom: 1.25rem;">
                        <i class="fas fa-lock"></i> Student Authentication Required
                    </div>
                    <h2 class="info-title" style="margin-bottom: 0.75rem;">Unlock OWASP 2026 Lab Access</h2>
                    <p class="info-desc" style="max-width: 600px; margin: 0 auto 2rem;">
                        Log in with your Secure Worldz Academy student account to subscribe, submit payment verification, or enter your unlocked labs.
                    </p>
                    <a href="login.php?redirect=owasp-2026-landing.php" class="btn-enter-animated" style="text-decoration: none;">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Log In to Proceed</span>
                        <i class="fas fa-arrow-right btn-arrow"></i>
                    </a>
                </div>

            <?php elseif ($verification && $verification['status'] === 'pending'): ?>
                <div style="text-align: center; padding: 1rem 0;">
                    <div class="payment-badge-status status-pending" style="margin-bottom: 1.25rem;">
                        <i class="fas fa-clock"></i> Pending Admin Review
                    </div>
                    <h2 class="info-title" style="margin-bottom: 0.75rem;">Payment Verification In Progress</h2>
                    <p class="info-desc" style="max-width: 600px; margin: 0 auto 2rem;">
                        Your payment proof was received on <strong><?= htmlspecialchars(date('M d, Y h:i A', strtotime($verification['created_at']))); ?></strong> via <strong><?= htmlspecialchars($verification['payment_method']); ?></strong>. An admin will review and approve your access shortly.
                    </p>
                    <a href="owasp-2026-payment.php" class="btn-enter-animated" style="text-decoration: none;">
                        <i class="fas fa-arrow-right btn-arrow"></i>
                        <span>View Submission Status</span>
                    </a>
                </div>

            <?php else: ?>
                <div style="text-align: center; padding: 1rem 0;">
                    <div class="payment-badge-status status-declined" style="margin-bottom: 1.25rem;">
                        <i class="fas fa-credit-card"></i> Lab Not Unlocked
                    </div>
                    <h2 class="info-title" style="margin-bottom: 0.75rem;">Subscribe to Unlock the Lab</h2>
                    <p class="info-desc" style="max-width: 600px; margin: 0 auto 2rem;">
                        <?php if ($verification && $verification['status'] === 'declined'): ?>
                            Your previous submission was declined<?= !empty($verification['decline_reason']) ? ': ' . htmlspecialchars($verification['decline_reason']) : ''; ?>.
                        <?php endif; ?>
                        Subscribe for <span style="color: #ffffff; font-weight: 700;"><?= htmlspecialchars($paymentConfig['lab_price']); ?></span> to get <?= htmlspecialchars($paymentConfig['billing_period']); ?> of access to all ten OWASP 2026 AI Security labs.
                    </p>
                    <a href="owasp-2026-payment.php" class="btn-enter-animated" style="text-decoration: none;">
                        <i class="fas fa-credit-card"></i>
                        <span>Unlock Lab Access — <?= htmlspecialchars($paymentConfig['lab_price']); ?></span>
                        <i class="fas fa-arrow-right btn-arrow"></i>
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p style="margin-top:1.25rem;font-family:'JetBrains Mono',monospace;font-size:0.72rem;color:var(--text-muted);">© 2026 Secure Worldz Academy &nbsp;·&nbsp; OWASP 2026 AI Security Lab</p>
    </footer>
</div>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        if (navbar) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }, { passive: true });
        }

        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');

        if (menuToggle && navLinks) {
            menuToggle.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                menuToggle.classList.toggle('active');
                
                const spans = menuToggle.querySelectorAll('span');
                if (spans.length >= 3) {
                    if (navLinks.classList.contains('active')) {
                        spans[0].style.transform = 'rotate(45deg) translateY(10px)';
                        spans[1].style.opacity = '0';
                        spans[2].style.transform = 'rotate(-45deg) translateY(-10px)';
                    } else {
                        spans[0].style.transform = 'none';
                        spans[1].style.opacity = '1';
                        spans[2].style.transform = 'none';
                    }
                }
            });

            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', () => {
                    navLinks.classList.remove('active');
                    menuToggle.classList.remove('active');
                    const spans = menuToggle.querySelectorAll('span');
                    if (spans.length >= 3) {
                        spans[0].style.transform = 'none';
                        spans[1].style.opacity = '1';
                        spans[2].style.transform = 'none';
                    }
                });
            });
        }
    </script>
</body>
</html>
