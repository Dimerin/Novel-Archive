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
        // Clear existing content
        while (homeDiv.firstChild) {
            homeDiv.removeChild(homeDiv.firstChild);
        }

        // Create and append new elements
        const div = document.createElement('div');
        div.className = 'w3-display-left w3-text-white';
        div.style.padding = '48px';

        const span1 = document.createElement('span');
        span1.className = 'w3-jumbo w3-hide-small w3-animate-bottom';
        span1.textContent = 'The account has been created successfully.';
        div.appendChild(span1);
        div.appendChild(document.createElement('br'));

        const span2 = document.createElement('span');
        span2.className = 'w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom';
        span2.textContent = 'The account has been created successfully.';
        div.appendChild(span2);
        div.appendChild(document.createElement('br'));

        const span3 = document.createElement('span');
        span3.className = 'w3-large w3-animate-bottom';
        span3.textContent = 'An email has been sent, confirm your account to access our services.';
        div.appendChild(span3);

        homeDiv.appendChild(div);
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