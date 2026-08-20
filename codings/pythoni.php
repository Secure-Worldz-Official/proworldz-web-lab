<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}
try {
    if (!class_exists('DBconfig')) {
        require_once __DIR__ . '/../../api/dbconfig.php';
    }
    $check_db = new DBconfig();
    $db_con = $check_db->check_con();
    if (!$db_con || $db_con === "connection error") {
        $userAccess = 'true';
    } else {
        $userAccess = $check_db->getFieldbyId($_SESSION['id'], 'access');
    }
} catch (Throwable $e) {
    $userAccess = 'true';
}
if ($userAccess != 'true') {
    echo "Access Restricted";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Python Interpreter - ProWorldz</title>
    <link rel="icon" type="image/png" href="../image.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            border-color: rgba(229, 231, 235, 0.3);
            outline-color: rgba(156, 163, 175, 0.5);
            overscroll-behavior: none;
        }

        body {
            font-family: 'Roboto Mono', monospace;
            background-color: #000000;
            color: #ffffff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: auto;
        }

        @font-face {
            font-family: "Rebels";
            src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --radius: 0.625rem;
            --background: #000000;
            --foreground: #ffffff;
            --card: #080808;
            --card-foreground: #ffffff;
            --popover: #080808;
            --popover-foreground: #ffffff;
            --primary: #e5191e;
            --primary-foreground: #ffffff;
            --secondary: #080808;
            --secondary-foreground: #ffffff;
            --muted: #080808;
            --muted-foreground: #a0a0a0;
            --accent: rgba(248, 250, 252, 0.05);
            --accent-foreground: #ffffff;
            --border: rgba(139, 12, 16, 0.1);
            --input: rgba(139, 12, 16, 0.15);
            --ring: rgba(148, 163, 184, 0.5);
            --success: #ff2a2f;
            --destructive: #e5191e;
            --warning: #8b0c10;
            --sidebar: #080808;
            --sidebar-foreground: #ffffff;
            --sidebar-primary: #e5191e;
            --sidebar-primary-foreground: #ffffff;
            --sidebar-accent: rgba(248, 250, 252, 0.05);
            --sidebar-accent-foreground: #ffffff;
            --sidebar-border: rgba(139, 12, 16, 0.1);
            --sidebar-ring: rgba(148, 163, 184, 0.5);
            --gap: 1.5rem;
            --sides: 1.5rem;
        }

        .desktop-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--gap);
            min-height: 100vh;
            padding: var(--sides);
            background-color: var(--background);
        }

        .desktop-main {
            display: flex;
            flex-direction: column;
            gap: var(--gap);
        }

        .font-display {
            font-family: 'Rebels', 'Roboto Mono', monospace;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .flex {
            display: flex;
        }

        .grid {
            display: grid;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .w-full {
            width: 100%;
        }

        .h-full {
            height: 100%;
        }

        .rounded-lg {
            border-radius: var(--radius);
        }

        .rounded-md {
            border-radius: calc(var(--radius) - 2px);
        }

        .border {
            border-width: 1px;
        }

        .border-2 {
            border-width: 2px;
        }

        .bg-card {
            background-color: var(--card);
        }

        .bg-accent {
            background-color: var(--accent);
        }

        .bg-primary {
            background-color: var(--primary);
        }

        .bg-secondary {
            background-color: var(--secondary);
        }

        .text-foreground {
            color: var(--foreground);
        }

        .text-primary {
            color: var(--primary);
        }

        .text-primary-foreground {
            color: var(--primary-foreground);
        }

        .text-muted-foreground {
            color: var(--muted-foreground);
        }

        .text-success {
            color: var(--success);
        }

        .text-destructive {
            color: var(--destructive);
        }

        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .text-base {
            font-size: 1rem;
            line-height: 1.5rem;
        }

        .text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }

        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }

        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }

        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .transition-colors {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 0.75rem;
        }

        .gap-4 {
            gap: 1rem;
        }

        .gap-6 {
            gap: 1.5rem;
        }

        .gap-8 {
            gap: 2rem;
        }

        .gap-gap {
            gap: var(--gap);
        }

        .p-3 {
            padding: 0.75rem;
        }

        .p-4 {
            padding: 1rem;
        }

        .p-6 {
            padding: 1.5rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .pt-4 {
            padding-top: 1rem;
        }

        .pb-4 {
            padding-bottom: 1rem;
        }

        .pr-4 {
            padding-right: 1rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }

        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }

        .space-y-4 > * + * {
            margin-top: 1rem;
        }

        .flex-1 {
            flex: 1 1 0%;
        }

        .flex-col {
            flex-direction: column;
        }

        .items-center {
            align-items: center;
        }

        .items-start {
            align-items: flex-start;
        }

        .justify-center {
            justify-content: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .size-10 {
            width: 2.5rem;
            height: 2.5rem;
        }

        .size-12 {
            width: 3rem;
            height: 3rem;
        }

        .size-16 {
            width: 4rem;
            height: 4rem;
        }

        .card {
            background-color: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid transparent;
        }

        .badge-destructive {
            background-color: var(--destructive);
            color: white;
            border-color: var(--destructive);
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: calc(var(--radius) - 2px);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid transparent;
            user-select: none;
            white-space: nowrap;
        }

        .button-default {
            background-color: var(--primary);
            color: var(--primary-foreground);
        }

        .button-default:hover:not(:disabled) {
            background-color: color-mix(in srgb, var(--primary) 90%, black);
            transform: translateY(-2px);
        }

        .button-secondary {
            background-color: var(--secondary);
            color: var(--secondary-foreground);
            border-color: var(--border);
        }

        .button-secondary:hover:not(:disabled) {
            background-color: color-mix(in srgb, var(--secondary) 90%, black);
            transform: translateY(-2px);
        }

        .button-ghost {
            background-color: transparent;
            color: currentColor;
        }

        .button-ghost:hover:not(:disabled) {
            background-color: var(--accent);
        }

        .button-lg {
            height: 3rem;
            padding: 0 1.5rem;
            font-size: 1rem;
        }

        .button-xl {
            height: 3.5rem;
            padding: 0 2rem;
            font-size: 1rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes coinSpin {
            0% {
                transform: rotateY(0deg) scale(1);
            }
            50% {
                transform: rotateY(180deg) scale(1.2);
            }
            100% {
                transform: rotateY(360deg) scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slideUp {
            animation: slideUp 0.3s ease-out;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        .animate-coin-spin {
            animation: coinSpin 1s ease-in-out;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--muted);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--muted-foreground);
        }

        .coin-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: linear-gradient(135deg, #8b0c10, #8b0c10);
            color: #000;
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }

        .coin-notification.show {
            transform: translateX(0);
        }

        .coin-notification i {
            font-size: 1.5rem;
            animation: coinSpin 2s infinite;
        }

        .coin-notification .coin-info {
            display: flex;
            flex-direction: column;
        }

        .coin-notification .coin-amount {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .coin-notification .coin-message {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        .coin-timer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999;
            background: rgba(8, 8, 8, 0.9);
            border: 1px solid var(--border);
            padding: 0.75rem 1.25rem;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            backdrop-filter: blur(10px);
        }

        .coin-timer .timer-label {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        .coin-timer .timer-display {
            font-weight: 700;
            color: var(--success);
            font-size: 1.125rem;
        }

        .coin-timer .timer-icon {
            color: var(--warning);
        }

        .interpreter-header {
            background: linear-gradient(135deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
            border-bottom: 1px solid var(--border);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .interpreter-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: radial-gradient(circle at center, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
        }

        .interpreter-hero {
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .interpreter-hero h1 {
            font-size: 2.8rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, #e5191e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        .interpreter-hero p {
            font-size: 1.2rem;
            color: var(--muted-foreground);
            max-width: 600px;
            line-height: 1.6;
        }

        .environment-info {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .env-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: calc(var(--radius) - 2px);
            font-size: 0.875rem;
        }

        .env-item i {
            color: var(--primary);
        }

        .interpreter-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            height: 600px;
            margin-bottom: 2rem;
        }

        @media (max-width: 1024px) {
            .interpreter-grid {
                grid-template-columns: 1fr;
                height: auto;
                min-height: 800px;
            }
        }

        .editor-section,
        .output-section {
            background: linear-gradient(135deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .editor-section::before,
        .output-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, var(--primary), #e5191e);
        }

        .editor-section:hover,
        .output-section:hover {
            transform: translateY(-2px);
            border-color: var(--primary);
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.3);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .section-header h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--foreground);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-header h3 i {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--success);
        }

        .status-dot.loading {
            background: var(--warning);
            animation: pulse 1.5s infinite;
        }

        .status-dot.error {
            background: var(--destructive);
        }

        .code-editor-container {
            flex: 1;
            background: rgba(8, 8, 8, 0.5);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) - 2px);
            overflow: hidden;
            position: relative;
            min-height: 400px;
        }
        .code-editor-container textarea {
            width: 100%;
            height: 100%;
            min-height: 380px;
            background: transparent;
            color: var(--foreground);
            border: none;
            outline: none;
            padding: 1.25rem;
            font-family: 'Roboto Mono', monospace;
            font-size: 15px;
            line-height: 1.6;
            resize: vertical;
            white-space: pre;
            overflow: auto;
        }
            color: var(--foreground);
            background: transparent;
            border: none;
            outline: none;
            resize: none;
            white-space: pre;
            tab-size: 4;
            caret-color: var(--primary);
        }


            color: var(--muted-foreground);
            opacity: 0.6;
        }


            width: 8px;
        }


            background: transparent;
        }


            background: var(--muted);
            border-radius: 4px;
        }


            background: var(--muted-foreground);
        }

        .output-display,

            flex: 1;
            background: rgba(8, 8, 8, 0.5);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) - 2px);
            padding: 1.5rem;
            font-family: 'Roboto Mono', monospace;
            font-size: 15px;
            line-height: 1.6;
            color: var(--foreground);
            white-space: pre-wrap;
            word-break: break-word;
            overflow-y: auto;
        }


        .output-display::-webkit-scrollbar {
            width: 8px;
        }


        .output-display::-webkit-scrollbar-track {
            background: transparent;
        }


        .output-display::-webkit-scrollbar-thumb {
            background: var(--muted);
            border-radius: 4px;
        }


        .output-display::-webkit-scrollbar-thumb:hover {
            background: var(--muted-foreground);
        }

        .output-success {
            color: var(--success);
        }

        .output-error {
            color: var(--destructive);
        }

        .output-info {
            color: var(--primary);
        }

        .controls-section {
            background: linear-gradient(135deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .controls-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, #e5191e, var(--primary));
        }

        .controls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .shortcut-hint {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(99, 102, 241, 0.1);
            border-left: 4px solid var(--primary);
            border-radius: 0 calc(var(--radius) - 2px) calc(var(--radius) - 2px) 0;
            font-size: 0.9rem;
            color: var(--muted-foreground);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .shortcut-hint i {
            color: var(--primary);
        }

        .shortcut-hint kbd {
            background: var(--secondary);
            padding: 0.25rem 0.75rem;
            border-radius: calc(var(--radius) - 4px);
            font-family: 'Roboto Mono', monospace;
            font-size: 0.875rem;
            border: 1px solid var(--border);
            color: var(--foreground);
            margin: 0 0.25rem;
        }

        .editor-stats {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .desktop-container {
                padding: 1rem;
            }

            .interpreter-hero h1 {
                font-size: 2.2rem;
            }

            .interpreter-hero p {
                font-size: 1rem;
            }

            .interpreter-grid {
                height: auto;
                min-height: 700px;
            }

            .editor-section,
            .output-section {
                padding: 1.25rem;
            }

            .controls-grid {
                grid-template-columns: 1fr;
            }

            .environment-info {
                gap: 1rem;
            }

            .env-item {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }

            .coin-notification {
                left: 20px;
                right: 20px;
                width: calc(100% - 40px);
            }
        }
    </style>
</head>
<body>
    
    <div class="desktop-container">
        <div class="desktop-main">
            <div class="card interpreter-header">
                <div class="interpreter-hero">
                    <h1 class="font-display">Python Interpreter</h1>
                    <a href="../langs.php"
                    style="
                        display:inline-flex;
                        align-items:center;
                        gap:0.5rem;
                        padding:0.6rem 1.2rem;
                        margin-top:1rem;
                        background:linear-gradient(135deg,#e5191e,#e5191e);
                        color:#ffffff;
                        text-decoration:none;
                        font-size:0.9rem;
                        font-weight:600;
                        letter-spacing:0.05em;
                        border-radius:0.5rem;
                        border:1px solid rgba(255,255,255,0.15);
                        box-shadow:0 6px 18px rgba(99,102,241,0.35);
                        transition:all 0.25s ease;
                    "
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 30px rgba(99,102,241,0.5)'"
                    onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 6px 18px rgba(99,102,241,0.35)'">
                    ← Back to Lab
                    </a>
                </div>
                <div style="padding:0.5rem;" id="shower-pending-assign-banner">
                    <h4 id="pending-assign-banner"></h4>
                </div>
            </div>

            <div class="interpreter-grid">
                <div class="editor-section">
                    <div class="section-header">
                        <h3><i class="fas fa-code"></i> Code Editor</h3>
                        <div class="status-indicator">
                            <div class="status-dot" id="statusDot"></div>
                            <span id="statusText">Initializing...</span>
                        </div>
                    </div>
                    <div class="code-editor-container">
                        <textarea id="code" placeholder="# Welcome to ProWorldz Python Interpreter...">print("Welcome to ProWorldz Python Interpreter")</textarea>
                    </div>
                   
                    <div class="shortcut-hint">
                        <i class="fas fa-keyboard"></i>
                        <div>
                            <strong>Keyboard Shortcuts:</strong>
                            <kbd>Ctrl</kbd> + <kbd>Enter</kbd> Run Code •
                            <kbd>Tab</kbd> Indent •
                            <kbd>Shift</kbd> + <kbd>Tab</kbd> Outdent
                        </div>
                    </div>
                </div>

                <div class="output-section">
                    <div class="section-header">
                        <h3><i class="fas fa-terminal"></i> Output Console</h3>
                        <div class="status-indicator">
                            <span id="executionTime">Ready</span>
                        </div>
                    </div>
                    <div id="output" class="output-display">Ready to execute code...</div>
                </div>
            </div>

            <div class="controls-section">
                <div class="controls-grid">
                    <button onclick="runPython()" class="button button-lg button-default" id="runBtn">
                        <i class="fas fa-play"></i> Execute Code
                    </button>
                    <button onclick="clearCode()" class="button button-lg button-secondary">
                        <i class="fas fa-eraser"></i> Clear Editor
                    </button>
                    <button onclick="resetInterpreter()" class="button button-lg button-ghost">
                        <i class="fas fa-redo"></i> Reset Environment
                    </button>
                    <button onclick="saveToFile()" class="button button-lg button-ghost">
                        <i class="fas fa-download"></i> Save Script
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let pyodide = null;
        let isInitialized = false;
        let codeTextarea = null;
        let currentTask = null;
        let originalRunPython = null;

        async function initializePyodide() {
            const statusDot = document.getElementById('statusDot');
            const statusText = document.getElementById('statusText');
            const runBtn = document.getElementById('runBtn');
            const output = document.getElementById('output');

            if (!statusDot || !statusText || !runBtn || !output) {
                console.error('Required HTML elements not found');
                return;
            }

            try {
                statusDot.className = 'status-dot loading';
                statusText.textContent = 'Loading Python Runtime...';
                runBtn.disabled = true;
                output.textContent = 'Initializing WebAssembly Python environment...\nLoading Pyodide v0.24.1...';

                pyodide = await loadPyodide();

                isInitialized = true;

                statusDot.className = 'status-dot';
                statusText.textContent = 'Python 3.11 Ready';
                runBtn.disabled = false;
                output.innerHTML = '<span class="output-success">✓ Python 3.11 environment initialized successfully!</span>\n<span class="output-info">Standard library modules loaded.</span>\n<span class="output-info">Write your Python code in the editor and press Execute.</span>';

                checkForTask();

            } catch (error) {
                statusDot.className = 'status-dot error';
                statusText.textContent = 'Initialization Failed';
                output.innerHTML = '<span class="output-error">✗ Failed to initialize Python environment.</span>\n\nError: ' + error.message + '\n\n<span class="output-info">Please check your internet connection and refresh the page.</span>';
            }
        }

        function setupEditor() {
            codeTextarea = document.getElementById('code');

            if (!codeTextarea) return;

            function updateStats() {
                const text = codeTextarea.value;
                const lines = text.split('\n').length;
                const chars = text.length;

                const lineCountEl = document.getElementById('lineCount');
                const charCountEl = document.getElementById('charCount');

                if (lineCountEl) {
                    lineCountEl.textContent = `Lines: ${lines}`;
                }

                if (charCountEl) {
                    charCountEl.textContent = `Characters: ${chars}`;
                }
            }

            codeTextarea.addEventListener('input', updateStats);
            updateStats();

            codeTextarea.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const start = this.selectionStart;
                    const end = this.selectionEnd;

                    this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
                    this.selectionStart = this.selectionEnd = start + 4;
                    updateStats();
                }

                if (e.ctrlKey && e.key === 'Enter') {
                    e.preventDefault();
                    runPython();
                }

                if (e.shiftKey && e.key === 'Tab') {
                    e.preventDefault();
                    const start = this.selectionStart;
                    const before = this.value.substring(0, start);

                    if (before.endsWith('    ')) {
                        this.value = this.value.substring(0, start - 4) + this.value.substring(start);
                        this.selectionStart = this.selectionEnd = start - 4;
                        updateStats();
                    }
                }
            });
        }

        async function runPython() {
            if (!pyodide || !isInitialized) {
                const output = document.getElementById('output');
                if (output) {
                    output.innerHTML = '<span class="output-error">Python environment is still initializing. Please wait...</span>';
                }
                return;
            }

            const code = document.getElementById('code').value;
            const output = document.getElementById('output');
            const runBtn = document.getElementById('runBtn');
            const statusDot = document.getElementById('statusDot');
            const statusText = document.getElementById('statusText');
            const executionTime = document.getElementById('executionTime');

            if (!output || !runBtn || !statusDot || !statusText) {
                console.error('Required HTML elements not found');
                return;
            }

            runBtn.disabled = true;
            runBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Executing...';
            statusDot.className = 'status-dot loading';
            statusText.textContent = 'Executing Code';
            
            if (executionTime) {
                executionTime.textContent = 'Running...';
            }

            const startTime = performance.now();

            try {

                await pyodide.runPythonAsync(`
import sys
import io
sys.stdout = io.StringIO()
sys.stderr = io.StringIO()
                `);


                await pyodide.runPythonAsync(code);


                const stdout = await pyodide.runPythonAsync("sys.stdout.getvalue()");
                const stderr = await pyodide.runPythonAsync("sys.stderr.getvalue()");

                const endTime = performance.now();
                const executionDuration = (endTime - startTime).toFixed(2);
                
                if (stderr) {
                    output.innerHTML = `<span class="output-error">${stderr}</span>`;
                    statusDot.className = 'status-dot error';
                    statusText.textContent = 'Execution Failed';
                } else if (stdout) {
                    output.textContent = stdout;
                    statusDot.className = 'status-dot';
                    statusText.textContent = 'Execution Successfull';
                    

                    if (currentTask) {
                        validateTask(code, stdout);
                    }
                } else {
                    output.innerHTML = '<span class="output-info">✓ Code executed successfully (no output generated)</span>';
                    statusDot.className = 'status-dot';
                    statusText.textContent = 'Execution Complete';
                }

                if (executionTime) {
                    executionTime.textContent = `${executionDuration}ms`;
                }

            } catch (error) {
                const endTime = performance.now();
                const executionDuration = (endTime - startTime).toFixed(2);

                output.innerHTML = `<span class="output-error">Error: ${error.message}</span>`;
                statusDot.className = 'status-dot error';
                statusText.textContent = 'Fatal Error';

                if (executionTime) {
                    executionTime.textContent = 'Error';
                }
            } finally {
                runBtn.disabled = false;
                if (currentTask) {
                    runBtn.innerHTML = `<i class="fas fa-check-circle"></i> Validate Task: ${currentTask.name}`;
                } else {
                    runBtn.innerHTML = '<i class="fas fa-play"></i> Execute Code';
                }
            }
        }

        function clearCode() {
            if (codeTextarea) {
                codeTextarea.value = '';
            }
            const output = document.getElementById('output');
            if (output) {
                output.innerHTML = '<span class="output-info">✓ Editor cleared. Write your Python code above.</span>';
            }
        }

        async function resetInterpreter() {
            const output = document.getElementById('output');
            if (output) {
                output.innerHTML = '<span class="output-info">Resetting Python environment...</span>';
            }
            isInitialized = false;
            await initializePyodide();
        }

        function saveToFile() {
            const code = codeTextarea.value;
            if (!code.trim()) {
                alert('No code to save!');
                return;
            }

            const blob = new Blob([code], { type: 'text/x-python' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'python_script.py';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        function checkForTask() {
            const taskInfo = localStorage.getItem('tasksinfo');
            
            if (taskInfo) {
                try {
                    const parts = taskInfo.split(',');
                    if (parts.length >= 2) {
                        const taskName = parts[0].trim();
                        const taskCoins = parseInt(parts[1].trim());
                        
                        if (taskName && !isNaN(taskCoins) && taskCoins > 0) {
                            currentTask = {
                                name: taskName,
                                coins: taskCoins
                            };
                            

                            const banner = document.getElementById('pending-assign-banner');
                            if (banner) {
                                banner.innerText = `Current Task: ${taskName} (${taskCoins} coins)`;
                            }
                            

                            const runBtn = document.getElementById('runBtn');
                            if (runBtn) {
                                runBtn.innerHTML = `<i class="fas fa-check-circle"></i> Validate Task: ${taskName}`;
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error parsing task:', error);
                }
            }
        }

        async function validateTask(code, stdout) {
            if (!currentTask) return;
            const n = currentTask.name.toLowerCase();
            const esc = JSON.stringify(code);

            // Prelude: find function, handle Solution class
            const H = `import sys,io as _io
_sv=sys.stdout;sys.stdout=_io.StringIO()
try:
    exec(${esc})
except:pass
sys.stdout=_sv
try:_s=Solution()
except:_s=None
def _fn(*ns):
    g=globals()
    for x in ns:
        if x in g:return g[x]
        if _s and hasattr(_s,x):return getattr(_s,x)
    return None
_p=[];_e=[]
`;
            const END = `\n'PWZTEST_PASS' if not _e else ('PWZTEST_FAIL:'+';'.join(_e))`;
            let py = null;

            if (n.includes('two sum')) {
                py = H+`f=_fn('twoSum','two_sum')
if not f:_e.append('Def: twoSum(nums,tgt)')
else:
 for i,t,ex in [([2,7,11,15],9,[0,1]),([3,2,4],6,[1,2])]:
  try:
   r=sorted(f(i,t))
   if r!=ex:_e.append(f'Exp {ex} got {r}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('reverse') && n.includes('list')) {
                py = H+`f=_fn('reverseList','reverse_list')
if not f:_e.append('Def: reverseList(head)')
else:
 for i,ex in [([1,2,3,4,5],[5,4,3,2,1]),([1,2],[2,1])]:
  try:
   r=list(f(i))
   if r!=ex:_e.append(f'Exp {ex} got {r}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('valid parenthes')) {
                py = H+`f=_fn('isValid','is_valid')
if not f:_e.append('Def: isValid(s)')
else:
 for s,ex in [('()',True),('()[]{}',True),('(]',False),('{[]}',True)]:
  try:
   r=f(s)
   if r!=ex:_e.append(f'"{s}" expected {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('buy and sell')) {
                py = H+`f=_fn('maxProfit','max_profit')
if not f:_e.append('Def: maxProfit(prices)')
else:
 for i,ex in [([7,1,5,3,6,4],5),([7,6,4,3,1],0)]:
  try:
   r=f(i)
   if r!=ex:_e.append(f'Exp {ex} got {r}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('climbing stairs')) {
                py = H+`f=_fn('climbStairs','climb_stairs')
if not f:_e.append('Def: climbStairs(n)')
else:
 for i,ex in [(2,2),(3,3),(4,5)]:
  try:
   if f(i)!=ex:_e.append(f'n={i} exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('maximum subarray')) {
                py = H+`f=_fn('maxSubArray','max_sub_array')
if not f:_e.append('Def: maxSubArray(nums)')
else:
 for i,ex in [([-2,1,-3,4,-1,2,1,-5,4],6),([1],1)]:
  try:
   if f(i)!=ex:_e.append(f'Exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('binary search')) {
                py = H+`f=_fn('search','binary_search')
if not f:_e.append('Def: search(nums,target)')
else:
 for i,t,ex in [([-1,0,3,5,9,12],9,4),([-1,0,3,5,9,12],2,-1)]:
  try:
   if f(i,t)!=ex:_e.append(f'Exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('longest common prefix')) {
                py = H+`f=_fn('longestCommonPrefix','longest_common_prefix')
if not f:_e.append('Def: longestCommonPrefix(strs)')
else:
 for i,ex in [(['flower','flow','flight'],'fl'),(['dog','racecar','car'],'')]:
  try:
   if f(i)!=ex:_e.append(f'Exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('palindrome number')) {
                py = H+`f=_fn('isPalindrome','is_palindrome')
if not f:_e.append('Def: isPalindrome(x)')
else:
 for i,ex in [(121,True),(-121,False),(10,False)]:
  try:
   if f(i)!=ex:_e.append(f'x={i} exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('longest substring')) {
                py = H+`f=_fn('lengthOfLongestSubstring','length_of_longest_substring')
if not f:_e.append('Def: lengthOfLongestSubstring(s)')
else:
 for i,ex in [('abcabcbb',3),('bbbbb',1),('pwwkew',3)]:
  try:
   if f(i)!=ex:_e.append(f'Exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('3sum')) {
                py = H+`f=_fn('threeSum','three_sum')
if not f:_e.append('Def: threeSum(nums)')
else:
 try:
  r=sorted([sorted(x) for x in f([-1,0,1,2,-1,-4])])
  if r!=[[-1,-1,2],[-1,0,1]]:_e.append('Incorrect triplets')
 except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('container with most water')) {
                py = H+`f=_fn('maxArea','max_area')
if not f:_e.append('Def: maxArea(height)')
else:
 for i,ex in [([1,8,6,2,5,4,8,3,7],49),([1,1],1)]:
  try:
   if f(i)!=ex:_e.append(f'Exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('group anagram')) {
                py = H+`f=_fn('groupAnagrams','group_anagrams')
if not f:_e.append('Def: groupAnagrams(strs)')
else:
 try:
  r=f(['eat','tea','tan','ate','nat','bat'])
  if len(r)!=3:_e.append('Exp 3 groups')
 except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('number of islands')) {
                py = H+`f=_fn('numIslands','num_islands')
if not f:_e.append('Def: numIslands(grid)')
else:
 try:
  g=[['1','1','0'],['1','1','0'],['0','0','1']]
  if f(g)!=2:_e.append('Exp 2 islands')
 except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('coin change')) {
                py = H+`f=_fn('coinChange','coin_change')
if not f:_e.append('Def: coinChange(coins,amount)')
else:
 for c,a,ex in [([1,5,11],15,3),([2],3,-1)]:
  try:
   if f(c,a)!=ex:_e.append(f'Exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('word search')) {
                py = H+`f=_fn('exist')
if not f:_e.append('Def: exist(board,word)')
else:
 try:
  b=[['A','B','C','E'],['S','F','C','S']]
  if f(b,'ABCC')!=True:_e.append('Word search failed')
 except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('merge k')) {
                py = H+`f=_fn('mergeKLists','merge_k_lists')
if not f:_e.append('Def: mergeKLists(lists)')
else:
 try:
  r=list(f([[1,4,5],[1,3,4]]))
  if r!=[1,1,3,4,4,5]:_e.append('Merge K failed')
 except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('trapping rain')) {
                py = H+`f=_fn('trap')
if not f:_e.append('Def: trap(height)')
else:
 for i,ex in [([0,1,0,2,1,0,1,3,2,1,2,1],6)]:
  try:
   if f(i)!=ex:_e.append(f'Exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('median of two sorted')) {
                py = H+`f=_fn('findMedianSortedArrays','find_median_sorted_arrays')
if not f:_e.append('Def: findMedianSortedArrays(n1,n2)')
else:
 for a,b,ex in [([1,3],[2],2.0),([1,2],[3,4],2.5)]:
  try:
   if abs(f(a,b)-ex)>0.001:_e.append(f'Exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            } else if (n.includes('merge two sorted')) {
                py = H+`f=_fn('mergeTwoLists','merge_two_lists')
if not f:_e.append('Def: mergeTwoLists(l1,l2)')
else:
 for a,b,ex in [([1,2,4],[1,3,4],[1,1,2,3,4,4])]:
  try:
   if list(f(a,b))!=ex:_e.append(f'Exp {ex}')
  except Exception as e:_e.append(str(e))`+END;
            }

            if (!py) {
                if (stdout && stdout.trim().length > 0) completeTask();
                return;
            }

            try {
                const res = await pyodide.runPythonAsync(py);
                if (res === 'PWZTEST_PASS') {
                    completeTask();
                    const ov = document.createElement('div');
                    ov.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,0.85);display:flex;align-items:center;justify-content:center;z-index:9999;';
                    ov.innerHTML=`<div style="background:#080808;border:2px solid #ff2a2f;border-radius:20px;padding:40px;text-align:center;max-width:400px;width:90%;"><div style="font-size:4rem;margin-bottom:1rem;">🎉</div><h2 style="color:#fff;margin-bottom:1rem;">Task Complete!</h2><p style="color:#a0a0a0;margin-bottom:1.5rem;">You earned <strong>${currentTask.coins}</strong> coins!</p><button onclick="this.closest('div').parentElement.remove()" style="background:#ff2a2f;color:#fff;border:none;padding:12px 30px;border-radius:10px;font-weight:bold;cursor:pointer;">Awesome!</button></div>`;
                    document.body.appendChild(ov);
                    setTimeout(()=>ov.remove(), 8000);
                } else {
                    const el = document.getElementById('output');
                    if (el) el.innerHTML += `\n<span class="output-error">✗ Validation failed: ${res.replace('PWZTEST_FAIL:','')}</span>`;
                }
            } catch(e) {
                console.error(e);
            }
        }

        function completeTask() {
            if (!currentTask) return;
            
            const taskName = currentTask.name;
            const taskCoins = currentTask.coins;
            console.log(taskName)
            console.log(taskCoins)
            const datas = new FormData();
            datas.append('task-name',String(taskName));
            datas.append('task-coin',Number(taskCoins));
            fetch('https://proworldz.page.gd/api/update_task.php', {
                method: 'POST',
                body:datas
            })
            .then(res => res.json())
            .then(data => {
                console.log('Task completion recorded:', data);
                showSuccessPopup(taskCoins);
                

                localStorage.removeItem('tasksinfo');
                currentTask = null;
                

                const banner = document.getElementById('pending-assign-banner');
                if (banner) {
                    banner.innerText = '';
                }
                
                const runBtn = document.getElementById('runBtn');
                if (runBtn) {
                    runBtn.innerHTML = '<i class="fas fa-play"></i> Execute Code';
                }
            })
            .catch(err => {
                console.error('Error updating task:', err);
                alert('Task completed! Coins will be added.');
                

                localStorage.removeItem('tasksinfo');
                currentTask = null;
                
                const banner = document.getElementById('pending-assign-banner');
                if (banner) {
                    banner.innerText = '';
                }
                
                const runBtn = document.getElementById('runBtn');
                if (runBtn) {
                    runBtn.innerHTML = '<i class="fas fa-play"></i> Execute Code';
                }
            });
        }

        function showSuccessPopup(coins) {

    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        animation: fadeIn 0.3s ease-out;
    `;


    const popup = document.createElement('div');
    popup.style.cssText = `
        background: linear-gradient(145deg, #080808, #0f1117);
        border-radius: 20px;
        padding: 40px 50px;
        text-align: center;
        max-width: 450px;
        width: 90%;
        border: 2px solid #e5191e;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 
                    0 0 0 1px rgba(99, 102, 241, 0.1),
                    inset 0 0 20px rgba(99, 102, 241, 0.1);
        position: relative;
        overflow: hidden;
        animation: slideUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    `;


    const glowEffect = document.createElement('div');
    glowEffect.style.cssText = `
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
        z-index: -1;
    `;
    popup.appendChild(glowEffect);


    const successIcon = document.createElement('div');
    successIcon.innerHTML = `
        <div style="
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            background: linear-gradient(135deg, #ff2a2f, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
            position: relative;
        ">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                <path d="M20 6L9 17L4 12" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div style="
                position: absolute;
                inset: -5px;
                border: 2px solid rgba(16, 185, 129, 0.3);
                border-radius: 50%;
                animation: pulseRing 2s infinite;
            "></div>
        </div>
    `;
    popup.appendChild(successIcon);


    const title = document.createElement('h2');
    title.textContent = '🎉 Task Completed!';
    title.style.cssText = `
        color: #ffffff;
        font-size: 28px;
        margin: 0 0 15px 0;
        font-weight: 700;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #ffffff, #a0a0a0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    `;
    popup.appendChild(title);


    const message = document.createElement('p');
    message.textContent = 'Congratulations! You have successfully completed the task.';
    message.style.cssText = `
        color: #a0a0a0;
        font-size: 16px;
        margin: 0 0 35px 0;
        line-height: 1.6;
    `;
    popup.appendChild(message);


    const coinsContainer = document.createElement('div');
    coinsContainer.style.cssText = `
        background: linear-gradient(135deg, rgba(139, 69, 19, 0.15), rgba(160, 82, 45, 0.1));
        border: 2px solid #8B4513;
        border-radius: 15px;
        padding: 20px 30px;
        margin: 0 auto 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        max-width: 300px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(139, 69, 19, 0.2);
    `;


    const coinIcon = document.createElement('img');
    coinIcon.src = '../images/coin.png';
    coinIcon.alt = 'Eagle Coin';
    coinIcon.style.cssText = `
        width: 50px;
        height: 50px;
        object-fit: contain;
        animation: coinSpin 2s ease-in-out;
        filter: drop-shadow(0 5px 15px rgba(245, 158, 11, 0.4));
    `;
    

    coinIcon.onerror = function() {
        this.onerror = null;
        this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjRkJCQjI0IiBzdHJva2Utd2lkdGg9IjIiPjxjaXJjbGUgY3g9IjEyIiBjeT0iMTIiIHI9IjEwIi8+PHBhdGggZD0iTTggMTIuNUwxMC41IDE1TDE2IDkiLz48L3N2Zz4=';
    };


    const coinsText = document.createElement('div');
    coinsText.style.cssText = `
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    `;

    const earnedText = document.createElement('span');
    earnedText.textContent = 'Earned';
    earnedText.style.cssText = `
        color: #a0a0a0;
        font-size: 14px;
        margin-bottom: 5px;
    `;

    const coinsAmount = document.createElement('span');
    coinsAmount.innerHTML = `<span style="color: #8b0c10; font-size: 36px; font-weight: 800;">${coins}</span> <span style="color: #ffffff; font-size: 20px; font-weight: 600;">Eagle Coins</span>`;
    
    coinsText.appendChild(earnedText);
    coinsText.appendChild(coinsAmount);
    coinsContainer.appendChild(coinIcon);
    coinsContainer.appendChild(coinsText);
    popup.appendChild(coinsContainer);


    const closeButton = document.createElement('button');
    closeButton.innerHTML = `
        <span style="margin-right: 8px;">✓</span>
        Awesome!
    `;
    closeButton.style.cssText = `
        background: linear-gradient(135deg, #e5191e, #e5191e);
        color: white;
        border: none;
        padding: 16px 40px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        min-width: 180px;
    `;
    
    closeButton.onmouseover = function() {
        this.style.transform = 'translateY(-3px)';
        this.style.boxShadow = '0 15px 35px rgba(99, 102, 241, 0.4)';
    };
    
    closeButton.onmouseout = function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 10px 25px rgba(99, 102, 241, 0.3)';
    };
    
    closeButton.onclick = function() {
        document.body.removeChild(overlay);
        if (typeof onPopupClose === 'function') {
            onPopupClose();
        }
    };
    popup.appendChild(closeButton);


    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                transform: translateY(40px) scale(0.95); 
                opacity: 0; 
            }
            to { 
                transform: translateY(0) scale(1); 
                opacity: 1; 
            }
        }
        
        @keyframes coinSpin {
            0% { 
                transform: rotateY(0deg) scale(1); 
            }
            50% { 
                transform: rotateY(180deg) scale(1.1); 
            }
            100% { 
                transform: rotateY(360deg) scale(1); 
            }
        }
        
        @keyframes pulseRing {
            0% { 
                transform: scale(1);
                opacity: 0.5;
            }
            50% { 
                transform: scale(1.2);
                opacity: 0;
            }
            100% { 
                transform: scale(1);
                opacity: 0.5;
            }
        }
        
        @keyframes float {
            0%, 100% { 
                transform: translateY(0px); 
            }
            50% { 
                transform: translateY(-10px); 
            }
        }
        
        .coin-float {
            animation: float 3s ease-in-out infinite;
        }
    `;
    document.head.appendChild(style);


    setTimeout(() => {
        coinIcon.classList.add('coin-float');
    }, 500);


    overlay.appendChild(popup);
    document.body.appendChild(overlay);


    setTimeout(() => {
        if (document.body.contains(overlay)) {
            document.body.removeChild(overlay);
        }
    }, 8000);


    const closeOnEsc = function(e) {
        if (e.key === 'Escape' && document.body.contains(overlay)) {
            document.body.removeChild(overlay);
            document.removeEventListener('keydown', closeOnEsc);
        }
    };
    document.addEventListener('keydown', closeOnEsc);
}

        document.addEventListener('DOMContentLoaded', function() {
            setupEditor();
            initializePyodide();
        });
    </script>
<script src="mascot_guide.js"></script>
</body>
</html>
