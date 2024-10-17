class Dashboard {
    constructor(mainContentId) {
        this.mainContent = document.getElementById(mainContentId);
        this.currentPage = 1;
        this.usersPerPage = 10;
        this.users = [];
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
                    <table class="w3-table w3-bordered w3-centered w3-animate-bottom w3-hoverable">
                        <thead>
                            <tr class="w3-black">
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
                    <div class="w3-center w3-padding-16">
                        <button class="w3-button w3-black" id="prevPageBtn">Previous</button>
                        <span id="pageInfo"></span>
                        <button class="w3-button w3-black" id="nextPageBtn">Next</button>
                    </div>
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
                this.users = result.data;
                this.renderUserList();
            } else {
                console.error('Cannot retrieve users:', result.message);
            }
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }

        this.ensureToastScript();
        this.updateLinkClasses(document.getElementById('adminPageLink'));

        // Add event listeners for pagination buttons
        document.getElementById('prevPageBtn').addEventListener('click', () => this.prevPage());
        document.getElementById('nextPageBtn').addEventListener('click', () => this.nextPage());

        // Add event listener for role change using event delegation
        document.getElementById('userTableBody').addEventListener('change', (event) => {
            if (event.target && event.target.name === 'role') {
                const userId = event.target.getAttribute('data-user-id');
                const newRole = event.target.value;
                const actualRole = event.target.getAttribute('data-actual-role');
                this.changeUserRole(userId, newRole, actualRole);
            }
        });
    }

    renderUserList() {
        const start = (this.currentPage - 1) * this.usersPerPage;
        const end = start + this.usersPerPage;
        const paginatedUsers = this.users.slice(start, end);

        let userHtml = '';

        paginatedUsers.forEach(user => {
            userHtml += `
                <tr>
                    <td class="w3-bold">${user.id}</td>
                    <td class="w3-bold">${user.username}</td>
                    <td class="w3-bold">${user.email}</td>
                    <td>
                        <select class="w3-select w3-border scrollable-menu" name="role" data-user-id="${user.id}" data-actual-role="${user.role}">
                            <option value="non-premium" ${user.role === 'non-premium' ? 'selected' : ''}>Non-Premium</option>
                            <option value="premium" ${user.role === 'premium' ? 'selected' : ''}>Premium</option>
                            <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                        </select>
                    </td>
                </tr>
            `;
        });

        document.getElementById('userTableBody').innerHTML = userHtml;
        this.updatePageInfo();
    }

    async changeUserRole(userId, newRole, actualRole) {
        try {
            const formData = new FormData();
            formData.append('id', userId);
            formData.append('new_role', newRole);
            formData.append('actual_role', actualRole);
            const response = await fetch('/api/change_role', {
                method: 'POST',
                body: formData
            });
            console.log(userId, newRole, actualRole);
            console.log(response.body);

            const result = await response.json();

            if (result.status === 'success') {
                showToast('success', result.message);
            } else {
                showToast('error', result.message);
                console.error('Failed to change role:', result.message);
            }
        } catch (error) {
            showToast('error', 'An error occurred while changing the role');
            console.error('There was a problem with the fetch operation:', error);
        }
    }

    updatePageInfo() {
        const pageInfo = document.getElementById('pageInfo');
        const totalPages = Math.ceil(this.users.length / this.usersPerPage);
        pageInfo.textContent = `Page ${this.currentPage} of ${totalPages}`;
    }

    prevPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.renderUserList();
        }
    }

    nextPage() {
        const totalPages = Math.ceil(this.users.length / this.usersPerPage);
        if (this.currentPage < totalPages) {
            this.currentPage++;
            this.renderUserList();
        }
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
