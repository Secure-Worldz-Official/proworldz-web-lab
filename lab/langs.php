<?php
// Secure session cookies
if (session_status() === PHP_SESSION_NONE) {
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $cookieParams['path'] ?? '/',
        'domain' => $cookieParams['domain'] ?? '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}
if (isset($_SESSION['id'])) {
    if (!class_exists('DBconfig')) {
        require_once '../api/dbconfig.php';
    }
    $check_db = new DBconfig();
    $user_access = $check_db->getFieldbyId($_SESSION['id'], 'access');
    if ($user_access != 'true') {
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"><style>body{margin:0;padding:0;background:#000;display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:\'Space Grotesk\',sans-serif;color:#fff;overflow:hidden}.overlay{position:fixed;inset:0;background:rgba(0,0,0,0.9);z-index:9999;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(15px)}.modal{background:#000000;border:1px solid rgba(255,0,0,0.3);padding:3rem;border-radius:20px;text-align:center;box-shadow:0 0 50px rgba(255,0,0,0.15);max-width:400px;width:90%;animation:modalEntry 0.5s cubic-bezier(0.4,0,0.2,1)}@keyframes modalEntry{from{opacity:0;transform:scale(0.9) translateY(20px)}to{opacity:1;transform:scale(1) translateY(0)}}.icon{color:#ff3333;font-size:4rem;margin-bottom:1.5rem;filter:drop-shadow(0 0 10px rgba(139, 12, 16,0.4))}h2{font-size:1.75rem;margin:0 0 1rem 0;letter-spacing:-0.02em}p{color:#a0a0a0;font-size:1rem;line-height:1.5;margin:0 0 2rem 0}.loader{width:40px;height:40px;border:3px solid rgba(139, 12, 16,0.1);border-top-color:#ff3333;border-radius:50%;animation:spin 1s linear infinite;margin:0 auto}@keyframes spin{to{transform:rotate(360deg)}}</style></head><body><div class="overlay"><div class="modal"><div class="icon"><i class="fas fa-shield-halved"></i></div><h2>Access Restricted</h2><p>Pay to get access to proworldz lab</p><div class="loader"></div></div></div><script>setTimeout(()=>{window.location.href="../logout.php"},3500)</script></body></html>';
        exit();
    }
}

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programming Labs | ProWorldz</title>
  <link rel="icon" type="image/webp" href="../image.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --header-mobile: 3.8rem;
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

        .grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
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

        .button-default:hover {
            background-color: color-mix(in srgb, var(--primary) 90%, black);
        }

        .button-secondary {
            background-color: var(--secondary);
            color: var(--secondary-foreground);
            border-color: var(--border);
        }

        .button-secondary:hover {
            background-color: color-mix(in srgb, var(--secondary) 90%, black);
        }

        .button-ghost {
            background-color: transparent;
            color: currentColor;
        }

        .button-ghost:hover {
            background-color: var(--accent);
        }

        .button-sm {
            height: 2rem;
            padding: 0 0.75rem;
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

        .nav-title span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--muted-foreground);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: calc(var(--radius) - 2px);
            text-decoration: none;
            color: var(--sidebar-foreground);
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }

        .nav-item:hover {
            background-color: var(--sidebar-accent);
        }

        .nav-item.active {
            background-color: var(--sidebar-primary);
            color: var(--sidebar-primary-foreground);
        }

        .nav-icon {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
        }

        .nav-label {
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slideUp {
            animation: slideUp 0.3s ease-out;
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

        
        .lab-header {
            background: linear-gradient(135deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
            border-bottom: 1px solid var(--border);
            margin-bottom: 2rem;
        }

        .lab-hero {
            padding: 3rem 2rem;
        }

        .lab-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, #e5191e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .lab-hero p {
            font-size: 1.1rem;
            color: var(--muted-foreground);
        }

        .lab-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin: 2rem 0;
        }

        @media (max-width: 1024px) {
            .lab-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .lab-grid {
                grid-template-columns: 1fr;
            }
        }

        .lab-card {
            background: linear-gradient(135deg, var(--card) 0%, rgba(8, 8, 8, 0.8) 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .lab-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), #e5191e);
        }

        .lab-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.2);
        }

        .lab-card-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, #e5191e 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2.5rem;
            color: white;
        }

        .lab-card-content h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--foreground);
            font-family: 'Rebels', monospace;
        }

        .lab-card-content p {
            color: var(--muted-foreground);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .back-btn-container {
            margin-bottom: 1.5rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--secondary);
            color: var(--secondary-foreground);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) - 2px);
            text-decoration: none;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: color-mix(in srgb, var(--secondary) 90%, black);
            transform: translateY(-2px);
        }

        .notification-btn {
            position: relative;
            width: 48px;
            height: 48px;
            border-radius: calc(var(--radius) - 2px);
            background: var(--accent);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notification-btn:hover {
            background: rgba(99, 102, 241, 0.1);
            border-color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--destructive);
            color: white;
            font-size: 0.75rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .profile-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary) 0%, #e5191e 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            font-size: 1.1rem;
        }

        .profile-info h4 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
            color: var(--foreground);
        }

        .profile-info p {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        @media (max-width: 768px) {
            .desktop-container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .desktop-sidebar {
                display: none;
            }

            .lab-hero h1 {
                font-size: 2rem;
            }

            .lab-hero p {
                font-size: 1rem;
            }

            .lab-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="desktop-container">

        <div class="desktop-main">
            
            <div class="card lab-header">
                <div class="lab-hero">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="font-display">Coding Language Labs</h1>
                            <p>Interactive programming environments for learning and practice</p>
                        </div>

                    </div>
                    
                    <div class="back-btn-container">
                        <a href="../lab.php" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to Lab</span>
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="lab-grid">
    <div class="lab-card animate-fadeIn">
        <div class="lab-card-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/5968/5968350.png" alt="Python" style="width: 60px; height: 60px; object-fit: contain;" loading="lazy">
        </div>
        <div class="lab-card-content">
            <h3>Python Lab</h3>
            <p>Write, run and debug Python code in our interactive environment. Perfect for beginners and advanced developers alike.</p>
            <a class="button button-lg button-default w-full" href="codings/pythoni.php" style="text-decoration:none">
                Go to Python Lab
            </a>
        </div>
    </div>

    <div class="lab-card animate-fadeIn" style="animation-delay: 0.1s">
        <div class="lab-card-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/6132/6132222.png" alt="C++" style="width: 60px; height: 60px; object-fit: contain;" loading="lazy">
        </div>
        <div class="lab-card-content">
            <h3>C++ Lab</h3>
            <p>Compile and execute C++ programs with our dedicated lab. Supports C++11, C++14, C++17 and C++20 standards.</p>
            <a class="button button-lg button-default w-full" href="codings/cpp.php" style="text-decoration:none">
                Go to C++ Lab
            </a>
        </div>
    </div>

    <div class="lab-card animate-fadeIn" style="animation-delay: 0.2s">
        <div class="lab-card-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/5968/5968332.png" alt="PHP" style="width: 60px; height: 60px; object-fit: contain;" loading="lazy">
        </div>
        <div class="lab-card-content">
            <h3>PHP Lab</h3>
            <p>Compile and execute PHP scripts with our dedicated lab. Supports modern PHP versions with fast and secure execution.</p>
            <a class="button button-lg button-default w-full" href="codings/php.php" style="text-decoration:none">
                Go to PHP Lab
            </a>
        </div>
    </div>

    <div class="lab-card animate-fadeIn" style="animation-delay: 0.3s">
        <div class="lab-card-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/5968/5968292.png" alt="JavaScript" style="width: 60px; height: 60px; object-fit: contain;" loading="lazy">
        </div>
        <div class="lab-card-content">
            <h3>JS Lab</h3>
            <p>Compile and execute JavaScript programs with our dedicated lab. Supports modern Node.js runtime for server-side execution.</p>
            <a class="button button-lg button-default w-full" href="codings/js.php" style="text-decoration:none">
                Go to JS Lab
            </a>
        </div>
    </div>
</div>
        </div>
    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function() {

            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (!this.classList.contains('disabled')) {
                        navItems.forEach(i => i.classList.remove('active'));
                        this.classList.add('active');
                        e.preventDefault();
                    }
                });
            });


            const notificationBtn = document.getElementById('notificationBtn');
            if (notificationBtn) {
                notificationBtn.addEventListener('click', function() {
                    const badge = this.querySelector('.notification-badge');
                    badge.style.display = 'none';
                    alert('You have 3 notifications:\n1. Assignment due tomorrow\n2. New grade posted\n3. Class schedule updated');
                });
            }


            const labCards = document.querySelectorAll('.lab-card');
            labCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 15px 35px rgba(99, 102, 241, 0.3)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 10px 30px rgba(99, 102, 241, 0.2)';
                });
            });


            const buttons = document.querySelectorAll('.button');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });


            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!entry.target.classList.contains('animate-fadeIn')) {
                            entry.target.classList.add('animate-fadeIn');
                        }
                    }
                });
            }, observerOptions);


            const animatedElements = document.querySelectorAll('.animate-fadeIn, .lab-card');
            animatedElements.forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>
