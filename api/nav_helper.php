<?php
// Function to generate the navigation bar
function get_navigation($active_page) {
    ?>
    <div class="nav-section">
        <a href="dashboard.php" class="nav-item <?php echo $active_page == 'dashboard.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none">
                <path stroke="currentColor" stroke-linecap="square" stroke-width="1.667" d="M5.833 3.333h-2.5v13.334h2.5m8.333-13.334h2.5v13.334h-2.5"/>
            </svg>
            <span class="nav-label">Overview</span>
        </a>
        <a href="lab.php" class="nav-item <?php echo $active_page == 'lab.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none">
                <path stroke="currentColor" stroke-width="1.667" d="M16.228 3.772c1.31 1.31-.416 5.16-3.856 8.6-3.44 3.44-7.29 5.167-8.6 3.856-1.31-1.31.415-5.16 3.855-8.6 3.44-3.44 7.29-5.167 8.6-3.856Z"/>
                <path stroke="currentColor" stroke-width="1.667" d="M16.228 16.228c-1.31 1.31-5.161-.416-8.601-3.855-3.44-3.44-5.166-7.29-3.856-8.601 1.31-1.31 5.162.416 8.601 3.855 3.44 3.44 5.166 7.29 3.856 8.601Z"/>
            </svg>
            <span class="nav-label">Laboratory</span>
        </a>
        <a href="tasks.php" class="nav-item <?php echo $active_page == 'tasks.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                <path stroke-width="1.5" d="M5 3.333h8.333a2.5 2.5 0 0 1 2.5 2.5v10a2.5 2.5 0 0 1-2.5 2.5H5V3.333z"/>
                <path stroke-width="1.5" d="M13.333 3.333v13.334"/>
                <path stroke-width="1.5" d="M3.333 14.167l1.667-1.667" stroke-linecap="round"/>
                <path stroke-width="1.5" d="M8.333 10l-3.333 3.333" stroke-linecap="round"/>
                <path stroke-width="1.2" d="M8.333 7.5h3.334" stroke-linecap="round"/>
                <path stroke-width="1.2" d="M8.333 9.167h5" stroke-linecap="round"/>
                <path stroke-width="1.2" d="M8.333 10.833h4.167" stroke-linecap="round"/>
            </svg>
            <span class="nav-label">Tasks</span>
        </a>
        <a href="ourcourse.php" class="nav-item <?php echo $active_page == 'ourcourse.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                <path stroke-width="1.5" d="M16.667 15V5.833a2.5 2.5 0 0 0-2.5-2.5H5.833a2.5 2.5 0 0 0-2.5 2.5v10a2.5 2.5 0 0 0 2.5 2.5h10"/>
                <path stroke-width="1.5" d="M6.667 3.333v13.334"/>
                <path stroke-width="1.5" d="M10 6.667h3.333"/>
                <path stroke-width="1.5" d="M10 10h3.333"/>
            </svg>
            <span class="nav-label">Courses</span>
        </a>
        <a href="assignment.php" class="nav-item <?php echo $active_page == 'assignment.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                <path stroke-width="1.5" d="M16.667 16.667V5a2.5 2.5 0 0 0-2.5-2.5H6.667a2.5 2.5 0 0 0-2.5 2.5v11.667"/>
                <path stroke-width="1.5" d="M6.667 2.5v15"/>
                <path stroke-width="1.5" d="M11.667 4.167l4.166 4.166" stroke-linecap="round"/>
                <path stroke-width="1.5" d="M13.333 8.333l-2.5 2.5-2.5-2.5 2.5-2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-label">Assignments</span>
        </a>
        <a href="maintanance.php" class="nav-item disabled <?php echo $active_page == 'maintanance.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none">
                <path stroke="currentColor" stroke-linecap="square" stroke-width="1.667" d="M10 4.164V2.497m3.333 1.67V2.5M6.667 4.167v-1.67M10 17.5v-1.667m3.333 1.667v-1.667M6.667 17.5v-1.667m9.166-2.5H17.5m-1.667-6.667H17.5M15.833 10H17.5m-15 0h1.667M2.5 13.334h1.667M2.5 6.666h1.667M12.5 10a2.501 2.501 0 1 1-5.002 0 2.501 2.501 0 0 1 5.002 0ZM4.167 4.167h11.666v11.666H4.167V4.167Z"/>
            </svg>
            <span class="nav-label">Devices</span>
        </a>
        <a href="leaderboard.php" class="nav-item <?php echo $active_page == 'leaderboard.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none">
                <path stroke="currentColor" stroke-width="1.667" d="M10 2.5l3.333 6.667H6.667L10 2.5z"/>
                <path stroke="currentColor" stroke-width="1.667" d="M3.333 10.833h13.334"/>
                <path stroke="currentColor" stroke-width="1.667" d="M5.833 13.333h8.334"/>
                <path stroke="currentColor" stroke-width="1.667" d="M7.5 15.833h5"/>
            </svg>
            <span class="nav-label">Leaderboard</span>
        </a>
        <a href="teams.php" class="nav-item <?php echo $active_page == 'teams.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span class="nav-label">Teams</span>
        </a>
        <a href="tournament.php" class="nav-item <?php echo $active_page == 'tournament.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                <path d="M4 22h16"></path>
                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
            </svg>
            <span class="nav-label">Tournament</span>
        </a>
        <a href="maintanance.php" class="nav-item disabled <?php echo $active_page == 'security' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none">
                <path stroke="currentColor" stroke-linecap="square" stroke-width="1.667" d="M10 3.333H4.166v7.5h11.667v-7.5H10Zm0 0V1.667m-6.667 12.5 1.25-1.25m12.083 1.25-1.25-1.25M7.5 6.667V7.5m5-.833V7.5M5 10.833V12.5a5 5 0 0 0 10 0v-1.667"/>
            </svg>
            <span class="nav-label">Security status</span>
        </a>
        <a href="contactus.php" class="nav-item <?php echo $active_page == 'contactus.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none">
                <path fill="currentColor" d="M17.5 4.167h.833v-.834H17.5v.834Zm0 11.666v.834h.833v-.834H17.5Zm-15 0h-.833v.834H2.5v-.834Zm0-11.666v-.834h-.833v.834H2.5Zm7.5 6.666-.528.645.528.432.528-.432-.528-.645Zm7.5-6.666h-.833v11.666h1.666V4.167H17.5Zm0 11.666V15h-15V16.667h15v-.834Zm-15 0h.833V4.167H1.667v11.666H2.5Zm0-11.666V5h15V3.333h-15v.834Zm7.5 6.666.528-.645-7.084-5.795-.527.645-.528.645 7.083 5.795.528-.645Zm7.083-5.795-.527-.645-7.084 5.795.528.645.528.645 7.083-5.795-.528-.645Z"/>
            </svg>
            <span class="nav-label">Contact support</span>
        </a>
        <a href="https://dragotool.shop/" class="nav-item" target="_blank" rel="noopener noreferrer">
            <svg class="nav-icon" viewBox="0 0 640 512" fill="currentColor">
                <path d="M18.32 255.78L192 223.96l-91.28 68.69c-10.08 10.08-2.94 27.31 11.31 27.31h222.7c.94 0 1.78-.23 2.65-.29l-79.21 88.62c-9.85 11.03-2.16 28.11 12.58 28.11 6.34 0 12.27-3.59 15.99-9.26l79.21-88.62c.39.04.78.07 1.18.07h78.65c14.26 0 21.39-17.22 11.32-27.31l-79.2-88.62c.39-.04.78-.07 1.18-.07h78.65c14.26 0 21.39-17.22 11.32-27.31L307.33 9.37c-6.01-6.76-17.64-6.76-23.65 0l-265.38 246.4c-10.08 10.08-2.94 27.31 11.31 27.31h79.21c.39 0 .78-.03 1.17-.07L18.32 255.78z"/>
            </svg>
            <span class="nav-label">Drago Tool</span>
        </a>
        <a href="eaglone_shop.php" class="nav-item <?php echo $active_page == 'eaglone_shop.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none">
                <path stroke="currentColor" stroke-width="1.5" d="M5 6.667h10l-1 9.166H6l-1-9.166Z"/>
                <path stroke="currentColor" stroke-width="1.5" d="M7.5 8.333V6.25a2.5 2.5 0 0 1 5 0v2.083"/>
            </svg>
            <span class="nav-label">Eaglone shop</span>
        </a>
        <a href="settings.php" class="nav-item <?php echo $active_page == 'settings.php' ? 'active' : ''; ?>">
            <svg class="nav-icon" viewBox="0 0 20 20" fill="none">
                <path stroke="currentColor" stroke-linecap="square" stroke-width="1.667" d="M10 10.833a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                <path stroke="currentColor" stroke-linecap="square" stroke-width="1.667" d="M15.833 6.667v-.834a2.5 2.5 0 0 0-2.5-2.5h-6.666a2.5 2.5 0 0 0-2.5 2.5v.834m-2.5 0h18.332v7.5H2.5v-7.5Zm.833 9.166a2.5 2.5 0 0 0 2.5 2.5h10a2.5 2.5 0 0 0 2.5-2.5V14.167"/>
            </svg>
            <span class="nav-label">Settings</span>
        </a>
        <a href="logout.php" class="nav-item">
            <svg class="nav-icon" viewBox="0 0 512 512" fill="currentColor">
                <path d="M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z"/>
            </svg>
            <span class="nav-label">Logout</span>
        </a>
    </div>
    <?php
}
?>
