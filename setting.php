<?php
require_once 'api/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @font-face {
            font-family: "Rebels";
            src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
            font-display: swap;
        }

        :root {
            --radius: 0.625rem;
            --background: #0d1015;
            --foreground: #f8fafc;
            --card: #1a1d24;
            --card-foreground: #f8fafc;
            --primary: #ff2a2f;
            --primary-light: #e5191e;
            --primary-foreground: #ffffff;
            --muted-foreground: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', 'Roboto Mono', sans-serif;
            background-color: #000000;
            color: #ffffff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
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
            padding: 0.75rem;
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
            background: #0d1015;
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
            border-top-color: #8b5cf6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        #loader-text {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.75rem;
            color: #8b5cf6;
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

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: rgba(22, 22, 45, 0.95);
            padding: 25px 20px;
            border-right: 1px solid var(--border);
            backdrop-filter: blur(10px);
            position: fixed;
            height: 100vh;
            z-index: 100;
            transition: width 0.3s ease;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            padding-left: 10px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            box-shadow: 0 0 15px var(--glow-primary);
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 10px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .nav-item.active {
            background-color: rgba(108, 99, 255, 0.2);
            border-left: 3px solid var(--primary);
        }

        .nav-item:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
        }

        .nav-icon {
            margin-right: 15px;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .nav-text {
            transition: opacity 0.3s ease;
        }

        .profile-section {
            position: absolute;
            bottom: 30px;
            left: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: rgba(40, 40, 70, 0.5);
            border-radius: 15px;
            border: 1px solid var(--border);
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .profile-info h4 {
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .profile-info p {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .page-title h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .page-title p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Updated Profile Avatar Container */
        .profile-avatar-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-btn {
            background-color: rgba(40, 40, 70, 0.5);
            border-radius: 12px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background-color: rgba(108, 99, 255, 0.2);
            transform: translateY(-2px);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--accent);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        /* Header Avatar */
        .header-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            border: 2px solid var(--primary);
            box-shadow: 0 0 10px var(--glow-primary);
        }

        .header-avatar-info {
            text-align: right;
        }

        .header-avatar-info h4 {
            font-size: 1rem;
            margin-bottom: 3px;
        }

        .header-avatar-info p {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Settings Container */
        .settings-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Profile Card */
        .profile-card {
            background-color: var(--card-bg);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .profile-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 2.5rem;
            border: 4px solid var(--primary);
            box-shadow: 0 0 20px var(--glow-primary);
            margin-right: 30px;
        }

        .profile-info-large h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .profile-info-large .student-id {
            color: var(--text-secondary);
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.05);
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            border: 1px solid var(--border);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 15px;
            background-color: rgba(40, 40, 70, 0.5);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
        }

        .form-input:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-hint {
            margin-top: 8px;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Button Styles */
        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff2a2f 0%, #b81419 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(255, 42, 47, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 42, 47, 0.55);
            filter: brightness(1.08);
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: rgba(255, 42, 47, 0.15);
            border-color: var(--primary);
        }

        .btn-edit {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background-color: rgba(255, 42, 47, 0.15);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        /* Success Message */
        .success-message {
            background-color: rgba(78, 205, 196, 0.2);
            color: var(--success);
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(78, 205, 196, 0.3);
            margin-top: 20px;
            display: none;
            align-items: center;
            gap: 10px;
        }

        .success-message.show {
            display: flex;
            animation: fadeIn 0.5s ease;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: var(--text-secondary);
            font-size: 0.9rem;
            border-top: 1px solid var(--border);
            margin-top: 30px;
        }

        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

    </style>
    <link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
<script src="api/includes/loading_resilience.js?v=20260822" defer></script>
</head>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div id="loader-text">INITIALIZING SETTINGS...</div>
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
            <!-- Header -->
            <div class="header">
                <div class="page-title">
                    <h1>Account Settings</h1>
                    <p>Manage your profile information and preferences</p>
                </div>
                
                <div class="header-actions">
                    <!-- Profile Avatar moved to left side of notification -->
                    <div class="profile-avatar-container">
                        <div class="header-avatar" id="headerAvatar">
                            JS
                        </div>
                        <div class="header-avatar-info">
                            <h4 id="headerName">John Smith</h4>
                            <p>Computer Science</p>
                        </div>
                        <div class="notification-btn" id="notificationBtn">
                            <i class="fas fa-bell"></i>
                            <div class="notification-badge">3</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Settings Container -->
            <div class="settings-container">
                <!-- Profile Card -->
                <div class="profile-card fade-in">
                    <div class="profile-header">
                        <div class="profile-avatar-large" id="profileAvatarLarge">
                            JS
                        </div>
                        <div class="profile-info-large">
                            <h2 id="profileNameDisplay">John Smith</h2>
                            <div class="student-id">
                                Student ID: <span id="studentId">CS2024001</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Form -->
                    <form id="profileForm">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input 
                                    type="text" 
                                    id="nameInput" 
                                    class="form-input" 
                                    value="John Smith"
                                    disabled
                                >
                                <button type="button" class="btn-edit" id="editNameBtn">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                            <div class="form-hint">
                                You can edit your display name here. This will update across the platform.
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Student ID</label>
                            <input 
                                type="text" 
                                id="studentIdInput" 
                                class="form-input" 
                                value="CS2024001"
                                disabled
                            >
                            <div class="form-hint">
                                Student ID cannot be changed. Contact administration for any issues.
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input 
                                type="email" 
                                id="emailInput" 
                                class="form-input" 
                                value="john.smith@student.edu"
                                disabled
                            >
                            <div class="form-hint">
                                Contact administration to change your email address.
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <input 
                                type="text" 
                                id="departmentInput" 
                                class="form-input" 
                                value="Computer Science"
                                disabled
                            >
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Enrollment Date</label>
                            <input 
                                type="text" 
                                id="enrollmentInput" 
                                class="form-input" 
                                value="September 2024"
                                disabled
                            >
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons" id="saveCancelButtons" style="display: none;">
                            <button type="button" class="btn btn-primary" id="saveBtn">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary" id="cancelBtn">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                    
                    <!-- Success Message -->
                    <div class="success-message" id="successMessage">
                        <i class="fas fa-check-circle"></i>
                        <span>Profile updated successfully!</span>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p>© 2026 Secure Worldz Academy copyrights reserved.</p>
            </div>
    </main>
    </div>

    <script>
        // // Desktop only - redirect mobile devices
        // const checkDevice = () => {
        //     const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        //     const isTablet = /(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i.test(navigator.userAgent);
            
        //     if (isMobile || isTablet || window.innerWidth <= 768) {
        //         document.body.innerHTML = `
        //             <div style="
        //                 display: flex;
        //                 align-items: center;
        //                 justify-content: center;
        //                 height: 100vh;
        //                 background: #0F0F23;
        //                 color: white;
        //                 font-family: 'Segoe UI', sans-serif;
        //                 text-align: center;
        //                 padding: 20px;
        //             ">
        //                 <div>
        //                     <h1 style="font-size: 2rem; margin-bottom: 20px; color: #6C63FF;">Desktop Only</h1>
        //                     <p style="font-size: 1.2rem; color: #A0A0C0; max-width: 500px;">
        //                         This dashboard is optimized for desktop devices. Please access it from a computer or laptop for the best experience.
        //                     </p>
        //                     <p style="margin-top: 20px; color: #718096;">
        //                         Screen width detected: ${window.innerWidth}px
        //                     </p>
        //                 </div>
        //             </div>
        //         `;
        //         return false;
        //     }
        //     return true;
        // };

        // // Check device on load and resize
        // if (!checkDevice()) {
        //     // Stop execution if mobile/tablet
        //     throw new Error('Mobile/tablet device detected');
        // }

        // window.addEventListener('resize', checkDevice);

        // User Profile Data
        const userProfile = {
            name: "John Smith",
            studentId: "CS2024001",
            email: "john.smith@student.edu",
            department: "Computer Science",
            enrollmentDate: "September 2024",
            initials: "JS"
        };

        // DOM Elements
        const nameInput = document.getElementById('nameInput');
        const studentIdInput = document.getElementById('studentIdInput');
        const emailInput = document.getElementById('emailInput');
        const departmentInput = document.getElementById('departmentInput');
        const enrollmentInput = document.getElementById('enrollmentInput');
        const editNameBtn = document.getElementById('editNameBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveCancelButtons = document.getElementById('saveCancelButtons');
        const successMessage = document.getElementById('successMessage');
        const profileNameDisplay = document.getElementById('profileNameDisplay');
        const headerName = document.getElementById('headerName');
        const sidebarName = document.getElementById('sidebarName');
        const profileAvatarLarge = document.getElementById('profileAvatarLarge');
        const headerAvatar = document.getElementById('headerAvatar');
        const notificationBtn = document.getElementById('notificationBtn');

        // Initialize Page
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            
            // Simulate loading
            setTimeout(() => {
                document.querySelectorAll('.fade-in').forEach(el => {
                    el.style.opacity = '1';
                });
            }, 300);
        });

        // Setup Event Listeners
        function setupEventListeners() {
            // Edit Name Button
            editNameBtn.addEventListener('click', function() {
                enableNameEditing();
            });

            // Save Button
            saveBtn.addEventListener('click', function() {
                saveProfileChanges();
            });

            // Cancel Button
            cancelBtn.addEventListener('click', function() {
                cancelNameEditing();
            });

            // Notification button
            notificationBtn.addEventListener('click', showNotifications);

            // Navigation items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (this.querySelector('a').getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                    document.querySelectorAll('.nav-item').forEach(nav => {
                        nav.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
        }

        // Enable Name Editing
        function enableNameEditing() {
            // Enable the name input
            nameInput.disabled = false;
            nameInput.focus();
            nameInput.style.borderColor = 'var(--primary)';
            nameInput.style.backgroundColor = 'rgba(108, 99, 255, 0.1)';
            
            // Show save/cancel buttons
            saveCancelButtons.style.display = 'flex';
            
            // Hide edit button
            editNameBtn.style.display = 'none';
        }

        // Save Profile Changes
        function saveProfileChanges() {
            const newName = nameInput.value.trim();
            
            // Validate name
            if (!newName) {
                alert('Please enter a valid name');
                nameInput.focus();
                return;
            }
            
            if (newName.length < 2) {
                alert('Name must be at least 2 characters long');
                nameInput.focus();
                return;
            }
            
            if (newName.length > 50) {
                alert('Name is too long (max 50 characters)');
                nameInput.focus();
                return;
            }
            
            // Update user profile
            userProfile.name = newName;
            
            // Extract initials from name
            const nameParts = newName.split(' ');
            let initials = '';
            if (nameParts.length >= 2) {
                initials = nameParts[0].charAt(0) + nameParts[nameParts.length - 1].charAt(0);
            } else if (nameParts.length === 1) {
                initials = nameParts[0].substring(0, 2).toUpperCase();
            }
            userProfile.initials = initials.toUpperCase();
            
            // Update all name displays
            updateNameDisplays(newName, initials);
            
            // Disable input and show success message
            nameInput.disabled = true;
            nameInput.style.borderColor = '';
            nameInput.style.backgroundColor = '';
            saveCancelButtons.style.display = 'none';
            editNameBtn.style.display = 'block';
            
            // Show success message
            successMessage.classList.add('show');
            
            // Hide success message after 3 seconds
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 3000);
            
            // In a real application, you would send an AJAX request to update the name in the database
            // console.log('Name updated to:', newName);
            
            // Simulate API call
            simulateApiCall(newName);
        }

        // Cancel Name Editing
        function cancelNameEditing() {
            // Reset name to original
            nameInput.value = userProfile.name;
            
            // Disable input
            nameInput.disabled = true;
            nameInput.style.borderColor = '';
            nameInput.style.backgroundColor = '';
            
            // Hide save/cancel buttons
            saveCancelButtons.style.display = 'none';
            
            // Show edit button
            editNameBtn.style.display = 'block';
        }

        // Update Name Displays Across the Page
        function updateNameDisplays(newName, initials) {
            // Update profile name display
            profileNameDisplay.textContent = newName;
            
            // Update header name
            headerName.textContent = newName;
            
            // Update sidebar name
            sidebarName.textContent = newName;
            
            // Update avatar initials
            profileAvatarLarge.textContent = initials;
            headerAvatar.textContent = initials;
            
            // Update sidebar avatar (if exists)
            const sidebarAvatar = document.querySelector('.profile-img');
            if (sidebarAvatar) {
                sidebarAvatar.textContent = initials;
            }
        }

        // Simulate API Call (for demonstration)
        function simulateApiCall(newName) {
            // This would be an actual AJAX request in a real application
            // Example:
            /*
            fetch('/api/update-profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: newName
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage();
                } else {
                    alert('Failed to update profile: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
            });
            */
        }

        // Show Notifications
        function showNotifications() {
            // Clear notification badge
            const badge = notificationBtn.querySelector('.notification-badge');
            badge.style.display = 'none';
            
            // In a real app, this would show a notifications panel
            alert('You have 3 notifications:\n1. Profile update successful\n2. New assignment posted\n3. System maintenance scheduled');
        }

        // Initialize name displays
        updateNameDisplays(userProfile.name, userProfile.initials);
    </script>
</body>
</html>
