<?php
require_once 'api/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programming Tasks | Secure Worldz Academy</title>
  <link rel="icon" type="image/webp" href="image.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            border-color: rgba(229, 231, 235, 0.3);
            outline-color: rgba(156, 163, 175, 0.5);
            overscroll-behavior: auto;
        }

        body {
            font-family: 'Space Grotesk', 'Roboto Mono', sans-serif;
            background-color: #000000;
            color: #ffffff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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
            --primary: #ff2a2f;
            --primary-foreground: #ffffff;
            --secondary: #080808;
            --secondary-foreground: #ffffff;
            --muted: #080808;
            --muted-foreground: #a0a0a0;
            --accent: rgba(248, 250, 252, 0.05);
            --accent-foreground: #ffffff;
            --border: rgba(139, 12, 16, 0.1);
            --pop: rgba(255, 255, 255, 0.025);
            --input: rgba(139, 12, 16, 0.15);
            --ring: rgba(148, 163, 184, 0.5);
            
            --success: #ff2a2f;
            --destructive: #ff2a2f;
            --warning: #8b0c10;
            
            --chart-1: #ff2a2f;
            --chart-2: #ff2a2f;
            --chart-3: #8b0c10;
            --chart-4: #ff2a2f;
            --chart-5: #ff2a2f;
            
            --sidebar: #080808;
            --sidebar-foreground: #ffffff;
            --sidebar-primary: #ff2a2f;
            --sidebar-primary-foreground: #ffffff;
            --sidebar-accent: rgba(248, 250, 252, 0.05);
            --sidebar-accent-foreground: #ffffff;
            --sidebar-border: rgba(139, 12, 16, 0.1);
            --sidebar-ring: rgba(148, 163, 184, 0.5);
            
            --gap: 1.5rem;
            --sides: 1.5rem;
            --header-mobile: 3.8rem;
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

        .hidden { display: none !important; }
        .block { display: block; }
        .flex { display: flex; }
        .grid { display: grid; }
        .relative { position: relative; }
        .absolute { position: absolute; }
        .w-full { width: 100%; }
        .h-full { height: 100%; }
        .overflow-hidden { overflow: hidden; }
        .overflow-y-auto { overflow-y: auto; }
        .rounded-lg { border-radius: var(--radius); }
        .rounded-md { border-radius: calc(var(--radius) - 2px); }
        .rounded-sm { border-radius: calc(var(--radius) - 4px); }
        .rounded-full { border-radius: 9999px; }
        .border { border-width: 1px; }
        .border-2 { border-width: 2px; }
        .border-b { border-bottom-width: 1px; }
        .border-t { border-top-width: 1px; }

        .bg-background { background-color: var(--background); }
        .bg-foreground { background-color: var(--foreground); }
        .bg-primary { background-color: var(--primary); }
        .bg-secondary { background-color: var(--secondary); }
        .bg-muted { background-color: var(--muted); }
        .bg-accent { background-color: var(--accent); }
        .bg-card { background-color: var(--card); }
        .bg-success { background-color: var(--success); }
        .bg-warning { background-color: var(--warning); }
        .bg-destructive { background-color: var(--destructive); }
        .bg-sidebar { background-color: var(--sidebar); }
        .bg-sidebar-primary { background-color: var(--sidebar-primary); }
        .bg-sidebar-accent { background-color: var(--sidebar-accent); }

        .text-foreground { color: var(--foreground); }
        .text-primary { color: var(--primary); }
        .text-primary-foreground { color: var(--primary-foreground); }
        .text-secondary { color: var(--secondary); }
        .text-secondary-foreground { color: var(--secondary-foreground); }
        .text-muted { color: var(--muted); }
        .text-muted-foreground { color: var(--muted-foreground); }
        .text-success { color: var(--success); }
        .text-warning { color: var(--warning); }
        .text-destructive { color: var(--destructive); }
        .text-sidebar-foreground { color: var(--sidebar-foreground); }
        .text-sidebar-primary { color: var(--sidebar-primary); }
        .text-sidebar-primary-foreground { color: var(--sidebar-primary-foreground); }

        .text-xs { font-size: 0.75rem; line-height: 1rem; }
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .text-base { font-size: 1rem; line-height: 1.5rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
        .text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
        .text-5xl { font-size: 3rem; line-height: 1; }

        .font-normal { font-weight: 400; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }

        .uppercase { text-transform: uppercase; }
        .italic { font-style: italic; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .opacity-0 { opacity: 0; }
        .opacity-50 { opacity: 0.5; }
        .opacity-100 { opacity: 1; }

        .cursor-pointer { cursor: pointer; }
        .select-none { user-select: none; }

        .transition-all { transition: all 0.3s ease; }
        .transition-colors { transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease; }
        .transition-opacity { transition: opacity 0.3s ease; }
        .transition-transform { transition: transform 0.3s ease; }

        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }

        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .gap-gap { gap: var(--gap); }

        .p-0 { padding: 0; }
        .p-1 { padding: 0.25rem; }
        .p-2 { padding: 0.5rem; }
        .p-3 { padding: 0.75rem; }
        .p-4 { padding: 1rem; }
        .p-6 { padding: 1.5rem; }
        .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
        .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .py-6 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
        .py-8 { padding-top: 2rem; padding-bottom: 2rem; }

        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-auto { margin-top: auto; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .ml-auto { margin-left: auto; }
        .mr-1 { margin-right: 0.25rem; }
        .mr-2 { margin-right: 0.5rem; }
        .mr-3 { margin-right: 0.75rem; }

        .space-y-1 > * + * { margin-top: 0.25rem; }
        .space-y-2 > * + * { margin-top: 0.5rem; }
        .space-y-3 > * + * { margin-top: 0.75rem; }
        .space-y-4 > * + * { margin-top: 1rem; }

        .flex-1 { flex: 1 1 0%; }
        .flex-col { flex-direction: column; }
        .flex-row { flex-direction: row; }
        .items-start { align-items: flex-start; }
        .items-center { align-items: center; }
        .items-baseline { align-items: baseline; }
        .items-stretch { align-items: stretch; }
        .justify-start { justify-content: flex-start; }
        .justify-center { justify-content: center; }
        .justify-between { justify-content: space-between; }
        .justify-end { justify-content: flex-end; }

        .min-w-0 { min-width: 0; }
        .max-w-xs { max-width: 20rem; }
        .max-w-sm { max-width: 24rem; }
        .max-w-md { max-width: 28rem; }
        .max-w-max { max-width: max-content; }

        .w-14 { width: 3.5rem; }
        .w-16 { width: 4rem; }
        .w-56 { width: 14rem; }
        .w-80 { width: 20rem; }

        .h-5 { height: 1.25rem; }
        .h-6 { height: 1.5rem; }
        .h-7 { height: 1.75rem; }
        .h-8 { height: 2rem; }
        .h-10 { height: 2.5rem; }
        .h-12 { height: 3rem; }
        .h-14 { height: 3.5rem; }
        .h-32 { height: 8rem; }

        .size-3 { width: 0.75rem; height: 0.75rem; }
        .size-4 { width: 1rem; height: 1rem; }
        .size-5 { width: 1.25rem; height: 1.25rem; }
        .size-6 { width: 1.5rem; height: 1.5rem; }
        .size-7 { width: 1.75rem; height: 1.75rem; }
        .size-9 { width: 2.25rem; height: 2.25rem; }
        .size-10 { width: 2.5rem; height: 2.5rem; }
        .size-12 { width: 3rem; height: 3rem; }
        .size-14 { width: 3.5rem; height: 3.5rem; }
        .size-16 { width: 4rem; height: 4rem; }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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

        .badge-default {
            background-color: var(--primary);
            color: var(--primary-foreground);
            border-color: var(--primary);
        }

        .badge-secondary {
            background-color: var(--secondary);
            color: var(--secondary-foreground);
            border-color: var(--border);
        }

        .badge-outline {
            background-color: transparent;
            color: currentColor;
            border-color: currentColor;
        }

        .badge-outline-success {
            background-color: transparent;
            color: var(--success);
            border-color: var(--success);
        }

        .badge-outline-warning {
            background-color: transparent;
            color: var(--warning);
            border-color: var(--warning);
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

        .button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .button-default {
            background-color: var(--primary);
            color: var(--primary-foreground);
        }

        .button-default:hover:not(:disabled) {
            background-color: color-mix(in srgb, var(--primary) 90%, black);
        }

        .button-secondary {
            background-color: var(--secondary);
            color: var(--secondary-foreground);
            border-color: var(--border);
        }

        .button-secondary:hover:not(:disabled) {
            background-color: color-mix(in srgb, var(--secondary) 90%, black);
        }

        .button-ghost {
            background-color: transparent;
            color: currentColor;
        }

        .button-ghost:hover:not(:disabled) {
            background-color: var(--accent);
        }

        .button-outline {
            background-color: transparent;
            color: currentColor;
            border-color: currentColor;
        }

        .button-outline:hover:not(:disabled) {
            background-color: var(--accent);
        }

        .button-sm {
            height: 2rem;
            padding: 0 0.75rem;
            font-size: 0.875rem;
        }

        .button-md {
            height: 2.5rem;
            padding: 0 1rem;
            font-size: 0.875rem;
        }

        .button-lg {
            height: 3rem;
            padding: 0 1.5rem;
            font-size: 1rem;
        }

        .button-icon {
            width: 2.5rem;
            height: 2.5rem;
            padding: 0;
        }

        

        .nav-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
        }

        .nav-title span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--muted-foreground);
        }

        

        

        

        .bullet {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background-color: var(--muted-foreground);
        }

        .bullet-success {
            background-color: var(--success);
        }

        .bullet-sm {
            width: 0.375rem;
            height: 0.375rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slideUp {
            animation: slideUp 0.3s ease-out;
        }

        .animate-slideDown {
            animation: slideDown 0.3s ease-out;
        }

        

        

        

        
        

        

        

        

        

        

        

        

        .ripple-container {
            position: relative;
            overflow: hidden;
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

        .task-card {
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 220px;
            height: auto;
        }

        .task-card:hover {
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
        }

        .task-difficulty-easy {
            background-color: rgba(16, 185, 129, 0.15);
            color: var(--success);
        }

        .task-difficulty-medium {
            background-color: rgba(245, 158, 11, 0.15);
            color: var(--warning);
        }

        .task-difficulty-hard {
            background-color: rgba(239, 68, 68, 0.15);
            color: var(--destructive);
        }

        .task-points {
            background: linear-gradient(45deg, var(--primary), var(--chart-2));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            width: fit-content;
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .task-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: auto;
        }

        .task-actions .button {
            flex: 1;
        }

        .completed-badge {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            padding: 0.75rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
        }
        
        .eagle-coins {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: var(--warning, #f59e0b);
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            width: fit-content;
        }

        
        

        
        

        
</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div id="loader-text">INITIALIZING ECOSYSTEM...</div>
    </div>

    
        <script>
            window.addEventListener('load', () => {
                setTimeout(() => {
                    document.body.classList.add('loaded');
                }, 500);
            });
        </script>
<div class="desktop-container">
        <!-- Left Sidebar - Navigation -->
                <?php include 'sidebar.php'; ?>

    <div class="desktop-main">
            <div class="card animate-fadeIn">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded bg-primary flex items-center justify-center">
                            <svg class="size-5 text-primary-foreground" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                <path stroke-width="1.5" d="M16.667 16.667V5a2.5 2.5 0 0 0-2.5-2.5H6.667a2.5 2.5 0 0 0-2.5 2.5v11.667"/>
                                <path stroke-width="1.5" d="M6.667 2.5v15"/>
                                <path stroke-width="1.5" d="M11.667 4.167l4.166 4.166" stroke-linecap="round"/>
                                <path stroke-width="1.5" d="M13.333 8.333l-2.5 2.5-2.5-2.5 2.5-2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-display">Programming Tasks</h1>
                            <div class="text-sm text-muted-foreground">Complete challenges to earn Eagle Points</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="card animate-fadeIn">
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bullet"></div>
                            <span class="text-sm font-medium uppercase">Total Tasks</span>
                        </div>
                    </div>
                    <div class="bg-accent p-4">
                        <div class="flex items-center">
                            <span id="total-tasks" class="text-5xl font-display text-primary">0</span>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm font-medium text-muted-foreground tracking-wide">AVAILABLE TASKS</p>
                        </div>
                    </div>
                </div>

                <div class="card animate-fadeIn" style="animation-delay: 0.1s">
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bullet bullet-success"></div>
                            <span class="text-sm font-medium uppercase">Completed</span>
                        </div>
                    </div>
                    <div class="bg-accent p-4">
                        <div class="flex items-center">
                            <span id="completed-tasks" class="text-5xl font-display text-success">0</span>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm font-medium text-muted-foreground tracking-wide">TASKS SOLVED</p>
                        </div>
                    </div>
                </div>

                <div class="card animate-fadeIn" style="animation-delay: 0.2s">
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bullet"></div>
                            <span class="text-sm font-medium uppercase">Pendings</span>
                        </div>
                    </div>
                    <div class="bg-accent p-4">
                        <div class="flex items-center">
                            <span id="pendings" class="text-5xl font-display text-warning">0</span>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm font-medium text-muted-foreground tracking-wide">Pending tasks</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="showall">

            </div>
        </div>
    </div>

</div>

<script>

// Heartbeat
setInterval(() => {
    fetch('api/heartbeat.php').then(r => r.json()).catch(e => console.error("Heartbeat error:", e));
}, 60000);

function render_tasks(title, e_coins, description, diff) {
    const diffColor = diff === 'Easy' ? '#10b981' : (diff === 'Hard' ? '#ef4444' : '#f59e0b');
    const diffBg = diff === 'Easy' ? 'rgba(16, 185, 129, 0.15)' : (diff === 'Hard' ? 'rgba(239, 68, 68, 0.15)' : 'rgba(245, 158, 11, 0.15)');
    return `<div class="task-card card p-4 flex flex-col justify-between transition-all hover:-translate-y-1 animate-slideUp">
        <div>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase" style="background: ${diffBg}; color: ${diffColor}; border: 1px solid ${diffColor}40;">
                    <div class="size-2 rounded-full" style="background: ${diffColor}"></div>
                    <span>${diff}</span>
                </div>
                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #f59e0b;">
                    <img src="images/coin.png" style="width: 14px; height: 14px; object-fit: contain;">
                    <span>${e_coins} EC</span>
                </div>
            </div>
            <h3 class="font-display mb-2" style="font-size: 1rem; color: #ffffff; line-height: 1.35;">${title}</h3>
            <p class="text-xs text-muted-foreground line-clamp-2 mb-4" style="line-height: 1.5;">${description || 'Complete this mission to earn Eagle Coins.'}</p>
        </div>
        <div class="mt-auto pt-2">
            <button class="solve-btn" 
                    data-task="${encodeURIComponent(title)}"
                    data-coins="${e_coins}"
                    style="display: block; width: 100%; padding: 0.65rem; background: var(--primary); color: white; border: none; border-radius: calc(var(--radius) - 2px); text-align: center; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: all 0.2s; letter-spacing: 0.05em;">
                Solve Problem
            </button>
        </div>
    </div>`;
}

// ── 20 LeetCode-style Programming Tasks ──────────────────────────────────────
const TASKS = [
    {
        title: "Two Sum",
        diff: "Easy", coins: 10,
        desc: "Given an array of integers nums and an integer target, return the indices of the two numbers that add up to target. Each input has exactly one solution and you may not use the same element twice. Example: nums = [2,7,11,15], target = 9 → Output: [0,1]."
    },
    {
        title: "Reverse a Linked List",
        diff: "Easy", coins: 10,
        desc: "Given the head of a singly linked list, reverse the list and return the reversed head. Example: head = [1→2→3→4→5] → Output: [5→4→3→2→1]. Must run in O(n) time and O(1) space."
    },
    {
        title: "Valid Parentheses",
        diff: "Easy", coins: 10,
        desc: "Given a string s containing only '(', ')', '{', '}', '[' and ']', determine if the input string is valid. A string is valid if every open bracket is closed by the same type in the correct order. Example: s = '()[]{}'→ true; s = '(]' → false."
    },
    {
        title: "Best Time to Buy and Sell Stock",
        diff: "Easy", coins: 10,
        desc: "Given an array prices where prices[i] is the price of a stock on day i, return the maximum profit you can achieve by buying on one day and selling on a later day. If no profit is possible, return 0. Example: prices = [7,1,5,3,6,4] → Output: 5."
    },
    {
        title: "Climbing Stairs",
        diff: "Easy", coins: 10,
        desc: "You are climbing a staircase of n steps. Each time you can climb 1 or 2 steps. In how many distinct ways can you climb to the top? Example: n = 3 → Output: 3 (1+1+1, 1+2, 2+1). Solve using dynamic programming."
    },
    {
        title: "Maximum Subarray (Kadane's Algorithm)",
        diff: "Easy", coins: 15,
        desc: "Given an integer array nums, find the contiguous subarray (containing at least one number) which has the largest sum and return its sum. Example: nums = [-2,1,-3,4,-1,2,1,-5,4] → Output: 6 (subarray [4,-1,2,1])."
    },
    {
        title: "Merge Two Sorted Lists",
        diff: "Easy", coins: 15,
        desc: "Merge two sorted linked lists and return it as a new sorted list. The new list should be made by splicing together the nodes of the first two lists. Example: l1 = [1→2→4], l2 = [1→3→4] → Output: [1→1→2→3→4→4]."
    },
    {
        title: "Binary Search",
        diff: "Easy", coins: 10,
        desc: "Given a sorted array of integers nums and a target integer, implement binary search and return the index of target. If the target does not exist, return -1. Must run in O(log n) time. Example: nums = [-1,0,3,5,9,12], target = 9 → Output: 4."
    },
    {
        title: "Longest Common Prefix",
        diff: "Easy", coins: 10,
        desc: "Write a function to find the longest common prefix string amongst an array of strings. If there is no common prefix, return an empty string ''. Example: strs = ['flower','flow','flight'] → Output: 'fl'. Example 2: strs = ['dog','racecar','car'] → Output: ''."
    },
    {
        title: "Palindrome Number",
        diff: "Easy", coins: 10,
        desc: "Given an integer x, return true if x is a palindrome (reads the same forward and backward), and false otherwise. Do NOT convert it to a string. Example: x = 121 → true. x = -121 → false (leading minus makes it not a palindrome)."
    },
    {
        title: "Longest Substring Without Repeating Characters",
        diff: "Medium", coins: 25,
        desc: "Given a string s, find the length of the longest substring without repeating characters. Use a sliding window approach. Example: s = 'abcabcbb' → Output: 3 (the answer is 'abc'). Example 2: s = 'pwwkew' → Output: 3 (the answer is 'wke')."
    },
    {
        title: "3Sum",
        diff: "Medium", coins: 25,
        desc: "Given an integer array nums, return all the triplets [nums[i], nums[j], nums[k]] such that i ≠ j ≠ k and nums[i] + nums[j] + nums[k] == 0. The solution set must not contain duplicate triplets. Example: nums = [-1,0,1,2,-1,-4] → Output: [[-1,-1,2],[-1,0,1]]."
    },
    {
        title: "Container With Most Water",
        diff: "Medium", coins: 25,
        desc: "Given an integer array height of length n, find two lines that together with the x-axis form a container that holds the most water. Return the maximum amount of water. Use the two-pointer technique. Example: height = [1,8,6,2,5,4,8,3,7] → Output: 49."
    },
    {
        title: "Group Anagrams",
        diff: "Medium", coins: 25,
        desc: "Given an array of strings strs, group the anagrams together. You can return the answer in any order. Example: strs = ['eat','tea','tan','ate','nat','bat'] → Output: [['bat'],['nat','tan'],['ate','eat','tea']]. Use a hash map with sorted string as key."
    },
    {
        title: "Number of Islands",
        diff: "Medium", coins: 30,
        desc: "Given an m×n 2D binary grid where '1' represents land and '0' represents water, return the number of islands. An island is surrounded by water and formed by connecting adjacent lands horizontally or vertically. Example: grid = [['1','1','0'],['1','1','0'],['0','0','1']] → Output: 2."
    },
    {
        title: "Coin Change",
        diff: "Medium", coins: 30,
        desc: "Given an integer array coins representing coins of different denominations and an integer amount, return the fewest number of coins needed to make up that amount. If it cannot be achieved, return -1. Solve using dynamic programming. Example: coins = [1,5,11], amount = 15 → Output: 3."
    },
    {
        title: "Word Search",
        diff: "Medium", coins: 30,
        desc: "Given an m×n board of characters and a string word, return true if the word exists in the grid. The word can be constructed from letters of sequentially adjacent cells (horizontally or vertically adjacent). Each cell may only be used once. Use backtracking DFS. Example: board = [['A','B','C','E'],['S','F','C','S'],['A','D','E','E']], word = 'ABCCED' → true."
    },
    {
        title: "Merge K Sorted Lists",
        diff: "Hard", coins: 50,
        desc: "You are given an array of k linked lists, each sorted in ascending order. Merge all the linked lists into one sorted list and return it. Example: lists = [[1→4→5],[1→3→4],[2→6]] → Output: [1→1→2→3→4→4→5→6]. Use a min-heap (priority queue) for O(N log k) time."
    },
    {
        title: "Trapping Rain Water",
        diff: "Hard", coins: 50,
        desc: "Given n non-negative integers representing an elevation map where the width of each bar is 1, compute how much water it can trap after raining. Example: height = [0,1,0,2,1,0,1,3,2,1,2,1] → Output: 6. Solve in O(n) time using two pointers or prefix/suffix max arrays."
    },
    {
        title: "Median of Two Sorted Arrays",
        diff: "Hard", coins: 60,
        desc: "Given two sorted arrays nums1 and nums2 of size m and n respectively, return the median of the two sorted arrays. The overall runtime complexity must be O(log(min(m,n))). Example: nums1 = [1,3], nums2 = [2] → Output: 2.0. Example 2: nums1 = [1,2], nums2 = [3,4] → Output: 2.5."
    }
];

// Populate stats
const completedTasks = JSON.parse(localStorage.getItem('pwz_completed_tasks') || '[]');
document.getElementById('total-tasks').textContent = TASKS.length;
document.getElementById('completed-tasks').textContent = completedTasks.length;
document.getElementById('pendings').textContent = Math.max(0, TASKS.length - completedTasks.length);

// Render tasks
const showallDiv = document.getElementById('showall');
showallDiv.innerHTML = `
    <div class="category-section animate-fadeIn">
        <div class="section-header" style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border);">
            <div style="width:4px;height:24px;background:var(--primary);border-radius:2px;"></div>
            <h2 class="font-display" style="font-size:1.25rem;text-transform:uppercase;letter-spacing:0.05em;">LeetCode Programming Challenges</h2>
        </div>
        <div class="tasks-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;">
            ${TASKS.map(t => {
                const done = completedTasks.includes(t.title);
                if (done) {
                    return `<div class="task-card card p-4 flex flex-col justify-between opacity-60" style="min-height:220px;">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-check-circle" style="color:#ff2a2f;"></i>
                                <span class="text-xs uppercase font-bold" style="color:#ff2a2f;">Completed</span>
                            </div>
                            <h3 class="font-display mb-2 text-muted-foreground" style="font-size:1rem;">${t.title}</h3>
                        </div>
                        <div class="mt-auto">
                            <div style="width:100%;padding:0.6rem;border:1px solid rgba(255,42,47,0.2);border-radius:8px;text-align:center;color:#ff2a2f;font-weight:700;font-size:0.75rem;text-transform:uppercase;">Mission Done</div>
                        </div>
                    </div>`;
                }
                return render_tasks(t.title, t.coins, t.desc, t.diff);
            }).join('')}
        </div>
    </div>
`;
</script>

<!-- ===== LANGUAGE SELECTION MODAL ===== -->
<div id="langModalOverlay" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.85);
    backdrop-filter: blur(8px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 24px;
">
    <div style="
        width: 100%;
        max-width: 700px;
        background: #0d1117;
        border: 1px solid var(--primary);
        border-radius: 12px;
        overflow: hidden;
        animation: modalIn 0.3s cubic-bezier(0.23, 1, 0.32, 1);
    ">
        <div style="
            background: linear-gradient(to right, rgba(255,42,47,0.2), transparent);
            padding: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <div>
                <div style="font-size:0.7rem; font-weight:800; color:var(--primary); text-transform:uppercase; letter-spacing:0.15em; margin-bottom:4px;">Choose Language</div>
                <h3 id="langModalTaskName" style="font-family:'Roboto Mono',monospace; font-size:1rem; font-weight:700; color:#fff; text-transform:uppercase;"></h3>
            </div>
            <button onclick="closeLangModal()" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.2rem;">&#10005;</button>
        </div>
        <div style="padding: 28px;">
            <p style="color:#94a3b8; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:20px;">Select the programming language you want to use:</p>
            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:14px;">
                <!-- Python -->
                <button class="lang-choice-btn" onclick="selectLang('lab/codings/pythoni.php')" style="
                    background: rgba(49,112,143,0.1);
                    border: 1px solid rgba(49,112,143,0.3);
                    border-radius:10px;
                    padding:18px 12px;
                    cursor:pointer;
                    display:flex;
                    flex-direction:column;
                    align-items:center;
                    gap:10px;
                    transition:all 0.2s;
                    color:#fff;
                ">
                    <i class="fab fa-python" style="font-size:2.5rem; color:#3b8ab8;"></i>
                    <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">Python</span>
                </button>
                <!-- JavaScript -->
                <button class="lang-choice-btn" onclick="selectLang('lab/codings/js.php')" style="
                    background: rgba(247,223,30,0.05);
                    border: 1px solid rgba(247,223,30,0.2);
                    border-radius:10px;
                    padding:18px 12px;
                    cursor:pointer;
                    display:flex;
                    flex-direction:column;
                    align-items:center;
                    gap:10px;
                    transition:all 0.2s;
                    color:#fff;
                ">
                    <i class="fab fa-js-square" style="font-size:2.5rem; color:#f7df1e;"></i>
                    <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">JavaScript</span>
                </button>
                <!-- C++ -->
                <button class="lang-choice-btn" onclick="selectLang('lab/codings/cpp.php')" style="
                    background: rgba(0,85,164,0.08);
                    border: 1px solid rgba(0,85,164,0.25);
                    border-radius:10px;
                    padding:18px 12px;
                    cursor:pointer;
                    display:flex;
                    flex-direction:column;
                    align-items:center;
                    gap:10px;
                    transition:all 0.2s;
                    color:#fff;
                ">
                    <i class="fas fa-cogs" style="font-size:2.5rem; color:#6495ed;"></i>
                    <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">C++</span>
                </button>
                <!-- PHP -->
                <button class="lang-choice-btn" onclick="selectLang('lab/codings/php.php')" style="
                    background: rgba(119,123,180,0.08);
                    border: 1px solid rgba(119,123,180,0.25);
                    border-radius:10px;
                    padding:18px 12px;
                    cursor:pointer;
                    display:flex;
                    flex-direction:column;
                    align-items:center;
                    gap:10px;
                    transition:all 0.2s;
                    color:#fff;
                ">
                    <i class="fab fa-php" style="font-size:2.5rem; color:#777bb4;"></i>
                    <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">PHP</span>
                </button>
            </div>
            <p style="color:#555; font-size:0.7rem; text-align:center; margin-top:18px;">You can switch language anytime from your lab environment.</p>
        </div>
    </div>
</div>

<style>
@keyframes modalIn { from { opacity: 0; transform: scale(0.9) translateY(20px); } to { opacity:1; transform:scale(1) translateY(0); } }
.lang-choice-btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(255,42,47,0.2);
    border-color: var(--primary) !important;
    background: rgba(255,42,47,0.08) !important;
}
</style>

<script>
function openLangModal(taskTitle, taskCoins) {
    document.getElementById('langModalTaskName').textContent = decodeURIComponent(taskTitle);
    window._pendingTask = taskTitle;
    window._pendingCoins = taskCoins;
    const overlay = document.getElementById('langModalOverlay');
    overlay.style.display = 'flex';
}
function closeLangModal() {
    document.getElementById('langModalOverlay').style.display = 'none';
}
function selectLang(labUrl) {
    localStorage.setItem('tasksinfo', [decodeURIComponent(window._pendingTask), window._pendingCoins]);
    closeLangModal();
    window.location.href = labUrl;
}
// Close on overlay click
document.getElementById('langModalOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeLangModal();
});
</script>

<footer class="footer" style="text-align: center; padding: 2rem; color: var(--muted-foreground); border-top: 1px solid var(--border); margin-top: auto; font-size: 0.875rem;">
    <p>&copy; 2026 Secure Worldz Academy Ecosystem. All rights reserved.</p>
</footer>

<script>
// Heartbeat & Online Status
setInterval(() => {
    fetch('api/heartbeat.php').then(r => r.json()).catch(e => console.error("Heartbeat error:", e));
}, 60000);

function updateDateTime() {
    const timeEl = document.getElementById('current-time');
    if (!timeEl) return;
    
    const now = new Date();
    const indiaTime = new Intl.DateTimeFormat('en-IN', {
        timeZone: 'Asia/Kolkata',
        hour: '2-digit', minute: '2-digit', hour12: true
    }).format(now);
    timeEl.textContent = indiaTime;
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    
    const dayOfWeek = document.getElementById('day-of-week');
    if(dayOfWeek) dayOfWeek.textContent = days[now.getDay()];
    
    const dateE = now.getDate();
    let suffix = 'th';
    if (dateE === 1 || dateE === 21 || dateE === 31) suffix = 'st';
    else if (dateE === 2 || dateE === 22) suffix = 'nd';
    else if (dateE === 3 || dateE === 23) suffix = 'rd';
    
    const fullDate = document.getElementById('full-date');
    if(fullDate) fullDate.textContent = `${months[now.getMonth()]} ${dateE}${suffix}, ${now.getFullYear()}`;
}
updateDateTime();
setInterval(updateDateTime, 1000);

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('solve-btn')) {
        const taskTitle = event.target.getAttribute('data-task');
        const taskCoins = event.target.getAttribute('data-coins');
        openLangModal(taskTitle, taskCoins);
    }
});
</script>
</body>
</html>
