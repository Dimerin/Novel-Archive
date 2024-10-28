class ForgotPassword {
    constructor(formId) {
        this.forgotPwdForm = document.getElementById(formId);
        this.init();
    }

    init() {
        this.forgotPwdForm.addEventListener('submit', (event) => this.forgotPwd(event));
        this.ensureToastScript();
    }

    async forgotPwd(event) {
        event.preventDefault();
        const formData = new FormData(this.forgotPwdForm);

        try {
            const response = await fetch('/api/init_reset_pwd', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                showToast('success', result.message);
                this.showResetPwdForm();
            } else {
                const error = await response.json();
                showToast('error', error.message);
            }
        } catch (error) {
            showToast('error', error.message);
        }
    }

    showResetPwdForm() {
        const homeDiv = document.getElementById('home');
        homeDiv.innerHTML = `
                <div class="w3-display-left w3-text-white" style="padding: 48px">
                    <span class="w3-jumbo w3-hide-small w3-animate-bottom">Check your email inbox.</span><br>
                    <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom" >Check your email inbox.</span><br>
                    <span class="w3-large w3-animate-bottom">An email has been sent to your email address to reset your password.</span>
                </div>
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



document.addEventListener('DOMContentLoaded', () => {
    new ForgotPassword('forgotPwdForm');
});