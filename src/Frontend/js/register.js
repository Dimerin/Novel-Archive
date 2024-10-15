window.addEventListener('load', init);

function init() {
    const registerForm = document.getElementById('registerForm');
    registerForm.addEventListener('submit', handleRegister);
}

async function handleRegister(event) {
    event.preventDefault();

    const registerForm = event.target;
    const formData = new FormData(registerForm);
    //const data = {
    //    username: formData.get('username'),
    //    password: formData.get('password'),
    //    email: formData.get('email')
    //};

    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            //headers: {
            //    'Content-Type': 'application/json'
            //},
            //body: JSON.stringify(data)
            body: formData
        });
        
        if (response.ok) {
            const result = await response.json();
            //console.log('Registration successful:', result);
            alert(result.message);
            // Handle successful registration (e.g., redirect to login page)
        } else {
            const error = await response.json();
            //console.error('Registration failed:', error);
            alert(error.message);
            // Handle registration error (e.g., display error message)
        }
    } catch (error) {
        //console.error('Error:', error);
        alert(error);
        // Handle network or other errors
    }
}