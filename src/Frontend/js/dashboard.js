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
            <div class="w3-display-center w3-text-black w3-padding-bottom-64 w3-margin-top" style="padding:48px">
                <div class="w3-margin-top w3-padding-top-64">
                <span class="w3-jumbo w3-hide-small w3-animate-bottom">Upload your content</span><br>
                <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom">Upload your content</span><br>
                </div>
                <form id="uploadForm" class="w3-animate-bottom">
                    <input type="radio" name="upload_type" id="upload_file_radio" value="file" checked>
                    <span class="w3-medium "><b>PDF</b></span>
                    <input type="radio" name="upload_type" id="upload_text_radio" value="text">
                    <span class="w3-medium"><b>Text</b></span>
                    <br>
                    <div class="overlap-container">
                        <div id="file_upload_section">
                            <label id="upload_file_label" for="upload_file"><b>Select your PDF file</b></label>
                            <input type="file" class="w3-input w3-border" name="upload_file" id="file"><br>
                            <input type="radio" name="novel-category" id="novel-category-free-pdf" value="free" checked>
                            <span class="w3-medium"><b>Free</b></span>
                            <input type="radio" name="novel-category" id="novel-category-pro-pdf" value="pro">
                            <span class="w3-medium"><b>Pro</b></span>
                        </div>
                        <div id="text_upload_section" class="hidden">
                            <label id="title_label" for="title"><b>Insert your title</b></label>
                            <input class="w3-input w3-border" type="text" name="title" id="title"><br>
                            <label id="text_content_label" for="text_content">Insert your text</label>
                            <textarea class="w3-input w3-border" name="text_content" id="text_content" rows="10" cols="30"></textarea><br>
                            <input type="radio" name="novel-category" id="novel-category-free-txt" value="free" checked>
                            <span class="w3-medium "><b>Free</b></span>
                            <input type="radio" name="novel-category" id="novel-category-pro-txt" value="pro">
                            <span class="w3-medium"><b>Pro</b></span>
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
            <div class="w3-display-center w3-text-black w3-padding-bottom-64 w3-margin-top" style="padding:48px">
                <div class="w3-margin-top w3-padding-top-64">
                <span class="w3-jumbo w3-hide-small w3-animate-bottom">Manage Users</span><br>
                <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom">Manage Users</span><br>
                </div>
                <div class="w3-container w3-margin-top">
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
            <div class="w3-center w3-padding-64">
                <div class="w3-center w3-text-black w3-margin-top">
                    <span class="w3-jumbo w3-hide-small w3-animate-bottom">Catalogue</span><br>
                    <span class="w3-xxxlarge w3-hide-large w3-hide-medium w3-animate-bottom">Catalogue</span><br>
                </div>
            
                <div class="w3-left-align w3-margin-left w3-section w3-bottombar w3-padding-16 w3-margin-bottom">
                    <span class="w3-margin-right w3-hide-small"><b>Filter:</b></span> 
                    <button class="w3-button w3-white" id="latestBtn">Latest</button>
                    <button class="w3-button w3-black" id="pdfBtn"><i class="fa fa-file-pdf-o w3-margin-right"></i>PDF</button>
                    <button class="w3-button w3-black" id="txtBtn"><i class="fa fa-file-text-o w3-margin-right"></i>Text</button>
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
    
        document.getElementById('latestBtn').addEventListener('click', (event) => this.handleButtonClick(event, ''));
        document.getElementById('pdfBtn').addEventListener('click', (event) => this.handleButtonClick(event, 'pdf'));
        document.getElementById('txtBtn').addEventListener('click', (event) => this.handleButtonClick(event, 'txt'));
        this.fetchCatalogueContent();
    }
    handleButtonClick(event, fileType) {
        this.updateButtonClasses(event.target);
        this.fetchCatalogueContent(fileType);
    }
    
    updateButtonClasses(activeButton) {
        const buttons = document.querySelectorAll('.w3-section .w3-button');
        buttons.forEach(button => {
            button.classList.remove('w3-white');
            button.classList.add('w3-black');
        });
        activeButton.classList.remove('w3-black');
        activeButton.classList.add('w3-white');
    }
    
    async fetchCatalogueContent(fileType = '') {
        try {
            const queryParams = new URLSearchParams({
                page: this.currentPage,
                limit: this.novelsPerPage,
            });
    
            if (fileType) {
                queryParams.append('file_type', fileType);
            }
    
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
                imageStyle = 'width:25%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" onclick="readFile(${file.id})">Read</button>`;
            } else if (file.filetype === 'pdf') {
                imageSrc = './Frontend/imgs/pdf-file.png'; 
                imageStyle = 'width: 25%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" onclick="downloadFile(${file.id})">Download</button>`;
            } else {
                imageSrc = '/Frontend/imgs/nicola.png';  // Default image
                imageStyle = 'width:25%';  // Default style
            }
    
            const card = `
                <div class="w3-third w3-container w3-center w3-margin-bottom w3-hover-opacity w3-card">
                    <img src="${imageSrc}" alt="${file.filename}" style="${imageStyle}">
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
                imageStyle = 'width:25%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" onclick="readFile(${file.id})">Read</button>`;
            } else if (file.filetype === 'pdf') {
                imageSrc = './Frontend/imgs/pdf-file.png'; 
                imageStyle = 'width:25%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" onclick="downloadFile(${file.id})">Download</button>`;
            } else {
                imageSrc = '/Frontend/imgs/nicola.png';  // Default image
                imageStyle = 'width:25%';  // Default style
            }
    
            const card = `
                <div class="w3-third w3-container w3-center w3-margin-bottom w3-hover-opacity w3-card">
                    <img src="${imageSrc}" alt="${file.filename}" style="${imageStyle}">
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
            const result = await response.json();

            if (result.status === 'success') {
                showToast('success', result.message);
                //Reload actual role
                const selectElement = document.querySelector(`select[data-user-id="${userId}"]`);
                if (selectElement) {
                    selectElement.setAttribute('data-actual-role', newRole);
                }
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
        const sidebarLinks = document.querySelectorAll('.w3-bar-item');
        sidebarLinks.forEach(link => {
            link.classList.remove('w3-white');
        });
        activeLink.classList.add('w3-white');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const dashboard = new Dashboard('mainContent');

    const uploadFileLink = document.getElementById('uploadFileLink');
    if (uploadFileLink) {
        uploadFileLink.addEventListener('click', function(event) {
            event.preventDefault();
            dashboard.loadUploadFileContent();
        });
    }
    const uploadFileLink_mobile = document.getElementById('uploadFileLink-mobile');
    if(uploadFileLink_mobile){
        uploadFileLink_mobile.addEventListener('click', function(event) {
            event.preventDefault();
            dashboard.loadUploadFileContent();
        });
    }

    const adminPageLink = document.getElementById('adminPageLink');
    if (adminPageLink) {
        adminPageLink.addEventListener('click', function(event) {
            event.preventDefault();
            dashboard.loadAdminPageContent();
        });
    }

    const adminPageLink_mobile = document.getElementById('adminPageLink-mobile');
    if(adminPageLink_mobile){
        adminPageLink_mobile.addEventListener('click', function(event) {
            event.preventDefault();
            dashboard.loadAdminPageContent();
        });
    }
    const catalogueLink = document.getElementById('catalogueLink');
    if (catalogueLink) {
        catalogueLink.addEventListener('click', function(event) {
            event.preventDefault();
            dashboard.loadCatalogueContent();
        });
    }
    const catalogueLink_mobile = document.getElementById('catalogueLink-mobile');
    if(catalogueLink_mobile){
        catalogueLink_mobile.addEventListener('click', function(event) {
            event.preventDefault();
            dashboard.loadCatalogueContent();
        });
    }
    dashboard.loadCatalogueContent();
});
