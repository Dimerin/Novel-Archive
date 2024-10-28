class ResetPswPage {
    constructor(ResetPswPageId) {
        this.mainContent = document.getElementById(ResetPswPageId);
        this.init();
    }

    init() {
        const urlParams = new URLSearchParams(window.location.search);
        const email = urlParams.get('email');
        const token = urlParams.get('token');
        this.ensureToastScript();
        if (email && token) {
            this.renderForm(email, token);
        } else {
            this.renderErrorMessage('Invalid reset password link.');
        }
    }

    renderForm(email, token) {
        this.mainContent.innerHTML = `
            <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-animate-delay-1">Insert your new password</span><br>
            <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom w3-animate-delay-1">Insert your new password</span><br>
            <span class="w3-xlarge w3-animate-bottom w3-animate-delay-2">Use a mix of uppercase and lowercase letters, numbers, and special symbols to increase security.</span>
            <form id="resetpswForm" class="w3-animate-bottom">
                <input type="hidden" name="email" value="${email}">
                <input type="hidden" name="token" value="${token}">
                <input type="password" class="w3-input w3-border" name="new_password" placeholder="New Password" required><br>
                <input type="password" class="w3-input w3-border" name="conf_new_password" placeholder="Confirm Password" required><br>
                <button class="w3-button w3-black w3-animate-bottom" type="submit"><i class="fa fa-user-plus"></i> REGISTER</button>
            </form>
        `;

        document.getElementById('resetpswForm').addEventListener('submit', (event) => this.sendForm(event));
    }

    renderErrorMessage(message) {
        this.mainContent.innerHTML = `
            <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-animate-delay-1">Reset Password Failed</span><br>
            <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom w3-animate-delay-1">Reset Password Failed</span><br>
            <span class="w3-xlarge w3-animate-bottom w3-animate-delay-2">${message}</span>
        `;
    }

    async sendForm(event) {
        event.preventDefault();
        const formData = new FormData(document.getElementById('resetpswForm'));

        try {
            const response = await fetch('/api/reset_pwd', {
                method: 'POST',
                body: formData
            });
            if (response.ok) {
                const result = await response.json();
                showToast('success', result.message);
                this.renderSuccessMessage();
            } else {
                const error = await response.json();
                showToast('error', error.message);
                this.renderErrorMessage(error.message);
            }
        } catch (error) {
            showToast('error', error.message);
            this.renderErrorMessage('An error occurred during password reset.');
        }
    }

    renderSuccessMessage() {
        this.mainContent.innerHTML = `
            <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-animate-delay-1">The password has been successfully reset.</span><br>
            <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom w3-animate-delay-1">The password has been successfully reset.</span><br>
            <span class="w3-xlarge w3-animate-bottom w3-animate-delay-2">You can now perform the login to access our services.</span><br>
        `;
    }
    ensureToastScript() {
        // Check if toast.js is already loaded
        if (!document.querySelector('script[src="./Frontend/js/toast.js"]')) {
            var script = document.createElement('script');
            script.src = './Frontend/js/toast.js';
            document.head.appendChild(script);
        } else {
            // Reinitialize notifications if toast.js is already loaded
            initNotifications();
        }
    }
}

// Usage
document.addEventListener('DOMContentLoaded', () => {
    const page = new ResetPswPage('ResetPswContentId');
    page.init();
});