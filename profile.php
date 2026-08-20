<?php
session_start();
require_once 'api/dbconf.php';
require_once 'api/avatar_helper.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['id'];
$db = new DBconfig();
$db->updateLastActive($userId);


$userInfo = $db->getUserInfo($userId, ['name', 'course', 'IPADDR', 'eagle_coins']);
$userName = $userInfo['name'] ?? 'User';
$userCourse = $userInfo['course'] ?? 'Not Enrolled';
$userIP = $userInfo['IPADDR'] ?? 'Unknown';
$userCoins = $userInfo['eagle_coins'] ?? 0;
$userRank = $db->getUserRank($userId);

$activeAvatarImage = getActiveAvatarImage($db, $userId);

?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --radius: 1rem;
            --background: #0d1117;
            --card: rgba(22, 27, 34, 0.8);
            --primary: #8b5cf6;
            --secondary: #6366f1;
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f0f6fc;
            --text-dim: #8b949e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto Mono', monospace;
            background: var(--background);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: radial-gradient(circle at top right, rgba(139, 92, 246, 0.15), transparent),
                        radial-gradient(circle at bottom left, rgba(99, 102, 241, 0.1), transparent),
                        #0d1117;
        }

        .profile-container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            animation: fadeIn 0.8s ease-out;
        }

        .glass-card {
            background: var(--card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .avatar-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .avatar-wrapper {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 3px solid var(--primary);
            padding: 5px;
            background: rgba(139, 92, 246, 0.1);
            position: relative;
            animation: float 4s ease-in-out infinite;
        }

        .user-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: contain;
            background: #161b22;
        }

        .online-status {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: #10b981;
            border: 3px solid #161b22;
            border-radius: 50%;
            box-shadow: 0 0 15px #10b981;
        }

        .info-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .username-input-group {
            position: relative;
            display: inline-block;
            margin-bottom: 8px;
        }

        .username-display {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(to bottom right, #fff, #8b949e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }

        .edit-btn {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            margin-left: 10px;
            font-size: 1.2rem;
            transition: transform 0.2s;
        }

        .edit-btn:hover {
            transform: scale(1.1);
        }

        .course-badge {
            font-size: 0.8rem;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--text-dim);
            text-transform: uppercase;
        }

        .footer-btns {
            display: flex;
            gap: 15px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border-radius: 12px;
            border: none;
            font-family: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 10px 20px rgba(139, 92, 246, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(139, 92, 246, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--text-dim);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #161b22;
            border: 1px solid var(--border);
            padding: 30px;
            border-radius: var(--radius);
            width: 90%;
            max-width: 400px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-dim);
            font-size: 0.8rem;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            background: rgba(0,0,0,0.3);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: white;
            font-family: inherit;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .modal-btns {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <div class="glass-card">
            <div class="avatar-section">
                <div class="avatar-wrapper">
                    <img src="<?php echo htmlspecialchars($activeAvatarImage); ?>" alt="Avatar" class="user-avatar">
                    <div class="online-status"></div>
                </div>
            </div>

            <div class="info-section">
                <div class="username-input-group">
                    <span class="username-display" id="display-name"><?php echo htmlspecialchars($userName); ?></span>
                    <button class="edit-btn" onclick="openModal()"><i class="fas fa-edit"></i></button>
                </div>
                <div>
                    <span class="course-badge"><?php echo htmlspecialchars($userCourse); ?></span>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">#<?php echo $userRank; ?></div>
                    <div class="stat-label">Global Rank</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo number_format($userCoins); ?></div>
                    <div class="stat-label">Eagle Coins</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="font-size: 0.8rem;"><?php echo htmlspecialchars($userIP); ?></div>
                    <div class="stat-label">IP Address</div>
                </div>
            </div>

            <div class="footer-btns">
                <a href="dashboard.php" class="btn btn-outline">Back to Dashboard</a>
                <a href="logout.php" class="btn btn-primary">Logout Session</a>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-bottom: 20px;">Update Username</h3>
            <div class="input-group">
                <label>New Username</label>
                <input type="text" id="newUsername" value="<?php echo htmlspecialchars($userName); ?>">
            </div>
            <div class="modal-btns">
                <button class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="updateUsername()">Save Changes</button>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        async function updateUsername() {
            const newName = document.getElementById('newUsername').value;
            if (!newName) return;

            const btn = event.target;
            btn.disabled = true;
            btn.innerText = 'Updating...';

            try {
                const response = await fetch('api/update_username.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `name=${encodeURIComponent(newName)}`
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    document.getElementById('display-name').innerText = newName;
                    closeModal();
                } else {
                    alert(data.message || 'Failed to update username');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Connection error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Save Changes';
            }
        }

        // Close modal on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
