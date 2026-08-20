<?php
declare(strict_types=1);


if (session_status() !== PHP_SESSION_ACTIVE) {
    
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => 0,
    'path' => $cookieParams['path'] ?? '/',
    'domain' => $cookieParams['domain'] ?? '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
if (isset($_SESSION['id'])) {
    if (!class_exists('DBconfig')) {
        require_once 'api/dbconfig.php';
    }
    $check_db = new DBconfig();
    $user_access = $check_db->getFieldbyId($_SESSION['id'], 'access');
    if ($user_access != 'true') {
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"><style>body{margin:0;padding:0;background:#000;display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:\'Space Grotesk\',sans-serif;color:#fff;overflow:hidden}.overlay{position:fixed;inset:0;background:rgba(0,0,0,0.9);z-index:9999;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(15px)}.modal{background:#000000;border:1px solid rgba(255,0,0,0.3);padding:3rem;border-radius:20px;text-align:center;box-shadow:0 0 50px rgba(255,0,0,0.15);max-width:400px;width:90%;animation:modalEntry 0.5s cubic-bezier(0.4,0,0.2,1)}@keyframes modalEntry{from{opacity:0;transform:scale(0.9) translateY(20px)}to{opacity:1;transform:scale(1) translateY(0)}}.icon{color:#ff3333;font-size:4rem;margin-bottom:1.5rem;filter:drop-shadow(0 0 10px rgba(139, 12, 16,0.4))}h2{font-size:1.75rem;margin:0 0 1rem 0;letter-spacing:-0.02em}p{color:#a0a0a0;font-size:1rem;line-height:1.5;margin:0 0 2rem 0}.loader{width:40px;height:40px;border:3px solid rgba(139, 12, 16,0.1);border-top-color:#ff3333;border-radius:50%;animation:spin 1s linear infinite;margin:0 auto}@keyframes spin{to{transform:rotate(360deg)}}</style></head><body><div class="overlay"><div class="modal"><div class="icon"><i class="fas fa-shield-halved"></i></div><h2>Access Restricted</h2><p>Upgrade to access the Secure Worldz Academy Lab</p><div class="loader"></div></div></div><script>setTimeout(()=>{window.location.href="logout.php"},3500)</script></body></html>';
        exit();
    }
}
}

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$title = isset($_GET['title']) ? urldecode($_GET['title']) : 'Course Videos';
$videoIds = ['dQw4w9WgXcQ', '9bZkp7q19f0', 'CduA0TULnow'];
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <link rel="icon" type="image/webp" href="image.webp">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Videos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Roboto Mono',monospace;background-color:#000000;color:#ffffff;min-height:100vh;}
        .video-container{max-width:1200px;margin:0 auto;padding:2rem;}
        .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.1);}
        .back-btn{background-color:#ff2a2f;color:white;border:none;padding:0.5rem 1.5rem;border-radius:0.375rem;cursor:pointer;font-family:inherit;font-weight:500;text-transform:uppercase;letter-spacing:0.05em;}
        .video-grid{display:grid;grid-template-columns:2fr 1fr;gap:2rem;}
        .main-video{background-color:#080808;border-radius:0.625rem;overflow:hidden;}
        .video-player-container{position:relative;width:100%;padding-top:56.25%;background-color:#000;}
        #player{position:absolute;top:0;left:0;width:100%;height:100%;}

        .video-controls{display:flex;align-items:center;gap:1rem;padding:1rem;background-color:rgba(0,0,0,0.5);}
        .control-btn{background:none;border:none;color:white;cursor:pointer;font-size:1.5rem;}
        .video-list{display:flex;flex-direction:column;gap:1rem;}
        .video-item{background-color:#080808;border-radius:0.625rem;overflow:hidden;cursor:pointer;transition:all 0.3s ease;}
        .video-item:hover{transform:translateY(-2px);border-color:#ff2a2f;}
        .video-item.active{background-color:rgba(99,102,241,0.2);border:2px solid #ff2a2f;}
        .video-thumb{position:relative;padding-top:56.25%;}
        .video-thumb img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;}
        .video-info{padding:1rem;}
        .video-info h3{font-size:1rem;margin-bottom:0.5rem;}
        .video-info p{font-size:0.875rem;color:rgba(255,255,255,0.7);}
        .play-btn{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background-color:rgba(0,0,0,0.7);color:white;border:none;border-radius:50%;width:50px;height:50px;cursor:pointer;font-size:1.5rem;}
        .volume-slider{-webkit-appearance:none;appearance:none;height:6px;background:rgba(255,255,255,0.2);border-radius:3px;outline:none;}
        .volume-slider::-webkit-slider-thumb{-webkit-appearance:none;appearance:none;width:18px;height:18px;border-radius:50%;background:#ff2a2f;cursor:pointer;}
        .volume-slider::-moz-range-thumb{width:18px;height:18px;border-radius:50%;background:#ff2a2f;cursor:pointer;border:none;}
        .pwz-badge{position:absolute;bottom:10px;right:10px;background:rgba(0,0,0,0.7);color:#ff2a2f;padding:5px 10px;border-radius:4px;font-family:'Roboto Mono',monospace;font-weight:700;font-size:14px;letter-spacing:2px;z-index:3;}
        iframe{border:none!important;}
        @media (max-width: 800px) {
            .video-container{padding:1rem;}
            .header{flex-wrap:wrap;gap:1rem;}
            .video-grid{grid-template-columns:1fr;gap:1.5rem;}
        }
</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
    <div class="video-container">
        <div class="header">
            <h1 style="font-size:2rem;font-weight:700;"><?php echo htmlspecialchars($title); ?></h1>
            <button class="back-btn" onclick="window.location.href='ourcourse.php'">Back to Courses</button>
        </div>
        <div class="video-grid">
            <div class="main-video">
                <div class="video-player-container">
                    <div id="player"></div>
                    <div class="pwz-badge">PWZ-101</div>
                </div>
                <div class="video-controls">
                    <button class="control-btn" onclick="playVideo()">▶</button>
                    <button class="control-btn" onclick="pauseVideo()">⏸</button>
                    <button class="control-btn" onclick="toggleMute()">🔊</button>
                    <input type="range" class="volume-slider" min="0" max="100" value="100" oninput="setVolume(this.value)" style="flex:1;">
                </div>
            </div>
            <div class="video-list" id="videoList">
                <?php foreach ($videoIds as $index => $id): ?>
                <div class="video-item <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeVideo('<?php echo $id; ?>', this)">
                    <div class="video-thumb">
                        <img src="https://img.youtube.com/vi/<?php echo $id; ?>/hqdefault.png" alt="Video <?php echo $index + 1; ?>" onerror="this.src='https://img.youtube.com/vi/<?php echo $id; ?>/default.png'"> 
                        <div class="play-btn">▶</div>
                    </div>
                    <div class="video-info">
                        <h3>Video <?php echo $index + 1; ?></h3>
                        <p>Watch this tutorial</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script>
    let player,currentVideoId='<?php echo $videoIds[0]; ?>',isMuted=false;
    function onYouTubeIframeAPIReady(){
        player=new YT.Player('player',{
            height:'100%',
            width:'100%',
            videoId:currentVideoId,
            playerVars:{
                'autoplay':1,
                'controls':0,
                'disablekb':1,
                'fs':0,
                'modestbranding':1,
                'rel':0,
                'showinfo':0,
                'iv_load_policy':3,
                'playsinline':1,
                'enablejsapi':1,
                'origin':window.location.origin
            },
            events:{
                'onReady':onPlayerReady,
                'onStateChange':onPlayerStateChange
            }
        });
    }
    function onPlayerReady(e){
        e.target.playVideo();
    }
    function onPlayerStateChange(){
    }
    function changeVideo(videoId,el){
        currentVideoId=videoId;
        document.querySelectorAll('.video-item').forEach(i=>i.classList.remove('active'));
        if(el)el.classList.add('active');
        if(player&&player.loadVideoById){
            player.loadVideoById(videoId);
            player.playVideo();
        }
    }
    function playVideo(){
        if(player&&player.playVideo)player.playVideo();
    }
    function pauseVideo(){
        if(player&&player.pauseVideo)player.pauseVideo();
    }
    function toggleMute(){
        if(player){
            if(isMuted){
                player.unMute();
                isMuted=false;
            }else{
                player.mute();
                isMuted=true;
            }
        }
    }
    function setVolume(v){
        if(player&&player.setVolume)player.setVolume(v);
    }
    const tag=document.createElement('script');
    tag.src="https://www.youtube.com/iframe_api";
    document.head.appendChild(tag);
</script>
</body>
</html>
