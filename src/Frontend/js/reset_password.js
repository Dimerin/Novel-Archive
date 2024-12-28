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
        // Clear existing content
        while (this.mainContent.firstChild) {
            this.mainContent.removeChild(this.mainContent.firstChild);
        }

        // Create and append new elements
        const span1 = document.createElement('span');
        span1.className = 'w3-jumbo w3-hide-small w3-animate-bottom';
        span1.textContent = 'Insert your new password';
        this.mainContent.appendChild(span1);
        this.mainContent.appendChild(document.createElement('br'));

        const span2 = document.createElement('span');
        span2.className = 'w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom';
        span2.textContent = 'Insert your new password';
        this.mainContent.appendChild(span2);
        this.mainContent.appendChild(document.createElement('br'));

        const span3 = document.createElement('span');
        span3.className = 'w3-xlarge w3-animate-bottom';
        span3.textContent = 'Use a mix of uppercase and lowercase letters, numbers, and special symbols to increase security.';
        this.mainContent.appendChild(span3);

        const form = document.createElement('form');
        form.id = 'resetpswForm';
        form.className = 'w3-animate-bottom';

        const csrfTokenInput = document.createElement('input');
        csrfTokenInput.type = 'hidden';
        csrfTokenInput.name = 'csrf_token';
        csrfTokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfTokenInput);

        const emailInput = document.createElement('input');
        emailInput.type = 'hidden';
        emailInput.name = 'email';
        emailInput.value = email;
        form.appendChild(emailInput);

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = 'token';
        tokenInput.value = token;
        form.appendChild(tokenInput);

        const newPasswordInput = document.createElement('input');
        newPasswordInput.type = 'password';
        newPasswordInput.className = 'w3-input w3-border';
        newPasswordInput.name = 'new_password';
        newPasswordInput.placeholder = 'New Password';
        newPasswordInput.required = true;
        form.appendChild(newPasswordInput);
        form.appendChild(document.createElement('br'));

        const confirmPasswordInput = document.createElement('input');
        confirmPasswordInput.type = 'password';
        confirmPasswordInput.className = 'w3-input w3-border';
        confirmPasswordInput.name = 'conf_new_password';
        confirmPasswordInput.placeholder = 'Confirm Password';
        confirmPasswordInput.required = true;
        form.appendChild(confirmPasswordInput);
        form.appendChild(document.createElement('br'));

        const submitButton = document.createElement('button');
        submitButton.className = 'w3-button w3-black w3-animate-bottom';
        submitButton.type = 'submit';
        const icon = document.createElement('i');
        icon.className = 'fa fa-user-plus';
        submitButton.appendChild(icon);
        submitButton.appendChild(document.createTextNode(' REGISTER'));
        form.appendChild(submitButton);

        this.mainContent.appendChild(form);

        document.getElementById('resetpswForm').addEventListener('submit', (event) => this.sendForm(event));
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
                setTimeout(() => {
                this.renderSuccessMessage();}, 2000);
            } else {
                const error = await response.json();
                showToast('error', error.message);
            }
        } catch (error) {
            showToast('error', error.message);
            this.renderErrorMessage('An error occurred during password reset.');
        }
    }

    renderSuccessMessage() {

        // Clear existing content
        while (this.mainContent.firstChild) {
            this.mainContent.removeChild(this.mainContent.firstChild);
        }

        // Create and append new elements
        const span1 = document.createElement('span');
        span1.className = 'w3-jumbo w3-hide-small w3-animate-bottom w3-animate-delay-1';
        span1.textContent = 'The password has been successfully reset.';
        this.mainContent.appendChild(span1);
        this.mainContent.appendChild(document.createElement('br'));

        const span2 = document.createElement('span');
        span2.className = 'w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom w3-animate-delay-1';
        span2.textContent = 'The password has been successfully reset.';
        this.mainContent.appendChild(span2);
        this.mainContent.appendChild(document.createElement('br'));

        const span3 = document.createElement('span');
        span3.className = 'w3-xlarge w3-animate-bottom w3-animate-delay-2';
        span3.textContent = 'You can now perform the login to access our services.';
        this.mainContent.appendChild(span3);
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