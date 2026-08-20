<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Worldz Academy | Cybersecurity Training Platform</title>
    <meta name="description" content="Secure Worldz Academy provides hands on cybersecurity training, web labs, AI integrated IDE, and competitive security challenges.">
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
            --primary-red-hover: #ff2a2f;
            --secondary-red: #ff2a2f;
            --accent-red: #ff2a2f;
            --dark-bg: #000000;
            --darker-bg: #000000;
            --card-bg: #080808;
            --card-hover: #080808;
            --text-primary: #ffffff;
            --text-secondary: #a3a3a3;
            --text-muted: #737373;
            --border-color: rgba(139, 12, 16, 0.1);
            --border-hover: rgba(255, 255, 255, 0.2);
            
            /* Spacing */
            --container-width: 1280px;
            --section-padding: 8rem 2rem;
            --card-padding: 2rem;
            
            /* Effects */
            --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.2s ease;
            --shadow-glow: 0 0 40px rgba(139, 12, 16, 0.1);
            --shadow-intense: 0 20px 60px rgba(139, 12, 16, 0.15);
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

        /* ========================================
           NAVIGATION
        ======================================== */
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

        /* ========================================
           HERO SECTION
        ======================================== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 8rem 2rem 6rem;
            background: 
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 12, 16, 0.05) 0%, transparent 50%),
                var(--darker-bg);
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
            border: 1px solid rgba(255, 42, 47, 0.3);
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--secondary-red);
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease;
        }

        .hero-badge i {
            animation: sparkle 1.5s infinite;
        }

        @keyframes sparkle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--secondary-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero p {
            font-size: 1.3rem;
            color: var(--text-secondary);
            margin-bottom: 3rem;
            line-height: 1.8;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .hero-cta-group {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.6s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: var(--primary-red);
            color: #fff;
            box-shadow: 0 4px 20px rgba(139, 12, 16, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-red-hover);
            box-shadow: var(--shadow-intense);
            transform: translateY(-3px);
            color: #fff;
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-primary);
            border-color: rgba(255, 42, 47, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(139, 12, 16, 0.08);
            border-color: var(--primary-red);
            transform: translateY(-3px);
        }

        /* ========================================
           FEATURES SECTION
        ======================================== */
        .features {
            padding: var(--section-padding);
            background: var(--dark-bg);
            position: relative;
        }

        .section-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 5rem;
        }

        .section-badge {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            background: rgba(139, 12, 16, 0.1);
            border: 1px solid rgba(255, 42, 47, 0.3);
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--secondary-red);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .section-title {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
        }

        .section-description {
            font-size: 1.15rem;
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .features-grid {
            max-width: var(--container-width);
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
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

        .feature-card:hover {
            background: var(--card-hover);
            border-color: var(--border-hover);
            transform: translateY(-8px);
            box-shadow: var(--shadow-glow);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(139, 12, 16, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: var(--transition-smooth);
        }

        .feature-card:hover .feature-icon {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1) rotate(5deg);
        }

        .feature-icon i {
            font-size: 1.75rem;
            color: var(--text-primary);
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .feature-card p {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* ========================================
           COURSES SECTION
        ======================================== */
        .courses {
            padding: var(--section-padding);
            background: var(--darker-bg);
            position: relative;
        }

        .courses-grid {
            max-width: var(--container-width);
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 2.5rem;
        }

        .course-card {
            background: var(--card-bg);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: var(--transition-smooth);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .course-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, rgba(139, 12, 16, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
        }

        .course-card:hover {
            border-color: var(--primary-red);
            transform: translateY(-10px);
            box-shadow: var(--shadow-intense);
        }

        .course-card:hover::after {
            opacity: 1;
        }

        .course-image {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(139, 12, 16, 0.1) 0%, rgba(139, 12, 16, 0.05) 100%);
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
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-primary);
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
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .course-card:hover .course-body h3 {
            color: var(--secondary-red);
        }

        .course-body p {
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 2rem;
            flex-grow: 1;
        }

        .course-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(139, 12, 16, 0.05);
        }

        .course-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: var(--primary-red);
            color: var(--dark-bg);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 10px;
            transition: var(--transition-smooth);
            width: 100%;
            justify-content: center;
        }

        .course-action:hover {
            background: var(--primary-red-hover);
            box-shadow: var(--shadow-glow);
            transform: translateX(5px);
            color: var(--dark-bg);
        }

        .course-action i {
            transition: transform 0.3s ease;
        }

        .course-action:hover i {
            transform: translateX(5px);
        }

        /* ========================================
           STATS SECTION
        ======================================== */
        .stats {
            padding: 6rem 2rem;
            background: var(--card-bg);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .stats-container {
            max-width: var(--container-width);
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
            background: linear-gradient(135deg, var(--primary-red), var(--secondary-red));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            font-family: 'Space Grotesk', sans-serif;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* ========================================
           CTA SECTION
        ======================================== */
        .cta-section {
            padding: 8rem 2rem;
            background: 
                radial-gradient(circle at center, rgba(139, 12, 16, 0.08) 0%, transparent 70%),
                var(--darker-bg);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: 
                repeating-linear-gradient(90deg, transparent, transparent 80px, rgba(255, 255, 255, 0.03) 80px, rgba(255, 255, 255, 0.03) 82px);
            pointer-events: none;
        }

        .cta-content {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .cta-content h2 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--secondary-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta-content p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 3rem;
            line-height: 1.8;
        }

        /* ========================================
           FOOTER
        ======================================== */
        .footer {
            padding: 4rem 2rem 2rem;
            background: var(--darker-bg);
            border-top: 1px solid var(--border-color);
        }

        .footer-container {
            max-width: var(--container-width);
            margin: 0 auto;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 3rem;
        }

        .footer-brand h3 {
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .footer-brand .logo-accent {
            color: var(--primary-red);
        }

        .footer-brand p {
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(139, 12, 16, 0.1);
            border: 1px solid rgba(255, 42, 47, 0.25);
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .social-link:hover {
            background: var(--primary-red);
            color: #fff;
            transform: translateY(-3px);
        }

        .footer-column h4 {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: var(--primary-red);
            transform: translateX(5px);
        }

        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid rgba(139, 12, 16, 0.05);
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* ========================================
           ANIMATIONS
        ======================================== */
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

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards;
        }

        /* Stagger animations */
        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }
        .feature-card:nth-child(4) { animation-delay: 0.4s; }
        .feature-card:nth-child(5) { animation-delay: 0.5s; }
        .feature-card:nth-child(6) { animation-delay: 0.6s; }

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

        /* ===== LOGO STYLES WITH IMAGE ===== */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.75rem;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            color: var(--text-primary);
            text-decoration: none;
            transition: var(--transition-fast);
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

        .logo-text {
            display: flex;
            align-items: center;
            gap: 0.1rem;
        }

        .logo-icon {
            display: none;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
        }
        body, p, span, td, li {
            font-weight: 400;
        }

        /* ========================================
           RESPONSIVE ENHANCEMENTS
           (Add this at the end of your CSS)
        ======================================== */

        /* Course Grid Improvements - 2 cards always centered */
        @media (min-width: 768px) {
            .courses-grid {
                grid-template-columns: repeat(2, minmax(300px, 380px));
                justify-content: center;
            }
        }

        /* Enhanced Mobile Responsiveness */
       @media (max-width: 767px) {
    .menu-toggle {
        display: flex;
        z-index: 1001;
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
    }

    .nav-links.active {
        right: 0;
    }

    .nav-cta {
        display: none;
    }

    .hero {
        padding: 100px 1.5rem 4rem;
        min-height: 90vh;
    }

    .hero h1 {
        font-size: 2.5rem;
    }

    .hero p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    .hero-cta-group {
        flex-direction: column;
        gap: 1rem;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .section-header {
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 2rem;
    }

    .section-description {
        font-size: 1rem;
    }

    .features-grid,
    .courses-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 0;
    }

    .feature-card {
        padding: 1.5rem;
    }

    .course-body {
        padding: 1.5rem;
    }

    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        padding: 0 1rem;
    }

    .stat-number {
        font-size: 2.5rem;
    }

    .cta-section {
        padding: 4rem 1.5rem;
    }

    .cta-content h2 {
        font-size: 2.5rem;
    }

    .footer {
        padding: 3rem 1.5rem 1.5rem;
    }

    .footer-top {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}
        /* Tablet Responsiveness */
        @media (min-width: 768px) and (max-width: 1024px) {
            .hero h1 {
                font-size: 3rem;
            }
            
            .hero p {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
            
            .footer-top {
                grid-template-columns: repeat(2, 1fr);
                gap: 3rem;
            }
            
            /* Tablet hero spacing */
            .hero {
                padding: 6rem 2rem 4rem;
            }
            
            /* Tablet section spacing */
            .features,
            .courses,
            .cta-section {
                padding: 6rem 2rem;
            }
            
            /* Tablet navigation */
            .nav-links {
                gap: 2rem;
            }
        }

        /* Small Mobile Devices (up to 480px) */
        @media (max-width: 480px) {
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .hero p {
                font-size: 0.95rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .section-description {
                font-size: 0.9rem;
            }
            
            .hero-badge,
            .section-badge {
                font-size: 0.75rem;
                padding: 0.4rem 1rem;
            }
            
            .stat-number {
                font-size: 1.8rem;
            }
            
            .stat-label {
                font-size: 0.9rem;
            }
            
            /* Ultra-compact mobile menu */
            .nav-container {
                padding: 1rem;
            }
            
            .logo {
                font-size: 1.5rem;
            }
            
            .logo-img {
                width: 32px;
                height: 32px;
            }
            
            /* Compact feature cards */
            .feature-card {
                padding: 1.25rem;
            }
            
            .feature-card h3 {
                font-size: 1.25rem;
            }
            
            /* Compact course cards */
            .course-body {
                padding: 1.5rem;
            }
            
            .course-body h3 {
                font-size: 1.4rem;
            }
            
            .course-action {
                padding: 0.75rem 1.5rem;
                font-size: 0.85rem;
            }
        }

        /* Extra Large Screens (1440px and above) */
        @media (min-width: 1440px) {
            .courses-grid {
                grid-template-columns: repeat(3, 400px);
                justify-content: center;
                gap: 3rem;
            }
            
            /* Center the last card */
            .courses-grid .course-card:last-child:nth-child(3n+1) {
                grid-column: 2;
            }
        }

        /* Landscape Mobile Devices */
        @media (max-height: 600px) and (orientation: landscape) and (max-width: 767px) {
            .hero {
                min-height: 120vh;
                padding: 4rem 2rem;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .nav-links {
                max-height: 80vh;
                overflow-y: auto;
            }
        }

        /* Fix for iOS Safari 100vh issue */
        @supports (-webkit-touch-callout: none) {
            .hero {
                min-height: -webkit-fill-available;
            }
        }

        /* Touch Device Optimizations */
        @media (hover: none) and (pointer: coarse) and (max-width: 767px) {
            .btn,
            .course-action,
            .nav-cta,
            .social-link {
                min-height: 44px;
                min-width: 44px;
            }
            
            .nav-links a {
                padding: 0.5rem;
                display: inline-block;
                min-height: 44px;
                line-height: 44px;
            }
            
            /* Remove hover effects on touch devices */
            .course-card:hover,
            .feature-card:hover {
                transform: none;
            }
            
            .btn:hover,
            .course-action:hover,
            .social-link:hover {
                transform: none;
            }
        }

        /* Fixed Positioning for Mobile */
        @media (max-width: 768px) {
            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                background: rgba(0, 0, 0, 0.95);
                backdrop-filter: blur(10px);
            }
            
            /* Prevent horizontal scroll */
            body {
                overflow-x: hidden;
                width: 100%;
            }
            
            /* Ensure content doesn't hide behind fixed navbar */
            .hero {
                padding-top: 80px;
            }
        }

        .courses-grid {
    max-width: var(--container-width);
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 380px));
    gap: 2.5rem;
    padding: 0 1rem;
    justify-content: center;
}

/* Tablet: 2 columns */
@media (min-width: 768px) and (max-width: 1199px) {
    .courses-grid {
        grid-template-columns: repeat(2, 1fr);
        justify-content: center;
    }
    
    /* Center the last course card on tablet when odd number */
    .courses-grid .course-card:last-child:nth-child(odd) {
        grid-column: 1 / span 2;
        justify-self: center;
        max-width: 380px;
    }
}

/* Desktop: keep centered */
@media (min-width: 1200px) {
    .courses-grid {
        grid-template-columns: repeat(2, 380px);
        justify-content: center;
    }
}
        /* Smooth scroll snap for sections */
        @media (min-width: 1024px) {
            .hero,
            .features,
            .courses,
            .cta-section {
                scroll-snap-align: start;
            }
        }

/* Fix for images on mobile */
img {
    max-width: 100%;
    height: auto;
    display: block;
}

.course-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

@media (max-width: 480px) {
    .course-image {
        height: 180px;
    }
    
    .hero h1 {
        font-size: 2rem;
        line-height: 1.1;
    }
    
    .hero p {
        font-size: 1rem;
    }
}

/* Ensure proper container widths */
.nav-container,
.section-header,
.courses-grid,
.features-grid,
.footer-container,
.stats-container {
    width: 100%;
    max-width: var(--container-width);
    margin-left: auto;
    margin-right: auto;
    padding-left: 1rem;
    padding-right: 1rem;
}

@media (min-width: 768px) {
    .nav-container,
    .section-header,
    .courses-grid,
    .features-grid,
    .footer-container,
    .stats-container {
        padding-left: 2rem;
        padding-right: 2rem;
    }
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
                    Secure<span class="logo-accent"> <b>Worldz Academy</b></span>
                </span>
            </a>

            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="about-home.php">About</a></li>
                <li><a href="contact-home.php">Contact</a></li>
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
                <i class="fas fa-shield-halved"></i>
                Cybersecurity Training Platform
            </div>
            <h1>Security Skills That Hold Up Under Pressure</h1>
            <p>Secure Worldz Academy trains professionals through hands-on labs, real attack simulations, and structured programs in offensive and defensive security. No filler. Just work that builds actual capability.</p>
            <div class="hero-cta-group">
                <a href="login.php" class="btn btn-primary">
                    Access the Platform
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#courses" class="btn btn-secondary">
                    <i class="fas fa-book"></i>
                    Explore Courses
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header">
            <div class="section-badge">Platform Capabilities</div>
            <h2 class="section-title">Built for Security Practitioners</h2>
            <p class="section-description">Every feature in the platform is designed around one goal: giving security students and professionals the environment they need to develop real skills through direct experimentation.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <h3>Web-Based Security Lab</h3>
                <p>A fully integrated lab environment where you can run code, complete security challenges, and experiment with real attack and defense scenarios. All inside the browser.</p>
            </div>

            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <i class="fas fa-terminal"></i>
                </div>
                <h3>Linux Terminal</h3>
                <p>A built-in Linux terminal runs directly inside the lab. Work with real commands, complete practical Linux-based challenges, and develop genuine command-line proficiency.</p>
            </div>

            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3>AI-Integrated IDE</h3>
                <p>Write, run, and test code in a web-based IDE with AI assistance built in. Supports multiple languages. Designed for security programming and tooling work.</p>
            </div>

            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h3>OWASP 2026 Lab</h3>
                <p>Dedicated lab environment covering the OWASP 2026 Top 10 AI security categories. Twelve structured labs across beginner to advanced difficulty. Authenticate and enter at your own pace.</p>
            </div>

            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>Tournaments & Teams</h3>
                <p>Compete in team versus team web tournaments. Build a team, recruit members, and measure your progress against other groups in structured competitive challenges.</p>
            </div>

            <div class="feature-card fade-in-up">
                <div class="feature-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <h3>Eagle Coin Rewards</h3>
                <p>Complete programming and Linux challenges to earn Eagle Coins, the platform's built in reward currency. Track your progress and rank on the leaderboard.</p>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section class="courses" id="courses">
        <div class="section-header">
            <div class="section-badge">Featured Programs</div>
            <h2 class="section-title">Explore Our Flagship Courses</h2>
            <p class="section-description">Gain hands-on expertise in cybersecurity, offensive and defensive techniques, and AI-powered development with industry-recognized training programs.</p>
        </div>

        <div class="courses-grid">
            <!-- 1st Course: Cyber Security Career -->
            <div class="course-card fade-in-up">
                <div class="course-image">
                    <img src="cyber-sec-car.png" alt="Cyber Security Career" loading="lazy">
                    <div class="course-badge">Career</div>
                </div>
                <div class="course-body">
                    <h3>Cyber Security For From Career</h3>
                    <p>Master comprehensive cybersecurity skills, penetration testing, threat analysis, and digital defense strategies with real-world practical labs.</p>
                    <a href="https://tagmango.app/ec517d00e6" target="_blank" rel="noopener noreferrer" class="course-action">
                        Register Now
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- 2nd Course: Dev With AI -->
            <div class="course-card fade-in-up">
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

        </div>
    </section>



    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number">30K+</div>
                <div class="stat-label">Active Students</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Expert Instructors</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">98%</div>
                <div class="stat-label">Success Rate</div>
            </div>
            <div class="stat-item">
                <!-- <div class="stat-number">24/7</div> -->
                 <div style="animation: none !important; font-size: 3rem !important; background: linear-gradient(135deg, var(--primary-red), var(--secondary-red)) !important; -webkit-background-clip: text !important; -webkit-text-fill-color: transparent !important; background-clip: text !important;margin-bottom:8px">24/7</div>
                <div class="stat-label">Support Available</div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2>Start Working in the Lab</h2>
            <p>Security skills do not develop from reading about them. Log in, enter the lab environment, and start working through real challenges at your own pace.</p>
            <div class="hero-cta-group">
                <a href="login.php" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Login to the Platform
                </a>
                <a href="contact-home.php" class="btn btn-secondary">
                    <i class="fas fa-headset"></i>
                    Contact Us
                </a>
            </div>
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

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in-up').forEach(el => {
            observer.observe(el);
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

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            
            if (heroEl && scrolled < window.innerHeight) {
                window.requestAnimationFrame(() => {
                    heroEl.style.transform = `translateY(${scrolled * 0.5}px)`;
                    heroEl.style.opacity = (1 - (scrolled / window.innerHeight)).toFixed(2);
                });
            }
         }, { passive: true });

        // Counter animation for stats
        const animateCounter = (element, target, duration = 2000) => {
            let start = 0;
            const increment = target / (duration / 16);
            const timer = setInterval(() => {
                start += increment;
                if (start >= target) {
                    element.textContent = target.toString().includes('+') ? target : target + '+';
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(start).toString();
                }
            }, 16);
        };

        // Trigger counter animation when stats section is visible
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    statNumbers.forEach(stat => {
                        const text = stat.textContent;
                        const number = parseInt(text.replace(/\D/g, ''));
                        stat.textContent = '0';
                        setTimeout(() => {
                            animateCounter(stat, number);
                        }, 200);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        const statsSection = document.querySelector('.stats');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }

        // Add hover effect to cards
        document.querySelectorAll('.course-card, .feature-card').forEach(card => {
            card.addEventListener('mouseenter', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                this.style.setProperty('--mouse-x', `${x}px`);
                this.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    </script>
</body>
</html>