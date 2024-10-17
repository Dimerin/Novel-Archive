class Dashboard {
    constructor(mainContentId) {
        this.mainContent = document.getElementById(mainContentId);
        this.userPage = 1;
        this.usersPerPage = 10;
        this.cataloguePage = 1;
        this.novelsPerPage = 6;
        this.novels = [];
        this.users = [];
        // Capture the original content at initialization
        //this.originalContent = this.mainContent ? this.mainContent.innerHTML : '';
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
                            <label id="title_label" for="title">Insert your title</label>
                            <input class="w3-input w3-border" type="text" name="title" id="title"><br>
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
                        <span id="usersPageInfo"></span>
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
        const start = (this.userPage - 1) * this.usersPerPage;
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
    async loadCatalogueContent() {
        this.mainContent.innerHTML = `
            <div class="toast-container">
                <ul class="notifications"></ul>
            </div> 
             <div class="w3-display-center w3-text-black" style="padding:26px">
                <span class="w3-xxxlarge w3-hide-small w3-animate-bottom">Catalogue</span><br>
                <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom">Catalogue</span><br>
                <div class="w3-section w3-bottombar w3-padding-16 w3-margin-bottom">
                    <span class="w3-margin-right w3-hide-small">Filter:</span> 
                    <button class="w3-button w3-black">Latest</button>
                    <button class="w3-button w3-white"><i class="fa fa-file-pdf-o w3-margin-right"></i>PDF</button>
                    <button class="w3-button w3-white"><i class="fa fa-file-text-o w3-margin-right"></i>Text</button>
                </div>
            </div>
            <div class="w3-row-padding w3-animate-bottom bg">
                <!-- First set of cards will be inserted here -->
            </div>
            <div class="w3-row-padding w3-animate-bottom second-container">
                <!-- Second set of cards will be inserted here -->
            </div>
            <div class="w3-center w3-padding-16 w3-animate-bottom">
                <button class="w3-button w3-black" id="prevPageBtn">Previous</button>
                <span id="cataloguePageInfo"></span>
                <button class="w3-button w3-black" id="nextPageBtn">Next</button>
            </div>
        `;
    
        try {
            const queryParams = new URLSearchParams({
                page: this.currentPage,
                limit: this.novelsPerPage,
            });
    
            const response = await fetch(`/api/show_files?${queryParams.toString()}`, {
                method: 'GET'
            });
    
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
    
            const result = await response.json();
            if (result.status === 'success' && Array.isArray(result.files)) {
                this.novels = result.files;
                this.renderCards(this.novels);
            } else {
                console.error('Cannot retrieve novels:', result.message);
            }
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
        this.updatePageInfo();
        this.updateLinkClasses(document.getElementById('catalogueLink'));
        this.ensureToastScript();
    }
    


    renderCards(files) {
        const container = this.mainContent.querySelector('.w3-row-padding.bg');
        const secondContainer = this.mainContent.querySelector('.second-container');
    
        if (!container || !secondContainer) {
            console.warn('One or both card containers not found. Skipping card rendering.');
            return;
        }
    
        container.innerHTML = '';  // Clear existing content for the first container
        secondContainer.innerHTML = '';  // Clear existing content for the second container
    
        // Determine how to split the files between the two containers
        const midpoint = Math.ceil(files.length / 2);
        const firstHalf = files.slice(0, midpoint);
        const secondHalf = files.slice(midpoint);
    
        // Render the first half of the files in the first container
        firstHalf.forEach(file => {
            let imageSrc;
            let imageStyle;
            let buttons = '';
    
            if (file.filetype === 'txt') {
                imageSrc = './Frontend/imgs/text-file.png'; 
                imageStyle = 'width:20%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" onclick="readFile(${file.id})">Read</button>`;
            } else if (file.filetype === 'pdf') {
                imageSrc = './Frontend/imgs/pdf-file.png'; 
                imageStyle = 'width:30%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" onclick="downloadFile(${file.id})">Download</button>`;
            } else {
                imageSrc = '/Frontend/imgs/nicola.png';  // Default image
                imageStyle = 'width:20%';  // Default style
            }
    
            const card = `
                <div class="w3-third w3-container w3-center w3-margin-bottom">
                    <img src="${imageSrc}" alt="${file.filename}" style="${imageStyle}" class="w3-hover-opacity">
                    <div class="w3-container">
                        <p><b>${file.filename}</b></p>
                        <p>File Type: ${file.filetype}</p>
                    </div>
                    ${buttons}
                </div>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });
    
        // Render the second half of the files in the second container
        secondHalf.forEach(file => {
            let imageSrc;
            let imageStyle;
            let buttons = '';
    
            if (file.filetype === 'txt') {
                imageSrc = './Frontend/imgs/text-file.png'; 
                imageStyle = 'width:20%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" onclick="readFile(${file.id})">Read</button>`;
            } else if (file.filetype === 'pdf') {
                imageSrc = './Frontend/imgs/pdf-file.png'; 
                imageStyle = 'width:30%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" onclick="downloadFile(${file.id})">Download</button>`;
            } else {
                imageSrc = '/Frontend/imgs/nicola.png';  // Default image
                imageStyle = 'width:20%';  // Default style
            }
    
            const card = `
                <div class="w3-third w3-container w3-center w3-margin-bottom">
                    <img src="${imageSrc}" alt="${file.filename}" style="${imageStyle}" class="w3-hover-opacity">
                    <div class="w3-container">
                        <p><b>${file.filename}</b></p>
                        <p>File Type: ${file.filetype}</p>
                    </div>
                    ${buttons}
                </div>
            `;
            secondContainer.insertAdjacentHTML('beforeend', card);
        });
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
        const cataloguePageInfo = document.getElementById('cataloguePageInfo');
        const usersPageInfo = document.getElementById('usersPageInfo');
        const totalUsersPages = Math.ceil(this.users.length / this.usersPerPage);
        const totalCataloguePages = Math.ceil(this.novels.length / this.novelsPerPage);

        if (usersPageInfo) {
            usersPageInfo.textContent = `Page ${this.userPage} of ${totalUsersPages}`;
        }

        if (cataloguePageInfo) {
            cataloguePageInfo.textContent = `Page ${this.cataloguePage} of ${totalCataloguePages}`;
        }
    }

    prevPage() {
        if (this.userPage > 1) {
            this.userPage--;
            this.renderUserList();
        }
    }

    nextPage() {
        const totalPages = Math.ceil(this.users.length / this.usersPerPage);
        if (this.userPage < totalPages) {
            this.userPage++;
            this.renderUserList();
        }
    }
    


    resetHomePage() {
        // Restore the original content captured during initialization
        //this.mainContent.innerHTML = this.originalContent;
        this.loadCatalogueContent();
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
    dashboard.loadCatalogueContent();
});
