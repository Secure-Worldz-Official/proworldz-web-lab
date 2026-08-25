<?php
require_once 'api/auth_check.php';

$userCourse = $course;



$directory = 'eagleshop/';
$allowed_extensions = ['png', 'jpg', 'jpeg', 'webp'];
$availableAvatars = [];

if (is_dir($directory)) {
    $files = scandir($directory);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed_extensions)) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $availableAvatars[] = [
                'name' => $name,
                'image' => $directory . $file,
                'price' => $db->avatar_coin($file)
            ];
        }
    }
}


$avatarImageMap = [];
$avatarPriceMap = [];
foreach ($availableAvatars as $av) {
    $avatarImageMap[$av['name']] = $av['image'];
    $avatarPriceMap[$av['name']] = (int)$av['price'];
}


$message = '';
$error = '';
if (isset($_SESSION['shop_msg'])) {
    $message = $_SESSION['shop_msg'];
    unset($_SESSION['shop_msg']);
}
if (isset($_SESSION['shop_err'])) {
    $error = $_SESSION['shop_err'];
    unset($_SESSION['shop_err']);
}


$purchasedAvatars = $db->getUserAvatars($userId) ?: [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['purchase_avatar'])) {
        $selected = trim($_POST['purchase_avatar']);
        $userCoins = (int) ($db->getEagleCoinsbyId($userId) ?? 0);

        if (!array_key_exists($selected, $avatarPriceMap)) {
            $_SESSION['shop_err'] = 'Avatar not found.';
        } elseif (in_array($selected, $purchasedAvatars, true)) {
            $_SESSION['shop_msg'] = 'You already own this avatar.';
        } else {
            $cost = $avatarPriceMap[$selected];
            if ($cost <= 0 || $userCoins >= $cost) {
                if ($cost > 0) {
                    $newCoins = $userCoins - $cost;
                    $db->updateUserEagleCoins($userId, $newCoins);
                }
                $purchasedAvatars[] = $selected;
                $purchasedAvatars = array_values(array_unique($purchasedAvatars));
                if ($db->setUserAvatars($userId, $purchasedAvatars)) {
                    $_SESSION['shop_msg'] = $selected . ' purchased successfully!';
                } else {
                    $_SESSION['shop_err'] = 'Failed to store avatar in database.';
                }
            } else {
                $_SESSION['shop_err'] = 'Not enough Eagle Coins.';
            }
        }
    }

    if (isset($_POST['set_avatar'])) {
        $selected = trim($_POST['set_avatar']);
        if (in_array($selected, $purchasedAvatars, true)) {
            
            $purchasedAvatars = array_values(array_unique(array_merge([$selected], $purchasedAvatars)));
            $db->setUserAvatars($userId, $purchasedAvatars);
            $_SESSION['shop_msg'] = 'Active avatar set to ' . $selected;
        }
    }

    header("Location: eaglone_shop.php");
    exit();
}


$purchasedAvatars = $db->getUserAvatars($userId) ?: $purchasedAvatars;
$purchasedAvatars = array_values(array_unique($purchasedAvatars));
$userCoins = (int) ($db->getEagleCoinsbyId($userId) ?? 0);

$primaryAvatarImage = $activeAvatarImage;
$yourAvatars = $purchasedAvatars;
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eaglone Shop | Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="api/includes/loading_resilience.js?v=20260822" defer></script>
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
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
        }

        @font-face {
            font-family: "Rebels";
            src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --sides: 1.5rem;
            --radius: 0.625rem;
            --background: #000000;
            --foreground: #ffffff;
            --card: #080808;
            --card-foreground: #ffffff;
            --popover: #080808;
            --popover-foreground: #ffffff;
            --primary: #ff2a2f;
            --primary-foreground: #ffffff;
            --secondary: #080808;
            --secondary-foreground: #ffffff;
            --muted: #080808;
            --muted-foreground: #a0a0a0;
            --accent: rgba(248, 250, 252, 0.05);
            --accent-foreground: #ffffff;
            --border: rgba(139, 12, 16, 0.1);
            --pop: rgba(255, 255, 255, 0.025);
            --input: rgba(139, 12, 16, 0.15);
            --ring: rgba(148, 163, 184, 0.5);
            --success: #ff2a2f;
            --destructive: #ff2a2f;
            --warning: #8b0c10;
            --sidebar: #080808;
            --sidebar-foreground: #ffffff;
            --sidebar-primary: #ff2a2f;
            --sidebar-primary-foreground: #ffffff;
            --sidebar-accent: rgba(248, 250, 252, 0.05);
            --sidebar-accent-foreground: #ffffff;
            --sidebar-border: rgba(139, 12, 16, 0.1);
            --sidebar-ring: rgba(148, 163, 184, 0.5);
            --gap: 1.5rem;
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

        

        .desktop-main {
            display: flex;
            flex-direction: column;
            gap: var(--gap);
        }

        .font-display {
            font-family: 'Rebels', 'Roboto Mono', monospace;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .flex-1 { flex: 1 1 0%; }
        .gap-3 { gap: 0.75rem; }
        .p-4 { padding: 1rem; }
        .p-3 { padding: 0.75rem; }
        .rounded-lg { border-radius: var(--radius); }
        .size-12 { width: 3rem; height: 3rem; }
        .size-8 { width: 2rem; height: 2rem; }
        .bg-primary { background-color: var(--primary); }
        .text-primary-foreground { color: var(--primary-foreground); }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .text-xs { font-size: 0.75rem; line-height: 1rem; }
        .uppercase { text-transform: uppercase; }
        .text-muted-foreground { color: var(--muted-foreground); }

        .card {
            background-color: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        
        
        
        
        .space-y-1 > * + * { margin-top: 0.25rem; }

        /* shop body */
        .shop-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .coin-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.75rem;
            border-radius: 9999px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid var(--border);
            font-weight: 700;
            width: fit-content;
        }
        .coin-pill img {
            width: 18px;
            height: 18px;
        }
        .active-avatar-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.75rem;
            border-radius: 9999px;
            background: rgba(148, 163, 184, 0.12);
            border: 1px solid var(--border);
            font-weight: 700;
            width: fit-content;
        }
        .shop-header h1 {
            font-size: 2rem;
            letter-spacing: -0.01em;
            margin-bottom: 0.35rem;
        }
        .shop-header p {
            color: var(--muted-foreground);
            font-size: 0.95rem;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--muted-foreground);
        }
        .avatar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }
        .avatar-card {
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            box-shadow: none;
            align-items: center;
            text-align: center;
        }
        .avatar-thumb {
            display: block;
            width: 100%;
            height: auto;
            max-height: 260px;
            object-fit: contain;
            border-radius: 0;
            background: transparent;
            border: none;
            box-shadow: none;
        }
        .avatar-thumb-wrap {
            position: relative;
            width: 100%;
        }
        .set-avatar-btn {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.5);
            color: #fff;
            opacity: 0;
            transition: opacity 0.2s ease;
            text-decoration: none;
            font-weight: 700;
            border-radius: 0;
        }
        .avatar-thumb-wrap:hover .set-avatar-btn {
            opacity: 1;
        }
        .avatar-name {
            font-weight: 700;
            font-size: 1rem;
        }
        .avatar-price {
            font-weight: 600;
            color: var(--warning);
            font-size: 0.95rem;
        }
        .avatar-status {
            font-weight: 700;
            color: var(--success);
            font-size: 0.9rem;
        }
        .buy-btn {
            margin-top: auto;
            display: block;
            width: 60%;
            min-width: 160px;
            max-width: 100%;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            padding: 0.65rem 0.9rem;
            border-radius: calc(var(--radius) - 2px);
            background: var(--primary);
            color: var(--primary-foreground);
            text-decoration: none;
            font-weight: 600;
            border: 1px solid var(--border);
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }
        .buy-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 30px rgba(99,102,241,0.25);
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--muted); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--muted-foreground); }

        
        

        
        

        

        

        

        

        
        

        

        

        

        

        

        

        

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
            <div class="shop-wrapper">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                    <div class="shop-header">
                        <h1 class="font-display">Welcome to the Shop</h1>
                        <p>Pick an avatar and purchase with your Eagle Coins.</p>
                    </div>
                    <div style="display:flex; gap:0.75rem; align-items:flex-start;">
                        <div class="active-avatar-pill">
                            <img src="<?php echo htmlspecialchars($primaryAvatarImage); ?>" alt="active avatar" style="width:24px; height:24px; object-fit:contain;"> 
                            <span>Active: <?php echo htmlspecialchars($primaryAvatarName); ?></span>
                        </div>
                        <div class="coin-pill">
                            <img src="images/coin.png" alt="coin" loading="lazy"> 
                            <span><?php echo $userCoins; ?> Eagle Coins</span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="avatar-status" style="color: var(--success); font-weight:700; text-align:left;"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="avatar-status" style="color: var(--destructive); font-weight:700; text-align:left;"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="section-title">Available Avatars</div>

                <div class="avatar-grid">
                    <?php 
                    
                    
                    
                    
                    if (count($availableAvatars) == 0): ?>
                        <div class="avatar-card" style="grid-column: 1 / -1; align-items:center; text-align:center; padding: 2rem;">
                            <div class="avatar-name" style="color: var(--muted-foreground);">No new avatars are available in the shop right now.</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($availableAvatars as $avatar): 
                            $owned = in_array($avatar['name'], $purchasedAvatars, true);
                            $imgSrc = $avatarImageMap[$avatar['name']] ?? $avatar['image'];
                        ?>
                        <div class="avatar-card">
                            <div class="avatar-thumb-wrap">
                                <img class="avatar-thumb" src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($avatar['name']); ?> avatar"> 
                                <?php if ($owned && $avatar['name'] !== $primaryAvatarName): ?>
                                <a class="set-avatar-btn" href="#" onclick="event.preventDefault(); document.getElementById('set-avatar-<?php echo md5($avatar['name']); ?>').submit();">Set as avatar</a>
                                <form id="set-avatar-<?php echo md5($avatar['name']); ?>" method="post" style="display:none;">
                                    <input type="hidden" name="set_avatar" value="<?php echo htmlspecialchars($avatar['name']); ?>">
                                </form>
                                <?php endif; ?>
                            </div>
                            <div class="avatar-name">Name : <?php echo htmlspecialchars($avatar['name']); ?></div>
                            <div class="avatar-price">Eagle Coin : <?php echo ($owned || $avatar['price'] == 0) ? 'Free' : intval($avatar['price']); ?></div>
                            <?php if ($owned): ?>
                                <div class="avatar-status">Purchased<?php if ($avatar['name'] === $primaryAvatarName) echo ' (Active)'; ?></div>
                            <?php else: ?>
                                <form method="post" style="width:100%; display:flex; justify-content:center;">
                                    <input type="hidden" name="purchase_avatar" value="<?php echo htmlspecialchars($avatar['name']); ?>">
                                    <button type="submit" class="buy-btn">Purchase</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="section-title" style="margin-top:1rem;">Your Avatars</div>
                <div class="avatar-grid">
                    <?php if (empty($yourAvatars)): ?>
                        <div class="avatar-card" style="align-items:center; text-align:center;">
                            <div class="avatar-name" style="color: var(--muted-foreground);">You haven’t purchased any avatars yet.</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($yourAvatars as $ownedName): 
                            $img = isset($avatarImageMap[$ownedName]) ? $avatarImageMap[$ownedName] : 'image.webp';
                        ?>
                        <div class="avatar-card">
                            <div class="avatar-thumb-wrap">
                                <img class="avatar-thumb" src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($ownedName); ?> avatar"> 
                                <?php if ($ownedName !== $primaryAvatarName): ?>
                                <a class="set-avatar-btn" href="#" onclick="event.preventDefault(); document.getElementById('set-avatar-owned-<?php echo md5($ownedName); ?>').submit();">Set as avatar</a>
                                <form id="set-avatar-owned-<?php echo md5($ownedName); ?>" method="post" style="display:none;">
                                    <input type="hidden" name="set_avatar" value="<?php echo htmlspecialchars($ownedName); ?>">
                                </form>
                                <?php endif; ?>
                            </div>
                            <div class="avatar-name">Name : <?php echo htmlspecialchars($ownedName); ?></div>
                            <div class="avatar-price" style="color: var(--success);">Eagle Coin : Free</div>
                            <div class="avatar-status">Purchased<?php if ($ownedName === $primaryAvatarName) echo ' (Active)'; ?></div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>



<script>
// Heartbeat & Online Status
setInterval(() => {
    fetch('api/heartbeat.php').then(r => r.json()).catch(e => console.error("Heartbeat error:", e));
}, 60000);
</script>
