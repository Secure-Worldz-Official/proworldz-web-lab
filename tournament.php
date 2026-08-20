<?php
require_once 'api/auth_check.php';

$eagleCoins = $userCoins;


$successMsg = "";
$errorMsg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'challenge_1v1') {
            $challengedId = intval($_POST['challenged_id']);
            if ($eagleCoins < 50) {
                $errorMsg = "Insufficient Eagle Coins. You need 50 coins to challenge.";
            } elseif ($challengedId == $userId) {
                $errorMsg = "You cannot challenge yourself.";
            } else {
                
                if ($db->createBattle('1v1', $userId, $challengedId, 50)) {
                    
                    $lastId = $db->con->insert_id;
                    $db->con->query("UPDATE coding_battles SET admin_status = 'pending_admin' WHERE id = $lastId");
                    
                    $db->addEagleCoins($userId, -50); 
                    $successMsg = "Challenge request sent to Admin for approval!";
                } else {
                    $errorMsg = "Failed to create challenge.";
                }
            }
        } elseif ($_POST['action'] === 'challenge_3v3') {
            $myTeamId = intval($_POST['my_team_id']);
            $oppTeamId = intval($_POST['opp_team_id']);
            if ($eagleCoins < 50) {
                $errorMsg = "Insufficient Eagle Coins. Leader pays 50 coins for the team battle.";
            } else {
                if ($db->createBattle('3v3', $myTeamId, $oppTeamId, 50)) {
                    $lastId = $db->con->insert_id;
                    $db->con->query("UPDATE coding_battles SET admin_status = 'pending_admin' WHERE id = $lastId");
                    
                    $db->addEagleCoins($userId, -50);
                    $successMsg = "Team Challenge request sent to Admin!";
                }
            }
        } elseif ($_POST['action'] === 'accept_battle') {
            $battleId = intval($_POST['battle_id']);
            if ($eagleCoins < 50) {
                $errorMsg = "You need 50 coins to accept the challenge.";
            } else {
                $db->addEagleCoins($userId, -50);
                $db->updateBattleStatus($battleId, 'accepted');
                $successMsg = "Challenge accepted! Get ready for battle.";
            }
        }
    }
}


$onlineUsers = $db->getOnlineUsers() ?: [];
$onlineUserIds = array_map('intval', array_column($onlineUsers, 'id'));
$teams = $db->getTeams() ?: [];
$leaderTeams = $db->getLeaderTeams($userName) ?: [];
$battles = $db->getBattlesForUser($userId) ?: [];

?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament - Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
        /* Dashboard Theme & Resets */
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Space Grotesk', 'Roboto Mono', sans-serif;
            background-color: #000000;
            color: #ffffff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        :root {
            --radius: 0.625rem;
            --background: #000000;
            --foreground: #ffffff;
            --card: #080808;
            --card-foreground: #ffffff;
            --primary: #ff2a2f;
            --primary-foreground: #ffffff;
            --secondary: #080808;
            --secondary-foreground: #ffffff;
            --muted-foreground: #a0a0a0;
            --border: rgba(139, 12, 16, 0.1);
            --gap: 1.5rem;
            --sides: 1.5rem;
        }

        /* Dashboard Utility Classes */
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .justify-between { justify-content: space-between; }
        .flex-1 { flex: 1 1 0%; }
        .gap-3 { gap: 0.75rem; }
        .size-12 { width: 3rem; height: 3rem; }
        .size-9 { width: 2.25rem; height: 2.25rem; }
        .size-5 { width: 1.25rem; height: 1.25rem; }
        .rounded-lg { border-radius: var(--radius); }
        .rounded { border-radius: 0.25rem; }
        .p-4 { padding: 1rem; }
        .p-3 { padding: 0.75rem; }
        .text-xs { font-size: 0.75rem; line-height: 1rem; }
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
        .uppercase { text-transform: uppercase; }
        .text-muted-foreground { color: var(--muted-foreground); }
        .text-primary-foreground { color: #ffffff; }

        @font-face {
            font-family: "Rebels";
            src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
            font-weight: normal; font-style: normal; font-display: swap;
        }

        .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap);
            min-height: 100vh;
            padding: var(--sides);
            background-color: var(--background);
        }

        

        .card {
            background-color: var(--card); border-radius: var(--radius);
            border: 1px solid var(--border); overflow: hidden;
            transition: all 0.3s ease;
        }

        
        .font-display { font-family: 'Rebels', 'Roboto Mono', monospace; font-weight: 700; letter-spacing: -0.02em; }

        /* Navigation Styles - Fix Blur & Container Fitting */
        
        
        
        
        
        
        

        body.loaded #loader-wrapper { opacity: 0; visibility: hidden; }

        .battle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        .battle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .battle-card {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        .battle-type {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 800;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            background: var(--primary);
            color: white;
        }

        /* Fixed Visibility for members */
        .member-list-text { color: #a0a0a0 !important; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.02em; }

        .vs-container {
            display: flex; align-items: center; justify-content: space-between; padding: 1rem 0;
        }

        .participant { text-align: center; flex: 1; }
        .participant img { width: 50px; height: 50px; border-radius: 50%; border: 2px solid var(--primary); margin-bottom: 0.5rem; background: #000; }
        .vs-divider { font-size: 1.1rem; font-weight: 900; color: var(--muted-foreground); padding: 0 0.5rem; }

        .btn {
            padding: 0.7rem 1rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;
            text-transform: uppercase; font-size: 0.75rem; transition: all 0.2s;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }

        .form-section { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
        .input-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .input-group label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--muted-foreground); }

        select, input {
            background: rgba(139, 12, 16, 0.05); color: #fff; border: 1px solid var(--border);
            padding: 0.75rem; border-radius: 6px; font-family: inherit; font-size: 0.85rem;
        }
        select option { background: #080808; color: #fff; }

        .alert { padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.85rem; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid #ff2a2f; color: #ff2a2f; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid #ff2a2f; color: #ff2a2f; }

        .badge { padding: 2px 8px; border-radius: 99px; font-size: 0.6rem; text-transform: uppercase; font-weight: 700; }
        .badge-pending_admin { color: #ff2a2f; border: 1px solid #ff2a2f; }
        .badge-pending { color: #8b0c10; border: 1px solid #8b0c10; }
        .badge-accepted { color: #ff2a2f; border: 1px solid #ff2a2f; }
        .badge-rejected { color: #ff2a2f; border: 1px solid #ff2a2f; }

        .search-box { position: relative; margin-bottom: 0.5rem; }
        .search-box i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--muted-foreground); font-size: 0.8rem; }
        .search-box input { padding-left: 30px; width: 100%; font-size: 0.8rem; }

        
        

        
        

        

        

        

        

        
        

        

        

        

        

        

        

        

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
            <?php include 'sidebar.php'; ?>

    <main class="desktop-main">
            <div class="card">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded bg-primary flex items-center justify-center">
                             <i class="fas fa-trophy text-primary-foreground" style="font-size:1rem;"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-display">Battle Arena</h1>
                            <div class="text-sm text-muted-foreground uppercase">Admin Approval Required for all Battles</div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($successMsg): ?><div class="alert alert-success"><?php echo $successMsg; ?></div><?php endif; ?>
            <?php if ($errorMsg): ?><div class="alert alert-error"><?php echo $errorMsg; ?></div><?php endif; ?>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:var(--gap);">
                <!-- 1v1 Challenge: Online Only -->
                <div class="card">
                    <div class="form-section">
                        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                            <i class="fas fa-user-ninja" style="color:var(--primary);"></i>
                            <h2 style="font-size:0.9rem; font-weight:700; text-transform:uppercase;">1v1 Battle</h2>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="action" value="challenge_1v1">
                            <div class="input-group">
                                <label>Select Online Opponent</label>
                                <select name="challenged_id" id="challengedPlayerSelect" required>
                                    <option value="">Choose a player...</option>
                                    <?php foreach ($onlineUsers as $u): if($u['id'] == $userId) continue; ?>
                                        <option value="<?php echo (int)$u['id']; ?>" data-user-id="<?php echo (int)$u['id']; ?>"><?php echo htmlspecialchars($u['name']); ?> (Online)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" id="challenge1v1Button" class="btn btn-primary" style="margin-top:1rem; width:100%;" <?php echo empty($onlineUsers) ? 'disabled' : ''; ?>>
                                Request Battle
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 3v3 Challenge: Leader Only + Search -->
                <div class="card">
                    <div class="form-section">
                        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                            <i class="fas fa-users" style="color:var(--primary);"></i>
                            <h2 style="font-size:0.9rem; font-weight:700; text-transform:uppercase;">Team Battle 3v3</h2>
                        </div>
                        <?php if (empty($leaderTeams)): ?>
                            <p style="font-size:0.75rem; color:#ff2a2f; border:1px solid rgba(239,68,68,0.3); padding:0.5rem; border-radius:4px; background:rgba(239,68,68,0.05);">
                                Only Team Leaders can initiate team battles.
                            </p>
                        <?php else: ?>
                            <form method="POST" id="teamBattleForm">
                                <input type="hidden" name="action" value="challenge_3v3">
                                <div class="input-group">
                                    <label>Your Team (Auto-filled)</label>
                                    <select name="my_team_id" required>
                                        <?php foreach ($leaderTeams as $t): ?>
                                            <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['team_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="input-group" style="margin-top:1rem;">
                                    <label>Opponent Team (Searchable)</label>
                                    <div class="search-box">
                                        <i class="fas fa-search"></i>
                                        <input type="text" id="oppTeamSearch" placeholder="Search teams..." onkeyup="filterTeams()">
                                    </div>
                                    <select name="opp_team_id" id="oppTeamSelect" required>
                                        <option value="">Select Target...</option>
                                        <?php foreach ($teams as $t): 
                                            $isMyTeam = false;
                                            foreach($leaderTeams as $lt) if($lt['id'] == $t['id']) $isMyTeam = true;
                                            if ($isMyTeam) continue; ?>
                                            <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['team_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" style="margin-top:1rem; width:100%;">Initiate Team War</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Active & Pending Challenges -->
            <div style="margin-top:1rem;">
                <h2 style="font-size:0.9rem; font-weight:700; text-transform:uppercase; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
                    <i class="fas fa-fire" style="color:#ff2a2f;"></i> Your Battle Dashboard
                </h2>
                <div class="battle-grid">
                    <?php if (empty($battles)): ?>
                        <div class="card" style="grid-column: 1/-1; padding: 2rem; text-align:center; color:var(--muted-foreground); font-size:0.85rem;">
                            No active or pending battles. Start one above!
                        </div>
                    <?php else: ?>
                        <?php foreach ($battles as $b): 
                            $admin_status = $b['admin_status'] ?? 'pending_admin';
                            $challengerName = ($b['type'] == '1v1') ? $db->getNamebyId($b['challenger_id']) : $db->getTeamNameById($b['challenger_id']);
                            $challengedName = ($b['type'] == '1v1') ? $db->getNamebyId($b['challenged_id']) : $db->getTeamNameById($b['challenged_id']);
                        ?>
                            <div class="battle-card">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                                    <span class="battle-type"><?php echo $b['type']; ?> Battle</span>
                                    <div style="display:flex; gap:0.4rem;">
                                        <span class="badge badge-<?php echo $admin_status; ?>">Admin: <?php echo str_replace('_', ' ', $admin_status); ?></span>
                                        <span class="badge badge-<?php echo $b['status']; ?>">Match: <?php echo $b['status']; ?></span>
                                    </div>
                                </div>
                                <div class="vs-container">
                                    <div class="participant" style="position:relative;">
                                        <div style="position:relative; display:inline-block;">
                                            <img src="api/get_avatar_img.php?id=<?php echo $b['challenger_id']; ?>" onerror="this.src='image.png'" style="margin-bottom:0;"> 
                                            <div
                                                data-online-dot
                                                data-user-id="<?php echo (int)$b['challenger_id']; ?>"
                                                style="position:absolute; bottom:0; right:0; width:12px; height:12px; background:#ff2a2f; border:2px solid #080808; border-radius:50%; z-index:10; display: <?php echo ($b['type'] == '1v1' && in_array($b['challenger_id'], $onlineUserIds)) ? 'block' : 'none'; ?>;">
                                            </div>
                                        </div>
                                        <div class="member-list-text" style="margin-top:0.5rem;"><?php echo htmlspecialchars($challengerName); ?></div>
                                    </div>
                                    <div class="vs-divider">VS</div>
                                    <div class="participant" style="position:relative;">
                                        <div style="position:relative; display:inline-block;">
                                            <img src="api/get_avatar_img.php?id=<?php echo $b['challenged_id']; ?>" onerror="this.src='image.png'" style="margin-bottom:0;"> 
                                            <div
                                                data-online-dot
                                                data-user-id="<?php echo (int)$b['challenged_id']; ?>"
                                                style="position:absolute; bottom:0; right:0; width:12px; height:12px; background:#ff2a2f; border:2px solid #080808; border-radius:50%; z-index:10; display: <?php echo ($b['type'] == '1v1' && in_array($b['challenged_id'], $onlineUserIds)) ? 'block' : 'none'; ?>;">
                                            </div>
                                        </div>
                                        <div class="member-list-text" style="margin-top:0.5rem;"><?php echo htmlspecialchars($challengedName); ?></div>
                                    </div>
                                </div>
                                <?php if ($b['status'] === 'pending' && $b['challenged_id'] == $userId && $admin_status === 'approved'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="action" value="accept_battle">
                                        <input type="hidden" name="battle_id" value="<?php echo $b['id']; ?>">
                                        <button type="submit" class="btn btn-primary" style="width:100%;">Accept (50 Coins)</button>
                                    </form>
                                <?php elseif ($admin_status === 'pending_admin'): ?>
                                    <div style="text-align:center; font-size:0.7rem; color:var(--muted-foreground); padding-top:0.5rem; border-top:1px solid var(--border);">
                                        Waiting for admin to verify the request...
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    function filterTeams() {
        var input = document.getElementById('oppTeamSearch');
        var filter = input.value.toUpperCase();
        var select = document.getElementById('oppTeamSelect');
        var options = select.getElementsByTagName('option');

        for (var i = 1; i < options.length; i++) {
            var txtValue = options[i].textContent || options[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                options[i].style.display = '';
            } else {
                options[i].style.display = 'none';
            }
        }
    }

    function applyBattlePresence(onlineSet) {
        document.querySelectorAll('[data-online-dot][data-user-id]').forEach(function (dot) {
            var userId = String(dot.dataset.userId || '');
            dot.style.display = onlineSet.has(userId) ? 'block' : 'none';
        });
    }

    function rebuildOpponentOptions(onlineUsers) {
        var currentUserId = '<?php echo addslashes((string)$userId); ?>';
        var select = document.getElementById('challengedPlayerSelect');
        var actionButton = document.getElementById('challenge1v1Button');
        if (!select || !actionButton) {
            return;
        }

        var selectedValue = select.value;
        var opponents = onlineUsers.filter(function (u) {
            return String(u.id) !== currentUserId;
        });

        select.innerHTML = '<option value=\"\">Choose a player...</option>';
        opponents.forEach(function (user) {
            var option = document.createElement('option');
            option.value = String(user.id);
            option.dataset.userId = String(user.id);
            option.textContent = user.name + ' (Online)';
            select.appendChild(option);
        });

        if (opponents.some(function (u) { return String(u.id) === selectedValue; })) {
            select.value = selectedValue;
        }

        actionButton.disabled = opponents.length === 0;
    }

    function refreshOnlineOpponents() {
        fetch('api/online_users.php', {
            credentials: 'same-origin',
            cache: 'no-store'
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Failed to load online users');
                }
                return response.json();
            })
            .then(function (users) {
                if (!Array.isArray(users)) {
                    return;
                }
                rebuildOpponentOptions(users);
            })
            .catch(function () {
                return null;
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        refreshOnlineOpponents();
        setInterval(refreshOnlineOpponents, 12000);

        if (window.presenceRealtime && typeof window.presenceRealtime.startOnlineStatusPolling === 'function') {
            window.presenceRealtime.startOnlineStatusPolling(applyBattlePresence, 12000);
        }
    });
    </script>
</body>
</html>
