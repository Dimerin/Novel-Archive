class Dashboard {
    constructor(mainContentId) {
        this.mainContent = document.getElementById(mainContentId);
        // Capture the original content at initialization
        this.originalContent = this.mainContent ? this.mainContent.innerHTML : '';
    }

    loadUploadFileContent() {
        this.mainContent.innerHTML = `
            <div class="toast-container">
                <ul class="notifications"></ul>
            </div> 
            <div class="w3-display-center w3-text-black" style="padding:48px">
                <span class="w3-jumbo w3-hide-small w3-animate-bottom">Upload your content</span><br>
                <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom">Upload your content</span><br>
                <form id="uploadForm" class="w3-animate-bottom">
                    <input type="radio" name="upload_type" id="upload_file_radio" value="file" checked>
                    <span class="w3-medium ">PDF</span>
                    <input type="radio" name="upload_type" id="upload_text_radio" value="text">
                    <span class="w3-medium">Text</span>
                    <br>
                    <div class="overlap-container">
                        <div id="file_upload_section">
                            <label id="upload_file_label" for="upload_file">Select your PDF file</label>
                            <input type="file" class="w3-input w3-border" name="upload_file" id="file"><br>
                        </div>
                        <div id="text_upload_section" class="hidden">
                            <label id="text_content_label" for="text_content">Insert your text</label>
                            <textarea class="w3-input w3-border" name="text_content" id="text_content" rows="10" cols="30"></textarea><br>
                        </div>
                    </div>
                    <button class="w3-button w3-black w3-animate-bottom" type="submit"><i class="fa fa-upload"></i> UPLOAD</button>
                </form>
            </div>
        `;
        init(); // Reinitialize event listeners

        // Ensure toast.js is loaded
        this.ensureToastScript();
        this.updateLinkClasses(document.getElementById('uploadFileLink'));
    }

    async loadAdminPageContent() {
        this.mainContent.innerHTML = `
            <div class="toast-container">
                <ul class="notifications"></ul>
            </div> 
            <div class="w3-display-center w3-text-black" style="padding:48px">
                <span class="w3-jumbo w3-hide-small w3-animate-bottom">Manage Users</span><br>
                <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom">Manage Users</span><br>
                <div class="w3-container">
                    <table class="w3-table w3-bordered  w3-centered w3-animate-bottom w3-hoverable">
                        <thead>
                            <tr class="w3-dark-grey">
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <!-- User rows will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        `;

        // Fetch user data
        try {
            const response = await fetch('/api/show_users', {
                method: 'GET'
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const result = await response.json();
            
            // Assuming the users data is inside `result.data`
            if (result.status === 'success' && result.data) {
                // Call renderUserList with the data array
                this.renderUserList(result.data);
            } else {
                console.error('Cannot retrieve users:', result.message);
            }
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }

        this.ensureToastScript();
        this.updateLinkClasses(document.getElementById('adminPageLink'));
    }

    renderUserList(users) {
        // Start by setting up the outer structure with a table
        let userHtml = '';

        // Loop through the users array to create a row for each user
        users.slice(0, 10).forEach(user => {
            userHtml += `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>
                        <select class="w3-select w3-border scrollable-menu" name="role">
                            <option value="non-premium" ${user.role === 'non-premium' ? 'selected' : ''}>Non-Premium</option>
                            <option value="premium" ${user.role === 'premium' ? 'selected' : ''}>Premium</option>
                            <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                        </select>
                    </td>
                </tr>
            `;
        });

        // Update the user table body with the generated HTML
        document.getElementById('userTableBody').innerHTML = userHtml;
    }

    resetHomePage() {
        // Restore the original content captured during initialization
        this.mainContent.innerHTML = this.originalContent;
        initNotifications(); // Reinitialize notifications
        this.updateLinkClasses(document.getElementById('catalogueLink'));
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

    updateLinkClasses(activeLink) {
        const sidebarLinks = document.querySelectorAll('.w3-sidebar .w3-bar-item');
        sidebarLinks.forEach(link => {
            link.classList.remove('w3-white');
        });
        activeLink.classList.add('w3-white');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const dashboard = new Dashboard('mainContent');

    document.getElementById('uploadFileLink').addEventListener('click', function(event) {
        event.preventDefault();
        dashboard.loadUploadFileContent();
    });

    const adminPageLink = document.getElementById('adminPageLink');
    if (adminPageLink) {
        adminPageLink.addEventListener('click', function(event) {
            event.preventDefault();
            dashboard.loadAdminPageContent();
        });
    }

    document.getElementById('catalogueLink').addEventListener('click', function(event) {
        event.preventDefault();
        dashboard.resetHomePage();
    });
});
