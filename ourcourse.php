<?php
require_once 'api/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Courses | Secure Worldz Academy</title>
  <link rel="icon" type="image/webp" href="image.webp">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="api/includes/loading_resilience.js?v=20260822" defer></script>
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
    font-family: 'Roboto Mono', monospace;
    background-color: #000000;
    color: #ffffff;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    min-height: 100vh;
    overflow-x: hidden;
}


@font-face {
    font-family: "Rebels";
    src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
    font-weight: normal;
    font-style: normal;
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
    --popover: #080808;
    --popover-foreground: #ffffff;
    --primary: #ff2a2f;
    --primary-light: #a78bfa;
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
    --destructive: #ff2a2f;
    --warning: #8b0c10;
    
    --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    --gradient-subtle: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(129, 131, 244, 0.1) 100%);
    --gradient-dark: linear-gradient(135deg, var(--background) 0%, var(--card) 100%);
    
    --sidebar: #080808;
    --sidebar-foreground: #ffffff;
    --sidebar-primary: #ff2a2f;
    --sidebar-primary-foreground: #ffffff;
    --sidebar-accent: rgba(248, 250, 252, 0.05);
    --sidebar-accent-foreground: #ffffff;
    --sidebar-border: rgba(139, 12, 16, 0.1);
    --sidebar-ring: rgba(148, 163, 184, 0.5);
    
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}


.desktop-container {
            display: grid !important;
    grid-template-columns: 280px 1fr !important;
    gap: var(--gap, 1.5rem) !important;
    min-height: 100vh !important;
    padding: var(--sides, 1.5rem) !important;
    background-color: var(--background) !important;
}



        body.loaded #loader-wrapper {
            opacity: 0;
            visibility: hidden;
        }
    



.card {
    background-color: var(--card);
    border-radius: var(--radius);
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
.rounded-lg { border-radius: var(--radius); }
.text-primary-foreground { color: var(--primary-foreground); }
.text-2xl { font-size: 1.5rem; line-height: 2rem; }
.font-display { font-family: 'Rebels', monospace; font-weight: 700; }
.text-xs { font-size: 0.75rem; line-height: 1rem; }
.text-muted-foreground { color: var(--muted-foreground); }
.uppercase { text-transform: uppercase; }
.flex-1 { flex: 1 1 0%; }
.flex-shrink-0 { flex-shrink: 0; }




.space-y-1 > * + * {
    margin-top: 0.25rem;
}












.desktop-main {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    overflow-y: auto;
    max-height: calc(100vh - 3rem);
    padding-right: 0.5rem;
}


.page-header {
    text-align: center;
    margin-bottom: 2rem;
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


.courses-container {
    display: grid;
     grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2.5rem;
    margin-bottom: 3rem;
    width: 100%;
}


  .course-card {
    background: linear-gradient(145deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
    border-radius: 20px;
    border: 1px solid var(--border);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-xl);
}

.course-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.5s ease;
    pointer-events: none;
}

.course-card:hover {
    border-color: var(--primary);
    transform: translateY(-10px);
    box-shadow: var(--shadow-2xl);
}

.course-card:hover::after {
    opacity: 1;
}

.course-image {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(129, 131, 244, 0.05) 100%);
}

.course-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.course-card:hover .course-image img {
    transform: scale(1.1);
}

.course-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.5rem 1rem;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(99, 102, 241, 0.3);
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--primary-light);
    text-transform: uppercase;
}

.course-body {
    padding: 2rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.course-body h3 {
    font-size: 1.6rem;
    margin-bottom: 1rem;
    color: var(--foreground);
    transition: color 0.3s ease;
    font-family: 'Rebels', monospace;
}

.course-card:hover .course-body h3 {
    color: var(--primary-light);
}

.course-body p {
    color: var(--muted-foreground);
    line-height: 1.7;
    margin-bottom: 2rem;
    flex-grow: 1;
    font-size: 0.95rem;
}

.course-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 2rem;
    background: var(--gradient-primary);
    color: var(--primary-foreground);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    border-radius: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    width: 100%;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border: none;
    cursor: pointer;
    font-family: 'Roboto Mono', monospace;
}

.course-action:hover {
    background: var(--primary);
    box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
    transform: translateX(5px);
}

.course-action i {
    transition: transform 0.3s ease;
}

.course-action:hover i {
    transform: translateX(5px);
}


.stats-section {
    padding: 6rem 2rem;
    background: var(--card);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    margin-bottom: 3rem;
}

.stats-container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 3rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
    font-family: 'Rebels', monospace;
}

.stat-label {
    font-size: 1rem;
    color: var(--muted-foreground);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}


.footer {
    text-align: center;
    padding: 2rem;
    color: var(--muted-foreground);
    border-top: 1px solid var(--border);
    margin-top: auto;
    font-size: 0.875rem;
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


.course-card:nth-child(1) { animation-delay: 0.1s; }
.course-card:nth-child(2) { animation-delay: 0.2s; }
.course-card:nth-child(3) { animation-delay: 0.3s; }
.course-card:nth-child(4) { animation-delay: 0.4s; }
.course-card:nth-child(5) { animation-delay: 0.5s; }
.course-card:nth-child(6) { animation-delay: 0.6s; }
.course-card:nth-child(7) { animation-delay: 0.7s; }
.course-card:nth-child(8) { animation-delay: 0.8s; }
.course-card:nth-child(9) { animation-delay: 0.9s; }
.course-card:nth-child(10) { animation-delay: 1s; }


@media (max-width: 1400px) {
    .courses-container {
        gap: 2rem;
    }
}

@media (max-width: 1024px) {
    .desktop-container {
           display: flex !important;
        flex-direction: column !important;
    }

  .desktop-sidebar {
        display: none !important;
    }
  
    .courses-container {
     gap: 1.5rem;   
    }
    
    .page-header h1 {
        font-size: 2.75rem;
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
    
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
}

@media (max-width: 480px) {  
    .page-header {
        margin-bottom: 1.5rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .course-body {
        padding: 1.5rem;
    }
    
    .course-body h3 {
        font-size: 1.4rem;
    }
    
    .stats-container {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
}

@media (max-width: 360px) {
    .page-header h1 {
        font-size: 1.75rem;
    }
}


.desktop-main::-webkit-scrollbar {
    width: 6px;
}

.desktop-main::-webkit-scrollbar-track {
    background: transparent;
}

.desktop-main::-webkit-scrollbar-thumb {
    background: var(--muted);
    border-radius: 3px;
}

.desktop-main::-webkit-scrollbar-thumb:hover {
    background: var(--muted-foreground);
}

        
        

        
        

        

        

        

        

        
        

        

        

        

        

        

        

        

        .ripple-container {
            position: relative;
            overflow: hidden;
        }

</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>

<div class="tv-noise"></div>
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
    
                    <?php include 'sidebar.php'; ?>

    <div class="desktop-main">
        
        <section class="page-header animate-fadeIn">
            <h1>Our Courses</h1>
            <p>Comprehensive programs designed by industry experts to transform you into a sought-after technology professional.</p>
        </section>

        
        <div class="courses-container">
            
            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="cyber-sec-car.png" alt="Cyber Security Career" loading="lazy"> 
                    <div class="course-badge">Career</div>
                </div>
                <div class="course-body">
                    <h3>Cyber Security Career</h3>
                    <p>Master comprehensive cybersecurity skills, penetration testing, threat analysis, and digital defense strategies with real-world practical labs.</p>
                    <a href="https://tagmango.app/ec517d00e6" target="_blank" rel="noopener noreferrer" class="course-action">
                        Register Now
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="dev-with-ai.png" alt="Dev With AI" loading="lazy"> 
                    <div class="course-badge">AI &amp; Development</div>
                </div>
                <div class="course-body">
                    <h3>Development With AI</h3>
                    <p>Learn AI-driven software engineering, full-stack web development with LLMs, code automation, and modern developer workflows.</p>
                    <a href="https://tagmango.app/f0027f4d6f" target="_blank" rel="noopener noreferrer" class="course-action">
                        Register Now
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="images/jai-bro/secure-x.png" alt="Secure X" loading="lazy"> 
                    <div class="course-badge">Advanced</div>
                </div>
                <div class="course-body">
                    <h3>Secure X</h3>
                    <p>Master advanced cybersecurity techniques and digital defense strategies. Learn to protect systems from sophisticated cyber threats and vulnerabilities.</p>
                    <button onclick="window.location.href='course-details/secure-x.pdf'" class="course-action">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            
            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="images/jai-bro/ai.png" alt="AI Verse Web Labs" loading="lazy"> 
                    <div class="course-badge">Professional</div>
                </div>
                <div class="course-body">
                    <h3>AI Verse Web Labs</h3>
                    <p>Build intelligent web applications using AI-driven development, machine learning integration, and automated engineering workflows.</p>
                    <button onclick="window.location.href='course-details/Ai.pdf'" class="course-action">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            
            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="images/jai-bro/hunt-elite.png" alt="Hunt Elite" loading="lazy"> 
                    <div class="course-badge">Expert</div>
                </div>
                <div class="course-body">
                    <h3>Hunt Elite</h3>
                    <p>Professional bug bounty hunting and exploit analysis. Learn advanced penetration testing and ethical hacking techniques.</p>
                    <button onclick="window.location.href='course-details/Bug.pdf'" class="course-action">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            
            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="images/jai-bro/creative-craft.png" alt="Creative Craft" loading="lazy"> 
                    <div class="course-badge">Creative</div>
                </div>
                <div class="course-body">
                    <h3>Creative Craft</h3>
                    <p>Master strategic visual communication design, branding, and UI/UX principles to create compelling digital experiences.</p>
                    <button onclick="window.location.href='course-details/Canva.pdf'" class="course-action">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            
            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="images/jai-bro/py-desk.png" alt="Py Desk Systems" loading="lazy"> 
                    <div class="course-badge">Development</div>
                </div>
                <div class="course-body">
                    <h3>Py Desk Systems</h3>
                    <p>Develop enterprise-grade desktop applications with Python. Master GUI frameworks and system-level programming.</p>
                    <button onclick="window.location.href='course-details/py.pdf'" class="course-action">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            
            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="images/jai-bro/code-f.png" alt="Code Foundry" loading="lazy"> 
                    <div class="course-badge">Fundamental</div>
                </div>
                <div class="course-body">
                    <h3>Code Foundry</h3>
                    <p>Professional programming language mastery. Deep dive into best practices and advanced software engineering concepts.</p>
                    <button onclick="window.location.href='course-details/Code.pdf'" class="course-action">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            
            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="images/jai-bro/startup.png" alt="Startup Gene Labs" loading="lazy"> 
                    <div class="course-badge">Entrepreneurship</div>
                </div>
                <div class="course-body">
                    <h3>Startup Gene Labs</h3>
                    <p>Venture creation and startup scaling. Build, fund, and grow tech startups from idea to successful enterprise.</p>
                    <button onclick="window.location.href='course-details/startup.pdf'" class="course-action">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            
            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="images/jai-bro/cli.png" alt="CLI++ Systems" loading="lazy"> 
                    <div class="course-badge">Systems</div>
                </div>
                <div class="course-body">
                    <h3>CLI++ Systems</h3>
                    <p>C++ command-line tool engineering for Linux. Build powerful system tools using advanced programming techniques.</p>
                    <button onclick="window.location.href='course-details/CLI.pdf'" class="course-action">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            
            <div class="course-card animate-fadeIn">
                <div class="course-image">
                    <img src="images/jai-bro/app.png" alt="API Man" loading="lazy"> 
                    <div class="course-badge">Backend</div>
                </div>
                <div class="course-body">
                    <h3>API Man</h3>
                    <p>Master API development and management. Build RESTful and GraphQL APIs with scalable architecture patterns.</p>
                    <button onclick="window.location.href='course-details/api.pdf'" class="course-action">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Heartbeat & Online Status
setInterval(() => {
    fetch('api/heartbeat.php').then(r => r.json()).catch(e => console.error("Heartbeat error:", e));
}, 60000);

const courseCards = document.querySelectorAll('.course-card');


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


document.querySelectorAll('.animate-fadeIn').forEach(el => {
    el.addEventListener('animationend', function() {
        this.style.animation = 'none';
        this.style.opacity = '1';
    }, { once: true });
    observer.observe(el);
});


courseCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) scale(1.02)';
        this.style.boxShadow = '0 25px 50px rgba(220, 38, 38, 0.3)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
        this.style.boxShadow = 'var(--shadow-xl)';
    });
});


window.addEventListener('load', () => {
    document.body.classList.add('loaded');

    const header = document.querySelector('.page-header h1');
    if (header) {
        header.style.opacity = '0';
        header.style.transform = 'translateY(20px)';
        setTimeout(() => {
            header.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }, 100);
    }
    

    const stats = document.querySelectorAll('.stat-number');
    stats.forEach(stat => {
        const target = parseInt(stat.textContent.replace(/[^0-9]/g, ''));
        const suffix = stat.textContent.replace(/[0-9]/g, '');
        
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                stat.textContent = target + suffix;
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(current) + suffix;
            }
        }, 30);
    });
});
</script>
</body>
</html>
