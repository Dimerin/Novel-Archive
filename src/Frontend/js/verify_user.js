class EmailConfirmationPage {
    constructor(confirmationContentId) {
        this.mainContent = document.getElementById(confirmationContentId);
    }

    async render() {
        const urlParams = new URLSearchParams(window.location.search);
        const email = urlParams.get('email');
        const token = urlParams.get('token');

        if (!email || !token) {
            this.renderErrorMessage('Invalid confirmation link.');
            return;
        }

        try {
            const response = await fetch(`/api/verify_user?email=${encodeURIComponent(email)}&token=${encodeURIComponent(token)}`, {
                method: 'GET'
            });

            const result = await response.json();

            if (result.status === 'success') {
                this.renderSuccessMessage();
            } else {
                this.renderErrorMessage(result.message || 'Confirmation failed.');
            }
        } catch (error) {
            console.error('Error during email confirmation:', error);
            this.renderErrorMessage('An error occurred during confirmation.');
        }
    }

    renderSuccessMessage() {
        this.mainContent.innerHTML = `
            <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-animate-delay-1">Confirmation Successful</span><br>
            <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom w3-animate-delay-1">Confirmation Successful</span><br>
            <span class="w3-xlarge w3-animate-bottom w3-animate-delay-2">You have successfully registered, you will be redirected to the homepage in <span id="countdown">3</span> seconds.</span>
        `;
        this.startCountdown();
    }

    renderErrorMessage(message) {
        this.mainContent.innerHTML = `
            <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-animate-delay-1">Confirmation Failed</span><br>
            <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom w3-animate-delay-1">Confirmation Failed</span><br>
            <span class="w3-xlarge w3-animate-bottom w3-animate-delay-2">${message}</span>
        `;
    }

    startCountdown() {
        let countdownElement = document.getElementById('countdown');
        let countdown = 4;
        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = '/login';
            }
        }, 1000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const emailConfirmationPage = new EmailConfirmationPage('confirmationContentId');
    emailConfirmationPage.render();
});