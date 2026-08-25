<?php
require_once 'api/auth_check.php';

$ideDownloadUrl = 'https://github.com/Secure-Worldz-Official/Secure-Worldz-desktop/releases/download/v1.1.3/eaglone-ide-1.1.3-setup.exe';
$ideVersion = '1.1.3';
$latestReleaseEndpoint = 'https://api.github.com/repos/Secure-Worldz-Official/Secure-Worldz-desktop/releases/latest';
$releaseResponse = false;

if (function_exists('curl_init')) {
    $ch = curl_init($latestReleaseEndpoint);
    if ($ch !== false) {
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER => [
                'Accept: application/vnd.github+json',
                'User-Agent: Secure Worldzz-IDE-Download',
            ],
        ]);
        $candidate = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($candidate !== false && $statusCode >= 200 && $statusCode < 400) {
            $releaseResponse = $candidate;
        }
        curl_close($ch);
    }
} else {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 8,
            'header' => "Accept: application/vnd.github+json\r\nUser-Agent: Secure Worldzz-IDE-Download\r\n",
        ],
    ]);
    $candidate = @file_get_contents($latestReleaseEndpoint, false, $context);
    if ($candidate !== false) {
        $releaseResponse = $candidate;
    }
}

if ($releaseResponse !== false) {
    $releaseData = json_decode($releaseResponse, true);
    if (is_array($releaseData)) {
        $tagName = (string)($releaseData['tag_name'] ?? '');
        $assets = $releaseData['assets'] ?? [];

        if (is_array($assets)) {
            foreach ($assets as $asset) {
                if (!is_array($asset)) {
                    continue;
                }

                $assetName = strtolower((string)($asset['name'] ?? ''));
                $assetUrl = (string)($asset['browser_download_url'] ?? '');

                if ($assetUrl !== '' && substr($assetName, -4) === '.exe') {
                    $ideDownloadUrl = $assetUrl;

                    if ($tagName !== '') {
                        $ideVersion = ltrim($tagName, 'vV');
                    } elseif (preg_match('/([0-9]+(?:\.[0-9]+)+)/', $assetName, $matches)) {
                        $ideVersion = $matches[1];
                    }
                    break;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/webp" href="image.webp">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eaglone IDE | Secure Worldzz</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="api/includes/loading_resilience.js?v=20260822" defer></script>
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto Mono', monospace;
    background-color: #000000;
    color: #ffffff;
    min-height: 100vh;
}

@font-face {
    font-family: "Rebels";
    src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
    font-display: swap;
}

:root {
            --gap: 1.5rem;
            --sides: 1.5rem;
    --background: #000000;
    --foreground: #ffffff;
    --card: #080808;
    --primary: #ff2a2f;
    --primary-light: #8183f4;
    --primary-foreground: #ffffff;
    --muted-foreground: #a0a0a0;
    --border: rgba(139, 12, 16, 0.1);
    
    --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    --gradient-subtle: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(129, 131, 244, 0.1) 100%);
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
    border-radius: 0.625rem;
    border: 1px solid var(--border);
    overflow: hidden;
}

.p-4 { padding: 1rem; }
.p-3 { padding: 0.75rem; }

.flex { display: flex; }
.items-center { align-items: center; }
.gap-3 { gap: 0.75rem; }
.size-12 { width: 3rem; height: 3rem; }
.bg-primary { background-color: var(--primary); }
.rounded-lg { border-radius: 0.625rem; }
.text-primary-foreground { color: var(--primary-foreground); }
.text-2xl { font-size: 1.5rem; line-height: 2rem; }
.font-display { font-family: 'Rebels', monospace; font-weight: 700; }
.text-xs { font-size: 0.75rem; line-height: 1rem; }
.text-muted-foreground { color: var(--muted-foreground); }
.uppercase { text-transform: uppercase; }
.flex-1 { flex: 1 1 0%; }
.flex-shrink-0 { flex-shrink: 0; }











.desktop-main {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    overflow-y: auto;
    max-height: calc(100vh - 3rem);
    padding-right: 0.5rem;
}


.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-family: 'Rebels', monospace;
    font-size: 3.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.page-header p {
    color: var(--muted-foreground);
    font-size: 1.125rem;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}


.maintenance-content {
    background: linear-gradient(145deg, var(--card) 0%, rgba(8, 8, 8, 0.9) 100%);
    padding: 4rem;
    border-radius: 0.625rem;
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
    width: 100%;
}


.download-wrapper {
    display: flex;
    justify-content: center;
    width: 100%;
}

.download-card {
    width: 100%;
    max-width: 880px;
    background: linear-gradient(145deg, var(--card) 0%, rgba(8, 8, 8, 0.96) 100%);
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    padding: 2.75rem;
    display: flex;
    gap: 2.5rem;
    align-items: center;
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.25);
}

.download-image {
    width: 360px;
    max-width: 100%;
    height: auto;
    border-radius: 0.65rem;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.06);
    padding: 0.75rem;
}

.download-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
}

.download-title {
    font-family: 'Rebels', monospace;
    font-size: 2.25rem;
    letter-spacing: 0.02em;
}

.download-desc {
    color: var(--muted-foreground);
    font-size: 1.05rem;
    line-height: 1.7;
}

.download-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.download-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.9rem 1.6rem;
    border-radius: 0.55rem;
    background: var(--gradient-primary);
    color: var(--primary-foreground);
    text-decoration: none;
    font-weight: 600;
    letter-spacing: 0.03em;
    box-shadow: 0 12px 24px rgba(99, 102, 241, 0.28);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 28px rgba(99, 102, 241, 0.32);
}

.download-meta {
    color: var(--muted-foreground);
    font-size: 0.9rem;
}

.maintenance-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.maintenance-content h2 {
    font-family: 'Rebels', monospace;
    font-size: 3rem;
    color: var(--foreground);
    margin-bottom: 2rem;
    text-align: center;
}

.maintenance-content > p {
    color: var(--muted-foreground);
    font-size: 1.25rem;
    line-height: 1.8;
    margin-bottom: 3rem;
    text-align: center;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}


.maintenance-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.maintenance-image-box {
    text-align: center;
    padding: 2rem;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid transparent;
    transition: all 0.3s ease;
    width: 100%;
}

.maintenance-image-box:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--primary);
    transform: translateY(-5px);
}

.maintenance-image {
    width: 300px;
    height: 200px;
    object-fit: contain;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.maintenance-status {
    color: var(--primary);
    font-size: 2rem;
    font-weight: 600;
    margin: 0;
}


.status-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
    width: 100%;
}

.status-box {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 2rem;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.status-box:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--primary);
    transform: translateY(-5px);
}

.status-box i {
    font-size: 2rem;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: var(--gradient-subtle);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.status-box:hover i {
    background: var(--gradient-primary);
    color: var(--primary-foreground);
    transform: scale(1.1);
}

.status-text {
    color: var(--foreground);
    font-size: 1.25rem;
    font-weight: 500;
    margin: 0;
}

.status-detail {
    color: var(--muted-foreground);
    font-size: 1rem;
    margin-top: 0.5rem;
}


.footer {
    text-align: center;
    padding: 2rem;
    color: var(--muted-foreground);
    border-top: 1px solid var(--border);
    margin-top: auto;
    font-size: 0.875rem;
}


.tv-noise {
    position: absolute;
    inset: 0;
    background: 
        repeating-linear-gradient(
            0deg,
            rgba(0, 0, 0, 0.1) 0px,
            rgba(0, 0, 0, 0.1) 1px,
            transparent 1px,
            transparent 2px
        ),
        repeating-linear-gradient(
            90deg,
            rgba(0, 0, 0, 0.1) 0px,
            rgba(0, 0, 0, 0.1) 1px,
            transparent 1px,
            transparent 2px
        );
    opacity: 0.1;
    pointer-events: none;
    z-index: 1;
    animation: tvNoise 0.1s infinite;
}

@keyframes tvNoise {
    0%, 100% { background-position: 0 0; }
    10% { background-position: -5% -10%; }
    20% { background-position: -15% 5%; }
    30% { background-position: 7% -25%; }
    40% { background-position: 20% 25%; }
    50% { background-position: -25% 10%; }
    60% { background-position: 15% 5%; }
    70% { background-position: 0 15%; }
    80% { background-position: 25% 35%; }
    90% { background-position: -10% 10%; }
}


@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-fadeIn {
    opacity: 0;
    animation: fadeInUp 0.8s ease forwards;
}

.animate-fadeIn.delay-1 { animation-delay: 0.1s; }
.animate-fadeIn.delay-2 { animation-delay: 0.2s; }
.animate-fadeIn.delay-3 { animation-delay: 0.3s; }

.pulse {
    animation: pulse 2s infinite;
}


@media (max-width: 1400px) {
    .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }
    
    .maintenance-content {
        padding: 3rem;
    }
}

@media (max-width: 1024px) {
    .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }
    
    
    
    .page-header h1 {
        font-size: 2.75rem;
    }
    
    .maintenance-content h2 {
        font-size: 2.5rem;
    }

    .download-card {
        flex-direction: column;
        text-align: center;
        padding: 2.25rem;
    }

    .download-info {
        align-items: center;
    }
}

@media (max-width: 768px) {
    .page-header h1 {
        font-size: 2.25rem;
    }
    
    .page-header p {
        font-size: 1rem;
        padding: 0 1rem;
    }
    
    .maintenance-content h2 {
        font-size: 2rem;
    }
    
    .maintenance-content {
        padding: 2rem;
    }
    
    .status-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .status-box {
        padding: 1.5rem;
    }
    
    .status-box i {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .status-text {
        font-size: 1rem;
    }
    
    .maintenance-image {
        width: 250px;
        height: 150px;
    }

    .download-title {
        font-size: 1.9rem;
    }
}

@media (max-width: 480px) {
    .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .maintenance-content {
        padding: 1.5rem;
    }
    
    .maintenance-content h2 {
        font-size: 1.75rem;
    }
    
    .maintenance-content > p {
        font-size: 1rem;
    }
}


.desktop-main::-webkit-scrollbar {
    width: 6px;
}

.desktop-main::-webkit-scrollbar-track {
    background: transparent;
}

.desktop-main::-webkit-scrollbar-thumb {
    background: #080808;
    border-radius: 3px;
}

.desktop-main::-webkit-scrollbar-thumb:hover {
    background: #a0a0a0;
}

        
        

        
        

        
</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div id="loader-text">INITIALIZING ECOSYSTEM...</div>
    </div>

<div class="tv-noise"></div>


    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.body.classList.add('loaded');
            }, 500);
        });
    </script>
<div class="desktop-container">
                        <?php include 'sidebar.php'; ?>

    <div class="desktop-main">
        
        <section class="page-header animate-fadeIn">
            <h1>Eaglone IDE</h1>
            <p>Download the latest stable desktop build for your labs and coursework.</p>
        </section>

        <div class="download-wrapper animate-fadeIn delay-1">
            <div class="download-card">
                <img class="download-image" src="images/eagle-ide.png" alt="Eaglone IDE" loading="lazy"> 
                <div class="download-info">
                    <h2 class="download-title">Download for Windows</h2>
                    <p class="download-desc">Secure, fast, and built for focused lab work with a streamlined developer experience.</p>
                    <div class="download-actions">
                        <a class="download-btn"
			   href="<?php echo htmlspecialchars($ideDownloadUrl, ENT_QUOTES, 'UTF-8'); ?>"
			   download>
			   Download Eaglone IDE
			</a>

                    </div>
                    <div class="download-meta">Latest version <?php echo htmlspecialchars($ideVersion, ENT_QUOTES, 'UTF-8'); ?> • Windows installer (.exe)</div>
                </div>
            </div>
        </div>

        
        <!-- <footer class="footer animate-fadeIn delay-3">
            <p>&copy; 2026 Secure Worldzz. All rights reserved.</p>
        </footer> -->
    </div>

    </div>
</div>

<script>
    setInterval(() => {
        fetch('api/heartbeat.php').then(r => r.json()).catch(e => console.error("Heartbeat error:", e));
    }, 60000);

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationPlayState = 'running';
            observer.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
});


document.querySelectorAll('.animate-fadeIn').forEach(el => {
    observer.observe(el);
});


document.querySelectorAll('.status-box, .maintenance-image-box').forEach(box => {
    box.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    
    box.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});


window.addEventListener('load', () => {

    const header = document.querySelector('.page-header h1');
    if (header) {
        header.style.opacity = '0';
        header.style.transform = 'translateY(20px)';
        setTimeout(() => {
            header.style.transition = 'all 0.8s ease';
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }, 100);
    }
});
</script>
</body>
</html>
