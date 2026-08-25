<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'api/dbconfig.php';
$paymentConfig = require 'api/payment_config.php';

$db = new DBconfig();
$userId = $_SESSION['id'] ?? null;

if (!$userId) {
    header('Location: login.php?redirect=owasp-2026-payment.php');
    exit;
}

$hasAccess = false;
$accessInfo = null;
$verification = null;
$submissionError = '';
$submissionSuccess = '';

$hasAccess = $db->hasOwasp2026Access($userId);
$accessInfo = $db->getOwasp2026AccessInfo($userId);
$verification = $db->getPaymentVerificationByUser($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'submit_payment_proof') {
    error_log("OWASP 2026 payment submission started for user_id: " . var_export($userId, true));
    $paymentMethod = trim($_POST['payment_method'] ?? '');
    if (empty($paymentMethod) || !isset($paymentConfig['methods'][$paymentMethod])) {
        error_log("OWASP 2026 payment submission failed: invalid payment method '" . var_export($paymentMethod, true) . "'");
        $submissionError = 'Please select a valid payment method.';
    } elseif (!isset($_FILES['screenshot']) || $_FILES['screenshot']['error'] !== UPLOAD_ERR_OK) {
        $uploadError = $_FILES['screenshot']['error'] ?? 'no_file';
        error_log("OWASP 2026 payment submission failed: upload error code " . var_export($uploadError, true));
        $submissionError = 'Please choose a payment screenshot image to upload.';
    } else {
        $file = $_FILES['screenshot'];
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            error_log("OWASP 2026 payment submission failed: file size " . $file['size'] . " exceeds limit");
            $submissionError = 'File size exceeds 5MB limit. Please upload a smaller image.';
        } else {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($ext, $allowedExts) || !in_array($mime, $allowedMimes)) {
                error_log("OWASP 2026 payment submission failed: invalid file type ext=" . var_export($ext, true) . " mime=" . var_export($mime, true));
                $submissionError = 'Invalid image file type. Please upload a JPG, JPEG, PNG, or WEBP image.';
            } else {
                $uploadDir = __DIR__ . '/uploads/payment_proofs/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $safeFilename = 'proof_' . preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$userId) . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $targetPath = $uploadDir . $safeFilename;
                $relPath = 'uploads/payment_proofs/' . $safeFilename;

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    error_log("OWASP 2026 payment file uploaded successfully: " . $relPath);
                    $methodName = $paymentConfig['methods'][$paymentMethod]['name'];
                    $saved = $db->submitPaymentVerification($userId, $methodName, $relPath);
                    if ($saved) {
                        error_log("OWASP 2026 payment verification saved successfully for user_id: " . var_export($userId, true));
                        $submissionSuccess = 'Payment proof submitted successfully! Your submission is now under admin review.';
                        $verification = $db->getPaymentVerificationByUser($userId);
                    } else {
                        error_log("OWASP 2026 payment submission failed: database insert returned false for user_id: " . var_export($userId, true));
                        $submissionError = 'Failed to save verification record in database. Please try again.';
                    }
                } else {
                    error_log("OWASP 2026 payment submission failed: move_uploaded_file failed for user_id: " . var_export($userId, true) . " target=" . $targetPath);
                    $submissionError = 'Failed to save uploaded file on server. Please try again.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlock OWASP 2026 AI Security Lab | Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <meta name="description" content="Pay and unlock the OWASP 2026 Top 10 AI Security Vulnerability Lab at Secure Worldz Academy.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary-red: #ff2a2f;
            --primary-red-hover: #e6191e;
            --accent-red: #ff4d4f;
            --red-glow: rgba(255, 42, 47, 0.35);
            --red-subtle: rgba(255, 42, 47, 0.12);
            --red-border: rgba(255, 42, 47, 0.28);
            --dark-bg: #030406;
            --card-bg: #080b10;
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #ffffff;
            --text-secondary: #a3a3a3;
            --text-muted: #666666;
            --shadow-glow: 0 0 35px rgba(255, 42, 47, 0.25);
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0; }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 rgba(234, 179, 8, 0); }
            50% { box-shadow: 0 0 15px rgba(234, 179, 8, 0.3); }
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

        @keyframes scanMove {
            0%   { transform: translateY(0); }
            100% { transform: translateY(4px); }
        }

        .page-content { position: relative; z-index: 1; }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(3, 4, 6, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            background: rgba(0, 0, 0, 0.97);
            border-bottom: 1px solid var(--red-border);
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

        .logo:hover { transform: translateY(-2px); }

        .logo-img { height: 35px; width: auto; object-fit: contain; }

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
        .nav-links a.active { color: #ffffff; }

        .nav-links a:hover::before,
        .nav-links a.active::before { width: 100%; }

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
            .menu-toggle { display: flex; }
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
            .nav-links.active { right: 0; }
            .nav-links .nav-cta { display: flex !important; margin-top: 1rem; justify-content: center; }
            .nav-container > .nav-cta { display: none !important; }
        }

        body, h1, h2, h3, h4, h5, h6, p, span, div, li, a {
            font-weight: 700 !important;
        }

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
            .main-container { gap: 2.5rem; padding: 2rem 1.25rem 4rem; }
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
            font-size: 3rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #ffffff 0%, var(--primary-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
            font-family: 'Space Grotesk', sans-serif;
        }

        .hero-desc {
            font-size: 1.05rem;
            color: var(--text-secondary);
            margin-bottom: 2.2rem;
            line-height: 1.8;
            max-width: 58ch;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.1rem; }
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

        .btn-enter-animated .btn-arrow { transition: transform 0.3s ease; }
        .btn-enter-animated:hover .btn-arrow { transform: translateX(5px); }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.8rem 1.5rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 50px;
            color: var(--text-secondary);
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-ghost:hover {
            color: #fff;
            border-color: rgba(255, 42, 47, 0.45);
            background: rgba(255, 42, 47, 0.08);
        }

        /* Payment & Verification UI */
        .payment-section {
            width: 100%;
            background: linear-gradient(145deg, #090d14 0%, #04060a 100%);
            border: 1px solid var(--red-border);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6), 0 0 30px rgba(255, 42, 47, 0.1);
            position: relative;
            overflow: hidden;
            text-align: left;
            animation: fadeUp 0.6s ease both;
            animation-delay: 0.15s;
        }

        .payment-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary-red), transparent);
        }

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

        .payment-method-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .method-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .method-card:hover {
            border-color: rgba(255, 42, 47, 0.4);
            background: rgba(255, 42, 47, 0.04);
            transform: translateY(-2px);
        }

        .method-card.active {
            border-color: var(--primary-red);
            background: rgba(255, 42, 47, 0.1);
            box-shadow: 0 0 20px rgba(255, 42, 47, 0.2);
        }

        .method-card i { font-size: 1.4rem; color: var(--primary-red); }
        .method-card span { font-weight: 600; font-size: 0.9rem; color: #ffffff; }

        .payment-details-box {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            font-size: 0.88rem;
            gap: 1rem;
        }

        .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
        .detail-label { color: var(--text-secondary); }
        .detail-value { font-family: 'JetBrains Mono', monospace; font-weight: 600; color: #ffffff; word-break: break-all; }

        .upload-dropzone {
            border: 2px dashed rgba(255, 42, 47, 0.35);
            background: rgba(255, 42, 47, 0.02);
            border-radius: 14px;
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            margin-bottom: 1.5rem;
        }

        .upload-dropzone:hover {
            border-color: var(--primary-red);
            background: rgba(255, 42, 47, 0.06);
        }

        .upload-dropzone input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .custom-alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .custom-alert.error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }

        .custom-alert.success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #86efac;
        }

        .custom-alert.warning {
            background: rgba(234, 179, 8, 0.12);
            border: 1px solid rgba(234, 179, 8, 0.4);
            color: #fde047;
        }

        .section-step {
            font-size: 0.85rem;
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
            display: block;
        }

        .plan-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .plan-tile {
            flex: 1 1 200px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1rem 1.25rem;
        }

        .plan-tile .tile-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.25rem;
        }

        .plan-tile .tile-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.3rem;
            color: #ffffff;
        }

        .plan-tile .tile-sub {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .footer {
            text-align: center;
            padding: 2rem 1.5rem 2.5rem;
        }
    </style>
</head>
<body>
<div class="page-content">

    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <img src="images/eaglone/p-eaglone.png" alt="Secure Worldz Academy Logo" class="logo-img" loading="lazy">
                <span>Secure<span class="logo-accent"> Worldz Academy</span></span>
            </a>

            <div class="menu-toggle" id="menuToggle">
                <span></span><span></span><span></span>
            </div>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li><a href="about-home.php">About</a></li>
                <li><a href="contact-home.php">Contact</a></li>
                <li><a href="swa-lab.php">Lab</a></li>
                <li><a href="owasp-2026-landing.php" class="active">OWASP 2026 Lab</a></li>
            </ul>

            <a href="logout.php" class="nav-cta" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.4);">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <main class="main-container">
        <div class="hero-left">
            <div class="hero-badge">
                <i class="fas fa-shield-halved"></i> OWASP 2026 · Lab Access Portal
            </div>
            <h1 class="hero-title">Unlock Your Lab Access</h1>
            <p class="hero-desc">
                Subscribe for <?= htmlspecialchars($paymentConfig['lab_price']); ?> to unlock all ten OWASP 2026 AI Security labs for <?= htmlspecialchars($paymentConfig['billing_period']); ?>. Complete payment via any option below and upload your confirmation screenshot.
            </p>
        </div>

        <section class="payment-section" id="unlock-lab">
            <?php if ($submissionError): ?>
                <div class="custom-alert error">
                    <i class="fas fa-circle-exclamation"></i>
                    <span><?= htmlspecialchars($submissionError); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($submissionSuccess): ?>
                <div class="custom-alert success">
                    <i class="fas fa-circle-check"></i>
                    <span><?= htmlspecialchars($submissionSuccess); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($hasAccess): ?>
                <!-- ACCESS GRANTED STATE -->
                <div style="text-align: center; padding: 2rem 1rem;">
                    <div class="payment-badge-status status-accepted" style="margin-bottom: 1.25rem;">
                        <i class="fas fa-circle-check"></i> Access Granted
                    </div>
                    <h2 class="info-title" style="margin-bottom: 0.75rem;">Your Lab Subscription Is Active</h2>
                    <p class="info-desc" style="max-width: 600px; margin: 0 auto 2rem;">
                        Your OWASP 2026 subscription is live.
                        <?php if (!empty($accessInfo['expires_at'])): ?>
                            It expires on <strong><?= htmlspecialchars(date('M d, Y h:i A', strtotime($accessInfo['expires_at']))); ?></strong>.
                        <?php endif; ?>
                    </p>
                    <a href="owasp-2026-lab.php" class="btn-enter-animated" style="text-decoration: none;">
                        <i class="fas fa-flask-vial"></i>
                        <span>Launch OWASP 2026 Lab</span>
                        <i class="fas fa-arrow-right btn-arrow"></i>
                    </a>
                    <div style="margin-top: 1.25rem;">
                        <a href="owasp-2026-landing.php" class="btn-ghost">
                            <i class="fas fa-arrow-left"></i> Back to Overview
                        </a>
                    </div>
                </div>

            <?php elseif ($verification && $verification['status'] === 'pending'): ?>
                <!-- PENDING ADMIN REVIEW STATE -->
                <div style="text-align: center; padding: 2rem 1rem;">
                    <div class="payment-badge-status status-pending" style="margin-bottom: 1.25rem;">
                        <i class="fas fa-clock"></i> Pending Admin Review
                    </div>
                    <h2 class="info-title" style="margin-bottom: 0.75rem;">Payment Verification In Progress</h2>
                    <p class="info-desc" style="max-width: 600px; margin: 0 auto 1.5rem;">
                        Your payment proof was received on <strong><?= htmlspecialchars(date('M d, Y h:i A', strtotime($verification['created_at']))); ?></strong> via <strong><?= htmlspecialchars($verification['payment_method']); ?></strong>.
                    </p>

                    <div style="max-width: 450px; margin: 0 auto 1.5rem; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 1.25rem;">
                        <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-family: 'JetBrains Mono', monospace;">Submitted Screenshot:</div>
                        <a href="<?= htmlspecialchars($verification['screenshot_path']); ?>" target="_blank" style="display: inline-block;">
                            <img src="<?= htmlspecialchars($verification['screenshot_path']); ?>" alt="Payment Proof" style="max-height: 140px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.15);">
                        </a>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.4rem;">(Click to view full image)</div>
                    </div>

                    <div class="custom-alert warning" style="max-width: 600px; margin: 0 auto;">
                        <i class="fas fa-shield-halved"></i>
                        <span>An admin will review and approve your access shortly. Once approved, the lab will unlock automatically.</span>
                    </div>
                    <div style="margin-top: 1.25rem;">
                        <a href="owasp-2026-landing.php" class="btn-ghost">
                            <i class="fas fa-arrow-left"></i> Back to Overview
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- PAYMENT PROOF SUBMISSION FORM -->
                <div>
                    <?php if ($verification && $verification['status'] === 'declined'): ?>
                        <div class="custom-alert error" style="margin-bottom: 2rem;">
                            <i class="fas fa-circle-xmark" style="font-size: 1.4rem;"></i>
                            <div>
                                <strong><?= (strpos((string)($verification['decline_reason'] ?? ''), 'expired') !== false) ? 'Your Access Has Expired' : 'Previous Payment Proof Declined'; ?></strong>
                                <?php if (!empty($verification['decline_reason'])): ?>
                                    <p style="margin-top: 0.25rem; font-size: 0.85rem;">Reason: <?= htmlspecialchars($verification['decline_reason']); ?></p>
                                <?php endif; ?>
                                <p style="margin-top: 0.25rem; font-size: 0.85rem;">Please renew below by submitting a fresh payment screenshot showing your transaction ID.</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="info-label" style="margin-bottom: 0.5rem;">Access Gating · OWASP 2026</div>
                    <h2 class="info-title" style="margin-bottom: 0.5rem;">Subscribe to Unlock the Lab</h2>
                    <p class="info-desc" style="max-width: 650px;">
                        Monthly subscription: <span style="color: #ffffff; font-weight: 700;"><?= htmlspecialchars($paymentConfig['lab_price']); ?></span>. Your access is activated for <?= htmlspecialchars($paymentConfig['billing_period']); ?> after admin verification.
                    </p>

                    <div class="plan-summary">
                        <div class="plan-tile">
                            <div class="tile-label">Subscription</div>
                            <div class="tile-value"><?= htmlspecialchars($paymentConfig['lab_price']); ?></div>
                            <div class="tile-sub">Billed monthly · <?= htmlspecialchars($paymentConfig['billing_period']); ?> access</div>
                        </div>
                        <div class="plan-tile">
                            <div class="tile-label">Labs Included</div>
                            <div class="tile-value">10 Labs</div>
                            <div class="tile-sub">ASI-01 to ASI-10, all difficulty levels</div>
                        </div>
                        <div class="plan-tile">
                            <div class="tile-label">Access Duration</div>
                            <div class="tile-value">30 Days</div>
                            <div class="tile-sub">Renews automatically on re-payment</div>
                        </div>
                    </div>

                    <span class="section-step">1. Select Payment Method:</span>
                    <div class="payment-method-selector">
                        <?php $firstMethod = true; foreach ($paymentConfig['methods'] as $key => $method): ?>
                            <div class="method-card <?= $firstMethod ? 'active' : ''; ?>" onclick="selectMethod('<?= htmlspecialchars($key); ?>', this)">
                                <i class="fas <?= htmlspecialchars($method['icon']); ?>"></i>
                                <span><?= htmlspecialchars($method['name']); ?></span>
                            </div>
                        <?php $firstMethod = false; endforeach; ?>
                    </div>

                    <span class="section-step">2. Payment Instructions &amp; Destination:</span>
                    <?php $firstDetail = true; foreach ($paymentConfig['methods'] as $key => $method): ?>
                        <div id="details-<?= htmlspecialchars($key); ?>" class="payment-details-box" style="<?= $firstDetail ? '' : 'display: none;'; ?>">
                            <?php foreach ($method['details'] as $label => $val): ?>
                                <div class="detail-row">
                                    <span class="detail-label"><?= htmlspecialchars($label); ?>:</span>
                                    <span class="detail-value"><?= htmlspecialchars($val); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php $firstDetail = false; endforeach; ?>

                    <form action="owasp-2026-payment.php#unlock-lab" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="submit_payment_proof">
                        <input type="hidden" name="payment_method" id="selected_payment_method" value="<?= htmlspecialchars(array_key_first($paymentConfig['methods'])); ?>">

                        <span class="section-step">3. Upload Payment Screenshot:</span>
                        <div class="upload-dropzone" onclick="document.getElementById('screenshot_input').click()">
                            <input type="file" id="screenshot_input" name="screenshot" accept="image/png, image/jpeg, image/jpg, image/webp" required onchange="handleFileSelect(event)">
                            <i class="fas fa-cloud-arrow-up" style="font-size: 2.5rem; color: var(--primary-red); margin-bottom: 0.75rem; display: block;"></i>
                            <div id="upload_prompt_text" style="font-size: 0.95rem; font-weight: 600; color: #ffffff; margin-bottom: 0.25rem;">
                                Click to select payment screenshot (JPG, PNG, WEBP - Max 5MB)
                            </div>
                            <div id="file_name_display" style="font-size: 0.8rem; color: var(--text-secondary); font-family: 'JetBrains Mono', monospace;"></div>
                        </div>

                        <div style="text-align: center;">
                            <button type="submit" class="btn-enter-animated" style="border: none;">
                                <i class="fas fa-paper-plane"></i>
                                <span>Submit Payment Proof for Verification</span>
                                <i class="fas fa-arrow-right btn-arrow"></i>
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        <p style="font-family:'JetBrains Mono',monospace;font-size:0.72rem;color:var(--text-muted);">© 2026 Secure Worldz Academy &nbsp;·&nbsp; OWASP 2026 AI Security Lab</p>
    </footer>
</div>

<script>
    function selectMethod(key, element) {
        document.querySelectorAll('.method-card').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        document.getElementById('selected_payment_method').value = key;

        document.querySelectorAll('.payment-details-box').forEach(el => el.style.display = 'none');
        const detailsBox = document.getElementById('details-' + key);
        if (detailsBox) {
            detailsBox.style.display = 'block';
        }
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        const nameDisplay = document.getElementById('file_name_display');
        const promptText = document.getElementById('upload_prompt_text');
        if (file) {
            promptText.textContent = 'Selected: ' + file.name;
            nameDisplay.textContent = 'Size: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB';
        }
    }

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
