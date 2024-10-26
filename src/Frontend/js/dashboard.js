class Dashboard {
    constructor(mainContentId) {
        this.mainContent = document.getElementById(mainContentId);
        this.originalClasses = this.mainContent.className;
        this.userPage = 1;
        this.usersPerPage = 10;
        this.cataloguePage = 1;
        this.novelsPerPage = 6;
        this.novels = [];
        this.users = [];
        this.isLastUserPage = false;
        this.isLastCataloguePage = false;
    }

    loadUploadFileContent() {
        this.toggleBackgroundImage(false);
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
                    <input type="radio" name="upload_type" id="upload_file_radio" value="file" class="w3-radio" checked>
                    <span class="w3-medium "><b>PDF</b></span>
                    <input type="radio" name="upload_type" id="upload_text_radio" class="w3-radio" value="text">
                    <span class="w3-medium"><b>Text</b></span>
                    <br>
                    <div class="overlap-container">
                        <div id="file_upload_section">
                            <label id="upload_file_label" for="upload_file"><b>Select your PDF file</b></label>
                            <input type="file" class="w3-input w3-border" name="upload_file" id="file"><br>
                           
                        </div>
                        <div id="text_upload_section" class="hidden">
                            <label id="title_label" for="title"><b>Insert your title</b></label>
                            <input class="w3-input w3-border" type="text" name="title" id="title"><br>
                            <label id="text_content_label" for="text_content"><b>Insert your text</b></label>
                            <textarea class="w3-input w3-border" name="text_content" id="text_content" rows="10" cols="30"></textarea><br>
                        </div>
                        <input type="radio" name="novel-category" id="novel-category-free-pdf" class="w3-radio" value="free" checked>
                        <span class="w3-medium"><b>Free</b></span>
                        <input type="radio" name="novel-category" id="novel-category-pro-pdf" class="w3-radio" value="pro">
                        <span class="w3-medium"><b>Pro</b></span>
                    </div>
                    <button class="w3-button w3-black w3-animate-bottom" type="submit"><i class="fa fa-upload"></i> UPLOAD</button>
                </form>
            </div>
        `;
        this.ensureUploadScript();
        init(); // Reinitialize event listeners
        this.ensureToastScript();
        this.updateLinkClasses(document.getElementById('uploadFileLink'));
    }

    async loadAdminPageContent() {
        this.toggleBackgroundImage(false);
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
                    <table class="w3-table w3-card-2 w3-border-black w3-round-large w3-centered w3-animate-bottom ">
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
                        <button class="w3-button w3-black" id="prevUserPageBtn">Previous</button>
                        <span id="usersPageInfo"></span>
                        <button class="w3-button w3-black" id="nextUserPageBtn">Next</button>
                    </div>
                </div>
            </div>
        `;

        await this.fetchUsers(this.userPage);

        this.ensureToastScript();
        this.updateLinkClasses(document.getElementById('adminPageLink'));

        // Add event listeners for pagination buttons
        document.getElementById('prevUserPageBtn').addEventListener('click', () => this.changePage('users', 'prev'));
        document.getElementById('nextUserPageBtn').addEventListener('click', () => this.changePage('users', 'next'));


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
        let userHtml = '';

        this.users.forEach(user => {
            userHtml += `
                <tr>
                    <td class="w3-bold">${user.id}</td>
                    <td class="w3-bold">${user.username}</td>
                    <td class="w3-bold">${user.email}</td>
                    <td>
                        <select class="w3-select w3-border-black w3-round-xxlarge scrollable-menu" name="role" data-user-id="${user.id}" data-actual-role="${user.role}">
                            <option value="free" ${user.role === 'free' ? 'selected' : ''}>Free</option>
                            <option value="pro" ${user.role === 'pro' ? 'selected' : ''}>Pro</option>
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
        this.toggleBackgroundImage(false);
        this.mainContent.innerHTML = `
            <div class="toast-container">
                <ul class="notifications"></ul>
            </div> 
            <div class="w3-center w3-padding-64">
                <div class="w3-center w3-text-black w3-margin-top">
                    <span class="w3-jumbo w3-hide-small w3-animate-bottom">Catalogue</span><br>
                    <span class="w3-xxxlarge w3-hide-large w3-hide-medium w3-animate-bottom">Catalogue</span><br>
                </div>
            
                <div class="w3-left-align w3-margin-left w3-section w3-bottombar w3-padding-16 w3-margin-bottom w3-animate-bottom">
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
                <button class="w3-button w3-black" id="prevCataloguePageBtn">Previous</button>
                <span id="cataloguePageInfo"></span>
                <button class="w3-button w3-black" id="nextCataloguePageBtn">Next</button>
            </div>
        `;
    
        document.getElementById('latestBtn').addEventListener('click', (event) => this.handleButtonClick(event, ''));
        document.getElementById('pdfBtn').addEventListener('click', (event) => this.handleButtonClick(event, 'pdf'));
        document.getElementById('txtBtn').addEventListener('click', (event) => this.handleButtonClick(event, 'txt'));
        document.getElementById('prevCataloguePageBtn').addEventListener('click', () => this.changePage('catalogue', 'prev'));
        document.getElementById('nextCataloguePageBtn').addEventListener('click', () => this.changePage('catalogue', 'next'));
        await this.fetchCatalogueContent(this.cataloguePage);

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
    
    async fetchCatalogueContent(page,fileType = '') {
        try {
            const queryParams = new URLSearchParams({
                page: page,
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
                this.isLastCataloguePage = result['last-page'];
                this.cataloguePage = page;
                this.renderCards(this.novels);
                this.updatePageInfo();
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

    async fetchUsers(page) {
        try {
            const queryParams = new URLSearchParams({
                page: page,
                limit: this.usersPerPage,
            });

            const response = await fetch(`/api/show_users?${queryParams.toString()}`, {
                method: 'GET'
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const result = await response.json();
            if (result.status === 'success' && Array.isArray(result.data)) {
                this.users = result.data;
                this.isLastUserPage = result['last-page'];
                this.userPage = page;
                this.renderUserList();
                this.updatePageInfo();
            } else {
                console.error('Cannot retrieve users:', result.message);
            }
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
    }

    async changePage(type, direction) {
        if (type === 'users') {
            if (direction === 'next' && !this.isLastUserPage) {
                this.userPage++;
            } else if (direction === 'prev' && this.userPage > 1) {
                this.userPage--;
            }
            await this.fetchUsers(this.userPage);
        } else if (type === 'catalogue') {
            if (direction === 'next' && !this.isLastCataloguePage) {
                this.cataloguePage++;
            } else if (direction === 'prev' && this.cataloguePage > 1) {
                this.cataloguePage--;
            }
            await this.fetchCatalogueContent(this.cataloguePage);
        }
        this.updatePageInfo();
    }



    renderCards(files) {
        const container = this.mainContent.querySelector('.w3-row-padding.bg');

    if (!container) {
        console.warn('Card container not found. Skipping card rendering.');
        return;
    }

    container.innerHTML = '';  // Clear existing content

    // Create rows dynamically and add cards to them
    let row;
    files.forEach((file, index) => {
        if (index % 3 === 0) {
            row = document.createElement('div');
            row.className = 'w3-row-padding';
            container.appendChild(row);
        }

        let imageSrc;
        let imageStyle;
        let buttons = '';
        let role;
        let roleColor;
        switch (file.visibility) {
            case 0:
                role = "Free";
                roleColor = "lightgreen";
                break;
            case 1:
                role = "Pro";
                roleColor = "yellow";
                break;
            default:
                role = "Undefined";
                roleColor = "black";
                break;
        }
            if (file.filetype === 'txt') {
                imageSrc = './Frontend/imgs/text-file.png'; 
                imageStyle = 'width:25%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" data-file-id="${file.id}" data-action="read">Read</button>`;
            } else if (file.filetype === 'pdf') {
                imageSrc = './Frontend/imgs/pdf-file.png'; 
                imageStyle = 'width: 25%';
                buttons = `<button class="w3-button w3-black w3-margin-bottom" data-file-id="${file.id}" data-action="download">Download</button>`;
            } else {
                imageSrc = '/Frontend/imgs/nicola.png';  // Default image
                imageStyle = 'width:25%';  // Default style
            }
    
            const card = `
                <div class="w3-third w3-container w3-center w3-margin-bottom w3-hover-shadow w3-card w3-border w3-round-xlarge">
                    <img src="${imageSrc}" alt="${file.filename}" style="${imageStyle}">
                    <div class="w3-container">
                        <p><b>${file.title}</b></p>
                        <p>Author: ${file.username}</p>
                        <p style="color: ${roleColor};"><b>${role}</b></p>

                    </div>
                    ${buttons}
                </div>
            `;
            row.insertAdjacentHTML('beforeend', card);
        });
    
       
        container.querySelectorAll('button[data-action="read"]').forEach(button => {
            button.addEventListener('click', (event) => this.readFile(event.target.dataset.fileId));
        });
        container.querySelectorAll('button[data-action="download"]').forEach(button => {
            button.addEventListener('click', (event) => this.downloadFile(event.target.dataset.fileId));
        });
    }
    async downloadFile(fileId) {
        try {
                if (!fileId) {
                    throw new Error('File ID is required');
                }
                const queryParams = new URLSearchParams({
                    file_id: fileId
                });
            
                const response = await fetch(`/api/download_file?${queryParams.toString()}`, {
                    method: 'GET'
                });
        
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
        
                const result = await response.json();
                if (result.status === 'success' && result.filetype === 'pdf') {
                    showToast('success', "Download started");
                    const link = document.createElement('a');
                    link.href = `data:application/pdf;base64,${result.filedata}`;
                    link.download = result.filename;
                    link.click();
                }
                else {
                    showToast('error', result.message);
                }
            } catch (error) {
                showToast('error', 'An error occurred while downloading the file');
            }
            this.updateLinkClasses(document.getElementById('catalogueLink'));
            this.ensureToastScript();
        }

    async readFile(fileId) {
        try {
            if (!fileId) {
                throw new Error('File ID is required');
            }
            const queryParams = new URLSearchParams({
                file_id: fileId
            });

            const response = await fetch(`/api/download_file?${queryParams.toString()}`, {
                method: 'GET'
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
    
            const result = await response.json();
            if (result.status === 'success' && result.filetype === 'txt') {
                this.loadNovelContent(result.filedata, result.title, result.author);
            }
            else {
                showToast('error', result.message);
            }
        } catch (error) {
            showToast('error', 'An error occurred while downloading the file');
        }
        this.updateLinkClasses(document.getElementById('catalogueLink'));
        this.ensureToastScript();
        const sidebarLinks = document.querySelectorAll('.w3-bar-item');
        sidebarLinks.forEach(link => {
            link.classList.remove('w3-white');
        });
    }

    

    loadNovelContent(fileData, title, author) {
        const modalHTML = `
            <div id="novelModal" class="w3-modal w3-top" style="display: block;">
                <div class="w3-modal-content w3-animate-opacity" style="position: relative; min-width: 80%; z-index:1000;">
                    <span class="w3-button w3-black w3-display-topleft w3-border w3-round-xxlarge" id="closeModal">&times;</span>
                    <div id="wrapper">
                        <div id="container">
                            <section class="open-book">
                                <header>
                                    <h6>Author: ${author}</h6>
                                </header>
                                <article>
                                    <h2 class="chapter-title">${title}</h2>
                                    <p>${fileData}</p>
                                </article>
                                <footer>
                                    <ol id="page-numbers">
                                        <li>1</li>
                                        <li>2</li>
                                    </ol>
                                </footer>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        `;
    
        // Append the modal to the body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Add event listener to the close button
        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('novelModal').remove();
        });
    
        this.ensureToastScript();
        this.updateLinkClasses(document.getElementById('catalogueLink'));
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
    
        if (usersPageInfo) {
            usersPageInfo.textContent = `Page ${this.userPage}`;
        }
    
        if (cataloguePageInfo) {
            cataloguePageInfo.textContent = `Page ${this.cataloguePage}`;
        }
    
        // Disable buttons and hide span if not enough novels for pagination
        const prevCataloguePageBtn = document.getElementById('prevCataloguePageBtn');
        const nextCataloguePageBtn = document.getElementById('nextCataloguePageBtn');
    
        if (prevCataloguePageBtn && nextCataloguePageBtn) {
            prevCataloguePageBtn.disabled = this.cataloguePage === 1;
            nextCataloguePageBtn.disabled = this.isLastCataloguePage;
        }
    
        // Disable buttons and hide span if not enough users for pagination
        const prevUserPageBtn = document.getElementById('prevUserPageBtn');
        const nextUserPageBtn = document.getElementById('nextUserPageBtn');
    
        if (prevUserPageBtn && nextUserPageBtn) {
            prevUserPageBtn.disabled = this.userPage === 1;
            nextUserPageBtn.disabled = this.isLastUserPage;
        }
    }

    toggleBackgroundImage(add) {
        if (add) {
            this.mainContent.classList.remove('bgimg-1');
            this.mainContent.classList.add('bgnovel');
        } else {
            this.mainContent.className = this.originalClasses;
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
    ensureUploadScript() {
        // Check if upload_file.js is already loaded
        if (!document.querySelector('script[src="./Frontend/js/upload_file.js"]')) {
            var script = document.createElement('script');
            script.src = './Frontend/js/upload_file.js';
            document.head.appendChild(script);
        } else {
            // Reinitialize event listeners if upload_file.js is already loaded
            init();
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
 
    const HomeLink = document.getElementById('HomeLink');

        HomeLink.href = '/dashboard';
        HomeLink.addEventListener('click', function(event) {
            event.preventDefault();
            // Load the catalogue content
            dashboard.loadCatalogueContent();
        });
    dashboard.loadCatalogueContent();
});
