<?php
require_once 'api/auth_check.php';

$eagleCoins = $userCoins;
$rank = $userRank;
$totalUsers = $db->getTotalUsersCount();


$allUsersData = $db->getAllUsersData(['id', 'name', 'eagle_coins', 'Avatars'], 'eagle_coins', 'DESC', 20);


$uniqueUsers = [];
$seenIds = [];
if (is_array($allUsersData)) {
    foreach($allUsersData as $u) {
        if (!isset($seenIds[$u['id']])) {
            $uniqueUsers[] = $u;
            $seenIds[$u['id']] = true;
        }
    }
}
$topUsers = array_slice($uniqueUsers, 0, 5);
$currentUserId = $userId;

$prevCoins = $_SESSION['prev_eagle_coins'] ?? $eagleCoins;
$prevRank = $_SESSION['prev_rank'] ?? $rank;
$rankChange = $prevRank - $rank; 
$coinsChange = $eagleCoins - $prevCoins;

$_SESSION['prev_eagle_coins'] = $eagleCoins;
$_SESSION['prev_rank'] = $rank;


$courseKey = strtolower(preg_replace("/[^a-z0-9]/i", "", (string)($course ?? "")));
$courseAssignments = [];
if ($courseKey === "securex") {
    $courseAssignments = [
        [
            'id' => '1', 
            'title' => 'Secure web server', 
            'description' => 'Design and implement a robust web server with built-in security features including header hardening, rate limiting, and secure file handling to prevent directory traversal attacks.',
            'reward_coins' => 1500, 
            'due_date' => date('Y-m-d', strtotime('+7 days'))
        ],
        [
            'id' => '2', 
            'title' => 'Log security monitoring CLI tool', 
            'description' => 'Develop a user-friendly command-line interface tool capable of parsing system logs in real-time to detect anomalous activity, failed login attempts, and potential brute-force patterns.',
            'reward_coins' => 2000, 
            'due_date' => date('Y-m-d', strtotime('+14 days'))
        ],
        [
            'id' => '3', 
            'title' => 'Secure chat server', 
            'description' => 'Build a multi-user chat server that utilizes end-to-end encryption (AES/RSA) to ensure message confidentiality and integrity, featuring secure session management and authentication.',
            'reward_coins' => 1800, 
            'due_date' => date('Y-m-d', strtotime('+21 days'))
        ]
    ];
} else {
    $courseAssignments = [
        ['id' => '99', 'title' => 'Make portfolio website for you', 'description' => '', 'reward_coins' => 100, 'due_date' => date('Y-m-d', strtotime('+10 days'))],
        ['id' => '100', 'title' => 'Final Project Submission', 'description' => '', 'reward_coins' => 1000, 'due_date' => date('Y-m-d', strtotime('+90 days'))]
    ];
}

$waitingAssignments = $db->get_waiting_assign($userId) ?? [];
$submittedTitles = array_column($waitingAssignments, 'title');
$activeCount = count($courseAssignments);
$doneCount = 0;
foreach($courseAssignments as $index => $a) {
    if (in_array($a['title'], $submittedTitles)) {
        $courseAssignments[$index]['completed'] = true;
        $doneCount++;
    } else {
        $courseAssignments[$index]['completed'] = false;
    }
}
$completionRate = ($activeCount > 0) ? ($doneCount / $activeCount) * 100 : 0;

$maxCoins = $db->getMaxEagleCoins();
if ($maxCoins === 0) $maxCoins = $eagleCoins ?: 1;

$_SESSION['welcome_shown'] = true;
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
    <script src="api/includes/loading_resilience.js?v=20260822" defer></script>
    <script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
    <style>
        
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            overscroll-behavior: auto;
        }

        :root {
            --bg: #0d1015;
            --card: #1a1d24;
            --border: rgba(255, 255, 255, 0.1);
            --primary: #c0151a;
            --primary-alt: #e5191e;
            --success: #10b981;
            --warning: #f59e0b;
            --destructive: #ef4444;
            --muted: #2d3748;
            --muted-fg: #94a3b8;
            --fg: #f8fafc;
            --sidebar: #1a1d24;
            --radius: 0.625rem;
            --gap: 1.5rem;
            --sides: 1.5rem;
            --red: #e5191e;
            --red-glow: rgba(229, 25, 30, 0.3);
        }

        body {
            font-family: 'Roboto Mono', monospace;
            background: var(--bg);
            color: var(--fg);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px
        }

        ::-webkit-scrollbar-track {
            background: transparent
        }

        ::-webkit-scrollbar-thumb {
            background: var(--muted);
            border-radius: 3px
        }



        /* LAYOUT */
        .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr 340px;
            gap: var(--gap);
            min-height: 100vh;
            padding: var(--sides);
            background: var(--bg);
            align-items: start;
        }

        /* ===== LEFT SIDEBAR — matches lab.php / sidebar.php exactly ===== */
        .desktop-sidebar {
            display: flex;
            flex-direction: column;
            gap: var(--gap);
            width: 280px;
            align-self: start;
            position: sticky;
            top: var(--sides);
            font-family: 'Roboto Mono', monospace;
            max-height: calc(100vh - (var(--sides) * 2));
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(160, 160, 160, 0.45) transparent;
        }
        .desktop-sidebar::-webkit-scrollbar { width: 6px; }
        .desktop-sidebar::-webkit-scrollbar-track { background: transparent; }
        .desktop-sidebar::-webkit-scrollbar-thumb {
            background: rgba(160, 160, 160, 0.45);
            border-radius: 999px;
        }
        .desktop-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(180, 180, 180, 0.7);
        }
        /* Prevent flex-shrink from compressing cards so sidebar always scrolls correctly */
        .desktop-sidebar > * { flex-shrink: 0; }

        .desktop-main,
        .desktop-right-sidebar {
            display: flex;
            flex-direction: column;
            gap: var(--gap);
        }

        /* CARD */
        .card {
            background: var(--card) !important;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: rgba(229, 25, 30, 0.3);
            box-shadow: 0 4px 20px rgba(229, 25, 30, 0.1);
        }

        /* SIDEBAR */
        .profile-card {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            text-align: center;
        }

        .avatar-ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-alt));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #fff;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            position: relative;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-6px)
            }
        }

        .avatar-ring img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 1rem;
            font-weight: 600;
            color: var(--fg);
        }

        .profile-role {
            font-size: 0.75rem;
            color: var(--muted-fg);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .coins-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
            color: var(--warning);
            font-weight: 600;
        }

        .coins-badge i {
            animation: spin 3s linear infinite;
        }

        .nav-section {
            padding: 0 0.75rem;
            margin-bottom: 0.5rem;
        }

        .nav-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--muted-fg);
            letter-spacing: 0.1em;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.25rem;
        }

        .nav-item {
            display: flex !important;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem !important;
            border-radius: calc(var(--radius) - 2px);
            text-decoration: none;
            color: var(--fg);
            transition: all 0.2s;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            font-family: 'Roboto Mono', monospace;
        }

        .nav-item:hover {
            background: rgba(248, 250, 252, 0.05) !important;
            transform: translateX(4px);
        }

        .nav-item.active {
            background: var(--primary) !important;
            color: #fff !important;
        }

        .nav-icon {
            width: 1.2rem;
            height: 1.2rem;
            flex-shrink: 0;
            text-align: center;
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 1.5rem;
            transition: all 0.3s;
            cursor: default;
            position: relative;
            overflow: hidden;
        }
    

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            transition: transform 0.4s ease;
            transform: scaleX(0);
            transform-origin: left;
        }

        .stat-card.purple::before {
            background: linear-gradient(90deg, var(--primary), var(--primary-alt));
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, var(--success), #34d399);
        }

        .stat-card.yellow::before {
            background: linear-gradient(90deg, var(--warning), #fbbf24);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(229, 25, 30, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--muted-fg);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            margin-bottom: 0.25rem;
        }

        .stat-value.purple {
            color: var(--primary);
        }

        .stat-value.green {
            color: var(--success);
        }

        .stat-value.yellow {
            color: var(--warning);
        }

        .stat-change {
            font-size: 0.75rem;
            color: var(--muted-fg);
        }

        .stat-change .pos {
            color: var(--success);
        }

        .stat-change .neg {
            color: #f87171;
        }

        /* PROGRESS */
        .progress-bar-bg {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 4px;
            height: 8px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .progress-bar {
            height: 100%;
            border-radius: 4px;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .progress-bar.purple {
            background: linear-gradient(90deg, var(--primary), var(--primary-alt));
        }

        .progress-bar.green {
            background: linear-gradient(90deg, var(--success), #34d399);
        }

        /* WELCOME HEADER */
        .welcome-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .welcome-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .welcome-title span {
            background: linear-gradient(135deg, var(--primary), var(--primary-alt));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-sub {
            font-size: 0.85rem;
            color: var(--muted-fg);
            margin-top: 0.3rem;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        /* ASSIGNMENTS */
        .assign-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.2s;
        }

        .assign-check {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.7rem;
            transition: all 0.3s;
            cursor: pointer;
        }

        .assign-check.done {
            background: var(--success);
            border-color: var(--success);
            color: #fff;
        }

        .assign-title {
            flex: 1;
            font-size: 0.875rem;
        }

        .assign-title.done-text {
            text-decoration: line-through;
            color: var(--muted-fg);
        }

        .assign-due {
            font-size: 0.75rem;
            color: var(--muted-fg);
            white-space: nowrap;
        }

        /* RIGHT SIDEBAR */
        .section-header-inner {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted-fg);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header-inner a {
            font-size: 0.75rem;
            color: var(--primary);
            text-decoration: none;
            text-transform: none;
            letter-spacing: 0;
        }

        /* BUTTON */
        .pwz-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: calc(var(--radius)-2px);
            font-family: 'Roboto Mono', monospace;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s;
            text-decoration: none;
        }

        .pwz-btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .pwz-btn-secondary {
            background: var(--muted);
            color: var(--fg);
        }

        /* NOTIFICATION DOT */
        .notif-btn {
            position: relative;
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius)-2px);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 1rem;
            color: var(--muted-fg);
        }

        .notif-dot {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 10px;
            height: 10px;
            background: var(--destructive);
            border-radius: 50%;
            border: 2px solid var(--bg);
        }

        /* ANIMATIONS */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px)
            }
            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .anim-1 { animation: fadeInUp 0.4s ease 0.1s both }
        .anim-2 { animation: fadeInUp 0.4s ease 0.2s both }
        .anim-3 { animation: fadeInUp 0.4s ease 0.3s both }
        .anim-4 { animation: fadeInUp 0.4s ease 0.4s both }
        .anim-5 { animation: fadeInUp 0.4s ease 0.5s both }
        .anim-6 { animation: fadeInUp 0.4s ease 0.6s both }
        .anim-7 { animation: fadeInUp 0.4s ease 0.7s both }
        .anim-8 { animation: fadeInUp 0.4s ease 0.8s both }
        .anim-9 { animation: fadeInUp 0.4s ease 0.9s both }
        .anim-10 { animation: fadeInUp 0.4s ease 1.0s both }

        .ripple-container {
            position: relative;
            overflow: hidden;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: scale(0);
            animation: ripple-anim 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-anim {
            to {
                transform: scale(4);
                opacity: 0
            }
        }
        /* UTILS */
        .font-display { font-family: 'Space Grotesk', sans-serif; font-weight: 700; }
        .hidden { display: none !important; }
        .p-8 { padding: 2rem !important; }
        .p-4 { padding: 1rem !important; }
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 1rem !important; }
        .opacity-20 { opacity: 0.2; }
        .flex { display: flex; }
        .grid { display: grid; }
        .relative { position: relative; }
        .absolute { position: absolute; }
        .w-full { width: 100%; }
        .h-full { height: 100%; }
        .rounded-lg { border-radius: var(--radius); }
        .rounded-full { border-radius: 9999px; }
        .overflow-hidden { overflow: hidden; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 0.5rem; }
        .p-4 { padding: 1rem; }
        .mb-4 { margin-bottom: 1rem; }


        .overflow-hidden {
            overflow: hidden;
        }

        .overflow-y-auto {
            overflow-y: auto;
        }

        .overflow-clip {
            overflow: clip;
        }

        .object-contain {
            object-fit: contain;
        }

        .object-cover {
            object-fit: cover;
        }

        .rounded {
            border-radius: 0.375rem;
        }

        .rounded-lg {
            border-radius: var(--radius);
        }

        .rounded-md {
            border-radius: calc(var(--radius) - 2px);
        }

        .rounded-sm {
            border-radius: calc(var(--radius) - 4px);
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .border {
            border-width: 1px;
        }

        .border-2 {
            border-width: 2px;
        }

        .border-b {
            border-bottom-width: 1px;
        }

        .border-t {
            border-top-width: 1px;
        }

        .ring-2 {
            box-shadow: 0 0 0 2px var(--ring);
        }

        .ring-pop {
            box-shadow: 0 0 0 2px var(--pop);
        }

        .bg-background {
            background-color: var(--background);
        }

        .bg-foreground {
            background-color: var(--foreground);
        }

        .bg-primary {
            background-color: var(--primary);
        }

        .bg-secondary {
            background-color: var(--secondary);
        }

        .bg-muted {
            background-color: var(--muted);
        }

        .bg-accent {
            background-color: var(--accent);
        }

        .bg-card {
            background-color: var(--card);
        }

        .bg-success {
            background-color: var(--success);
        }

        .bg-warning {
            background-color: var(--warning);
        }

        .bg-destructive {
            background-color: var(--destructive);
        }

        .bg-sidebar {
            background-color: var(--sidebar);
        }

        .bg-sidebar-primary {
            background-color: var(--sidebar-primary);
        }

        .bg-sidebar-accent {
            background-color: var(--sidebar-accent);
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

        .text-secondary {
            color: var(--secondary);
        }

        .text-secondary-foreground {
            color: var(--secondary-foreground);
        }

        .text-muted {
            color: var(--muted);
        }

        .text-muted-foreground {
            color: var(--muted-fg);
        }

        .text-success {
            color: var(--success);
        }

        .text-warning {
            color: var(--warning);
        }

        .text-destructive {
            color: var(--destructive);
        }

        .text-sidebar-foreground {
            color: var(--sidebar-foreground);
        }

        .text-sidebar-primary {
            color: var(--sidebar-primary);
        }

        .text-sidebar-primary-foreground {
            color: var(--sidebar-primary-foreground);
        }

        .text-sidebar-accent-foreground {
            color: var(--sidebar-accent-foreground);
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

        .text-4xl {
            font-size: 2.25rem;
            line-height: 2.5rem;
        }

        .text-5xl {
            font-size: 3rem;
            line-height: 1;
        }

        .font-normal {
            font-weight: 400;
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

        .italic {
            font-style: italic;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .opacity-0 {
            opacity: 0;
        }

        .opacity-10 {
            opacity: 0.1;
        }

        .opacity-20 {
            opacity: 0.2;
        }

        .opacity-30 {
            opacity: 0.3;
        }

        .opacity-40 {
            opacity: 0.4;
        }

        .opacity-50 {
            opacity: 0.5;
        }

        .opacity-60 {
            opacity: 0.6;
        }

        .opacity-70 {
            opacity: 0.7;
        }

        .opacity-80 {
            opacity: 0.8;
        }

        .opacity-90 {
            opacity: 0.9;
        }

        .opacity-100 {
            opacity: 1;
        }

        .grayscale {
            filter: grayscale(100%);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .cursor-grab {
            cursor: grab;
        }

        .cursor-grabbing {
            cursor: grabbing;
        }

        .select-none {
            user-select: none;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .transition-colors {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        .transition-opacity {
            transition: opacity 0.3s ease;
        }

        .transition-transform {
            transition: transform 0.3s ease;
        }

        .duration-200 {
            transition-duration: 0.2s;
        }

        .duration-300 {
            transition-duration: 0.3s;
        }

        .duration-500 {
            transition-duration: 0.5s;
        }

        .ease-out {
            transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
        }

        .ease-in-out {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .group:hover .group-hover\:opacity-100 {
            opacity: 1 !important;
        }

        .group:hover .group-hover\:scale-105 {
            transform: scale(1.05);
        }

        .group:hover .group-hover\:brightness-110 {
            filter: brightness(1.1);
        }

        
        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-cols-12 {
            grid-template-columns: repeat(12, minmax(0, 1fr));
        }

        .col-span-1 {
            grid-column: span 1 / span 1;
        }

        .col-span-2 {
            grid-column: span 2 / span 2;
        }

        .col-span-3 {
            grid-column: span 3 / span 3;
        }

        .col-span-7 {
            grid-column: span 7 / span 7;
        }

        .gap-1 {
            gap: 0.25rem;
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

        .gap-10 {
            gap: 2.5rem;
        }

        .gap-gap {
            gap: var(--gap);
        }

        .p-0 {
            padding: 0;
        }

        .p-1 {
            padding: 0.25rem;
        }

        .p-2 {
            padding: 0.5rem;
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

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .px-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
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

        .py-8 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .py-10 {
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }

        .py-sides {
            padding-top: var(--sides);
            padding-bottom: var(--sides);
        }

        .px-sides {
            padding-left: var(--sides);
            padding-right: var(--sides);
        }

        .pt-1 {
            padding-top: 0.25rem;
        }

        .pt-2 {
            padding-top: 0.5rem;
        }

        .pt-4 {
            padding-top: 1rem;
        }

        .pb-1 {
            padding-bottom: 0.25rem;
        }

        .pb-4 {
            padding-bottom: 1rem;
        }

        .pl-2 {
            padding-left: 0.5rem;
        }

        .pl-3 {
            padding-left: 0.75rem;
        }

        .pl-4 {
            padding-left: 1rem;
        }

        .pr-1 {
            padding-right: 0.25rem;
        }

        .pr-2 {
            padding-right: 0.5rem;
        }

        .pr-3 {
            padding-right: 0.75rem;
        }

        .pr-4 {
            padding-right: 1rem;
        }

        .mt-1 {
            margin-top: 0.25rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-auto {
            margin-top: auto;
        }

        .mb-1 {
            margin-bottom: 0.25rem;
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

        .ml-auto {
            margin-left: auto;
        }

        .mr-1 {
            margin-right: 0.25rem;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .mr-3 {
            margin-right: 0.75rem;
        }

        .space-y-1 > * + * {
            margin-top: 0.25rem;
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

        .space-y-6 > * + * {
            margin-top: 1.5rem;
        }

        .flex-1 {
            flex: 1 1 0%;
        }

        .flex-col {
            flex-direction: column;
        }

        .flex-row {
            flex-direction: row;
        }

        .items-start {
            align-items: flex-start;
        }

        .items-center {
            align-items: center;
        }

        .items-baseline {
            align-items: baseline;
        }

        .items-stretch {
            align-items: stretch;
        }

        .justify-start {
            justify-content: flex-start;
        }

        .justify-center {
            justify-content: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .justify-end {
            justify-content: flex-end;
        }

        .min-w-0 {
            min-width: 0;
        }

        .max-w-xs {
            max-width: 20rem;
        }

        .max-w-sm {
            max-width: 24rem;
        }

        .max-w-md {
            max-width: 28rem;
        }

        .max-w-max {
            max-width: max-content;
        }

        .w-1\/4 {
            width: 25%;
        }

        .w-14 {
            width: 3.5rem;
        }

        .w-16 {
            width: 4rem;
        }

        .w-56 {
            width: 14rem;
        }

        .w-80 {
            width: 20rem;
        }

        .h-5 {
            height: 1.25rem;
        }

        .h-6 {
            height: 1.5rem;
        }

        .h-7 {
            height: 1.75rem;
        }

        .h-8 {
            height: 2rem;
        }

        .h-10 {
            height: 2.5rem;
        }

        .h-12 {
            height: 3rem;
        }

        .h-14 {
            height: 3.5rem;
        }

        .h-32 {
            height: 8rem;
        }

        .h-header-mobile {
            height: var(--header-mobile);
        }

        .size-3 {
            width: 0.75rem;
            height: 0.75rem;
        }

        .size-4 {
            width: 1rem;
            height: 1rem;
        }

        .size-5 {
            width: 1.25rem;
            height: 1.25rem;
        }

        .size-6 {
            width: 1.5rem;
            height: 1.5rem;
        }

        .size-7 {
            width: 1.75rem;
            height: 1.75rem;
        }

        .size-9 {
            width: 2.25rem;
            height: 2.25rem;
        }

        .size-10 {
            width: 2.5rem;
            height: 2.5rem;
        }

        .size-12 {
            width: 3rem;
            height: 3rem;
        }

        .size-14 {
            width: 3.5rem;
            height: 3.5rem;
        }

        .size-16 {
            width: 4rem;
            height: 4rem;
        }

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

        .button-xl {
            height: 3.5rem;
            padding: 0 2rem;
            font-size: 1rem;
        }

        .button-icon {
            width: 2.5rem;
            height: 2.5rem;
            padding: 0;
        }

        .input {
            width: 100%;
            height: 2.5rem;
            padding: 0 0.75rem;
            background-color: var(--input);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) - 2px);
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }

        .input:focus {
            outline: none;
            border-color: var(--ring);
        }

        .input::placeholder {
            color: var(--muted-foreground);
            opacity: 0.7;
        }

        .separator {
            width: 100%;
            height: 1px;
            background-color: var(--border);
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

        .bullet-warning {
            background-color: var(--warning);
        }

        .bullet-destructive {
            background-color: var(--destructive);
        }

        .bullet-sm {
            width: 0.375rem;
            height: 0.375rem;
        }

        .sheet {
            position: fixed;
            z-index: 50;
            background-color: var(--background);
            transition: transform 0.3s ease;
        }

        .sheet-bottom {
            bottom: 0;
            left: 0;
            right: 0;
            transform: translateY(100%);
        }

        .sheet-bottom.open {
            transform: translateY(0);
        }

        .sheet-right {
            top: 0;
            right: 0;
            bottom: 0;
            transform: translateX(100%);
        }

        .sheet-right.open {
            transform: translateX(0);
        }

        .tooltip {
            position: relative;
        }

        .tooltip-content {
            position: absolute;
            z-index: 50;
            padding: 0.5rem 0.75rem;
            background-color: var(--popover);
            color: var(--popover-foreground);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) - 2px);
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .tooltip:hover .tooltip-content {
            opacity: 1;
        }

        .tabs-list {
            display: flex;
            gap: 0.5rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 0.5rem;
        }

        .tabs-trigger {
            padding: 0.5rem 1rem;
            background: transparent;
            border: none;
            color: var(--muted-foreground);
            font-family: inherit;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: color 0.2s;
            position: relative;
        }

        .tabs-trigger:hover {
            color: var(--foreground);
        }

        .tabs-trigger.active {
            color: var(--foreground);
        }

        .tabs-trigger.active::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--primary);
        }

        
        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
        }

        .nav-title .bullet {
            width: 0.375rem;
            height: 0.375rem;
        }

        .nav-title span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--muted-foreground);
        }



        
        @keyframes marquee-up {
            0% {
                transform: translate3d(0, 0, 0);
            }
            100% {
                transform: translate3d(0, -50%, 0);
            }
        }

        @keyframes marquee-down {
            0% {
                transform: translate3d(0, -50%, 0);
            }
            100% {
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes marquee-pulse {
            0%, 100% {
                opacity: 0.15;
                transform: scale(1) translateY(0);
            }
            25% {
                opacity: 0.4;
                transform: scale(1.02) translateY(-1px);
            }
            50% {
                opacity: 0.8;
                transform: scale(1.05) translateY(-2px);
            }
            75% {
                opacity: 0.6;
                transform: scale(1.02) translateY(-1px);
            }
        }

        .animate-marquee-up {
            animation: marquee-up 6s ease-in-out infinite;
        }

        .animate-marquee-down {
            animation: marquee-down 6s ease-in-out infinite;
        }

        .animate-marquee-pulse {
            animation: marquee-pulse 3s ease-in-out infinite;
        }

        .group:hover .animate-marquee-pulse {
            animation-play-state: paused;
        }

        .group:hover .animate-marquee-up,
        .group:hover .animate-marquee-down {
            animation-play-state: paused;
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

        
        .tv-noise {
            position: absolute;
            inset: 0;
            background: 
                repeating-linear-gradient(
                    0deg,
                    rgba(0, 0, 0, 0.1) 0px,
                    rgba(0, 0, 0, 0.1) 1px,
                    transparent 1px,
                    transparent 2px
                ),
                repeating-linear-gradient(
                    90deg,
                    rgba(0, 0, 0, 0.1) 0px,
                    rgba(0, 0, 0, 0.1) 1px,
                    transparent 1px,
                    transparent 2px
                );
            opacity: 0.3;
            pointer-events: none;
            z-index: 10;
            animation: tvNoise 0.1s infinite;
        }

        @keyframes tvNoise {
            0%, 100% { background-position: 0 0; }
            10% { background-position: -5% -10%; }
            20% { background-position: -15% 5%; }
            30% { background-position: 7% -25%; }
            40% { background-position: 20% 25%; }
            50% { background-position: -25% 10%; }
            60% { background-position: 15% 5%; }
            70% { background-position: 0 15%; }
            80% { background-position: 25% 35%; }
            90% { background-position: -10% 10%; }
        }

        
        .chart-container {
            position: relative;
            width: 100%;
        }

        .chart-grid {
            stroke: var(--muted-foreground);
            stroke-opacity: 0.3;
            stroke-width: 2;
            stroke-dasharray: 8 8;
        }

        .chart-axis {
            stroke: var(--muted-foreground);
            stroke-width: 1.5;
        }

        .chart-area {
            fill-opacity: 0.4;
            stroke-width: 2;
        }

        .chart-tooltip {
            position: absolute;
            background-color: var(--popover);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) - 2px);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            pointer-events: none;
            z-index: 50;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .chart-tooltip::before {
            content: '';
            position: absolute;
            top: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 8px;
            background-color: var(--popover);
            border-left: 1px solid var(--border);
            border-top: 1px solid var(--border);
            transform: rotate(45deg);
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
        

#notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            transition: all 0.3s ease;
            animation: fadeIn 0.3s ease-out;
        }

        .notification-item:hover {
            background-color: var(--accent);
            transform: translateX(2px);
        }

        #view-all-container {
            border-top: 1px solid var(--border);
            padding-top: 1rem;
        }

        #view-all-notifications:hover {
            background-color: var(--accent);
        }




        
        

        
        

        

.desktop-container .desktop-sidebar {
    height: fit-content !important;
    min-height: 0 !important;
    align-self: start !important;
    position: sticky !important;
    top: var(--sides) !important;
    max-height: calc(100vh - (var(--sides) * 2)) !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    scrollbar-width: thin;
    scrollbar-color: rgba(160, 160, 160, 0.45) transparent;
}

.desktop-container .desktop-sidebar::-webkit-scrollbar {
    width: 6px;
}

.desktop-container .desktop-sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.desktop-container .desktop-sidebar::-webkit-scrollbar-thumb {
    background: rgba(160, 160, 160, 0.45);
    border-radius: 999px;
}

.desktop-container .desktop-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(180, 180, 180, 0.7);
}

/* Assignments List with Boxes */
.assignments-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    margin-top: 0.5rem;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--fg);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title::before {
    content: '';
    width: 4px;
    height: 1.2rem;
    background: var(--primary);
    border-radius: 2px;
}

.assignments-list {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 1024px) {
    .assignments-list {
        max-height: min(340px, calc(100vh - 430px));
        min-height: 0;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 0.25rem;
        scrollbar-width: thin;
        scrollbar-color: rgba(160, 160, 160, 0.45) transparent;
    }

    .assignments-list::-webkit-scrollbar { width: 6px; }
    .assignments-list::-webkit-scrollbar-track { background: transparent; }
    .assignments-list::-webkit-scrollbar-thumb {
        background: rgba(160, 160, 160, 0.45);
        border-radius: 999px;
    }
    .assignments-list::-webkit-scrollbar-thumb:hover {
        background: rgba(180, 180, 180, 0.7);
    }
}

.assignment-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.assignment-card:hover {
    border-color: rgba(229, 25, 30, 0.4);
    transform: translateX(6px);
    box-shadow: -4px 0 0 var(--primary), 0 10px 30px rgba(0, 0, 0, 0.2);
}

.assignment-info {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.assignment-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: var(--primary);
    border: 1px solid var(--border);
    transition: all 0.3s;
}

.assignment-icon.completed {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border-color: rgba(16, 185, 129, 0.2);
}

.assignment-title {
    font-weight: 600;
    font-size: 1rem;
    color: var(--fg);
    margin-bottom: 0.25rem;
    cursor: pointer;
    transition: color 0.2s;
}

.assignment-title:hover {
    color: var(--primary);
}

.assignment-desc {
    font-size: 0.85rem;
    color: var(--muted-fg);
    margin-bottom: 0.75rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.03);
    border-left: 2px solid var(--primary);
    border-radius: 0 4px 4px 0;
    display: none;
    line-height: 1.5;
}

.assignment-meta {
    font-size: 0.75rem;
    color: var(--muted-fg);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dot-sep {
    width: 3px;
    height: 3px;
    background: var(--muted-fg);
    border-radius: 50%;
    opacity: 0.5;
}

.status-badge {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    border: 1px solid transparent;
}

.status-badge.pending {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border-color: rgba(245, 158, 11, 0.2);
}

.status-badge.completed {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border-color: rgba(16, 185, 129, 0.2);
}

/* Hall of Fame Style */
.card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.02);
}

.card-header span {
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--fg);
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.leaderboard-mini {
    padding: 0.5rem;
}

.leader-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: var(--radius);
    transition: all 0.2s;
    margin-bottom: 0.25rem;
}

.leader-item:hover {
    background: rgba(255, 255, 255, 0.03);
}

.leader-item.active {
    background: rgba(229, 25, 30, 0.05);
    border: 1px solid rgba(229, 25, 30, 0.1);
}

.leader-rank {
    width: 24px;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--muted-fg);
}

.leader-item:nth-child(1) .leader-rank { color: #ffd700; }
.leader-item:nth-child(2) .leader-rank { color: #c0c0c0; }
.leader-item:nth-child(3) .leader-rank { color: #cd7f32; }

.leader-avatar {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    overflow: hidden;
    background: var(--muted);
    border: 1px solid var(--border);
}

.leader-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.leader-info {
    flex: 1;
    min-width: 0;
}

.leader-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--fg);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.leader-coins {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--warning);
}

/* Rank Card Sidebar */
.rank-card {
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.rank-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), var(--primary-alt));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #fff;
    box-shadow: 0 4px 15px var(--red-glow);
}

.rank-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted-fg);
    margin-bottom: 0.2rem;
}

.rank-value {
    font-size: 1.25rem;
    font-weight: 800;
    font-family: 'Space Grotesk', sans-serif;
    color: var(--fg);
}

.pwz-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.2s;
}

.pwz-link:hover {
    color: var(--primary-alt);
    text-decoration: underline;
}

.pwz-btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
}

.pwz-btn-icon:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px var(--red-glow);
}

.performance-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(13, 16, 21, 0.4);
    backdrop-filter: blur(2px);
    text-align: center;
}

/* RESPONSIVENESS (Handled in app-theme-overrides.css) */


</style>
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
            }, 600);
        });
    </script>
<div class="desktop-container">
        <!-- Left Sidebar — same structure as lab.php -->
        <?php include 'sidebar.php'; ?>

    <main class="desktop-main">
        <!-- Welcome Header -->
        <div class="card anim-1">
            <div class="welcome-header">
                <div>
                    <div class="welcome-title">Hi, <span><?php echo htmlspecialchars($userName); ?></span> 👋</div>
                    <div class="welcome-sub">System status: <span style="color:var(--success)">Secure & Operational</span></div>
                </div>
                <div class="header-actions">
                    <a href="eaglone_shop.php" class="pwz-btn pwz-btn-primary ripple-container" style="border-radius: 50px;">
                        <i class="fas fa-shopping-cart"></i> Shop EC
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card purple anim-2 ripple-container">
                <div class="stat-label">Current Rank</div>
                <div class="stat-value purple">#<span id="stat-rank"><?php echo $rank; ?></span></div>
                <div class="stat-change">
                    <span class="<?= $rankChange >= 0 ? 'pos' : 'neg' ?>">
                        <i class="fas fa-caret-<?= $rankChange >= 0 ? 'up' : 'down' ?>"></i> 
                        <?= abs($rankChange) ?>
                    </span> positions this week
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-bar purple" id="pb-rank" style="width: <?= ($rank > 0) ? (100 - min(100, ($rank / ($totalUsers ?: 1)) * 100)) : 0 ?>%"></div>
                </div>
            </div>
            <div class="stat-card green anim-3 ripple-container">
                <div class="stat-label">Projects</div>
                <div class="stat-value green"><span id="stat-assign"><?php echo $doneCount; ?></span> / <?= $activeCount ?></div>
                <div class="stat-change">
                    <span class="pos"><i class="fas fa-check-circle"></i> <?= $doneCount ?></span> completed
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-bar green" id="pb-assign" style="width: <?= $completionRate ?>%"></div>
                </div>
            </div>
            <div class="stat-card yellow anim-4 ripple-container">
                <div class="stat-label">Eagle Coins</div>
                <div class="stat-value yellow"><span id="stat-coins"><?php echo number_format($eagleCoins); ?></span></div>
                <div class="stat-change">
                    <span class="<?= $coinsChange >= 0 ? 'pos' : 'neg' ?>"><i class="fas fa-arrow-<?= $coinsChange >= 0 ? 'up' : 'down' ?>"></i> <?= number_format(abs($coinsChange)) ?></span> from last login
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-bar yellow" id="pb-coins" style="width: <?= ($maxCoins > 0) ? ($eagleCoins / $maxCoins) * 100 : 0 ?>%; background:var(--warning)"></div>
                </div>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="assignments-header">
            <h3 class="section-title"><i class="fas fa-layer-group" style="color:var(--primary)"></i> Active Projects</h3>
            <a href="assignment.php" class="pwz-link">Manage Projects <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="assignments-list">
            <?php 
            if (!empty($courseAssignments)): 
                foreach($courseAssignments as $index => $row): 
                    $assignCount = $index + 1;
                    $isCompleted = $row['completed'];
            ?>
            <div class="assignment-card anim-<?= $assignCount + 4 ?>">
                <div class="assignment-info">
                    <div class="assignment-icon <?= $isCompleted ? 'completed' : '' ?>">
                        <i class="fas <?= $isCompleted ? 'fa-check' : 'fa-code' ?>"></i>
                    </div>
                    <div>
                        <div class="assignment-title" onclick="toggleAssigndesc(this)"><?= htmlspecialchars($row['title']) ?></div>
                        <?php if (!empty($row['description'])): ?>
                            <div class="assignment-desc"><?= htmlspecialchars($row['description']) ?></div>
                        <?php endif; ?>
                        <div class="assignment-meta">
                            <i class="far fa-clock"></i> <?= $row['due_date'] ? date('M d, Y', strtotime($row['due_date'])) : 'No Deadline' ?>
                            <span class="dot-sep"></span>
                            <i class="fas fa-coins"></i> <?= number_format($row['reward_coins']) ?> EC
                        </div>
                    </div>
                </div>
                <div class="assignment-status">
                    <?php if($isCompleted): ?>
                        <span class="status-badge completed">Completed</span>
                    <?php else: ?>
                        <span class="status-badge pending">Pending</span>
                        <a href="assignment.php" class="pwz-btn-icon ripple-container"><i class="fas fa-play"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php 
                endforeach; 
            else: 
            ?>
            <div class="card p-8 text-center text-muted-foreground">
                <i class="fas fa-clipboard-list fa-3x mb-4 opacity-20"></i>
                <p>No active projects found. Check back later!</p>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Right Sidebar -->
    <aside class="desktop-right-sidebar">
        <!-- Rank & Coins Card (Mini) -->
        <div class="card anim-1">
            <div class="rank-card">
                <div class="rank-icon">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <div class="rank-label">Global Standing</div>
                    <div class="rank-value">#<?= $rank ?></div>
                </div>
            </div>
        </div>

        <!-- Mini Leaderboard -->
        <div class="card anim-2">
            <div class="card-header">
                <span><i class="fas fa-trophy" style="color:var(--warning)"></i> Hall of Fame</span>
                <a href="leaderboard.php" class="pwz-link text-xs">Full Rank</a>
            </div>
            <div class="leaderboard-mini">
                <?php foreach($topUsers as $index => $user): ?>
                <div class="leader-item <?= $user['id'] == $currentUserId ? 'active' : '' ?>">
                    <div class="leader-rank"><?= $index + 1 ?></div>
                    <div class="leader-avatar">
                        <?php 
                        $avatars = json_decode($user['Avatars'] ?: '[]', true);
                        $firstAvatar = $avatars[0] ?? '';
                        $avatarPath = $firstAvatar ? 'eagleshop/' . $firstAvatar . '.png' : '';
                        
                        
                        if (!$avatarPath) {
                            $avatarPath = 'images/eaglone/eaglone.png';
                        }
                        ?>
                            <img src="<?= $avatarPath ?>" alt="Avatar"> 
                    </div>
                    <div class="leader-info">
                        <div class="leader-name"><?= htmlspecialchars($user['name']) ?></div>
                    </div>
                    <div class="leader-coins"><?= number_format($user['eagle_coins']) ?> EC</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

       
    </aside>
    </div>

    <script>

        // --- Ripple Effects ---
        document.querySelectorAll('.ripple-container').forEach(el => {
            el.addEventListener('mousedown', function(e) {
                const rect = el.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const ripple = document.createElement('span');
                ripple.className = 'ripple';
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;

                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // --- Notification System ---
        const notifManager = {
            notifications: [],
            maxNotifs: 20,

            container() { return document.getElementById('notif-feed'); },
            count() { return document.getElementById('notif-count'); },

            add(msg, type = 'info') {
                this.notifications.unshift({ msg, type, time: new Date() });
                if (this.notifications.length > this.maxNotifs) this.notifications.pop();
                this.render();
            },

            render() {
                const container = this.container();
                if (!container) return;

                if (this.notifications.length === 0) {
                    container.innerHTML = `
                        <div class="empty-notif">
                            <i class="fas fa-satellite-dish fa-spin mb-2" style="color:var(--primary)"></i>
                            <p>Syncing secure stream...</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = this.notifications.map(n => `
                    <div class="notif-item ${n.type}">
                        <div class="notif-indicator"></div>
                        <div class="notif-content">${n.msg}</div>
                        <div class="notif-time">${this.formatTime(n.time)}</div>
                    </div>
                `).join('');

                const count = this.count();
                if (count) count.textContent = this.notifications.length;
            },

            formatTime(date) {
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        };

        // --- Performance Analytics ---
        function initPerformance() {
            const ctx = document.getElementById('performanceChart');
            if (!ctx) return;

            const eagleCoins = <?= $eagleCoins ?>;
            const coinDisplay = document.querySelector('.performance-overlay .text-2xl');

            let start = 0;
            const duration = 1500;
            const step = (eagleCoins / (duration / 16));

            const timer = setInterval(() => {
                start += step;
                if (start >= eagleCoins) {
                    start = eagleCoins;
                    clearInterval(timer);
                }
                if (coinDisplay) coinDisplay.textContent = Math.floor(start).toLocaleString();
            }, 16);
        }

        // --- Init ---
        document.addEventListener('DOMContentLoaded', () => {
            initPerformance();

            setTimeout(() => {
                notifManager.add('Secure connection established.', 'success');
            }, 500);

            setTimeout(() => {
                notifManager.add('Weekly leaderboard refreshed.', 'info');
            }, 1500);

            <?php if ($rankChange > 0): ?>
            setTimeout(() => {
                notifManager.add('Performance Alert: Rank improved to #<?= $rank ?>!', 'success');
            }, 2500);
            <?php endif; ?>

            setTimeout(() => {
                document.body.classList.add('loaded');
            }, 100);
        });

        // Global function for other parts of the system
        window.pwzNotify = (msg, type) => notifManager.add(msg, type);

        function toggleAssigndesc(el) {
            const desc = el.nextElementSibling;
            if (desc && desc.classList.contains('assignment-desc')) {
                const isVisible = window.getComputedStyle(desc).display !== 'none';
                desc.style.display = isVisible ? 'none' : 'block';

                // Add a micro-animation effect
                if (!isVisible) {
                    desc.animate([
                        { opacity: 0, transform: 'translateY(-10px)' },
                        { opacity: 1, transform: 'translateY(0)' }
                    ], { duration: 250, easing: 'ease-out' });
                }
            }
        }
    </script>
</body>
</html>
