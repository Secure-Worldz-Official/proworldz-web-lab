
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Secure Worldz Academy</title>
    <meta name="description" content="Get in touch with Secure Worldz Academy. Reach us by WhatsApp, email, or phone.">
    <link rel="icon" type="image/webp" href="image.webp">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
            --gap: 1.5rem;
            --sides: 1.5rem;
    --primary-red: #ff2a2f;
    --primary-red-hover: #e6191e;
    --secondary-red: #ff2a2f;
    --primary-red: #ff2a2f;
    --secondary-red: #ff2a2f;
    --text-primary: #ffffff;
    --text-secondary: #a0a0a0;
    --text-muted: #737373;
    --border-color: rgba(139, 12, 16, 0.12);
    --border-hover: rgba(255, 42, 47, 0.25);
    --card-bg: rgba(255, 255, 255, 0.02);
    --card-hover: rgba(255, 255, 255, 0.04);
    --dark-bg: #000000;
    --darker-bg: #000000;
    --container-width: 1280px;
    --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-fast: all 0.2s ease;
    --shadow-glow: 0 0 40px rgba(139, 12, 16, 0.15);
    --shadow-intense: 0 20px 60px rgba(139, 12, 16, 0.2);
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--dark-bg);
    color: var(--text-primary);
    line-height: 1.6;
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 700;
    line-height: 1.2;
}

.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition-smooth);
}

.navbar.scrolled {
    background: rgba(0, 0, 0, 0.95);
    border-bottom: 1px solid var(--border-hover);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.nav-container {
    max-width: var(--container-width);
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
    transition: var(--transition-fast);
}

.logo:hover {
    transform: translateY(-2px);
}

.logo-accent {
    color: var(--primary-red);
}

.logo-img {
    width: 40px;
    height: 40px;
    object-fit: contain;
    filter: brightness(1.2);
    transition: var(--transition-smooth);
}

.logo:hover .logo-img {
    transform: rotate(10deg) scale(1.1);
    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
}

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
    transition: var(--transition-fast);
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
.nav-links a.active {
    color: var(--text-primary);
}

.nav-links a:hover::before,
.nav-links a.active::before {
    width: 100%;
}

        .nav-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.75rem;
    background: var(--primary-red);
    color: var(--dark-bg);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    border-radius: 8px;
    transition: var(--transition-smooth);
    border: 1px solid transparent;
}

.nav-cta:hover {
    background: var(--primary-red-hover);
    box-shadow: var(--shadow-glow);
    transform: translateY(-2px);
    color: var(--dark-bg);
}

.menu-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    padding: 0.5rem;
    z-index: 1001;
    background: none;
    border: none;
}

.menu-toggle span {
    width: 24px;
    height: 2px;
    background: var(--text-primary);
    transition: var(--transition-fast);
}

.menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.menu-toggle.active span:nth-child(2) {
    opacity: 0;
}

.menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

.hero {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: clamp(4rem, 6vw, 6rem) 2rem clamp(2rem, 3vw, 3rem);
    background: #000000;
    margin-top: clamp(60px, 8vw, 80px);
}

.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: 
        linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.03) 50%, transparent 100%),
        repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(255, 255, 255, 0.01) 2px, rgba(255, 255, 255, 0.01) 4px);
    pointer-events: none;
}

.hero-content {
    max-width: 900px;
    text-align: center;
    position: relative;
    z-index: 2;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    background: rgba(139, 12, 16, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    font-size: clamp(0.75rem, 1.5vw, 0.85rem);
    font-weight: 600;
    color: var(--secondary-red);
    margin-bottom: clamp(1rem, 2vw, 2rem);
    animation: fadeInDown 0.8s ease;
}

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

.hero h1 {
    font-size: clamp(2rem, 5vw, 4.5rem);
    font-weight: 900;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, var(--text-primary) 0%, var(--secondary-red) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: fadeInUp 0.8s ease 0.2s both;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.hero p {
    font-size: clamp(1rem, 2vw, 1.3rem);
    color: var(--text-secondary);
    margin-bottom: 3rem;
    line-height: 1.8;
    animation: fadeInUp 0.8s ease 0.4s both;
}

.contact-section {
    padding: clamp(2rem, 4vw, 4rem) 2rem;
    background: var(--dark-bg);
    position: relative;
}

.section-header {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 2.5rem;
}

.section-badge {
    display: inline-block;
    padding: 0.5rem 1.25rem;
    background: rgba(139, 12, 16, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--secondary-red);
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.section-title {
    font-size: clamp(1.75rem, 4vw, 3rem);
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.section-description {
    font-size: clamp(0.95rem, 2vw, 1.15rem);
    color: var(--text-secondary);
    line-height: 1.8;
}

.contact-cards {
    max-width: var(--container-width);
    margin: 0 auto 6rem;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5rem;
}

.contact-card {
    background: var(--card-bg);
    padding: 3rem 2.5rem;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    text-align: center;
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
}

.contact-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-red), var(--secondary-red));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.5s ease;
}

.contact-card:hover {
    background: var(--card-hover);
    border-color: var(--border-hover);
    transform: translateY(-10px);
    box-shadow: var(--shadow-intense);
}

.contact-card:hover::before {
    transform: scaleX(1);
}

.contact-icon {
    width: 80px;
    height: 80px;
    background: rgba(139, 12, 16, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    transition: var(--transition-smooth);
}

.contact-card:hover .contact-icon {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1) rotate(5deg);
}

.contact-icon i {
    font-size: 2.5rem;
    color: var(--text-primary);
}

.contact-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--text-primary);
}

.contact-card p {
    color: var(--text-secondary);
    line-height: 1.7;
    font-size: 1.1rem;
}

.contact-form-section {
    padding: 0 2rem 3rem;
    background: var(--darker-bg);
    position: relative;
}

.contact-form-box {
    background: var(--card-bg);
    padding: 4rem;
    border-radius: 24px;
    max-width: 800px;
    margin: 0 auto;
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
}

.contact-form-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-red), var(--secondary-red));
}

.contact-form-box h2 {
    text-align: center;
    font-family: 'Space Grotesk', sans-serif;
    font-size: clamp(1.75rem, 3vw, 2.5rem);
    margin-bottom: 3rem;
    background: linear-gradient(135deg, var(--text-primary) 0%, var(--secondary-red) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.input-group {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.form-group {
    position: relative;
}

input, textarea {
    width: 100%;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    padding: 1.25rem 1.5rem;
    border-radius: 12px;
    font-size: 1rem;
    font-family: 'Inter', sans-serif;
    transition: var(--transition-smooth);
}

input::placeholder,
textarea::placeholder {
    color: var(--text-muted);
    opacity: 0.7;
}

input:focus,
textarea:focus {
    outline: none;
    border-color: var(--primary-red);
    box-shadow: 0 0 0 3px rgba(139, 12, 16, 0.1);
    transform: translateY(-2px);
}

textarea {
    min-height: 180px;
    resize: vertical;
}

.submit-btn {
    background: linear-gradient(135deg, #ffffff 0%, #d0d0d0 100%);
    border: none;
    padding: 1.25rem 3rem;
    border-radius: 12px;
    color: #000000;
    font-size: 1.1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    cursor: pointer;
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
    font-family: 'Space Grotesk', sans-serif;
    width: 100%;
    margin-top: 1rem;
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
    background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
    box-shadow: var(--shadow-intense);
    transform: translateY(-3px);
    color: #000000;
}

.footer {
    padding: 4rem 2rem 2rem;
    background: var(--darker-bg);
    border-top: 1px solid var(--border-color);
}

.footer-container {
    max-width: var(--container-width);
    margin: 0 auto;
    text-align: center;
}

.footer-bottom {
    padding-top: 2rem;
    border-top: 1px solid rgba(139, 12, 16, 0.05);
    color: var(--text-muted);
    font-size: 0.9rem;
}

.fade-in-up {
    opacity: 0;
    animation: fadeInUp 0.8s ease forwards;
}

.fade-in-up:nth-child(1) { animation-delay: 0.1s; }
.fade-in-up:nth-child(2) { animation-delay: 0.2s; }
.fade-in-up:nth-child(3) { animation-delay: 0.3s; }
.fade-in-up:nth-child(4) { animation-delay: 0.4s; }

@media (max-width: 1024px) {
    .contact-cards {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
    
    .contact-card:last-child {
        grid-column: span 2;
        max-width: 400px;
        margin: 0 auto;
    }
}

@media (max-width: 768px) {
    .menu-toggle {
        display: flex;
    }
    
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
        border-left: 1px solid var(--border-color);
        z-index: 1000;
        list-style: none;
    }
    
    .nav-links.active {
        right: 0;
    }
    
    .nav-links .nav-cta {
        display: flex !important;
        margin-top: 1rem;
        justify-content: center;
    }
    
    .nav-container > .nav-cta {
        display: none !important;
    }
    
    .hero {
        padding: 80px 1.5rem 2.5rem;
        min-height: auto;
        margin-top: 0;
    }
    
    .contact-cards {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .contact-card:last-child {
        grid-column: 1;
        max-width: 100%;
    }
    
    .contact-form-box {
        padding: 2.5rem 1.5rem;
    }
    
    .input-group {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .contact-card {
        padding: 2rem 1.5rem;
    }
}

@media (max-width: 480px) {
    .hero {
        padding: 3.5rem 1rem 2rem;
        margin-top: 60px;
    }
    
    .contact-card {
        padding: 2rem 1.5rem;
    }
    
    .contact-icon {
        width: 70px;
        height: 70px;
    }
    
    .contact-icon i {
        font-size: 2rem;
    }
    
    .contact-form-box {
        padding: 2.5rem 1.5rem;
    }
    
    input, textarea {
        padding: 1rem 1.25rem;
    }
    
    .submit-btn {
        padding: 1rem 2rem;
        font-size: 1rem;
    }
    
    .footer {
        padding: 2rem 1rem 1rem;
    }
}

body, h1, h2, h3, h4, h5, h6, p, span, div, li, a {
    font-weight: 700 !important;
}
</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <img src="images/eaglone/p-eaglone.png" alt="Secure Worldz Academy Logo" class="logo-img" loading="lazy">
                <span class="logo-text">
                    Secure<span class="logo-accent"> Worldz Academy</span>
                </span>
            </a>

            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li><a href="about-home.php">About</a></li>
                <li><a href="contact-home.php" class="active">Contact</a></li>
                <li><a href="swa-lab.php">Lab</a></li>
                <li><a href="owasp-2026-landing.php">OWASP 2026 challanges</a></li>
            </ul>

            <a href="login.php" class="nav-cta">
                <i class="fas fa-sign-in-alt"></i>
                Login
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-headset"></i>
                Get in Touch
            </div>
            <h1>Contact Secure Worldz Academy</h1>
            <p>Have questions about the platform, courses, or accessing the OWASP 2026 Lab? Our team is ready to help. Reach out via WhatsApp for the fastest response.</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="section-header fade-in-up">
            <div class="section-badge">Contact Information</div>
            <h2 class="section-title">How to Reach Us</h2>
            <p class="section-description">Choose your preferred method of communication. We're committed to providing timely and helpful responses.</p>
        </div>

        <div class="contact-cards">
            <div class="contact-card fade-in-up">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p>Proworldzacademy@gmail.com</p>
            </div>

            <div class="contact-card fade-in-up">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3>Phone</h3>
                <p>+91 78450 88387</p>
            </div>

            <div class="contact-card fade-in-up">
                <div class="contact-icon">
                    <i class="fas fa-location-dot"></i>
                </div>
                <h3>Location</h3>
                <p>Chennai, Tamil Nadu</p>
            </div>
        </div>
    </section>

    <!-- WhatsApp CTA Section -->
    <section class="contact-form-section">
        <div class="contact-form-box fade-in-up" style="text-align:center;">
            <div style="width:80px;height:80px;background:rgba(255,42,47,0.1);border:1px solid rgba(255,42,47,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 2rem;">
                <i class="fab fa-whatsapp" style="font-size:2.5rem;color:#25d366;"></i>
            </div>
            <h2>Chat With Us on WhatsApp</h2>
            <p style="color:var(--text-secondary);font-size:1.1rem;line-height:1.8;margin-bottom:2.5rem;">
                The fastest way to reach us is through WhatsApp. Tap the button below and we'll respond as soon as possible.
            </p>
            <a href="https://wa.me/917845088387?text=Hi" target="_blank" rel="noopener noreferrer"
               id="whatsapp-cta-btn"
               style="display:inline-flex;align-items:center;gap:0.75rem;padding:1.1rem 2.75rem;background:linear-gradient(135deg,#25d366,#128c7e);color:#fff;font-size:1.1rem;font-weight:700;border-radius:12px;text-decoration:none;transition:all 0.3s ease;box-shadow:0 4px 20px rgba(255,42,47,0.3);font-family:'Space Grotesk',sans-serif;">
                <i class="fab fa-whatsapp" style="font-size:1.4rem;"></i>
                Message Us on WhatsApp
            </a>
            <!-- <p style="margin-top:1.5rem;color:var(--text-muted);font-size:0.9rem;">+91 99449 94778 &nbsp;·&nbsp; Response within a few hours</p> -->
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-bottom">
                <p>&copy; 2026 Secure Worldz Academy. All rights reserved. | Privacy Policy | Terms of Service</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
         }, { passive: true });

        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');

        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            menuToggle.classList.toggle('active');
            
            // Animate hamburger
            const spans = menuToggle.querySelectorAll('span');
            if (navLinks.classList.contains('active')) {
                spans[0].style.transform = 'rotate(45deg) translateY(10px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translateY(-10px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });

        // Close mobile menu on link click
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                menuToggle.classList.remove('active');
                const spans = menuToggle.querySelectorAll('span');
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            });
        });

        // Form submission
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Get form values
                const name = contactForm.querySelector('input[type="text"]').value;
                const email = contactForm.querySelector('input[type="email"]').value;
                const subject = contactForm.querySelectorAll('input[type="text"]')[1].value;
                const message = contactForm.querySelector('textarea').value;
                
                // In a real application, you would send this data to a server
                // For demo purposes, we'll show a success message
                alert(`Thank you, ${name}! Your message has been sent successfully.\nWe'll respond to ${email} within 24 hours.`);
                
                // Reset form
                contactForm.reset();
                
                // Add visual feedback
                const submitBtn = contactForm.querySelector('.submit-btn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Message Sent!';
                submitBtn.style.background = 'var(--success)';
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.style.background = '';
                }, 3000);
            });
        }

        // Intersection Observer for fade-in animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Observe all animated elements
        document.querySelectorAll('.fade-in-up').forEach(el => {
            observer.observe(el);
        });

        // Add hover effects to cards
        document.querySelectorAll('.contact-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Active nav link on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinksAll = document.querySelectorAll('.nav-links a');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.scrollY >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            navLinksAll.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
       }, { passive: true });

        // Initialize active state for contact page
        document.addEventListener('DOMContentLoaded', () => {
            const contactLink = document.querySelector('.nav-links a[href*="contact"]');
            if (contactLink) {
                contactLink.classList.add('active');
            }
        });

        // Form input focus effects
        const inputs = document.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
