<?php
require_once 'api/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | Secure Worldz Academy</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
/* ===== CSS RESET & BASE ===== */
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
    --primary-light: #8183f4;
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
    border: 3px solid rgba(139, 92, 246, 0.1);
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

/* Navigation Styles */


.space-y-1 > * + * {
    margin-top: 0.25rem;
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

/* ===== CONTACT CONTAINER ===== */
.contact-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

/* ===== CONTACT INFO ===== */
.contact-info {
    background: linear-gradient(145deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
    padding: 3rem;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

.contact-info::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.contact-info h2 {
    font-family: 'Rebels', monospace;
    font-size: 2.25rem;
    color: var(--foreground);
    margin-bottom: 1.5rem;
    position: relative;
}

.contact-info > p {
    color: var(--muted-foreground);
    font-size: 1.125rem;
    line-height: 1.8;
    margin-bottom: 2.5rem;
}

.info-box {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    border-radius: calc(var(--radius) - 2px);
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid transparent;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.info-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(139, 12, 16, 0.05), transparent);
    transition: left 0.5s;
}

.info-box:hover::before {
    left: 100%;
}

.info-box:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--primary);
    transform: translateX(8px);
}

.info-box i {
    font-size: 1.5rem;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: var(--gradient-subtle);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.info-box:hover i {
    background: var(--gradient-primary);
    color: var(--primary-foreground);
    transform: scale(1.1);
}

.info-box p {
    color: var(--foreground);
    font-size: 1.125rem;
    font-weight: 500;
    margin: 0;
}

/* ===== CONTACT FORM ===== */
.contact-form {
    background: linear-gradient(145deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
    padding: 3rem;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

.contact-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.contact-form h2 {
    font-family: 'Rebels', monospace;
    font-size: 2.25rem;
    color: var(--foreground);
    margin-bottom: 2rem;
    position: relative;
}

.form-group {
    margin-bottom: 1.75rem;
    position: relative;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--muted-foreground);
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: color 0.3s ease;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 1rem 1.25rem;
    background: rgba(139, 12, 16, 0.05);
    border: 1px solid var(--border);
    border-radius: calc(var(--radius) - 4px);
    color: var(--foreground);
    font-family: inherit;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: var(--muted-foreground);
    opacity: 0.7;
}

.form-group textarea {
    min-height: 150px;
    resize: vertical;
}

.submit-btn {
    width: 100%;
    padding: 1.125rem;
    background: var(--gradient-primary);
    color: var(--primary-foreground);
    border: none;
    border-radius: calc(var(--radius) - 4px);
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.submit-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.submit-btn:hover::before {
    left: 100%;
}

.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.submit-btn i {
    margin-right: 0.75rem;
}

/* ===== CTA SECTION ===== */
.contact-cta {
    background: linear-gradient(145deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
    padding: 4rem;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    text-align: center;
    margin: 0 auto 4rem;
    max-width: 800px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
}

.contact-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.contact-cta h2 {
    font-family: 'Rebels', monospace;
    font-size: 2.5rem;
    color: var(--foreground);
    margin-bottom: 1.5rem;
    position: relative;
}

.contact-cta h2 span {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.125rem 2.5rem;
    background: var(--gradient-primary);
    color: var(--primary-foreground);
    text-decoration: none;
    border-radius: calc(var(--radius) - 4px);
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.cta-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.cta-btn:hover::before {
    left: 100%;
}

.cta-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.cta-btn i {
    transition: transform 0.3s ease;
}

.cta-btn:hover i {
    transform: translateX(5px);
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

/* ===== NOTIFICATION ===== */
.notification {
    position: fixed;
    top: 100px;
    right: 2rem;
    padding: 1.25rem 1.75rem;
    border-radius: calc(var(--radius) - 2px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    z-index: 10000;
    animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    max-width: 400px;
    box-shadow: var(--shadow-2xl);
    backdrop-filter: blur(10px);
    border: 1px solid var(--border);
}

.notification.success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(30, 144, 255, 0.9));
    color: white;
}

.notification.error {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.9), rgba(255, 107, 129, 0.9));
    color: white;
}

.notification span {
    flex: 1;
    font-size: 0.875rem;
    font-weight: 500;
}

.close-notification {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.close-notification:hover {
    opacity: 1;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
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
.animate-fadeIn.delay-4 { animation-delay: 0.4s; }

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
    
    .contact-container {
        gap: 2rem;
    }
    
    .contact-info,
    .contact-form,
    .contact-cta {
        padding: 2.5rem;
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
    
    
    
    .contact-container {
        grid-template-columns: 1fr;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
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
    
    .contact-info h2,
    .contact-form h2,
    .contact-cta h2 {
        font-size: 1.875rem;
    }
    
    .contact-info,
    .contact-form,
    .contact-cta {
        padding: 2rem;
    }
    
    .contact-cta {
        padding: 2.5rem 2rem;
    }
    
    .contact-cta h2 {
        font-size: 1.75rem;
    }
    
    .info-box {
        padding: 1.25rem;
    }
    
    .info-box i {
        width: 45px;
        height: 45px;
        font-size: 1.25rem;
    }
    
    .info-box p {
        font-size: 1rem;
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
    
    .page-header {
        margin-bottom: 1.5rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .contact-info,
    .contact-form {
        padding: 1.75rem 1.25rem;
    }
    
    .contact-cta {
        padding: 2rem 1.25rem;
    }
    
    .form-group input,
    .form-group textarea {
        padding: 0.875rem 1rem;
    }
    
    .submit-btn,
    .cta-btn {
        padding: 1rem 2rem;
    }
}

@media (max-width: 360px) {
    .page-header h1 {
        font-size: 1.75rem;
    }
    
    .contact-info h2,
    .contact-form h2,
    .contact-cta h2 {
        font-size: 1.5rem;
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
    background: var(--muted);
    border-radius: 3px;
}

.desktop-main::-webkit-scrollbar-thumb:hover {
    background: var(--muted-foreground);
}

/* Print Styles */
@media print {
    body {
        background: white;
        color: black;
    }
    
    
    
    .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }
    
    .contact-info,
    .contact-form,
    .contact-cta {
        border: 1px solid #000;
        box-shadow: none;
    }
```html
}

        
        

        
        

        

        

        

        

        
        

        

        

        

        

        

        

        

        .ripple-container {
            position: relative;
            overflow: hidden;
        }

</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
<!-- TV Noise Effect -->
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
    <!-- Left Sidebar - Navigation -->
                    <?php include 'sidebar.php'; ?>

    <div class="desktop-main">
        <!-- HEADER -->
        <section class="page-header animate-fadeIn">
            <h1>Contact Secure Worldz Academy</h1>
            <p>Let's connect and build your future in technology</p>
        </section>

        <!-- CONTACT CONTAINER -->
        <div class="contact-container">
            <!-- CONTACT INFO -->
            <div class="contact-info animate-fadeIn delay-1">
                <h2>Get in Touch</h2>
                <p>Have questions about our courses or platform? Send us a message and our team will reach out to you.</p>

                <div class="info-box animate-fadeIn delay-2">
                    <i class="fa-solid fa-envelope"></i>
                    <p>Secure Worldz Academyacademy@gmail.com</p>
                </div>

                <div class="info-box animate-fadeIn delay-3">
                    <i class="fa-solid fa-phone"></i>
                    <p>+91 98765 43210</p>
                </div>

                <div class="info-box animate-fadeIn delay-4">
                    <i class="fa-solid fa-map-marker-alt"></i>
                    <p>India</p>
                </div>
            </div>

            <!-- CONTACT FORM -->
            <form class="contact-form animate-fadeIn delay-2" id="contactForm">
                <h2>Send Message</h2>
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email address">
                </div>

                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" required placeholder="Enter your message here..."></textarea>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                    Send Message
                </button>
            </form>
        </div>


        <!-- FOOTER -->
        <footer class="footer animate-fadeIn delay-4">
            <p>&copy; 2026 Secure Worldz Academy. All rights reserved.</p>
        </footer>
    </div>
</div>

<script>
// DOM Elements
const contactForm = document.getElementById('contactForm');
const submitBtn = document.getElementById('submitBtn');

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

// Add hover effects to info boxes
document.querySelectorAll('.info-box').forEach(box => {
    box.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(8px)';
    });
    
    box.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
    });
});

// Form submission
contactForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();
    
    // Form validation
    if (!name || !email || !message) {
        showNotification('Please fill in all fields', 'error');
        return;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showNotification('Please enter a valid email address', 'error');
        return;
    }
    
    // Disable submit button and show loading
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Show success message
        showNotification('Message sent successfully! We will get back to you soon.', 'success');
        
        // Reset form
        contactForm.reset();
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Add success animation
        submitBtn.style.animation = 'none';
        setTimeout(() => {
            submitBtn.style.animation = 'pulse 2s infinite';
        }, 10);
    }, 2000);
});

// Notification function
function showNotification(message, type) {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button class="close-notification">&times;</button>
    `;
    
    // Add close button event
    const closeBtn = notification.querySelector('.close-notification');
    closeBtn.addEventListener('click', () => {
        notification.style.animation = 'slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1) reverse';
        setTimeout(() => notification.remove(), 400);
    });
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1) reverse';
            setTimeout(() => notification.remove(), 400);
        }
    }, 5000);
    
    // Add to document
    document.body.appendChild(notification);
}

// Add hover effects to form inputs
document.querySelectorAll('.form-group input, .form-group textarea').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
        const label = this.parentElement.querySelector('label');
        if (label) {
            label.style.color = 'var(--primary)';
        }
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
        if (!this.value) {
            const label = this.parentElement.querySelector('label');
            if (label) {
                label.style.color = '';
            }
        }
    });
});

// Navigation active states
</script>

</div>

<footer class="footer" style="text-align: center; padding: 2rem; color: var(--muted-foreground); border-top: 1px solid var(--border); margin-top: auto; font-size: 0.875rem;">
    <p>&copy; 2026 Secure Worldz Academy Ecosystem. All rights reserved.</p>
</footer>

<script>
// Heartbeat & Online Status
setInterval(() => {
    fetch('api/heartbeat.php').then(r => r.json()).catch(e => console.error("Heartbeat error:", e));
}, 60000);
</script>
</body>
</html>
