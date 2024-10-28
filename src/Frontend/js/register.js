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
                const result = await response.json();
                console.log(result);
                showToast('success', result.message);
                this.showConfirmationPage();
            } else {
                const error = await response.json();
                console.log(error);
                showToast('error', error.message);
            }
        } catch (error) {
            showToast('error', error.message);
        }
    }

    showConfirmationPage() {
        const homeDiv = document.getElementById('home');
        homeDiv.innerHTML = `
               <div class="w3-display-left w3-text-white" style="padding: 48px">
                    <span class="w3-jumbo w3-hide-small w3-animate-bottom">The account has been created successfully.</span><br>
                    <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom" >The account has been created successfully.</span><br>
                    <span class="w3-large w3-animate-bottom">An email has been sent, confirm your account to access our services.</span>
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

window.addEventListener('load', () => new Register());