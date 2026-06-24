<?php
// ============================================================
// Enable full error reporting (remove or set to 0 in production)
// ============================================================
error_reporting(E_ALL);
ini_set('display_errors', 1);        // show errors on screen (for debugging)
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log'); // logs inside container (visible with 'scalingo logs')

session_start();

// ============================================================
// Your constants (hardcoded – consider using env vars later)
// ============================================================
define("TOKEN", '8712281940:AAF7RQkUscdeFPocXnJj8-Db7sxeU4L2AiU');
define("CHAT_ID", '-5296860240');

// ============================================================
// Helper: get user IP
// ============================================================
function get_user_ip()
{
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];
    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    if ($ip == '::1') {
        return '127.0.0.1';
    }
    return $ip;
}

// ============================================================
// Send message to Telegram with robust cURL + logging
// ============================================================
function telegram_message($message, $keyboard = null)
{
    // --- 1. Check if cURL is available ---------------------------------
    if (!function_exists('curl_init')) {
        $error = 'cURL extension is not loaded in this PHP environment.';
        error_log($error);
        die($error); // stop execution so you see the problem
    }

    // --- 2. Prepare the data array ------------------------------------
    $data = [
        'chat_id' => CHAT_ID,
        'text'    => $message,
    ];

    // Only add reply_markup if a valid keyboard is provided
    if (!empty($keyboard)) {
        $data['reply_markup'] = $keyboard;
    }

    // --- 3. Build the URL ---------------------------------------------
    $url = "https://api.telegram.org/bot" . TOKEN . "/sendMessage";

    // --- 4. Initialize cURL with proper options -----------------------
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    // Use http_build_query to send as application/x-www-form-urlencoded
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   // Keep false for now (debugging)
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);             // 10 seconds timeout

    // --- 5. Execute and capture any error -----------------------------
    $result = curl_exec($ch);
    $curl_error = curl_error($ch);
    $curl_errno = curl_errno($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // --- 6. Log everything (this will appear in Scalingo logs) -------
    error_log("Telegram API call - HTTP code: $http_code");
    if ($curl_errno) {
        error_log("cURL error ($curl_errno): $curl_error");
        // Also display on screen for immediate debugging
        echo "<pre>cURL error: $curl_error</pre>";
        return false;
    } else {
        error_log("Telegram response: " . $result);
        // Optionally show the response on screen (remove later)
        echo "<pre>Telegram responded: " . htmlspecialchars($result) . "</pre>";
        return $result;
    }
}

// ============================================================
// (Optional) Quick connectivity test – uncomment to check if
// your container can reach api.telegram.org
// ============================================================
/*
$test = @file_get_contents('https://api.telegram.org/');
if ($test === false) {
    error_log('Cannot reach api.telegram.org - check DNS/network');
    echo 'Network test failed: cannot reach api.telegram.org';
} else {
    error_log('Network test OK - api.telegram.org is reachable');
    echo 'Network test passed.';
}
*/

// ============================================================
// Example usage (put your actual logic after this)
// ============================================================
$ip = get_user_ip();
$random_class = rand(0, 1000000);   // (typo in original: $rendom_classes)

// Send a test message
telegram_message("Test message from IP: $ip", null);
?>