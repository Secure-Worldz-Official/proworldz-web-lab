<?php
require_once 'api/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Programming Labs | Secure Worldz Academy</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="api/includes/loading_resilience.js?v=20260822" defer></script>
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
@font-face {
    font-family: "Rebels";
    src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
    font-display: swap;
}

:root {
            --gap: 1.5rem;
            --sides: 1.5rem;
    --radius: 0.625rem;
    --background: #000000;
    --foreground: #ffffff;
    --card: #080808;
    --card-foreground: #ffffff;
    --primary: #ff2a2f;
    --primary-light: #8183f4;
    --primary-foreground: #ffffff;
    --muted-foreground: #a0a0a0;
    --border: rgba(139, 12, 16, 0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto Mono', monospace;
    background-color: var(--background);
    color: var(--foreground);
    min-height: 100vh;
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

#loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #000000;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    transition: opacity 0.5s ease, visibility 0.5s;
}

#loader {
    width: 50px;
    height: 50px;
    border: 3px solid rgba(139, 12, 16, 0.1);
    border-top-color: #ff2a2f;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
}

#loader-text {
    font-family: 'Roboto Mono', monospace;
    font-size: 0.75rem;
    color: #ff2a2f;
    letter-spacing: 0.2em;
    animation: pulse-loader 1.5s infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }
@keyframes pulse-loader { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }

body.loaded #loader-wrapper {
    opacity: 0;
    visibility: hidden;
}

.font-display { font-family: 'Rebels', monospace; font-weight: 700; }

.p-4 { padding: 1rem; }
.p-3 { padding: 0.75rem; }
.flex { display: flex; }
.items-center { align-items: center; }
.gap-3 { gap: 0.75rem; }
.size-12 { width: 3rem; height: 3rem; }
.rounded-lg { border-radius: 0.625rem; }
.flex-1 { flex: 1 1 0%; }
.text-2xl { font-size: 1.5rem; line-height: 2rem; }
.text-xs { font-size: 0.75rem; line-height: 1rem; }
.uppercase { text-transform: uppercase; }

/* BACK BUTTON STYLES */
.back-btn-container {
    display: flex;
    align-items: center;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(139, 12, 16, 0.1);
    border: 1px solid rgba(139, 12, 16, 0.3);
    color: #ff2a2f;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 0.8rem;
    text-transform: uppercase;
}

.back-btn:hover {
    background: rgba(139, 12, 16, 0.2);
    box-shadow: 0 0 15px rgba(139, 12, 16, 0.3);
    transform: translateY(-2px);
}

.back-btn i {
    font-size: 18px;
}

/* MAIN CONTENT */
.main {
    padding: 0 30px 30px;
    width: 100%;
    min-height: 100vh;
}

/* PAGE HEADER */
.page-header {
    margin-bottom: 40px;
    text-align: center;
}

.page-header h1 {
    font-size: 2.5rem;
    color: #ff2a2f;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* LABS GRID */
.lab-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

/* LAB CARD */
.lab-card {
    background: #080808;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(139, 12, 16, 0.05);
}

.lab-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(139, 12, 16, 0.3);
    border-color: rgba(139, 12, 16, 0.3);
}

.lab-icon {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.lab-icon i {
    font-size: 6rem;
    transition: transform 0.3s ease;
}

.lab-card:hover .lab-icon i {
    transform: scale(1.1);
}

.lab-content {
    padding: 25px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.lab-content h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #fff;
    text-align: center;
}

.lab-content p {
    color: #aaa;
    line-height: 1.6;
    margin-bottom: 20px;
    flex-grow: 1;
    text-align: center;
}

.lab-btn {
    background: linear-gradient(135deg, #ff2a2f, #ff2a2f);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
    width: 100%;
}

.lab-btn:hover {
    background: linear-gradient(135deg, #ff2a2f, #ff2a2f);
    box-shadow: 0 5px 15px rgba(139, 12, 16, 0.4);
    transform: translateY(-2px);
}

/* RESPONSIVE DESIGN */
@media (max-width: 1200px) {
    .lab-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        max-width: 900px;
    }
}

@media (max-width: 1024px) {
    .lab-grid {
        grid-template-columns: repeat(2, 1fr);
        max-width: 700px;
    }
    
    .main {
        padding: 0 20px 20px;
    }
}

@media (max-width: 768px) {
    .main {
        padding: 0 15px 15px;
    }
    
    .lab-grid {
        grid-template-columns: 1fr;
        max-width: 500px;
        gap: 20px;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .back-btn-container {
        padding: 15px;
    }
}

@media (max-width: 480px) {
    .lab-content {
        padding: 20px;
    }
    
    .lab-content h3 {
        font-size: 1.3rem;
    }
    
    .lab-icon {
        height: 180px;
    }
    
    .lab-btn {
        padding: 10px 20px;
        font-size: 0.85rem;
    }
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
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div id="loader-text">INITIALIZING LABS...</div>
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

    <main class="desktop-main">
            <div class="card">
                <div class="p-4 flex items-center justify-between">
                    <div class="back-btn-container">
                        <a href="lab.php" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to Lab</span>
                        </a>
                    </div>
                    <div class="text-xs uppercase text-muted-foreground font-display" style="letter-spacing: 0.1em;">CODING LANGUAGE LABS</div>
                </div>
            </div>

            <!-- HEADER -->
            <section class="page-header">
                <h1 class="font-display" style="font-size: 2.5rem; color: #ff2a2f;">Coding Language Labs</h1>
            </section>

    <!-- LABS GRID - 2 per row for 4 cards -->
    <section class="lab-grid" style="grid-template-columns: repeat(2, 1fr); max-width: 900px;">
      
      <!-- Python Lab -->
      <div class="lab-card">
        <div class="lab-icon" style="background: linear-gradient(135deg, rgba(49, 112, 143, 0.2), rgba(0, 0, 0, 0.2));">
          <i class="fab fa-python" style="color:#3b8ab8; font-size: 7rem;"></i>
        </div>
        <div class="lab-content">
          <h3 style="color:#ff2a2f">Python Lab</h3>
          <p>Write, run and debug Python code in our interactive environment. Perfect for beginners and advanced developers alike.</p>
          <button class="lab-btn" onclick="location.href='lab/codings/pythoni.php'" style="background:#ff2a2f">
            Go to Python Lab
          </button>
        </div>
      </div>

      <!-- C++ Lab -->
      <div class="lab-card">
        <div class="lab-icon" style="background: linear-gradient(135deg, rgba(0, 85, 164, 0.2), rgba(0, 0, 0, 0.2));">
          <i class="fas fa-cogs" style="color:#6495ed; font-size: 6.5rem;"></i>
        </div>
        <div class="lab-content">
          <h3 style="color:#ff2a2f">C++ Lab</h3>
          <p>Compile and execute C++ programs with our dedicated lab. Supports C++11, C++14, C++17 and C++20 standards.</p>
          <button class="lab-btn" onclick="location.href='lab/codings/cpp.php'" style="background:#ff2a2f">
            Go to C++ Lab
          </button>
        </div>
      </div>

      <!-- PHP Lab -->
      <div class="lab-card">
        <div class="lab-icon" style="background: linear-gradient(135deg, rgba(119, 123, 180, 0.2), rgba(0, 0, 0, 0.2));">
          <i class="fab fa-php" style="color:#777bb4; font-size: 7rem;"></i>
        </div>
        <div class="lab-content">
          <h3 style="color:#ff2a2f">PHP Lab</h3>
          <p>Compile and execute PHP scripts with our dedicated lab. Supports modern PHP versions with fast and secure execution.</p>
          <button class="lab-btn" onclick="location.href='lab/codings/php.php'" style="background:#ff2a2f">
            Go to PHP Lab
          </button>
        </div>
      </div>

      <!-- JavaScript Lab -->
      <div class="lab-card">
        <div class="lab-icon" style="background: linear-gradient(135deg, rgba(247, 223, 30, 0.1), rgba(0, 0, 0, 0.2));">
          <i class="fab fa-js-square" style="color:#f7df1e; font-size: 7rem;"></i>
        </div>
        <div class="lab-content">
          <h3 style="color:#ff2a2f">JS Lab</h3>
          <p>Compile and execute JavaScript programs with our dedicated lab. Supports modern Node.js runtime for server-side execution.</p>
          <button class="lab-btn" onclick="location.href='lab/codings/js.php'" style="background:#ff2a2f">
            Go to JS Lab
          </button>
        </div>
      </div>

    </section>

        </main>
    </div>

<script>
// Add hover effect to lab cards
const labCards = document.querySelectorAll('.lab-card');
labCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px)';
        this.style.boxShadow = '0 15px 30px rgba(139, 12, 16, 0.3)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.2)';
    });
});

// Button hover effects
const labButtons = document.querySelectorAll('.lab-btn');
labButtons.forEach(button => {
    button.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 5px 15px rgba(139, 12, 16, 0.4)';
    });
    
    button.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = 'none';
    });
});
</script>

</body>
</html>
