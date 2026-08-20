<?php
require_once 'api/auth_check.php';
$currentCourse = $course;


$lms_db_host = "sql309.infinityfree.com";
$lms_db_user = "if0_41355325";
$lms_db_pass = "ysIc3WBv0rkf";
$lms_db_name = "if0_41355325_lmsdb";


$lms_conn = new mysqli($lms_db_host, $lms_db_user, $lms_db_pass, $lms_db_name);

if ($lms_conn->connect_error) {
    
    $videos = [];
} else {
    $stmt = $lms_conn->prepare("SELECT title, description, video_path, document_path FROM videos WHERE course = ?");
    $stmt->bind_param("s", $currentCourse);
    $stmt->execute();
    $result = $stmt->get_result();
    $videos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $lms_conn->close();
}

?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Videos - Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        
        
        
        
        
        
        

        #loader-wrapper {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: #000000; display: flex; flex-direction: column;
            align-items: center; justify-content: center; z-index: 99999;
            transition: opacity 0.5s ease, visibility 0.5s;
        }
        #loader {
            width: 50px; height: 50px; border: 3px solid rgba(139, 92, 246, 0.1);
            border-top-color: #ff2a2f; border-radius: 50%;
            animation: spin 1s linear infinite; margin-bottom: 20px;
        }
        #loader-text {
            font-family: 'Roboto Mono', monospace; font-size: 0.75rem;
            color: #ff2a2f; letter-spacing: 0.2em; animation: pulse-loader 1.5s infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes pulse-loader { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }
        body.loaded #loader-wrapper { opacity: 0; visibility: hidden; }
            color: white !important; 
            box-shadow: none !important; 
            border: 1px solid rgba(255,255,255,0.1) !important; 
            filter: none !important; 
            backdrop-filter: none !important; 
        }
        
        
        

        /* Video Styles */
        .video-grid { display: grid; grid-template-columns: minmax(0, 3fr) 1fr; gap: 1.5rem; align-items: start; }
        .player-section { background: var(--card); border-radius: 1rem; overflow: hidden; border: 1px solid var(--border); }
        .video-wrapper { position: relative; padding-top: 56.25%; background: #000; }
        .video-wrapper iframe, .video-wrapper video { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; z-index: 1; }
        .video-watermark {
            position: absolute; top: 20px; left: 20px; z-index: 10; pointer-events: none;
            color: rgba(255, 255, 255, 0.4); font-size: 0.875rem; font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); background: rgba(0, 0, 0, 0.4);
            padding: 4px 12px; border-radius: 4px; border: 1px solid rgba(139, 12, 16, 0.1);
            backdrop-filter: blur(4px); animation: pulse-opacity 4s infinite ease-in-out;
        }
        @keyframes pulse-opacity { 0%, 100% { opacity: 0.6; } 50% { opacity: 1; } }
        .video-details { padding: 1.5rem; }
        .video-details h2 { font-size: 1.5rem; margin-bottom: 0.5rem; font-family: 'Rebels', monospace; }
        .video-details p { color: #a0a0a0; line-height: 1.6; margin-bottom: 1rem; font-size: 0.875rem; }
        .doc-link {
            display: inline-flex; align-items: center; gap: 0.5rem; background: var(--primary);
            color: white; padding: 0.6rem 1.2rem; border-radius: 0.5rem; text-decoration: none;
            font-weight: 600; font-size: 0.8rem; text-transform: uppercase; transition: all 0.2s;
        }
        .doc-link:hover { background: #ff2a2f; transform: translateY(-1px); }

        .playlist-section { background: var(--card); border-radius: 1rem; border: 1px solid var(--border); overflow: hidden; display: flex; flex-direction: column; height: 100%; }
        .playlist-header { padding: 1rem; border-bottom: 1px solid var(--border); background: rgba(255,255,255,0.02); flex-shrink: 0; }
        .playlist-items { flex: 1; overflow-y: auto; max-height: 600px; }
        .playlist-item { padding: 0.75rem 1rem; display: flex; gap: 1rem; cursor: pointer; transition: all 0.2s; border-bottom: 1px solid var(--border); }
        .playlist-item:last-child { border-bottom: none; }
        .playlist-item:hover { background: rgba(139, 12, 16, 0.05); }
        .playlist-item.active { background: rgba(139, 92, 246, 0.1); border-left: 4px solid var(--primary); }
        .item-thumb { width: 80px; height: 45px; background: #000; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .item-thumb i { font-size: 1rem; color: var(--primary); }
        .item-info { flex: 1; overflow: hidden; }
        .item-info h4 { font-size: 0.8rem; margin-bottom: 0.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: white; }
        .item-info span { font-size: 0.7rem; color: #a0a0a0; }

        .tv-noise { position: absolute; inset: 0; background: repeating-linear-gradient(0deg, rgba(0, 0, 0, 0.1) 0px, rgba(0, 0, 0, 0.1) 1px, transparent 1px, transparent 2px); opacity: 0.1; pointer-events: none; z-index: 10; animation: tvNoise 0.1s infinite; }
        @keyframes tvNoise { 0%, 100% { background-position: 0 0; } 10% { background-position: -5% -10%; } 20% { background-position: -15% 5%; } 50% { background-position: -25% 10%; } }

        .badge-destructive { background: #ff2a2f; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; }


        
        

        
        

        

        

        

        

        
        

        

        

        

        

        

        

        

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
        <div id="loader-text">INITIALIZING CONTENT...</div>
    </div>

    
        <script>
            window.addEventListener('load', () => {
                setTimeout(() => { document.body.classList.add('loaded'); }, 500);
            });
        </script>
<div class="desktop-container">

        <!-- Left Sidebar -->
            <?php include 'sidebar.php'; ?>

    <div class="desktop-main">
            <div class="card">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded bg-primary flex items-center justify-center">
                             <i class="fas fa-video text-primary-foreground" style="font-size:1rem;"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-display">Course Content</h1>
                            <div class="text-sm text-muted-foreground uppercase">STREAMING CURRICULUM VIDEOS</div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (empty($videos)): ?>
                <div class="card" style="padding:4rem; text-align:center;">
                    <i class="fas fa-video-slash" style="font-size:3rem; color:var(--primary); margin-bottom:1.5rem; opacity:0.5;"></i>
                    <h3 style="color:white; margin-bottom:0.5rem;">No Videos Available</h3>
                    <p style="color:var(--muted-foreground);">There are no videos uploaded for your current course yet.</p>
                </div>
            <?php else: ?>
                <div class="video-grid">
                    <div class="player-section">
                        <div class="video-wrapper">
                            <div class="video-watermark">ID: <?php echo htmlspecialchars((string)$userId); ?></div>
                            <div id="videoDisplay" style="position: absolute; inset: 0; z-index: 1;">
                                <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: #000;">
                                    <p style="color:var(--muted-foreground);">Select a video to play</p>
                                </div>
                            </div>
                        </div>
                        <div class="video-details">
                            <h2 id="videoTitle" style="color:white; margin-bottom:0.5rem; font-family:'Rebels', monospace;">Select a video</h2>
                            <p id="videoDesc" style="color:var(--muted-foreground); font-size:0.875rem; line-height:1.6; margin-bottom:1.5rem;">Choose a video from the playlist to start learning.</p>
                            <div id="docContainer" style="display: none;">
                                <a href="#" id="docLink" class="doc-link" target="_blank">
                                    <i class="fas fa-file-pdf"></i> View Document
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="playlist-section">
                        <div class="playlist-header">
                            <h3 style="font-size: 0.9rem; color:white; text-transform:uppercase;">Curriculum Playlist</h3>
                            <p style="font-size: 0.7rem; color: #a0a0a0;"><?php echo count($videos); ?> lessons found</p>
                        </div>
                        <div class="playlist-items">
                            <?php foreach ($videos as $index => $video): ?>
                                <div class="playlist-item" onclick="loadVideo(<?php echo htmlspecialchars(json_encode($video)); ?>, this)">
                                    <div class="item-thumb">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="item-info">
                                        <h4><?php echo htmlspecialchars($video['title']); ?></h4>
                                        <span>Lesson <?php echo $index + 1; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
    </div>

    <script>

        // Heartbeat
        setInterval(() => {
            fetch('api/heartbeat.php').then(r => r.json()).catch(e => console.error("Heartbeat error:", e));
        }, 60000);

        // Video Player Logic
        let lastTime = 0;
        let isSeekingLocked = false; // Set to false to allow initial exploration

        function getYouTubeId(url) {
            // Handle various YouTube URL formats
            const patterns = [
                /[?&]v=([^&#]+)/,
                /youtu\.be\/([^?&#]+)/,
                /youtube\.com\/embed\/([^?&#]+)/,
                /youtube\.com\/v\/([^?&#]+)/,
                /youtube\.com\/shorts\/([^?&#]+)/
            ];
            for (const p of patterns) {
                const m = url.match(p);
                if (m) return m[1];
            }
            return null;
        }

        function isExternalUrl(path) {
            return path.startsWith('http://') || path.startsWith('https://') || path.startsWith('//');
        }

        function loadVideo(video, element) {
            document.querySelectorAll('.playlist-item').forEach(item => item.classList.remove('active'));
            element.classList.add('active');
            lastTime = 0;
            document.getElementById('videoTitle').innerText = video.title || 'No title';
            document.getElementById('videoDesc').innerText = video.description || '';

            const player = document.getElementById('videoDisplay');
            player.innerHTML = `<div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: #000; z-index: 20;">
                <div style="text-align: center;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;"></i>
                    <p style="font-size: 0.875rem; color: #a0a0a0;">Loading content...</p>
                </div>
            </div>`;

            setTimeout(() => {
                const path = video.video_path ? video.video_path.trim() : '';
                if (!path) {
                    player.innerHTML = `<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:#000;">
                        <p style="color:#ff2a2f;"><i class="fas fa-exclamation-triangle" style="margin-right:0.5rem;"></i>No video path provided.</p>
                    </div>`;
                    return;
                }

                let videoHtml = '';

                if (path.includes('youtube.com') || path.includes('youtu.be')) {
                    const videoId = getYouTubeId(path);
                    if (videoId) {
                        videoHtml = `<iframe 
                            src="https://www.youtube.com/embed/${videoId}?autoplay=1&modestbranding=1&rel=0&controls=1" 
                            allow="autoplay; encrypted-media; fullscreen" 
                            allowfullscreen 
                            style="position:absolute;inset:0;width:100%;height:100%;border:none;"
                        ></iframe>`;
                    } else {
                        videoHtml = `<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:#000;"><p style="color:#ff2a2f;">Invalid YouTube URL.</p></div>`;
                    }
                } else if (path.includes('drive.google.com')) {
                    // Google Drive embed
                    let driveId = '';
                    const m = path.match(/\/d\/([a-zA-Z0-9_-]+)/);
                    if (m) driveId = m[1];
                    if (driveId) {
                        videoHtml = `<iframe 
                            src="https://drive.google.com/file/d/${driveId}/preview" 
                            allow="autoplay" 
                            allowfullscreen 
                            style="position:absolute;inset:0;width:100%;height:100%;border:none;"
                        ></iframe>`;
                    } else {
                        videoHtml = `<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:#000;"><p style="color:#ff2a2f;">Invalid Google Drive URL.</p></div>`;
                    }
                } else {
                    // Direct video file - either full URL or relative path
                    const videoSrc = isExternalUrl(path) ? path : path;
                    videoHtml = `<video 
                        id="activeVideo" 
                        controls 
                        autoplay
                        controlsList="nodownload" 
                        oncontextmenu="return false;" 
                        style="position:absolute;inset:0;width:100%;height:100%;background:#000;"
                    >
                        <source src="${videoSrc}" type="video/mp4">
                        <source src="${videoSrc}" type="video/webm">
                        Your browser does not support the video element.
                    </video>`;
                }

                player.innerHTML = videoHtml;

                const videoElement = document.getElementById('activeVideo');
                if (videoElement) {
                    videoElement.addEventListener('error', function() {
                        player.innerHTML = `<div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;background:#000000;gap:1rem;">
                            <i class="fas fa-exclamation-triangle" style="font-size:2.5rem;color:#ff2a2f;"></i>
                            <p style="color:#ffffff;font-size:1rem;">Failed to load video.</p>
                            <p style="color:#a0a0a0;font-size:0.8rem;max-width:300px;text-align:center;">The video file could not be loaded. Please contact support or check the video path.</p>
                            <a href="${path}" target="_blank" style="color:var(--primary);font-size:0.8rem;">Try opening directly</a>
                        </div>`;
                    });
                    videoElement.addEventListener('loadedmetadata', function() {
                        videoElement.play().catch(() => {});
                    });
                    videoElement.addEventListener('timeupdate', function() {
                        if (!videoElement.seeking && isSeekingLocked) {
                            if (videoElement.currentTime > lastTime + 1.5) {
                                videoElement.currentTime = lastTime;
                            } else {
                                lastTime = videoElement.currentTime;
                            }
                        }
                    });
                    videoElement.addEventListener('seeking', function() {
                        if (isSeekingLocked && videoElement.currentTime > lastTime) {
                            videoElement.currentTime = lastTime;
                        }
                    });
                }
            }, 600);

            const docContainer = document.getElementById('docContainer');
            const docLink = document.getElementById('docLink');
            if (video.document_path && video.document_path.trim() !== '') {
                docLink.href = video.document_path;
                docContainer.style.display = 'block';
            } else {
                docContainer.style.display = 'none';
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const firstItem = document.querySelector('.playlist-item');
            if (firstItem) { firstItem.click(); }
        });
    </script>
</body>
</html>
