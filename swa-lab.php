<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab | Secure Worldz Academy</title>
    <meta name="description" content="Explore the Secure Worldz Academy Lab. A hands on cybersecurity training environment with built in IDE, Linux terminal, security challenges, OWASP labs, and team competitions.">
    <link rel="icon" type="image/webp" href="image.webp">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-red: #ff2a2f;
            --primary-red-hover: #e6191e;
            --secondary-red: #ff2a2f;
            --accent-red: #ff4d4f;
            --dark-bg: #000000;
            --card-bg: #080c08;
            --card-bg-alt: #0d120e;
            --border-color: rgba(255, 42, 47, 0.15);
            --border-hover: rgba(255, 42, 47, 0.35);
            --text-primary: #ffffff;
            --text-secondary: #a3a3a3;
            --text-muted: #737373;
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-glow: 0 0 40px rgba(139, 12, 16, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            background: rgba(0, 0, 0, 0.95);
            border-bottom: 1px solid var(--border-hover);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .nav-container {
            max-width: 1280px;
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
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: translateY(-2px);
        }

        .logo-img {
            height: 35px;
            width: auto;
            object-fit: contain;
        }

        .logo-accent {
            color: var(--primary-red);
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
            transition: all 0.3s ease;
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
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 8px;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .nav-cta:hover {
            background: var(--primary-red-hover);
            box-shadow: var(--shadow-glow);
            transform: translateY(-2px);
            color: #fff;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 0.5rem;
            background: none;
            border: none;
            z-index: 1001;
        }

        .menu-toggle span {
            width: 24px;
            height: 2px;
            background: var(--text-primary);
            transition: all 0.3s ease;
        }

        /* ── HERO ── */
        .hero {
            background: radial-gradient(ellipse at top, rgba(139,12,16,0.08) 0%, transparent 60%), #000;
            padding: 7rem 2rem 5rem;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 1.2rem;
            background: rgba(139,12,16,0.12);
            border: 1px solid rgba(255,42,47,0.3);
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--secondary-red);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 2rem;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 900;
            background: linear-gradient(135deg, #fff 0%, var(--secondary-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }
        .hero-desc {
            max-width: 720px;
            margin: 0 auto 3rem;
            font-size: 1.15rem;
            color: var(--text-secondary);
            line-height: 1.8;
        }
        .hero-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 2rem;
            background: var(--primary-red);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            border-radius: 10px;
            font-family: 'Space Grotesk', sans-serif;
            transition: var(--transition);
            box-shadow: 0 4px 20px rgba(139,12,16,0.3);
        }
        .btn-primary:hover {
            background: var(--primary-red-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(139,12,16,0.4);
        }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 2rem;
            background: transparent;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 700;
            border-radius: 10px;
            font-family: 'Space Grotesk', sans-serif;
            border: 1px solid rgba(255,42,47,0.3);
            transition: var(--transition);
        }
        .btn-secondary:hover {
            background: rgba(139,12,16,0.08);
            border-color: var(--secondary-red);
            transform: translateY(-3px);
        }

        /* ── SECTION BASE ── */
        .section {
            padding: 6rem 2rem;
        }
        .section:nth-child(even) { background: rgba(13,18,14,0.6); }
        .section-inner { max-width: 1280px; margin: 0 auto; }
        .section-tag {
            display: inline-block;
            padding: 0.35rem 1rem;
            background: rgba(139,12,16,0.1);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--secondary-red);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
        }
        .section-title {
            font-size: clamp(1.8rem, 3.5vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .section-desc {
            font-size: 1.05rem;
            color: var(--text-secondary);
            max-width: 660px;
            line-height: 1.8;
        }

        /* ── CAPABILITY CARDS ── */
        .caps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.75rem;
            margin-top: 3.5rem;
        }
        .cap-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .cap-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-red), var(--accent-red));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        .cap-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(139,12,16,0.12);
        }
        .cap-card:hover::before { transform: scaleX(1); }
        .cap-icon {
            width: 52px; height: 52px;
            background: rgba(139,12,16,0.12);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--secondary-red);
            margin-bottom: 1.5rem;
        }
        .cap-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        .cap-desc {
            font-size: 0.95rem;
            color: var(--text-secondary);
            line-height: 1.7;
        }
        .cap-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1.25rem;
        }
        .cap-tag {
            padding: 0.25rem 0.75rem;
            background: rgba(255, 42, 47, 0.08);
            border: 1px solid rgba(255,42,47,0.2);
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--secondary-red);
        }

        /* ── PRICING ── */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3.5rem;
        }
        .pricing-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2.5rem;
            position: relative;
            transition: var(--transition);
        }
        .pricing-card:hover {
            border-color: var(--border-hover);
            box-shadow: var(--shadow-glow);
            transform: translateY(-5px);
        }
        .pricing-card.featured {
            background: linear-gradient(135deg, rgba(139,12,16,0.1) 0%, rgba(13,18,14,1) 50%);
            border-color: var(--secondary-red);
        }
        .pricing-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary-red);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            white-space: nowrap;
        }
        .pricing-tier {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--secondary-red);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
        }
        .pricing-price {
            margin-bottom: 0.5rem;
        }
        .pricing-price .amount {
            font-size: 3rem;
            font-weight: 900;
            font-family: 'Space Grotesk', sans-serif;
            color: #fff;
        }
        .pricing-price .currency {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-secondary);
            vertical-align: super;
        }
        .pricing-price .period {
            font-size: 1rem;
            color: var(--text-muted);
        }
        .pricing-desc {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .pricing-features {
            list-style: none;
            margin-bottom: 2.5rem;
        }
        .pricing-features li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.6rem 0;
            font-size: 0.92rem;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
        }
        .pricing-features li:last-child { border-bottom: none; }
        .pricing-features li i {
            color: var(--secondary-red);
            margin-top: 3px;
            flex-shrink: 0;
        }
        .pricing-features li.unavailable {
            opacity: 0.45;
        }
        .pricing-features li.unavailable i { color: var(--text-muted); }
        .pricing-cta {
            display: block;
            text-align: center;
            padding: 0.9rem 1.5rem;
            background: var(--primary-red);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            border-radius: 10px;
            font-family: 'Space Grotesk', sans-serif;
            transition: var(--transition);
        }
        .pricing-cta:hover {
            background: var(--primary-red-hover);
            transform: translateY(-2px);
        }
        .pricing-cta.outline {
            background: transparent;
            border: 1px solid rgba(255,42,47,0.3);
            color: var(--text-primary);
        }
        .pricing-cta.outline:hover {
            background: rgba(139,12,16,0.08);
            border-color: var(--secondary-red);
        }
        .pricing-cta.whatsapp {
            background: linear-gradient(135deg, #25d366, #128c7e);
        }
        .pricing-cta.whatsapp:hover {
            box-shadow: 0 6px 25px rgba(255,42,47,0.35);
        }

        /* ── FOOTER ── */
        footer {
            background: #000;
            border-top: 1px solid var(--border-color);
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* ── MOBILE ── */
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
                padding: 6rem 1.5rem 3rem;
            }
            .hero h1 {
                font-size: clamp(2rem, 6vw, 3rem);
            }
            .hero-desc {
                font-size: 1rem;
                margin-bottom: 2rem;
            }
            .hero-actions {
                flex-direction: column;
                align-items: center;
            }
            .section {
                padding: 3.5rem 1.5rem;
            }
            .caps-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
            .pricing-grid {
                grid-template-columns: 1fr;
                max-width: 400px !important;
                margin-left: auto;
                margin-right: auto;
            }
            .section-title {
                font-size: clamp(1.5rem, 5vw, 2.2rem);
            }
        }

        @media (max-width: 480px) {
            .nav-container {
                padding: 1rem;
            }
            .logo {
                font-size: 1.3rem;
            }
            .logo-img {
                height: 28px;
            }
            .hero {
                padding: 5rem 1rem 2.5rem;
            }
            .hero h1 {
                font-size: clamp(1.6rem, 6vw, 2.5rem);
            }
            .section {
                padding: 2.5rem 1rem;
            }
            .cap-card {
                padding: 1.5rem;
            }
            .pricing-card {
                padding: 2rem 1.5rem;
            }
        }

        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /* iOS Safari fix */
        @supports (-webkit-touch-callout: none) {
            .nav-links {
                padding-top: 120px;
            }
        }

        body, h1, h2, h3, h4, h5, h6, p, span, div, li, a {
            font-weight: 700 !important;
        }
    </style>
    <link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>

<!-- ── NAVBAR ── -->
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
            <li><a href="contact-home.php">Contact</a></li>
            <li><a href="swa-lab.php" class="active">Lab</a></li>
            <li><a href="owasp-2026-landing.php">OWASP 2026 Lab</a></li>
        </ul>

        <a href="login.php" class="nav-cta">
            <i class="fas fa-sign-in-alt"></i>
            Login
        </a>
    </div>
</nav>

<!-- ── HERO ── -->
<section class="hero">
    <div class="hero-badge">
        <i class="fas fa-flask"></i> Interactive Security Lab
    </div>
    <h1>The Secure Worldz Academy Lab</h1>
    <p class="hero-desc">
        A complete, browser accessible environment for hands on cybersecurity practice. Write code, break things, solve challenges, compete with teams, and work through structured vulnerability labs all inside one platform.
    </p>
    <div class="hero-actions">
        <a href="login.php" class="btn-primary">
            <i class="fas fa-sign-in-alt"></i> Access the Lab
        </a>
        <a href="owasp-2026-landing.php" class="btn-secondary">
            <i class="fas fa-shield-halved"></i> OWASP 2026 Lab
        </a>
    </div>
</section>

<!-- ── CAPABILITIES ── -->
<section class="section" id="capabilities">
    <div class="section-inner">
        <div class="section-tag">Core Capabilities</div>
        <h2 class="section-title">Seven Built-In Lab Features</h2>
        <p class="section-desc">Every capability is designed to develop real, useful security skills without padded exercises.</p>

        <div class="caps-grid">

            <div class="cap-card">
                <div class="cap-icon"><i class="fas fa-flask"></i></div>
                <h3 class="cap-title">Web-Based Security Lab</h3>
                <p class="cap-desc">
                    Run interactive security challenges directly in the browser with no VM, no setup, and no prior configuration needed. The lab environment simulates realistic attack surfaces with full interactivity.
                </p>
                <div class="cap-tags">
                    <span class="cap-tag">Interactive</span>
                    <span class="cap-tag">Browser-only</span>
                    <span class="cap-tag">No setup</span>
                </div>
            </div>

            <div class="cap-card">
                <div class="cap-icon"><i class="fas fa-terminal"></i></div>
                <h3 class="cap-title">Linux Terminal</h3>
                <p class="cap-desc">
                    A fully functional Linux terminal session embedded in the platform. Run commands, complete Linux-based challenges, and develop fluency with the command line as part of your training workflow.
                </p>
                <div class="cap-tags">
                    <span class="cap-tag">Full shell access</span>
                    <span class="cap-tag">Real commands</span>
                    <span class="cap-tag">Guided tasks</span>
                </div>
            </div>

            <div class="cap-card">
                <div class="cap-icon"><i class="fas fa-code"></i></div>
                <h3 class="cap-title">AI-Integrated IDE</h3>
                <p class="cap-desc">
                    A code editor with multi language support and an integrated AI assistant. Write, run, and test security tooling code directly inside the lab from Python scripts to JavaScript security utilities.
                </p>
                <div class="cap-tags">
                    <span class="cap-tag">Multi-language</span>
                    <span class="cap-tag">AI assistant</span>
                    <span class="cap-tag">Run in-browser</span>
                </div>
            </div>

            <div class="cap-card">
                <div class="cap-icon"><i class="fas fa-shield-halved"></i></div>
                <h3 class="cap-title">OWASP 2026 Lab</h3>
                <p class="cap-desc">
                    Ten dedicated simulations covering the full OWASP 2026 Agentic AI Security Top 10. Structured across beginner through advanced difficulty with a gated entry flow and complete session management.
                </p>
                <div class="cap-tags">
                    <span class="cap-tag">10 simulations</span>
                    <span class="cap-tag">Gated access</span>
                    <span class="cap-tag">Progressive difficulty</span>
                </div>
            </div>

            <div class="cap-card">
                <div class="cap-icon"><i class="fas fa-trophy"></i></div>
                <h3 class="cap-title">Tournaments &amp; Team Competitions</h3>
                <p class="cap-desc">
                    Form a team, recruit members, and compete in structured web security tournaments against other groups. A competitive layer that drives real engagement and tests skills under pressure.
                </p>
                <div class="cap-tags">
                    <span class="cap-tag">Team-based</span>
                    <span class="cap-tag">Web challenges</span>
                    <span class="cap-tag">Competitive</span>
                </div>
            </div>

            <div class="cap-card">
                <div class="cap-icon"><i class="fas fa-coins"></i></div>
                <h3 class="cap-title">Eagle Coin Rewards</h3>
                <p class="cap-desc">
                    Earn Eagle Coins by solving programming tasks and completing Linux challenges. The coin system tracks progress and creates a measurable, motivating feedback loop throughout the lab experience.
                </p>
                <div class="cap-tags">
                    <span class="cap-tag">In-lab currency</span>
                    <span class="cap-tag">Leaderboard</span>
                    <span class="cap-tag">Progress tracking</span>
                </div>
            </div>

            <div class="cap-card">
                <div class="cap-icon"><i class="fas fa-bug"></i></div>
                <h3 class="cap-title">Vulnerable App Playgrounds</h3>
                <p class="cap-desc">
                    Purpose-built vulnerable applications for hands-on offensive security practice. Covers SQL injection, broken access control, privilege escalation, and more in a contained, safe simulation environment.
                </p>
                <div class="cap-tags">
                    <span class="cap-tag">SQLi</span>
                    <span class="cap-tag">Access control</span>
                    <span class="cap-tag">OWASP coverage</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── PRICING ── -->
<section class="section" id="pricing">
    <div class="section-inner">
        <div class="section-tag">Pricing</div>
        <h2 class="section-title">Lab Access</h2>
        <p class="section-desc">Full platform access to all labs, tools, and training environments. Choose the plan that fits your commitment.</p>

        <div class="pricing-grid" style="max-width:760px;margin:3.5rem auto 0;">

            <!-- Monthly -->
            <div class="pricing-card">
                <div class="pricing-tier">Monthly</div>
                <div class="pricing-price">
                    <span class="currency">₹</span>
                    <span class="amount">500</span>
                    <span class="period">/month</span>
                </div>
                <p class="pricing-desc">Full lab access, billed monthly. Ideal for short-term upskilling and coursework.</p>
                <a href="https://wa.me/919944994778?text=Hi%20willing%20to%20experiment%20secure%20worldz%20academy%20lab%20as%20monthly%20plan%2CI%20want%20to%20know%20more%20details" target="_blank" class="pricing-cta whatsapp">
                    <i class="fab fa-whatsapp"></i> Enquire via WhatsApp
                </a>
            </div>

            <!-- Yearly -->
            <div class="pricing-card featured">
                <div class="pricing-badge">Best Value</div>
                <div class="pricing-tier">Yearly</div>
                <div class="pricing-price">
                    <span class="currency">₹</span>
                    <span class="amount">3,000</span>
                    <span class="period">/year</span>
                </div>
                <p class="pricing-desc">Full lab access for 12 months. Maximum value for serious learners and professionals.</p>
                <a href="https://wa.me/919944994778?text=Hi%2C%20i%20willing%20to%20experiment%20secure%20worldz%20academy%20lab%20as%20yearly%20plan%2CI%20want%20to%20know%20more%20details" target="_blank" class="pricing-cta whatsapp">
                    <i class="fab fa-whatsapp"></i> Enquire via WhatsApp
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ── CTA STRIP ── -->
<section style="background: radial-gradient(circle at center, rgba(139,12,16,0.08) 0%, transparent 70%); padding: 5rem 2rem; text-align:center; border-top: 1px solid var(--border-color);">
    <h2 style="font-size:2.5rem;font-weight:900;margin-bottom:1rem;background:linear-gradient(135deg,#fff,var(--secondary-red));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Ready to Enter the Lab?</h2>
    <p style="color:var(--text-secondary);font-size:1.1rem;max-width:580px;margin:0 auto 2.5rem;line-height:1.8;">Log in or register to unlock your lab environment. The platform works in any modern browser with zero installation.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="login.php" class="btn-primary" style="display:inline-flex;align-items:center;gap:.5rem;padding:.9rem 2rem;background:var(--primary-red);color:#fff;text-decoration:none;font-weight:700;border-radius:10px;font-family:'Space Grotesk',sans-serif;transition:all .3s;">
            <i class="fas fa-sign-in-alt"></i> Login to the Platform
        </a>
        <a href="contact-home.php" class="btn-secondary" style="display:inline-flex;align-items:center;gap:.5rem;padding:.9rem 2rem;background:transparent;color:#fff;text-decoration:none;font-weight:700;border-radius:10px;border:1px solid rgba(255,42,47,0.3);font-family:'Space Grotesk',sans-serif;transition:all .3s;">
            <i class="fas fa-headset"></i> Contact Us
        </a>
    </div>
</section>

<!-- ── FOOTER ── -->
<footer>
    <p>&copy; 2026 Secure Worldz Academy. All rights reserved. | Cybersecurity Training Platform</p>
</footer>

<script>
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }, { passive: true });
    }

    // Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            menuToggle.classList.toggle('active');
            
            // Animate hamburger
            const spans = menuToggle.querySelectorAll('span');
            if (spans.length >= 3) {
                if (navLinks.classList.contains('active')) {
                    spans[0].style.transform = 'rotate(45deg) translateY(10px)';
                    spans[1].style.opacity = '0';
                    spans[2].style.transform = 'rotate(-45deg) translateY(-10px)';
                } else {
                    spans[0].style.transform = 'none';
                    spans[1].style.opacity = '1';
                    spans[2].style.transform = 'none';
                }
            }
        });

        // Close mobile menu on link click
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                menuToggle.classList.remove('active');
                const spans = menuToggle.querySelectorAll('span');
                if (spans.length >= 3) {
                    spans[0].style.transform = 'none';
                    spans[1].style.opacity = '1';
                    spans[2].style.transform = 'none';
                }
            });
        });
    }
</script>
</body>
</html>
