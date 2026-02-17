<?php

header( 'Content-Type: text/html; charset=utf-8' );
header( 'Cache-Control: s-maxage=2678400, max-age=2678400' );

$path = $_SERVER['REQUEST_URI'];
$actual_link = 'https://' . $_SERVER['HTTP_HOST'] . $path;
$encUrl = htmlspecialchars( $path );
$encHost = htmlspecialchars( $_SERVER['HTTP_HOST'] );
http_response_code( 410 );

echo <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/x-icon" href="https://static.wikioasis.org/images/metawiki/1/18/favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Wiki Deleted</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style type="text/css">
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding-left: 10%;
    background: #1a1f2e;
    color: white;
    overflow: hidden;
    position: relative;
}

.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.5;
    pointer-events: none;
    z-index: 0;
    animation: fade 8s ease-in-out infinite, float 15s ease-in-out infinite;
    will-change: transform, opacity;
}

@keyframes fade {
    0%, 100% {
        opacity: 0.4;
    }
    50% {
        opacity: 0.6;
    }
}

@keyframes float {
    0%, 100% {
        transform: translate(-50%, -50%) translate(0, 0);
    }
    25% {
        transform: translate(-50%, -50%) translate(30px, -20px);
    }
    50% {
        transform: translate(-50%, -50%) translate(-20px, 30px);
    }
    75% {
        transform: translate(-50%, -50%) translate(20px, 20px);
    }
}

.container {
    display: flex;
    align-items: center;
    position: relative;
    z-index: 1;
    gap: 24px;
}

.logo {
    width: 240px;
    height: auto;
    flex-shrink: 0;
}

.content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

h1 {
    font-size: 28px;
    font-weight: 600;
    color: white;
    margin-bottom: 2px;
}

.url {
    font-size: 13px;
    color: #5ba4d4;
    margin-bottom: 2px;
}

.description {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 16px;
}

.buttons {
    display: flex;
    gap: 12px;
}

.btn {
    padding: 10px 24px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: white;
}

.btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

.btn:active {
    transform: scale(0.98);
}

@media (max-width: 600px) {
    body {
        padding: 20px;
        justify-content: center;
    }
    
    .container {
        flex-direction: column;
        text-align: center;
    }
    
    .buttons {
        justify-content: center;
    }
}
</style>
<link rel="shortcut icon" href="https://static.wikioasis.org/metawiki/1/18/favicon.ico" />
</head>
<body>

<div class="container">
    <img class="logo" src="https://static.wikioasis.org/metawiki/3/38/WikiOasis_Logo.png" alt="WikiOasis Logo" />
    <div class="content">
        <h1>Wiki Missing</h1>
        <p class="url">{$encHost}{$encUrl}</p>
        <p class="description">We couldn't find this wiki, double check that you've typed the URL correctly.</p>
        <div class="buttons">
            <a href="https://meta.wikioasis.org/" class="btn">Go home</a>
            <a href="https://discord.gg/GrrTcsVC2x" class="btn">Discord</a>
        </div>
    </div>
</div>

<script>
(function() {
    const colors = [
        'rgba(64, 224, 208, 0.25)',   // Turquoise
        'rgba(46, 139, 87, 0.2)',     // Green
        'rgba(0, 150, 136, 0.25)',    // Teal
        'rgba(30, 144, 255, 0.3)',    // Blue
        'rgba(30, 80, 120, 0.35)',    // Deep blue
        'rgba(45, 120, 160, 0.3)',    // Steel blue
        'rgba(180, 160, 60, 0.25)',   // Golden
        'rgba(200, 180, 80, 0.2)',    // Amber
        'rgba(0, 206, 209, 0.25)',    // Cyan
        'rgba(65, 105, 225, 0.2)'     // Royal blue
    ];
    
    const numBlobs = 8 + Math.floor(Math.random() * 5); // 8-12 blobs
    
    for (let i = 0; i < numBlobs; i++) {
        const blob = document.createElement('div');
        blob.className = 'blob';
        
        // Random size between 300px and 600px (larger for more blur coverage)
        const size = 300 + Math.random() * 300;
        blob.style.width = size + 'px';
        blob.style.height = size + 'px';
        
        // Random position - bias towards right side of screen
        const xBias = 0.3 + Math.random() * 0.7; // 30% to 100% of screen width
        blob.style.left = (xBias * 100) + '%';
        blob.style.top = (Math.random() * 100) + '%';
        blob.style.transform = 'translate(-50%, -50%)';
        
        // Random color from palette
        blob.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        
        // Random animation durations and delays for independent movement
        const fadeDuration = 6 + Math.random() * 8; // 6-14 seconds
        const floatDuration = 12 + Math.random() * 16; // 12-28 seconds
        const fadeDelay = Math.random() * 5; // 0-5 seconds delay
        const floatDelay = Math.random() * 5; // 0-5 seconds delay
        
        blob.style.animation = 
            'fade ' + fadeDuration + 's ease-in-out ' + fadeDelay + 's infinite, ' +
            'float ' + floatDuration + 's ease-in-out ' + floatDelay + 's infinite';
        
        document.body.appendChild(blob);
    }
})();
</script>

</body>
</html>
EOF;
die( 1 );

