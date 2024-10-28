class Register {
    constructor() {
        this.init();
    }

    init() {
        const registerForm = document.getElementById('registerForm');
        registerForm.addEventListener('submit', (event) => this.handleRegister(event));
        this.ensureToastScript();
    }

    async handleRegister(event) {
        event.preventDefault();

        const registerForm = event.target;
        const formData = new FormData(registerForm);

        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const result = await response.text();
                showToast('success', result.message);
                this.showOtpForm();
            } else {
                const error = await response.text();
                showToast('error', error.message);
            }
        } catch (error) {
            showToast('error', error.message);
        }
    }

    showOtpForm() {
        const homeDiv = document.getElementById('home');
        homeDiv.innerHTML = `
               <div class="w3-display-left w3-text-white" style="padding: 48px">
                    <span class="w3-jumbo w3-hide-small w3-animate-bottom">Insert OTP code</span><br>
                    <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom" >Insert OTP code</span><br>
                    <span class="w3-large w3-animate-bottom">An email has been sent, check your mailbox.</span>
                    <form id="otpRegistrationForm" class="w3-animate-bottom">
                        <input type="text" class="w3-input w3-border" name="otpRegistration" placeholder="Enter OTP Code" required><br>
                        <button class="w3-button w3-black w3-animate-bottom" type="submit"><i class="fa fa-user-plus"></i> Submit</button>
                    </form>
                </div>
        `;
        document.getElementById('otpRegistrationForm').addEventListener('submit', (event) => this.handleOtpSubmit(event));
    }

    async handleOtpSubmit(event) {
        event.preventDefault();

        const otpCode = document.getElementById('otpCode').value;
        const formData = new FormData();
        formData.append('otp', otpCode);

        try {
            const response = await fetch('/api/verify_user', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                showToast('success', result.message);
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            } else {
                const error = await response.json();
                showToast('error', error.message);
            }
        } catch (error) {
            showToast('error', error.message);
        }
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

window.addEventListener('load', () => new Register());