window.addEventListener('load', init);

function init() {
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', handleLogin);
}

async function handleLogin(event){
    event.preventDefault();
    const loginForm = event.target;
    const formData = new FormData(loginForm);

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            const result = await response.json();
            //console.log('Login successful:', result);
            alert(result.message);
            // Redirecting to the home page
            window.location.href = '/dashboard';
        } else {
            const error = await response.json();
            //console.error('Login failed:', error);
            alert(error.message);
            // Handle login error (e.g., display error message)
        }
    } catch (error) {
        //console.error('Error:', error);
        alert(error);
        // Handle network or other errors
    }
}
