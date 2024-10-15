
async function logoutUser() {
    try {
        const response = await fetch('/api/logout', {
            method: 'GET'
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        if (data.status === 'success') {
            //alert("Logout successful");
            // Redirect to the login page after successful logout
            window.location.href = '/logout';
        } else {
            console.error('Logout failed:', data.message);
        }
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
    }
}