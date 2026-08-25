<?php
require_once 'api/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us | Secure Worldz Academy</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ===== CSS RESET & BASE ===== */
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
    background-color: #0d1015;
    color: #f8fafc;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    min-height: 100vh;
}

/* ===== CUSTOM FONTS ===== */
@font-face {
    font-family: "Rebels";
    src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}

/* ===== CUSTOM PROPERTIES (CSS Variables) ===== */
:root {
    --radius: 0.625rem;
    --background: #0d1015;
    --foreground: #f8fafc;
    --card: #1a1d24;
    --card-foreground: #f8fafc;
    --popover: #1a1d24;
    --popover-foreground: #f8fafc;
    --primary: #6366f1;
    --primary-light: #8183f4;
    --primary-foreground: #ffffff;
    --secondary: #2d3748;
    --secondary-foreground: #f8fafc;
    --muted: #2d3748;
    --muted-foreground: #94a3b8;
    --accent: rgba(248, 250, 252, 0.05);
    --accent-foreground: #f8fafc;
    --border: rgba(255, 255, 255, 0.1);
    --input: rgba(255, 255, 255, 0.15);
    --ring: rgba(148, 163, 184, 0.5);
    
    --success: #10b981;
    --destructive: #ef4444;
    --warning: #f59e0b;
    
    --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    --gradient-subtle: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(129, 131, 244, 0.1) 100%);
    --gradient-dark: linear-gradient(135deg, var(--background) 0%, var(--card) 100%);
    
    --sidebar: #1a1d24;
    --sidebar-foreground: #f8fafc;
    --sidebar-primary: #6366f1;
    --sidebar-primary-foreground: #ffffff;
    --sidebar-accent: rgba(248, 250, 252, 0.05);
    --sidebar-accent-foreground: #f8fafc;
    --sidebar-border: rgba(255, 255, 255, 0.1);
    --sidebar-ring: rgba(148, 163, 184, 0.5);
    
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* ===== DESKTOP LAYOUT ===== */
.desktop-container {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.5rem;
    min-height: 100vh;
    padding: 1.5rem;
    background-color: var(--background);
}

/* ===== SIDEBAR STYLES ===== */
.desktop-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.profile-card-section {
    background: transparent;
    border: none;
    padding: 0;
    margin-bottom: 0.5rem;
}

.card {
    background-color: var(--card);
    border-radius: 0.625rem;
    border: 1px solid var(--border);
    overflow: hidden;
}

.nav-section {
    margin-bottom: 1.5rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 0.375rem;
    text-decoration: none;
    color: var(--muted-foreground);
    transition: all 0.2s;
    margin-bottom: 0.25rem;
    cursor: pointer;
}

.nav-item:hover {
    background-color: rgba(255, 255, 255, 0.05);
    color: white;
}

.nav-item.active {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.nav-item.disabled {
    opacity: 0.5;
    cursor: not-allowed;
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

.desktop-main {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    overflow-y: auto;
    max-height: calc(100vh - 3rem);
    padding-right: 0.5rem;
}

.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--sidebar-border);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.logo {
    font-family: 'Rebels', monospace;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--foreground);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.logo span {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.logo::before {
    content: '';
    display: block;
    width: 6px;
    height: 6px;
    background: var(--primary);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.menu {
    list-style: none;
    padding: 1.5rem;
}

.menu li {
    margin-bottom: 0.5rem;
}

.menu li a {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    border-radius: calc(var(--radius) - 2px);
    color: var(--sidebar-foreground);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.menu li a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
    transition: left 0.5s;
}

.menu li a:hover::before {
    left: 100%;
}

.menu li a i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.menu li a:hover,
.menu li.active a {
    background-color: var(--sidebar-accent);
    color: var(--sidebar-primary);
    border-left: 3px solid var(--sidebar-primary);
}

.menu li a:hover i,
.menu li.active a i {
    color: var(--sidebar-primary);
}

.close-btn {
    display: none;
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    color: var(--muted-foreground);
    font-size: 1.25rem;
    cursor: pointer;
    transition: color 0.3s;
}

.close-btn:hover {
    color: var(--foreground);
}

/* ===== MAIN CONTENT ===== */
.main {
    margin-left: 260px;
    padding: 2rem;
    width: calc(100% - 260px);
    background: var(--background);
    position: relative;
}

.main::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--gradient-primary);
    opacity: 0.2;
}

/* ===== PAGE HEADER ===== */
.page-header {
    text-align: center;
    margin-bottom: 4rem;
    position: relative;
}

.page-header h1 {
    font-family: 'Rebels', monospace;
    font-size: 3.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

.page-header p {
    color: var(--muted-foreground);
    font-size: 1.125rem;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* ===== ABOUT CARD ===== */
.about-card {
    background: linear-gradient(145deg, var(--card) 0%, rgba(26, 29, 36, 0.9) 100%);
    padding: 3rem;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    max-width: 800px;
    margin: 0 auto 4rem;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

.about-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.about-card h2 {
    text-align: center;
    font-family: 'Rebels', monospace;
    font-size: 2.25rem;
    color: var(--foreground);
    margin-bottom: 1.5rem;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.about-card h2::before,
.about-card h2::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

.about-card p {
    text-align: center;
    color: var(--muted-foreground);
    font-size: 1.125rem;
    line-height: 1.8;
    max-width: 700px;
    margin: 0 auto;
}

/* ===== INFO CARDS ===== */
.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto 4rem;
}

.info-card {
    background: linear-gradient(145deg, var(--card) 0%, rgba(26, 29, 36, 0.8) 100%);
    padding: 2.5rem 2rem;
    border-radius: var(--radius);
    text-align: center;
    border: 1px solid var(--border);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.info-card:hover {
    transform: translateY(-8px);
    border-color: var(--primary);
    box-shadow: var(--shadow-2xl);
}

.info-card:hover::before {
    transform: scaleX(1);
}

.info-card i {
    font-size: 2.5rem;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: var(--gradient-subtle);
    color: var(--primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.info-card:hover i {
    transform: scale(1.1);
    background: var(--gradient-primary);
    color: var(--primary-foreground);
}

.info-card h3 {
    font-family: 'Rebels', monospace;
    font-size: 1.5rem;
    color: var(--foreground);
    margin-bottom: 1rem;
}

.info-card p {
    color: var(--muted-foreground);
    font-size: 1rem;
    line-height: 1.7;
}

/* ===== STATS ===== */
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    max-width: 900px;
    margin: 0 auto;
}

.stat-box {
    background: linear-gradient(145deg, var(--card) 0%, rgba(26, 29, 36, 0.8) 100%);
    padding: 3rem 2rem;
    border-radius: var(--radius);
    text-align: center;
    border: 1px solid var(--border);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.stat-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--gradient-subtle);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-box:hover::before {
    opacity: 1;
}

.stat-box:hover {
    transform: translateY(-6px);
    border-color: var(--primary);
    box-shadow: var(--shadow-xl);
}

.stat-box h2 {
    font-family: 'Rebels', monospace;
    font-size: 3rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

.stat-box span {
    color: var(--muted-foreground);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    position: relative;
    z-index: 1;
}

/* ===== MOBILE MENU ===== */
.menu-btn {
    display: none;
    position: fixed;
    top: 1rem;
    left: 1rem;
    font-size: 1.5rem;
    background: none;
    border: none;
    color: var(--foreground);
    z-index: 1100;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: calc(var(--radius) - 4px);
    background: var(--card);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
}

.menu-btn:hover {
    background: var(--accent);
    color: var(--primary);
}

.overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    z-index: 900;
}

/* ===== TV NOISE EFFECT ===== */
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
    opacity: 0.1;
    pointer-events: none;
    z-index: 1;
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

/* ===== ANIMATIONS ===== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    opacity: 0;
    animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.animate-fadeIn.delay-1 { animation-delay: 0.1s; }
.animate-fadeIn.delay-2 { animation-delay: 0.2s; }
.animate-fadeIn.delay-3 { animation-delay: 0.3s; }

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .sidebar {
        width: 240px;
    }
    
    .main {
        margin-left: 240px;
        width: calc(100% - 240px);
        padding: 1.5rem;
    }
    
    .page-header h1 {
        font-size: 2.75rem;
    }
    
    .info-cards {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .stat-box:last-child {
        grid-column: span 2;
        max-width: 300px;
        justify-self: center;
    }
}

@media (max-width: 768px) {
    .menu-btn {
        display: block;
    }
    
    .close-btn {
        display: block;
    }
    
    .sidebar {
        transform: translateX(-100%);
        width: 300px;
    }
    
    .sidebar.active {
        transform: translateX(0);
    }
    
    .overlay.active {
        display: block;
    }
    
    .main {
        margin-left: 0;
        width: 100%;
        padding: 1.25rem;
    }
    
    .page-header h1 {
        font-size: 2.25rem;
    }
    
    .page-header p {
        font-size: 1rem;
    }
    
    .about-card {
        padding: 2rem 1.5rem;
    }
    
    .about-card h2 {
        font-size: 1.875rem;
    }
    
    .info-cards {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .stats {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .stat-box:last-child {
        grid-column: 1;
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .main {
        padding: 1rem;
    }
    
    .page-header {
        margin-bottom: 2.5rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .about-card,
    .info-card,
    .stat-box {
        padding: 2rem 1.25rem;
    }
    
    .info-card i {
        width: 60px;
        height: 60px;
        font-size: 2rem;
    }
    
    .stat-box h2 {
        font-size: 2.5rem;
    }
}

@media (max-width: 360px) {
    .page-header h1 {
        font-size: 1.75rem;
    }
    
    .about-card h2 {
        font-size: 1.5rem;
    }
    
    .info-card h3 {
        font-size: 1.25rem;
    }
}

/* Landscape Mode */
@media (max-height: 600px) and (orientation: landscape) {
    .sidebar {
        overflow-y: auto;
    }
    
    .menu {
        padding: 1rem;
    }
    
    .menu li {
        margin-bottom: 0.25rem;
    }
    
    .menu li a {
        padding: 0.75rem 1rem;
    }
}

/* High DPI Screens */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .info-card,
    .stat-box,
    .about-card {
        border-width: 1.5px;
    }
}

/* Print Styles */
@media print {
    body {
        background: white;
        color: black;
    }
    
    .sidebar {
        display: none;
    }
    
    .main {
        margin-left: 0;
        width: 100%;
    }
    
    .info-card,
    .stat-box,
    .about-card {
        border: 1px solid #000;
        box-shadow: none;
    }
}
</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
<script src="api/includes/loading_resilience.js?v=20260822" defer></script>
</head>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div id="loader-text">INITIALIZING ECOSYSTEM...</div>
    </div>
    <div class="desktop-container">
        <script>
            window.addEventListener('load', () => {
                setTimeout(() => {
                    document.body.classList.add('loaded');
                }, 500);
            });
        </script>
        
        <?php include 'sidebar.php'; ?>

        <main class="desktop-main">
    <!-- HEADER -->
    <section class="page-header animate-fadeIn">
      <h1>About Secure Worldz Academy</h1>
      <p>Empowering Students with Real-World Technology Skills</p>
    </section>

    <!-- ABOUT CARD -->
    <section class="about-card animate-fadeIn delay-1">
      <h2>Who We Are</h2>
      <p>
        Secure Worldz Academy is a modern learning platform focused on empowering students
        with real-world skills in technology, development, and startups.
        Our goal is to bridge the gap between education and industry.
      </p>
    </section>

    <!-- INFO CARDS -->
    <section class="info-cards">
      <div class="info-card animate-fadeIn delay-1">
        <i class="fa-solid fa-bullseye"></i>
        <h3>Our Mission</h3>
        <p>
          To provide practical, hands-on learning experiences that help students
          build confidence and job-ready skills through industry-relevant projects.
        </p>
      </div>

      <div class="info-card animate-fadeIn delay-2">
        <i class="fa-solid fa-eye"></i>
        <h3>Our Vision</h3>
        <p>
          To become a trusted digital learning ecosystem for students,
          creators, and future innovators shaping the technology landscape.
        </p>
      </div>

      <div class="info-card animate-fadeIn delay-3">
        <i class="fa-solid fa-users"></i>
        <h3>Who We Help</h3>
        <p>
          College students, beginners, and aspiring professionals who want
          to learn by doing and grow their careers in technology.
        </p>
      </div>
    </section>

    <!-- STATS -->
    <section class="stats">
      <div class="stat-box animate-fadeIn delay-1">
        <h2>10K+</h2>
        <span>Active Students</span>
      </div>

      <div class="stat-box animate-fadeIn delay-2">
        <h2>50+</h2>
        <span>Courses Available</span>
      </div>

      <div class="stat-box animate-fadeIn delay-3">
        <h2>100+</h2>
        <span>Real Projects</span>
      </div>
    </section>
    </main>
</div>

<script>
// Animation observer
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationPlayState = 'running';
            observer.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
});

// Observe all animated elements
document.querySelectorAll('.animate-fadeIn').forEach(el => {
    observer.observe(el);
});

// Add hover effects to cards
document.querySelectorAll('.info-card, .stat-box').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = this.classList.contains('info-card') ? 'translateY(-8px)' : 'translateY(-6px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Animate counter stats
function animateCounter(element, target) {
    let current = 0;
    const increment = target / 50;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current).toLocaleString() + '+';
    }, 30);
}

// Start counter animation when stats are in view
const statObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const statNumbers = document.querySelectorAll('.stat-box h2');
            statNumbers.forEach((stat, index) => {
                const target = [10000, 50, 100][index];
                setTimeout(() => {
                    animateCounter(stat, target);
                }, index * 200);
            });
            statObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

const statsSection = document.querySelector('.stats');
if (statsSection) {
    statObserver.observe(statsSection);
}
</script>
</body>
</html>
