<?php
require_once 'api/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Maintenance | Secure Worldz Academy</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto Mono', monospace;
    background-color: #000000;
    color: #ffffff;
    min-height: 100vh;
}

@font-face {
    font-family: "Rebels";
    src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
    font-display: swap;
}

:root {
            --gap: 1.5rem;
            --sides: 1.5rem;
    --background: #000000;
    --foreground: #ffffff;
    --card: #080808;
    --primary: #ff2a2f;
    --primary-light: #8183f4;
    --primary-foreground: #ffffff;
    --muted-foreground: #a0a0a0;
    --border: rgba(139, 12, 16, 0.1);
    
    --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    --gradient-subtle: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(129, 131, 244, 0.1) 100%);
}

/* ===== DESKTOP LAYOUT ===== */
.desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }

/* ===== SIDEBAR STYLES ===== */
.desktop-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.card {
    background-color: var(--card);
    border-radius: 0.625rem;
    border: 1px solid var(--border);
    overflow: hidden;
}

.p-4 { padding: 1rem; }
.p-3 { padding: 0.75rem; }

.flex { display: flex; }
.items-center { align-items: center; }
.gap-3 { gap: 0.75rem; }
.size-12 { width: 3rem; height: 3rem; }
.bg-primary { background-color: var(--primary); }
.rounded-lg { border-radius: 0.625rem; }
.text-primary-foreground { color: var(--primary-foreground); }
.text-2xl { font-size: 1.5rem; line-height: 2rem; }
.font-display { font-family: 'Rebels', monospace; font-weight: 700; }
.text-xs { font-size: 0.75rem; line-height: 1rem; }
.text-muted-foreground { color: var(--muted-foreground); }
.uppercase { text-transform: uppercase; }
.flex-1 { flex: 1 1 0%; }
.flex-shrink-0 { flex-shrink: 0; }

/* Navigation Styles */
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
    color: #ffffff;
    transition: background-color 0.2s;
    margin-bottom: 0.25rem;
    cursor: pointer;
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

/* ===== MAIN CONTENT ===== */
.desktop-main {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    overflow-y: auto;
    max-height: calc(100vh - 3rem);
    padding-right: 0.5rem;
}

/* Page Header */
.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-family: 'Rebels', monospace;
    font-size: 3.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.page-header p {
    color: var(--muted-foreground);
    font-size: 1.125rem;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* ===== MAINTENANCE CONTENT ===== */
.maintenance-content {
    background: linear-gradient(145deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
    padding: 4rem;
    border-radius: 0.625rem;
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
    width: 100%;
}

.maintenance-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.maintenance-content h2 {
    font-family: 'Rebels', monospace;
    font-size: 3rem;
    color: var(--foreground);
    margin-bottom: 2rem;
    text-align: center;
}

.maintenance-content > p {
    color: var(--muted-foreground);
    font-size: 1.25rem;
    line-height: 1.8;
    margin-bottom: 3rem;
    text-align: center;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

/* Maintenance Image Container */
.maintenance-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.maintenance-image-box {
    text-align: center;
    padding: 2rem;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid transparent;
    transition: all 0.3s ease;
    width: 100%;
}

.maintenance-image-box:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--primary);
    transform: translateY(-5px);
}

.maintenance-image {
    width: 300px;
    height: 200px;
    object-fit: contain;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.maintenance-status {
    color: var(--primary);
    font-size: 2rem;
    font-weight: 600;
    margin: 0;
}

/* Status Items */
.status-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
    width: 100%;
}

.status-box {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 2rem;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.status-box:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--primary);
    transform: translateY(-5px);
}

.status-box i {
    font-size: 2rem;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: var(--gradient-subtle);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.status-box:hover i {
    background: var(--gradient-primary);
    color: var(--primary-foreground);
    transform: scale(1.1);
}

.status-text {
    color: var(--foreground);
    font-size: 1.25rem;
    font-weight: 500;
    margin: 0;
}

.status-detail {
    color: var(--muted-foreground);
    font-size: 1rem;
    margin-top: 0.5rem;
}

/* ===== FOOTER ===== */
.footer {
    text-align: center;
    padding: 2rem;
    color: var(--muted-foreground);
    border-top: 1px solid var(--border);
    margin-top: auto;
    font-size: 0.875rem;
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

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-fadeIn {
    opacity: 0;
    animation: fadeInUp 0.8s ease forwards;
}

.animate-fadeIn.delay-1 { animation-delay: 0.1s; }
.animate-fadeIn.delay-2 { animation-delay: 0.2s; }
.animate-fadeIn.delay-3 { animation-delay: 0.3s; }

.pulse {
    animation: pulse 2s infinite;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1400px) {
    .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }
    
    .maintenance-content {
        padding: 3rem;
    }
}

@media (max-width: 1024px) {
    .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }
    
    .desktop-sidebar {
        display: none;
    }
    
    .page-header h1 {
        font-size: 2.75rem;
    }
    
    .maintenance-content h2 {
        font-size: 2.5rem;
    }
}

@media (max-width: 768px) {
    .page-header h1 {
        font-size: 2.25rem;
    }
    
    .page-header p {
        font-size: 1rem;
        padding: 0 1rem;
    }
    
    .maintenance-content h2 {
        font-size: 2rem;
    }
    
    .maintenance-content {
        padding: 2rem;
    }
    
    .status-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .status-box {
        padding: 1.5rem;
    }
    
    .status-box i {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .status-text {
        font-size: 1rem;
    }
    
    .maintenance-image {
        width: 250px;
        height: 150px;
    }
}

@media (max-width: 480px) {
    .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .maintenance-content {
        padding: 1.5rem;
    }
    
    .maintenance-content h2 {
        font-size: 1.75rem;
    }
    
    .maintenance-content > p {
        font-size: 1rem;
    }
}

/* Custom Scrollbar */
.desktop-main::-webkit-scrollbar {
    width: 6px;
}

.desktop-main::-webkit-scrollbar-track {
    background: transparent;
}

.desktop-main::-webkit-scrollbar-thumb {
    background: #080808;
    border-radius: 3px;
}

.desktop-main::-webkit-scrollbar-thumb:hover {
    background: #a0a0a0;
}

        
        

        
        

        
/* LOCAL_DASHBOARD_NAV_LOCK */
.desktop-container .desktop-sidebar {
    height: fit-content !important;
    min-height: 0 !important;
    align-self: start !important;
    position: sticky !important;
    top: var(--sides, 1.5rem) !important;
    max-height: calc(100vh - (var(--sides, 1.5rem) * 2)) !important;
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

.desktop-container .desktop-sidebar > .card {
    flex: 0 0 auto !important;
    height: fit-content !important;
}

.desktop-container .desktop-sidebar .profile-card-section {
    background-color: var(--card, #080808) !important;
    border: 1px solid var(--border, rgba(139, 12, 16, 0.1)) !important;
    padding: 0 !important;
    margin-bottom: 0.5rem !important;
    overflow: hidden !important;
}

.desktop-container .desktop-sidebar .profile-card-section .p-4 {
    padding: 0.75rem !important;
}

.desktop-container .desktop-sidebar .profile-card-section .flex.items-center {
    min-width: 0 !important;
}

.desktop-container .desktop-sidebar .profile-card-section .size-12 {
    width: 3rem !important;
    height: 3rem !important;
    flex: 0 0 3rem !important;
}

.desktop-container .desktop-sidebar .profile-card-section .flex-1 {
    min-width: 0 !important;
}

.desktop-container .desktop-sidebar .profile-card-section .text-2xl,
.desktop-container .desktop-sidebar .profile-card-section .text-xs {
    width: 100% !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    line-height: 1.2 !important;
}

.desktop-container .desktop-sidebar .nav-section {
    margin-bottom: 1.5rem !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-item {
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
    padding: 0.75rem !important;
    border-radius: calc(var(--radius, 0.625rem) - 2px) !important;
    text-decoration: none !important;
    color: var(--sidebar-foreground, #ffffff) !important;
    margin-bottom: 0.25rem !important;
    background-color: transparent !important;
    filter: none !important;
    backdrop-filter: none !important;
    box-shadow: none !important;
    opacity: 1 !important;
    pointer-events: auto !important;
    cursor: pointer !important;
    transition: none !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-item:hover,
.desktop-container .desktop-sidebar .nav-section .nav-item:hover:not(.active),
.desktop-container .desktop-sidebar .nav-section .nav-item.disabled:hover {
    background-color: transparent !important;
    color: var(--sidebar-foreground, #ffffff) !important;
    filter: none !important;
    backdrop-filter: none !important;
    box-shadow: none !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-item.active {
    background-color: transparent !important;
    color: #ffffff !important;
    border: 1px solid rgba(139, 12, 16, 0.1) !important;
    box-shadow: none !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-item.disabled {
    opacity: 1 !important;
    pointer-events: auto !important;
    cursor: pointer !important;
    border: none !important;
    background-color: transparent !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-icon {
    width: 1.25rem !important;
    height: 1.25rem !important;
    flex-shrink: 0 !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-label {
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    text-transform: uppercase !important;
}
</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
<!-- TV Noise Effect -->
<div class="tv-noise"></div>

<div class="desktop-container">
    <!-- Left Sidebar - Navigation -->
    <?php include 'sidebar.php'; ?>

    <div class="desktop-main">
        <!-- HEADER -->
        <section class="page-header animate-fadeIn">
            <h1>System Maintenance</h1>
            <p>We're working hard to improve your experience</p>
        </section>

        <div style="display: flex; justify-content: center;">
            <img src="images/eaglone/maint.png" alt="maintenance" style="width: 700px; height: auto; max-width: 100%;" loading="lazy"> 
        </div>

        <!-- FOOTER -->
        <footer class="footer animate-fadeIn delay-3">
            <p>&copy; 2026 Secure Worldz Academy. All rights reserved. | Estimated completion: 48 hours</p>
        </footer>
    </div>
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

// Add hover effects to boxes
document.querySelectorAll('.status-box, .maintenance-image-box').forEach(box => {
    box.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    
    box.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Navigation active states
const navItems = document.querySelectorAll('.nav-item');
navItems.forEach(item => {
    item.addEventListener('click', function(e) {
        if (!this.classList.contains('disabled')) {
            navItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        }
    });
});

// Initialize on load
window.addEventListener('load', () => {
    // Add loading animation to page header
    const header = document.querySelector('.page-header h1');
    if (header) {
        header.style.opacity = '0';
        header.style.transform = 'translateY(20px)';
        setTimeout(() => {
            header.style.transition = 'all 0.8s ease';
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }, 100);
    }
});
</script>
</body>
</html>
