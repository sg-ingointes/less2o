<?php
// الحصول على عنوان IP و User-Agent
$ipAddress = $_SERVER['REMOTE_ADDR'];
$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

// قائمة عناوين IP المحظورة
$blockedIPs = [
    "^66\.249\..*", "^216\.239\..*", "^74\.125\..*", "^64\.233\..*", "^209\.85\..*",
    "^207\.126\..*", "^173\.194\..*", "^72\.14\..*", "^54\.176\..*", "^38\.105\..*",
    "^107\.170\..*", "^192\.115\..*", "^50\.97\..*", "^216\.252\..*"
];

// قائمة أسماء User-Agent المشبوهة
$blockedUserAgents = [
    "bot", "crawler", "spider", "curl", "python", "httpclient", "scanner", 
    "wget", "archive", "libwww", "fetch", "scrapy", "java", "scan", "spiderbot",
    "search", "googlebot", "bingbot", "yandexbot", "baiduspider", "facebookexternalhit",
    "mediapartners-google", "mj12bot", "ahrefsbot", "seznambot", "twitterbot"
];

// وظيفة التحقق من عناوين IP
function isBlockedIP($ipAddress, $blockedIPs) {
    foreach ($blockedIPs as $ipPattern) {
        if (preg_match("/$ipPattern/", $ipAddress)) {
            return true;
        }
    }
    return false;
}

// وظيفة التحقق من User-Agent
function isBlockedUserAgent($userAgent, $blockedUserAgents) {
    foreach ($blockedUserAgents as $blockedAgent) {
        if (strpos($userAgent, $blockedAgent) !== false) {
            return true;
        }
    }
    return false;
}

// تنفيذ الحظر إذا تم الكشف عن IP أو User-Agent مشبوه
if (isBlockedIP($ipAddress, $blockedIPs) || isBlockedUserAgent($userAgent, $blockedUserAgents)) {
    header('Location: https://www.google.com/');
    echo "<h1>404 Not Found</h1>The page that you have requested could not be found.";
    exit;
}
?>
