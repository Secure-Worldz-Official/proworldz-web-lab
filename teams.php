<?php
require_once 'api/auth_check.php';

$isAdmin = isset($_SESSION['admin_username']);
$eagleCoins = $userCoins;
$db->ensureJoinRequestsTable();

$teams = $db->getTeams();
$allUsers = $db->getAllUsers();
$allUsersWithStats = $db->getAllUsersWithStats();


$createSuccess = false;
$createError = "";
$successMsg = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['confirm_creation']) || isset($_POST['create_team']))) {
    $teamName = trim($_POST['team_name'] ?? '');
    $members = $_POST['members'] ?? [];
    $coLeader = $_POST['co_leader'] ?? '';
    
    
    $leader = $userName;

    
    if (empty($teamName)) {
        $createError = "Please enter a team name.";
    } elseif ($db->checkTeamNameExists($teamName)) {
        $createError = "A team with that name already exists. Please choose another.";
    } elseif (count($members) !== 2) {
        $createError = "Please select exactly 2 members to form a 3-member team.";
    } else {
        $profileImage = null;
        if (isset($_FILES['team_profile']) && $_FILES['team_profile']['error'] === UPLOAD_ERR_OK) {
            $profileImage = file_get_contents($_FILES['team_profile']['tmp_name']);
        }

        
        $finalMembers = array_merge([$leader], $members);

        if ($db->createTeam($teamName, $finalMembers, $leader, null, $profileImage)) {
            $createSuccess = true;
            $successMsg = "Team '$teamName' created successfully!";
            $teams = $db->getTeams(); 
        } else {
            $dbError = $db->con->error;
            if (empty($dbError)) $dbError = "Check if team name already exists.";
            $createError = "Database error: " . $dbError;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'rename_team') {
    $teamId = intval($_POST['team_id'] ?? 0);
    $newName = trim($_POST['new_name'] ?? '');
    $team = $db->getTeamById($teamId);

    if ($team && ($isAdmin || $team['leader_pos'] === $userName)) {
        if ($newName !== $team['team_name'] && $db->checkTeamNameExists($newName)) {
            $createError = "A team with that name already exists.";
        } elseif ($db->updateTeamName($teamId, $newName)) {
            $createSuccess = true;
            $successMsg = "Team renamed to '$newName' successfully!";
            $teams = $db->getTeams(); 
        } else {
            $createError = "Failed to update team name.";
        }
    } else {
        $createError = "Unauthorized or team not found.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove_member') {
    $teamId = intval($_POST['team_id'] ?? 0);
    $memberName = trim($_POST['member_name'] ?? '');
    $team = $db->getTeamById($teamId);

    if ($team && ($isAdmin || $team['leader_pos'] === $userName)) {
        if ($db->removeTeamMember($teamId, $memberName)) {
            $createSuccess = true;
            $successMsg = "Member '$memberName' removed from the squad.";
            $teams = $db->getTeams(); 
        } else {
            $createError = "Failed to remove member.";
        }
    } else {
        $createError = "Unauthorized or team not found.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_team') {
    $teamId = intval($_POST['team_id'] ?? 0);
    $team = $db->getTeamById($teamId);

    if ($team && ($isAdmin || $team['leader_pos'] === $userName)) {
        if ($db->deleteTeam($teamId)) {
            $createSuccess = true;
            $successMsg = "Team deleted successfully.";
            $teams = $db->getTeams(); 
        } else {
            $createError = "Failed to delete team.";
        }
    } else {
        $createError = "Unauthorized or team not found.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_team_profile') {
    $teamId = intval($_POST['team_id'] ?? 0);
    $team = $db->getTeamById($teamId);

    if ($team && ($isAdmin || $team['leader_pos'] === $userName)) {
        if (isset($_FILES['team_profile']) && $_FILES['team_profile']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['team_profile']['tmp_name']);
            if ($db->updateTeamProfile($teamId, $imageData)) {
                $createSuccess = true;
                $successMsg = "Team profile image updated!";
                $teams = $db->getTeams(); 
            } else {
                $createError = "Failed to update profile image.";
            }
        } else {
            $createError = "Please select a valid image.";
        }
    } else {
        $createError = "Unauthorized or team not found.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'join_request') {
    $teamId = intval($_POST['team_id'] ?? 0);
    $team = $db->getTeamById($teamId);
    $currentMembers = json_decode($team['members_list'], true);
    if (count($currentMembers) >= 3) {
        $createError = "This team is already full.";
    } elseif ($db->createJoinRequest($teamId, $userName, $userId)) {
        $createSuccess = true;
        $successMsg = "Your request to join has been sent to the Team Leader.";
    } else {
        $createError = "Failed to send join request.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['accept_request', 'reject_request'])) {
    $requestId = intval($_POST['request_id'] ?? 0);
    $status = ($_POST['action'] === 'accept_request') ? 'accepted' : 'rejected';
    
    if ($db->processJoinRequest($requestId, $status)) {
        $createSuccess = true;
        $successMsg = "Join request " . ucfirst($status) . " successfully.";
        $teams = $db->getTeams(); 
    } else {
        $createError = "Failed to process request.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_notified') {
    $requestId = intval($_POST['request_id'] ?? 0);
    $db->markRequestAsNotified($requestId);
    exit; 
}


$leaderRequests = $db->getJoinRequestsForLeader($userName);


$battleNotifications = $db->getBattleNotifications($userId) ?: [];

$prevCoins = isset($_SESSION['prev_eagle_coins']) ? $_SESSION['prev_eagle_coins'] : $eagleCoins;


$userNotifications = $db->getProcessedRequestsForUser($userId);


if (isset($_GET['ajax']) && $_GET['ajax'] === 'check_notifications') {
    header('Content-Type: application/json');
    echo json_encode([
        'leaderRequests' => $leaderRequests,
        'userNotifications' => $userNotifications,
        'battleNotifications' => $battleNotifications,
        'prevCoins' => $prevCoins
    ]);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams - Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            border-color: rgba(229, 231, 235, 0.3);
            outline-color: rgba(156, 163, 175, 0.5);
        }

        body {
            font-family: 'Roboto Mono', monospace;
            background-color: #000000;
            color: #ffffff;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        :root {
            --radius: 0.625rem;
            --background: #000000;
            --foreground: #ffffff;
            --card: #080808;
            --primary: #ff2a2f; /* Professional Blue */
            --sidebar: #080808;
            --sidebar-foreground: #ffffff;
            --sidebar-accent: rgba(248, 250, 252, 0.05);
            --sidebar-primary: #ff2a2f;
            --border: rgba(139, 12, 16, 0.1);
            --muted-foreground: #a0a0a0;
            --success: #ff2a2f;
            --gap: 1.5rem;
            --sides: 1.5rem;
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

        
        
        
        

        .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }

        
        

        .card {
            background-color: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .p-4 { padding: 1rem; }
        .p-3 { padding: 0.75rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-3 { gap: 0.75rem; }
        .size-12 { width: 3rem; height: 3rem; }
        .flex-1 { flex: 1; }
        .text-2xl { font-size: 1.5rem; }
        .text-xs { font-size: 0.75rem; }
        .uppercase { text-transform: uppercase; }
        .text-muted-foreground { color: var(--muted-foreground); }
        .space-y-1 > * + * { margin-top: 0.25rem; }

        

        

        

        

        .desktop-main {
            display: flex;
            flex-direction: column;
            gap: var(--gap);
        }

        .header-card {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-container {
            position: relative;
            max-width: 400px;
            width: 100%;
        }

        .search-input {
            width: 100%;
            background-color: rgba(139, 12, 16, 0.05);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) - 2px);
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            color: white;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: var(--primary);
        }

        .search-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-foreground);
            font-size: 0.875rem;
        }

        .create-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: calc(var(--radius) - 2px);
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.2s;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .create-btn:hover {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            color: var(--muted-foreground);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-weight: 700;
        }

        .form-input {
            width: 100%;
            background: #000000;
            border: 1px solid var(--border);
            padding: 0.75rem;
            border-radius: 4px;
            color: white;
            font-family: inherit;
            appearance: none;
            cursor: pointer;
        }

        select.form-input option {
            background: #080808;
            color: white;
            padding: 10px;
        }

        .leader-modal {
            max-width: 900px;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .stats-table th {
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--muted-foreground);
            padding: 1rem;
            border-bottom: 2px solid var(--border);
        }

        .stats-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .coin-amount {
            color: #8b0c10;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .members-picker {
            border: 1px solid var(--border);
            border-radius: 4px;
            max-height: 200px;
            overflow-y: auto;
            background: rgba(255,255,255,0.02);
            padding: 0.5rem;
        }

        .picker-item {
            padding: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border-bottom: 1px solid var(--border);
        }

        .picker-item:hover {
            background: rgba(255,255,255,0.05);
        }

        .picker-item input {
            cursor: pointer;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); color: var(--success); }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid #ff2a2f; color: #ff2a2f; }

        .create-modal-content {
            max-width: 500px;
        }

        .teams-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .team-card {
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .team-card:hover {
            transform: translateY(-4px);
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .team-profile {
            height: 120px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(16, 185, 129, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border);
        }

        .team-profile img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }

        .team-info {
            padding: 1rem;
        }

        .team-name {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }

        .team-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border);
        }

        .section-header h2 {
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary);
        }

        .section-header .line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .modal-content {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .close-modal {
            position: absolute;
            top: 1rem; right: 1rem;
            background: none;
            border: none;
            color: var(--muted-foreground);
            cursor: pointer;
            font-size: 1.25rem;
        }

        .member-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .member-item {
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .member-item.leader {
            border-color: #8b0c10;
            background: rgba(251, 191, 36, 0.05);
        }

        .member-item.co-leader {
            border-color: #60a5fa;
            background: rgba(96, 165, 250, 0.05);
        }

        .role-tag {
            font-size: 0.6rem;
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .role-leader { background: #8b0c10; color: #000; }
        .role-co-leader { background: #60a5fa; color: #000; }
        .role-member { background: #4b5563; color: #fff; }

        .empty-state {
            text-align: center;
            padding: 4rem;
            color: var(--muted-foreground);
        }

        .animate-fadeIn {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        
        

        
        

        

        

        

        

        
        

        

        

        

        

        

        

        

        .ripple-container {
            position: relative;
            overflow: hidden;
        }

</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
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
            <div class="card header-card animate-fadeIn">
                <div>
                    <h1 class="text-2xl font-bold">Teams</h1>
                    <p class="text-xs text-muted-foreground uppercase">Discover and collaborate with specialized squads</p>
                </div>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="teamSearch" class="search-input" placeholder="Search team name...">
                </div>
                <button class="create-btn" onclick="openCreateModal()">
                    <i class="fas fa-plus"></i> Create Team
                </button>
            </div>

            <?php if ($createSuccess): ?>
                <div class="alert alert-success animate-fadeIn">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($successMsg ?: "Action completed successfully"); ?>
                </div>
            <?php elseif ($createError): ?>
                <div class="alert alert-error animate-fadeIn">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($createError); ?>
                </div>
            <?php endif; ?>

            <?php
            $yourTeams = [];
            $allOtherTeams = $teams ?: []; 
            
            if ($teams !== false) {
                foreach ($teams as $team) {
                    
                    if ($team['leader_pos'] === $userName) {
                        $yourTeams[] = $team;
                    }
                }
            }
            ?>

            <!-- Your Team Section -->
            <div class="section-header">
                <h2>Your Team</h2>
                <div class="line"></div>
            </div>
            
            <div class="teams-grid" style="margin-bottom: 3rem;">
                <?php if (empty($yourTeams)): ?>
                    <div class="card empty-state" style="grid-column: 1 / -1; padding: 2rem;">
                        <p style="font-size: 0.8rem;">You haven't created a squad yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($yourTeams as $team): ?>
                        <div class="card team-card animate-fadeIn" onclick="openTeamByDataId('<?php echo $team['id']; ?>')" style="border-left: 4px solid var(--primary);">
                            <div class="team-profile">
                                <?php if ($team['has_profile']): ?>
                                    <img src="api/get_team_img.php?id=<?php echo $team['id']; ?>" alt="Team Profile" style="object-fit: cover; width:60px; height:60px; border-radius:50%;">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">
                                        <?php echo strtoupper(substr($team['team_name'] ?? 'T', 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="team-info">
                                <div class="team-name"><?php echo htmlspecialchars($team['team_name']); ?></div>
                                <div class="team-meta">
                                    <span><i class="fas fa-crown" style="color:#8b0c10;"></i> <?php echo htmlspecialchars($team['leader_pos']); ?></span>
                                    <span><i class="fas fa-calendar-alt"></i> <?php echo date('M Y', strtotime($team['created_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- All Teams Section -->
            <div class="section-header">
                <h2>Global Alliances</h2>
                <div class="line"></div>
            </div>

            <div class="teams-grid" id="teamsList">
                <?php if ($teams === false): ?>
                    <div class="card empty-state" style="grid-column: 1 / -1; border-color: #ff2a2f;">
                        <i class="fas fa-exclamation-circle" style="font-size: 3rem; color: #ff2a2f; margin-bottom: 1rem;"></i>
                        <p>Connection Error</p>
                    </div>
                <?php elseif (empty($allOtherTeams)): ?>
                    <div class="card empty-state" style="grid-column: 1 / -1;">
                        <i class="fas fa-users-slash" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                        <p>No teams found</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($allOtherTeams as $team): ?>
                        <div class="card team-card animate-fadeIn" onclick="openTeamByDataId('<?php echo $team['id']; ?>')">
                            <div class="team-profile">
                                <?php if ($team['has_profile']): ?>
                                    <img src="api/get_team_img.php?id=<?php echo $team['id']; ?>" alt="Team Profile" style="object-fit: cover; width:60px; height:60px; border-radius:50%;">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">
                                        <?php echo strtoupper(substr($team['team_name'] ?? 'T', 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="team-info">
                                <div class="team-name"><?php echo htmlspecialchars($team['team_name']); ?></div>
                                <div class="team-meta">
                                    <span><i class="fas fa-crown" style="color:#8b0c10;"></i> <?php echo htmlspecialchars($team['leader_pos']); ?></span>
                                    <span><i class="fas fa-calendar-alt"></i> <?php echo date('M Y', strtotime($team['created_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>


    </div>

    <!-- Modal -->
    <div id="teamModal" class="modal-overlay" onclick="closeTeamModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <button class="close-modal" onclick="closeTeamModal(event)">&times;</button>
            <div class="modal-header" id="modalHeader">
                <!-- Dynamic Content -->
            </div>
            <div class="modal-body">
                <h3 class="uppercase text-xs text-muted-foreground font-bold" style="margin-bottom: 1rem;">Squad Roster (3 Members)</h3>
                <div class="member-list" id="memberList">
                    <!-- Dynamic Content -->
                </div>
            </div>
        </div>
    </div>

    <!-- Create Team Modal -->
    <div id="createModal" class="modal-overlay" onclick="closeCreateModal(event)">
        <div class="modal-content create-modal-content" onclick="event.stopPropagation()">
            <button class="close-modal" onclick="closeCreateModal(event)">&times;</button>
            <div class="modal-header">
                <i class="fas fa-users-crown text-2xl text-primary" style="font-size: 2rem;"></i>
                <div>
                    <h2 class="text-2xl font-bold">Forge Your Squad</h2>
                    <p class="text-xs text-muted-foreground uppercase">Assemble 3 elite members</p>
                </div>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data" id="createTeamForm">
                    <div class="form-group">
                        <label>Team Name</label>
                        <input type="text" name="team_name" class="form-input" placeholder="Epic Squad Name" required>
                    </div>

                    <div class="form-group">
                        <label>Team Profile Image</label>
                        <input type="file" name="team_profile" class="form-input" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label>Select 2 Members <span id="memberCountText">(1/3)</span></label>
                        <p class="text-xs text-muted-foreground" style="margin-bottom: 0.5rem;">You are automatically set as the Leader.</p>
                        <div class="members-picker" id="membersPicker">
                            <?php foreach ($allUsers as $u): 
                                if ($u['name'] === $userName) continue; ?>
                                <label class="picker-item">
                                    <input type="checkbox" name="members[]" value="<?php echo htmlspecialchars($u['name']); ?>" onchange="updateSelection()">
                                    <span><?php echo htmlspecialchars($u['name']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <input type="hidden" name="create_team" value="1">
                    <button type="submit" name="confirm_creation" id="submitTeam" class="create-btn" style="width:100%; justify-content:center; margin-top:1.5rem;" disabled>
                        Confirm Creation
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Leader Dashboard Modal (Big Popup) -->
    <div id="leaderModal" class="modal-overlay" onclick="closeLeaderModal(event)">
        <div class="modal-content leader-modal" onclick="event.stopPropagation()">
            <button class="close-modal" onclick="closeLeaderModal(event)">&times;</button>
            <div class="modal-header">
                <i class="fas fa-crown text-2xl" style="color: #8b0c10; font-size: 2rem;"></i>
                <div>
                    <h2 class="text-2xl font-bold">Commander Dashboard</h2>
                    <p class="text-xs text-muted-foreground uppercase" id="leaderModalSubtitle">Full Roster Management</p>
                </div>
            </div>
            <div class="modal-body">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Status</th>
                            <th>Eagle Coins</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsersWithStats as $u): ?>
                            <tr>
                                <td class="flex items-center gap-3">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; overflow:hidden; background: rgba(255,255,255,0.05); border: 1px solid var(--primary);">
                                        <img src="api/get_avatar_img.php?name=<?php echo urlencode($u['name']); ?>" style="width:100%; height:100%; object-fit:contain;" onerror="this.src='image.png'">
                                    </div>
                                    <span class="font-bold"><?php echo htmlspecialchars($u['name']); ?></span>
                                </td>
                                <td>
                                    <span class="role-tag role-member">Active</span>
                                </td>
                                <td>
                                    <div class="coin-amount">
                                        <i class="fas fa-coins"></i> <?php echo number_format($u['eagle_coins']); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const teamsData = <?php echo json_encode($teams); ?>;
        const currentUserName = <?php echo json_encode($userName); ?>;
        const allUserStats = <?php echo json_encode($allUsersWithStats); ?>;
        const isAdmin = <?php echo json_encode($isAdmin); ?>;
        const leaderRequests = <?php echo json_encode($leaderRequests); ?>;
        const userNotifications = <?php echo json_encode($userNotifications); ?>;
        const teamsList = document.getElementById('teamsList');
        const searchInput = document.getElementById('teamSearch');

        // Helper to get coins for a name
        function getUserCoins(name) {
            if (!allUserStats) return 0;
            const user = allUserStats.find(u => u.name && u.name.trim() === name.trim());
            return user ? parseInt(user.eagle_coins || 0) : 0;
        }

        // Helper to get avatar for a name
        function getUserAvatar(name) {
            if (!allUserStats) return 'image.png';
            const user = allUserStats.find(u => u.name && u.name.trim() === name.trim());
            return user && user.avatar ? `api/get_avatar_img.php?name=${encodeURIComponent(user.name)}` : 'image.png';
        }

        function openCreateModal() {
            document.getElementById('createModal').style.display = 'flex';
        }

        function closeCreateModal(e) {
            document.getElementById('createModal').style.display = 'none';
        }

        function updateSelection() {
            const checkboxes = document.querySelectorAll('#membersPicker input[type="checkbox"]');
            const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
            const countText = document.getElementById('memberCountText');
            const submitBtn = document.getElementById('submitTeam');

            countText.textContent = `(${selected.length + 1}/3)`;

            if (selected.length === 2) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }

            // Limit selection to 2
            checkboxes.forEach(cb => {
                if (!cb.checked && selected.length >= 2) {
                    cb.disabled = true;
                } else {
                    cb.disabled = false;
                }
            });
        }

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const cards = teamsList.getElementsByClassName('team-card');
            
            Array.from(cards).forEach(card => {
                const name = card.querySelector('.team-name').textContent.toLowerCase();
                if (name.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        function openTeamByDataId(id) {
            const team = teamsData.find(t => t.id == id);
            if (team) openTeamModal(team);
        }

        function openTeamModal(team) {
            console.log("Opening modal for team:", team);
            const modal = document.getElementById('teamModal');
            const header = document.getElementById('modalHeader');
            const memberList = document.getElementById('memberList');
            
            // Parse members list (json)
            let membersNames = [];
            if (typeof team.members_list === 'string') {
                try {
                    membersNames = JSON.parse(team.members_list);
                } catch(e) { console.error("JSON parse error:", e); membersNames = []; }
            } else if (Array.isArray(team.members_list)) {
                membersNames = team.members_list;
            }

            // Normalize: DB may store members as strings or as {id, name} objects
            membersNames = membersNames.map(m => typeof m === 'string' ? m : (m && m.name) || '');

            // Ensure profile visibility
            const profileHtml = team.has_profile == 1 ? 
                `<img src="api/get_team_img.php?id=${team.id}" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid var(--primary); object-fit: cover;">` :
                `<div style="width: 80px; height: 80px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; border: 3px solid rgba(255,255,255,0.1);">${team.team_name ? team.team_name[0].toUpperCase() : 'T'}</div>`;

            header.innerHTML = `
                <div style="display:flex; align-items:center; gap:1.5rem; width:100%;">
                    ${profileHtml}
                    <div style="flex:1;">
                        <h2 class="text-2xl font-bold" id="modalTeamName" style="margin:0; color:white; font-family: 'Outfit', sans-serif;">${team.team_name}</h2>
                        <div style="display:flex; gap:1rem; margin-top:0.5rem; flex-wrap: wrap;">
                            <p class="text-xs text-muted-foreground uppercase" style="display:flex; align-items:center; gap:0.4rem; background: rgba(251, 191, 36, 0.1); padding: 4px 8px; border-radius: 4px; color: #8b0c10; border: 1px solid rgba(251, 191, 36, 0.2);">
                                <i class="fas fa-crown"></i> ${team.leader_pos}
                            </p>
                            <p class="text-xs text-muted-foreground uppercase" style="display:flex; align-items:center; gap:0.4rem; background: rgba(139, 12, 16, 0.05); padding: 4px 8px; border-radius: 4px; border: 1px solid rgba(139, 12, 16, 0.1);">
                                <i class="fas fa-calendar-alt"></i> ${new Date(team.created_at).toLocaleDateString('en-US', {month:'long', year:'numeric'})}
                            </p>
                        </div>
                    </div>
                    ${(isAdmin || team.leader_pos === currentUserName) ? `
                        <div style="display:flex; gap:0.5rem; align-items:center;">
                            <button onclick="handleChangeProfile('${team.id}')" style="background: rgba(16, 185, 129, 0.1); border: 1px solid #ff2a2f; color: #ff2a2f; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;" title="Change Profile Image">
                                <i class="fas fa-camera"></i>
                            </button>
                            <button onclick="handleRename('${team.id}', '${team.team_name}')" style="background: rgba(139, 92, 246, 0.1); border: 1px solid var(--primary); color: var(--primary); padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;" title="Rename Team">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="handleDelete('${team.id}')" style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ff2a2f; color: #ff2a2f; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;" title="Delete Team">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ` : `
                        ${(!membersNames.includes(currentUserName)) ? `
                            ${membersNames.length >= 3 ? `
                                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ff2a2f; color: #ff2a2f; padding: 6px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">
                                    <i class="fas fa-users-slash"></i> Team is Full
                                </div>
                            ` : `
                                <button onclick="handleJoinRequest('${team.id}')" id="joinBtn_${team.id}" class="create-btn" style="padding: 6px 15px; font-size: 0.75rem;">
                                    <i class="fas fa-user-plus"></i> Join Request
                                </button>
                            `}
                        ` : ''}
                    `}
                </div>
            `;

            // Sort members by coins
            const sortedMembers = membersNames.map(name => ({
                name: name,
                coins: getUserCoins(name)
            })).sort((a, b) => b.coins - a.coins);

            memberList.innerHTML = '';
            
            // Add Pending Requests Section for Leader
            if (team.leader_pos === currentUserName && leaderRequests.length > 0) {
                const teamRequests = leaderRequests.filter(r => r.team_id == team.id);
                if (teamRequests.length > 0) {
                    memberList.innerHTML += `
                        <div style="grid-column: 1/-1; margin-top: 1rem; padding: 0.75rem; background: rgba(139, 92, 246, 0.05); border: 1px dashed var(--primary); border-radius: 0.5rem;">
                            <h4 class="uppercase text-xs font-bold" style="color:var(--primary); margin-bottom: 0.5rem;">Join Requests (${teamRequests.length})</h4>
                            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                                ${teamRequests.map(r => `
                                    <div style="display:flex; justify-between; items-center; background: rgba(0,0,0,0.2); padding: 8px 12px; border-radius: 4px;">
                                        <div style="flex:1;">
                                            <span style="font-size: 0.8rem; font-weight: bold; color: white;">${r.user_name}</span>
                                        </div>
                                        <div style="display:flex; gap:0.5rem;">
                                            <button onclick="submitAction({action:'accept_request', request_id:'${r.id}'})" style="background:#ff2a2f; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:0.7rem; cursor:pointer;">Accept</button>
                                            <button onclick="submitAction({action:'reject_request', request_id:'${r.id}'})" style="background:#ff2a2f; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:0.7rem; cursor:pointer;">Reject</button>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
            }

            sortedMembers.forEach((mObj, idx) => {
                const m = mObj.name;
                const coins = mObj.coins;
                let role = 'Member';
                let roleClass = 'role-member';
                let itemClass = '';

                if (m === team.leader_pos) {
                    role = 'Leader';
                    roleClass = 'role-leader';
                    itemClass = 'leader';
                }

                memberList.innerHTML += `
                    <div class="member-item ${itemClass}" style="animation-delay: ${idx * 0.05}s">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.05); overflow:hidden; border: 2px solid ${m === team.leader_pos ? '#8b0c10' : 'rgba(255,255,255,0.1)'};">
                            <img src="${getUserAvatar(m)}" style="width:100%; height:100%; object-fit:contain;">
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold" style="color:white; letter-spacing: 0.5px;">${m}</span>
                                <div style="display:flex; align-items:center; gap:0.5rem;">
                                    <span class="role-tag ${roleClass}">${role}</span>
                                    ${((isAdmin || team.leader_pos === currentUserName) && m !== team.leader_pos) ? `
                                        <button onclick="handleRemoveMember('${team.id}', '${m}')" style="background: none; border: none; color: #ff2a2f; opacity: 0.6; cursor: pointer; padding: 2px; font-size: 0.8rem;" title="Remove Member">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="text-xs" style="color:#8b0c10; display:flex; align-items:center; gap:0.3rem; font-weight:bold; margin-top: 2px;">
                                <i class="fas fa-coins" style="font-size: 0.7rem;"></i> 
                                <span style="font-family: monospace;">${coins.toLocaleString()}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            modal.style.display = 'flex';
        }

        function closeTeamModal(e) {
            document.getElementById('teamModal').style.display = 'none';
        }

        function closeLeaderModal(e) {
            document.getElementById('leaderModal').style.display = 'none';
        }

        // Team Management Functions
        function handleRename(id, oldName) {
            const newName = prompt("Enter new team name:", oldName);
            if (newName && newName.trim() !== "" && newName !== oldName) {
                submitAction({ action: 'rename_team', team_id: id, new_name: newName.trim() });
            }
        }

        function handleDelete(id) {
            if (confirm("Are you SURE you want to DELETE this team? This cannot be undone.")) {
                submitAction({ action: 'delete_team', team_id: id });
            }
        }

        function handleRemoveMember(teamId, memberName) {
            if (confirm(`Remove ${memberName} from the squad?`)) {
                submitAction({ action: 'remove_member', team_id: teamId, member_name: memberName });
            }
        }

        function handleJoinRequest(teamId) {
            submitAction({ action: 'join_request', team_id: teamId });
        }

        function handleChangeProfile(id) {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append('action', 'update_team_profile');
                    formData.append('team_id', id);
                    formData.append('team_profile', file);
                    
                    fetch('', {
                        method: 'POST',
                        body: formData
                    }).then(() => window.location.reload());
                }
            };
            input.click();
        }

        function submitAction(params) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            for (const key in params) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = params[key];
                form.appendChild(input);
            }
            
            document.body.appendChild(form);
            form.submit();
        }

        // Notifications Popups
        window.addEventListener('DOMContentLoaded', () => {
            // Notifications for requester
            if (userNotifications && userNotifications.length > 0) {
                userNotifications.forEach(n => {
                    const color = n.status === 'accepted' ? '#ff2a2f' : '#ff2a2f';
                    const icon = n.status === 'accepted' ? 'check-circle' : 'times-circle';
                    showNotificationPopup(
                        `Team Request Update`,
                        `Your request to join <b>${n.team_name}</b> was <b>${n.status}</b>.`,
                        color,
                        icon,
                        n.id
                    );
                });
            }

            // Notifications for leader
            if (leaderRequests && leaderRequests.length > 0) {
                leaderRequests.forEach(r => {
                    showNotificationPopup(
                        `New Join Request`,
                        `<b>${r.user_name}</b> wants to join your team <b>${r.team_name}</b>.`,
                        '#ff2a2f',
                        'user-plus',
                        null,
                        `req_${r.id}`
                    );
                });
            }

            // Polling for live updates (Every 30s)
            setInterval(() => {
                fetch('?ajax=check_notifications')
                .then(r => r.json())
                .then(data => {
                    if (data.userNotifications) {
                        data.userNotifications.forEach(n => {
                            const color = n.status === 'accepted' ? '#ff2a2f' : '#ff2a2f';
                            showNotificationPopup(`Team Request Update`, `Your request to join <b>${n.team_name}</b> was <b>${n.status}</b>.`, color, n.status === 'accepted' ? 'check-circle' : 'times-circle', n.id, `notif_${n.id}`);
                        });
                    }
                    if (data.leaderRequests) {
                        data.leaderRequests.forEach(r => {
                            showNotificationPopup(`New Join Request`, `<b>${r.user_name}</b> wants to join your team <b>${r.team_name}</b>.`, '#ff2a2f', 'user-plus', null, `req_${r.id}`);
                        });
                    }
                }).catch(e => console.log("Notif error:", e));
            }, 30000);
        });

        const activeNotifs = new Set();
        function showNotificationPopup(title, body, color, icon, requestIdToMark, uniqueId) {
            if (uniqueId && activeNotifs.has(uniqueId)) return;
            if (uniqueId) activeNotifs.add(uniqueId);

            const container = document.createElement('div');
            container.style = `
                position: fixed; top: 20px; right: 20px; z-index: 9999;
                background: #080808; border: 1px solid ${color}; border-left: 5px solid ${color};
                padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.5);
                color: white; max-width: 350px; animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                margin-bottom: 1rem;
            `;
            
            container.innerHTML = `
                <div style="display:flex; gap:1rem; align-items:start;">
                    <i class="fas fa-${icon}" style="color:${color}; font-size: 1.5rem; margin-top: 2px;"></i>
                    <div style="flex:1;">
                        <h4 style="margin:0 0 5px 0; font-size: 0.9rem; font-weight: 700;">${title}</h4>
                        <p style="margin:0; font-size: 0.8rem; color: #a0a0a0; line-height: 1.4;">${body}</p>
                    </div>
                    <button style="background:none; border:none; color: #a0a0a0; cursor:pointer; font-size: 1.2rem; padding:0;">&times;</button>
                </div>
            `;

            container.querySelector('button').onclick = () => {
                container.style.animation = 'slideOutRight 0.5s forwards';
                setTimeout(() => container.remove(), 500);
                if (requestIdToMark) {
                    const fd = new FormData();
                    fd.append('action', 'mark_notified');
                    fd.append('request_id', requestIdToMark);
                    fetch('', { method: 'POST', body: fd });
                }
            };

            // Inject keyframes if not present
            if (!document.getElementById('notif-styles')) {
                const style = document.createElement('style');
                style.id = 'notif-styles';
                style.innerHTML = `
                    @keyframes slideInRight {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOutRight {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }

            document.body.appendChild(container);
            
            // Auto hide after 8s
            setTimeout(() => {
                if (container.parentNode) {
                   container.querySelector('button').click();
                }
            }, 8000);
        }
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
