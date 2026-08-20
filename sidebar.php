<!-- Dependencies (Font Awesome & Google Fonts) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="app-theme-overrides.css?v=20260518">

<style>
    /* Exact CSS from Dashboard Page */
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

    /* Prevent flex-shrink from compressing cards so sidebar always scrolls correctly */
    .desktop-sidebar > * { flex-shrink: 0; }

    .desktop-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .desktop-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .desktop-sidebar::-webkit-scrollbar-thumb {
        background: rgba(160, 160, 160, 0.45);
        border-radius: 999px;
    }

    .desktop-sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(180, 180, 180, 0.7);
    }

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

    /* SideBar Profile Card */
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
        0%, 100% { transform: translateY(0) }
        50% { transform: translateY(-6px) }
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

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Navigation Sections */
    .nav-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--muted-fg);
        letter-spacing: 0.1em;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.25rem;
    }

    .nav-section {
        padding: 0 0.75rem;
        margin-bottom: 1.5rem; /* Matches the 1.5rem from later in dashboard.php */
    }

    .nav-item {
        display: flex !important;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem !important;
        border-radius: calc(var(--radius) - 2px);
        text-decoration: none;
        color: inherit;
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
        font-family: inherit;
    }

    .nav-item:hover {
        background: none !important; /* Remove hover background */
        transform: none !important; /* Remove hover transform */
        color: #fff !important; /* Ensure text stays white */
    }

    .nav-item span {
        color: #fff !important; /* Ensure span text is white */
    }

    .nav-icon {
        width: 1.2rem;
        height: 1.2rem;
        flex-shrink: 0;
        text-align: center;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white !important; /* Icon always white */
    }

    .nav-item, .ripple-container {
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
        to { transform: scale(4); opacity: 0 }
    }

    /* Entry Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px) }
        to { opacity: 1; transform: translateY(0) }
    }

    .anim-1 { animation: fadeInUp 0.4s ease 0.1s both }
    .anim-2 { animation: fadeInUp 0.4s ease 0.2s both }

    /* DASHBOARD NAV CONTAINER */
    /* DASHBOARD NAV CONTAINER */
    .dashboard-nav-container {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0.75rem;
        margin-bottom: var(--gap);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    /* DASHBOARD NAV CONTAINER (Global Top Bar) */
    .dashboard-nav-container {
        background: var(--card);
        border-bottom: 1px solid var(--border);
        padding: 0.75rem 1rem;
        position: relative; 
        width: 100%;
        z-index: 9999;
        display: none; 
        overflow: hidden; /* Prevent parent from scrolling horizontally if children handle it */
    }

    .dashboard-nav-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none !important;
        -ms-overflow-style: none;
        width: 100%;
    }

    .dashboard-nav-scroll::-webkit-scrollbar {
        display: none !important;
    }

    .mobile-profile-header {
        display: none;
        align-items: center;
        gap: 1rem;
        padding-bottom: 0.75rem;
        margin-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
    }

    .mobile-profile-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary);
    }

    .mobile-profile-info {
        flex: 1;
    }

    .mobile-profile-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--fg);
    }

    .mobile-profile-stats {
        font-size: 0.75rem;
        color: var(--warning);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .dashboard-nav-container::-webkit-scrollbar {
        display: none;
    }

    .dashboard-nav-wrapper {
        display: flex;
        gap: 0.75rem;
        width: max-content;
        align-items: center;
    }

    .dashboard-nav-container .nav-item {
        margin-bottom: 0 !important;
        padding: 0.5rem 1rem !important;
        white-space: nowrap;
        width: auto;
        flex-shrink: 0;
        font-size: 0.85rem;
        color: var(--fg) !important;
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--border);
        border-radius: 20px;
        transition: all 0.3s;
    }

    .dashboard-nav-container .nav-item.active {
        background: var(--primary) !important;
        color: #fff !important;
    }

    @media (max-width: 1024px) {
        .dashboard-nav-container {
            display: block;
        }
        .mobile-profile-header {
            display: flex;
        }
    }
</style>


<!-- Dashboard Nav Container (Horizontal Scrollable for Mobile) -->
<div class="dashboard-nav-container anim-1">
    <!-- Profile Info for Mobile -->
    <div class="mobile-profile-header">
        <?php 
        $activeAvatar = isset($activeAvatarImage) ? htmlspecialchars($activeAvatarImage) : '';
        $userInitials = isset($userName) ? strtoupper(substr($userName, 0, 1)) : '?';
        ?>
        <img src="<?php echo $activeAvatar; ?>" class="mobile-profile-avatar" alt="Profile" onerror="this.style.display='none';this.parentElement.textContent='<?php echo $userInitials; ?>'">
        <div class="mobile-profile-info">
            <div class="mobile-profile-name"><?php echo htmlspecialchars($userName); ?></div>
            <div class="mobile-profile-stats">
                <i class="fas fa-coins"></i> <?php echo number_format($userCoins ?? 0); ?> EC
            </div>
        </div>
        <a href="logout.php" style="color: var(--destructive); font-size: 1.1rem;"><i class="fas fa-power-off"></i></a>
    </div>
    
    <div class="dashboard-nav-scroll">
        <div class="dashboard-nav-wrapper">
        <a href="dashboard.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-th-large nav-icon"></i>
            <span>Dashboard</span>
        </a>
        <a href="lab.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'lab.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-flask nav-icon"></i>
            <span>Laboratory</span>
        </a>
        <a href="https://n-os-lab.vercel.app/" class="nav-item ripple-container" target="_blank" rel="noopener noreferrer">
            <i class="fas fa-network-wired nav-icon"></i>
            <span>Network Lab</span>
        </a>
        <a href="owasp-lab.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'owasp-lab.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-shield-halved nav-icon"></i>
            <span>OWASP Lab</span>
        </a>
        <?php if (isset($course) && strtolower(preg_replace("/[^a-z0-9]/i", "", (string)$course)) === "securex"): ?>
        <a href="vulnerable-saas-app.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'vulnerable-saas-app.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-box-open nav-icon"></i>
            <span>SaaS App</span>
        </a>
        <?php endif; ?>
        <a href="tasks.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'tasks.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-tasks nav-icon"></i>
            <span>Tasks</span>
        </a>
        <a href="ourcourse.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ourcourse.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-book nav-icon"></i>
            <span>Courses</span>
        </a>
        <a href="assignment.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'assignment.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-file-alt nav-icon"></i>
            <span>Projects</span>
        </a>
        <a href="leaderboard.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'leaderboard.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-medal nav-icon"></i>
            <span>Leaderboard</span>
        </a>
        <a href="teams.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'teams.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-user-friends nav-icon"></i>
            <span>Teams</span>
        </a>
        <a href="tournament.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'tournament.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-trophy nav-icon"></i>
            <span>Tournament</span>
        </a>
        <a href="contactus.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'contactus.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-headset nav-icon"></i>
            <span>Support</span>
        </a>
        <a href="https://dragotool.shop/" class="nav-item ripple-container" target="_blank">
            <i class="fas fa-dragon nav-icon"></i>
            <span>Drago Tool</span>
        </a>
        <a href="eaglone_shop.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'eaglone_shop.php') ? 'active' : ''; ?> ripple-container">
            <i class="fas fa-store nav-icon"></i>
            <span>Shop</span>
        </a>
        <?php if (isset($course) && strtolower(preg_replace("/[^a-z0-9]/i", "", (string)$course)) === "securex"): ?>
        <a href="https://ctf-page.vercel.app/" class="nav-item ripple-container" target="_blank">
            <i class="fas fa-flag nav-icon"></i>
            <span>CTF Page</span>
        </a>
        <?php endif; ?>
        <a href="download.php" class="nav-item ripple-container">
            <i class="fas fa-laptop-code nav-icon"></i>
            <span>IDE</span>
        </a>
        <a href="logout.php" class="nav-item ripple-container" style="color:var(--destructive)">
            <i class="fas fa-sign-out-alt nav-icon"></i>
            <span>Logout</span>
        </a>
    </div>
</div>
</div>

<?php if (isset($daysUntilExpiry) && $daysUntilExpiry !== null && $daysUntilExpiry <= 7 && $daysUntilExpiry > 0): ?>
<div style="
    background: rgba(245,158,11,0.12);
    border: 1px solid rgba(245,158,11,0.4);
    border-radius: var(--radius);
    padding: 0.75rem 1rem;
    margin-bottom: var(--gap);
    color: var(--warning);
    font-family: 'Roboto Mono', monospace;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    line-height: 1.4;
">
    <i class="fas fa-exclamation-triangle" style="flex-shrink:0;"></i>
    <span>Your lab access will expire in <strong><?php echo $daysUntilExpiry; ?> day<?php echo $daysUntilExpiry !== 1 ? 's' : ''; ?></strong>. Contact admin to renew.</span>
</div>
<?php endif; ?>

<aside class="desktop-sidebar">
    <!-- Profile Card -->
    <div class="card anim-1">
        <div class="profile-card">
            <div class="avatar-ring">
                <?php 
                $activeAvatar = isset($activeAvatarImage) ? htmlspecialchars($activeAvatarImage) : '';
                $userInitials = isset($userName) ? strtoupper(substr($userName, 0, 1)) : '?';
                ?>
                <img src="<?php echo $activeAvatar; ?>" alt="Avatar" onerror="this.style.display='none';this.parentElement.textContent='<?php echo $userInitials; ?>'">
            </div>
            <div>
                <div class="profile-name"><?php echo htmlspecialchars($userName ?? 'Guest'); ?></div>
                <div class="profile-role"><?php echo htmlspecialchars($course ?? 'Member'); ?></div>
            </div>
            <div class="coins-badge">
                <i class="fas fa-coins"></i>
                <span id="coinDisplay"><?php echo number_format($userCoins ?? 0); ?></span> EC
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="card anim-2">
        <div style="padding:1rem 0.75rem;">
            <div class="nav-label">Main Menu</div>
            <div class="nav-section">
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-th-large nav-icon"></i>
                    <span>Dashboard</span>
                </a>
                <a href="lab.php" class="nav-item">
                    <i class="fas fa-flask nav-icon"></i>
                    <span>Laboratory</span>
                </a>
                <a href="https://n-os-lab.vercel.app/" class="nav-item" target="_blank" rel="noopener noreferrer">
                    <i class="fas fa-network-wired nav-icon"></i>
                    <span>Network Lab</span>
                </a>
                <?php if (isset($course) && strtolower(preg_replace("/[^a-z0-9]/i", "", (string)$course)) === "securex"): ?>
                <a href="owasp-lab.php" class="nav-item">
                    <i class="fas fa-shield-halved nav-icon"></i>
                    <span>OWASP Lab</span>
                </a>
                <a href="vulnerable-saas-app.php" class="nav-item">
                    <i class="fas fa-box-open nav-icon"></i>
                    <span>vulnerable Saas app</span>
                </a>
                <?php endif; ?>
                <a href="tasks.php" class="nav-item">
                    <i class="fas fa-tasks nav-icon"></i>
                    <span>Tasks</span>
                </a>
                <a href="ourcourse.php" class="nav-item">
                    <i class="fas fa-book nav-icon"></i>
                    <span>Courses</span>
                </a>
                <a href="assignment.php" class="nav-item">
                    <i class="fas fa-file-alt nav-icon"></i>
                    <span>Projects</span>
                </a>
                <a href="leaderboard.php" class="nav-item">
                    <i class="fas fa-medal nav-icon"></i>
                    <span>Leaderboard</span>
                </a>
                <a href="teams.php" class="nav-item">
                    <i class="fas fa-user-friends nav-icon"></i>
                    <span>Teams</span>
                </a>
                <a href="tournament.php" class="nav-item">
                    <i class="fas fa-trophy nav-icon"></i>
                    <span>Tournament</span>
                </a>
            </div>

            <div class="nav-label" style="margin-top:0.75rem;">Tools & Support</div>
            <div class="nav-section">
                <a href="contactus.php" class="nav-item">
                    <i class="fas fa-headset nav-icon"></i>
                    <span>Support</span>
                </a>
                <a href="https://dragotool.shop/" class="nav-item" target="_blank">
                    <i class="fas fa-dragon nav-icon"></i>
                    <span>Drago Tool</span>
                </a>
                <a href="eaglone_shop.php" class="nav-item">
                    <i class="fas fa-store nav-icon"></i>
                    <span>Shop</span>
                </a>
                <?php if (isset($course) && strtolower(preg_replace("/[^a-z0-9]/i", "", (string)$course)) === "securex"): ?>
                <a href="https://ctf-page.vercel.app/" class="nav-item" target="_blank">
                    <i class="fas fa-flag nav-icon"></i>
                    <span>CTF Page</span>
                </a>
                <?php endif; ?>
                <a href="download.php" class="nav-item">
                    <i class="fas fa-laptop-code nav-icon"></i>
                    <span>IDE</span>
                </a>
            </div>

            <div class="nav-label" style="margin-top:0.75rem;">Account</div>
            <div class="nav-section">
                <a href="logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</aside>

<script>
    // Desktop navigation active path matcher
    document.addEventListener('DOMContentLoaded', function() {
        const currentFile = window.location.pathname.split('/').pop() || 'dashboard.php';
        document.querySelectorAll('.desktop-sidebar .nav-item').forEach(item => {
            const href = item.getAttribute('href');
            if (href) {
                const hrefFile = href.split('/').pop();
                if (hrefFile === currentFile) {
                    item.classList.add('active');
                }
            }
        });
    });
    
    document.addEventListener('mousedown', function(e) {
        const el = e.target.closest('.nav-item, .ripple-container');
        if (!el) return;
        const rect = el.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;
        el.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
</script>
