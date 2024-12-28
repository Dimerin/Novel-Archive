class Dashboard {
    constructor(mainContentId) {
        this.mainContent = document.getElementById(mainContentId);
        this.originalClasses = this.mainContent.className;
        this.cataloguePage = 1;
        this.novelsPerPage = 6;
        this.novels = [];
        this.isLastCataloguePage = false;
    }

    loadUploadFileContent() {
        // Clear the main content
        this.clearMainContent();
    
        // Create the toast container
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        const notificationsList = document.createElement('ul');
        notificationsList.className = 'notifications';
        toastContainer.appendChild(notificationsList);
    
        // Create the main container
        const mainContainer = document.createElement('div');
        mainContainer.className = 'w3-display-center w3-text-black w3-padding-bottom-64 w3-margin-top';
        mainContainer.style.padding = '48px';
    
        // Create the header
        const headerContainer = document.createElement('div');
        headerContainer.className = 'w3-margin-top w3-padding-top-64';
        const headerSpan1 = document.createElement('span');
        headerSpan1.className = 'w3-jumbo w3-hide-small w3-animate-bottom';
        headerSpan1.textContent = 'Upload your content';
        const headerSpan2 = document.createElement('span');
        headerSpan2.className = 'w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom';
        headerSpan2.textContent = 'Upload your content';
        headerContainer.appendChild(headerSpan1);
        headerContainer.appendChild(document.createElement('br'));
        headerContainer.appendChild(headerSpan2);
        headerContainer.appendChild(document.createElement('br'));
    
        // Create the form
        const form = document.createElement('form');
        form.id = 'uploadForm';
        form.className = 'w3-animate-bottom';
    
        // Create the radio buttons for upload type
        const uploadFileRadio = document.createElement('input');
        uploadFileRadio.type = 'radio';
        uploadFileRadio.name = 'upload_type';
        uploadFileRadio.id = 'upload_file_radio';
        uploadFileRadio.value = 'file';
        uploadFileRadio.className = 'w3-radio';
        uploadFileRadio.checked = true;
        const uploadFileLabel = document.createElement('span');
        uploadFileLabel.className = 'w3-medium';
        const boldFileText = document.createElement('b');
        boldFileText.textContent = 'PDF';
        uploadFileLabel.appendChild(boldFileText);
        uploadFileLabel.style.marginRight = "10px";
    
        const uploadTextRadio = document.createElement('input');
        uploadTextRadio.type = 'radio';
        uploadTextRadio.name = 'upload_type';
        uploadTextRadio.id = 'upload_text_radio';
        uploadTextRadio.className = 'w3-radio';
        uploadTextRadio.value = 'text';
        const uploadTextLabel = document.createElement('span');
        uploadTextLabel.className = 'w3-medium';
        const boldText = document.createElement('b');
        boldText.textContent = 'Text';
        uploadTextLabel.appendChild(boldText);
    
        form.appendChild(uploadFileRadio);
        form.appendChild(uploadFileLabel);
        form.appendChild(uploadTextRadio);
        form.appendChild(uploadTextLabel);
        form.appendChild(document.createElement('br'));
    
        // Create the overlap container
        const overlapContainer = document.createElement('div');
        overlapContainer.className = 'overlap-container';
    
        // Create the file upload section
        const fileUploadSection = document.createElement('div');
        fileUploadSection.id = 'file_upload_section';
        const fileUploadLabel = document.createElement('label');
        fileUploadLabel.id = 'upload_file_label';
        fileUploadLabel.htmlFor = 'upload_file';
        const boldFileUploadText = document.createElement('b');
        boldFileUploadText.textContent = 'Select your PDF file';
        fileUploadLabel.appendChild(boldFileUploadText);
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.className = 'w3-input w3-border';
        fileInput.name = 'upload_file';
        fileInput.id = 'file';
        fileUploadSection.appendChild(fileUploadLabel);
        fileUploadSection.appendChild(fileInput);
        fileUploadSection.appendChild(document.createElement('br'));
    
        // Create the text upload section
        const textUploadSection = document.createElement('div');
        textUploadSection.id = 'text_upload_section';
        textUploadSection.className = 'hidden';
        const titleLabel = document.createElement('label');
        titleLabel.id = 'title_label';
        titleLabel.htmlFor = 'title';
        const boldTitleText = document.createElement('b');
        boldTitleText.textContent = 'Insert your title';
        titleLabel.appendChild(boldTitleText);
        const titleInput = document.createElement('input');
        titleInput.className = 'w3-input w3-border';
        titleInput.type = 'text';
        titleInput.name = 'title';
        titleInput.id = 'title';
        const textContentLabel = document.createElement('label');
        textContentLabel.id = 'text_content_label';
        textContentLabel.htmlFor = 'text_content';
        const boldTextContent = document.createElement('b');
        boldTextContent.textContent = 'Insert your text';
        textContentLabel.appendChild(boldTextContent);
        const textContentTextarea = document.createElement('textarea');
        textContentTextarea.className = 'w3-input w3-border';
        textContentTextarea.name = 'text_content';
        textContentTextarea.id = 'text_content';
        textContentTextarea.rows = 10;
        textContentTextarea.cols = 30;
        textUploadSection.appendChild(titleLabel);
        textUploadSection.appendChild(titleInput);
        textUploadSection.appendChild(document.createElement('br'));
        textUploadSection.appendChild(textContentLabel);
        textUploadSection.appendChild(textContentTextarea);
        textUploadSection.appendChild(document.createElement('br'));
    
        overlapContainer.appendChild(fileUploadSection);
        overlapContainer.appendChild(textUploadSection);
    
        // Create the radio buttons for novel category
        const novelCategoryFreeRadio = document.createElement('input');
        novelCategoryFreeRadio.type = 'radio';
        novelCategoryFreeRadio.name = 'novel-category';
        novelCategoryFreeRadio.id = 'novel-category-free-pdf';
        novelCategoryFreeRadio.className = 'w3-radio';
        novelCategoryFreeRadio.value = 'free';
        novelCategoryFreeRadio.checked = true;
        const novelCategoryFreeLabel = document.createElement('span');
        novelCategoryFreeLabel.className = 'w3-medium';
        const boldFreeText = document.createElement('b');
        boldFreeText.textContent = 'Free';
        novelCategoryFreeLabel.appendChild(boldFreeText);
        novelCategoryFreeLabel.style.marginRight = "10px";
        
        const novelCategoryProRadio = document.createElement('input');
        novelCategoryProRadio.type = 'radio';
        novelCategoryProRadio.name = 'novel-category';
        novelCategoryProRadio.id = 'novel-category-pro-pdf';
        novelCategoryProRadio.className = 'w3-radio';
        novelCategoryProRadio.value = 'pro';
        
        const novelCategoryProLabel = document.createElement('span');
        novelCategoryProLabel.className = 'w3-medium';
        const boldProText = document.createElement('b');
        boldProText.textContent = 'Pro';
        novelCategoryProLabel.appendChild(boldProText);
    
        overlapContainer.appendChild(novelCategoryFreeRadio);
        overlapContainer.appendChild(novelCategoryFreeLabel);
        overlapContainer.appendChild(novelCategoryProRadio);
        overlapContainer.appendChild(novelCategoryProLabel);
    
        form.appendChild(overlapContainer);
    
        // Create the submit button
        const submitButton = document.createElement('button');
        submitButton.className = 'w3-button w3-black w3-animate-bottom';
        submitButton.type = 'submit';
        
        const uploadIcon = document.createElement('i');
        uploadIcon.className = 'fa fa-upload';
        submitButton.appendChild(uploadIcon);
        
        const uploadText = document.createTextNode(' UPLOAD');
        submitButton.appendChild(uploadText);
    
        form.appendChild(submitButton);
    
        mainContainer.appendChild(headerContainer);
        mainContainer.appendChild(form);
    
        this.mainContent.appendChild(toastContainer);
        this.mainContent.appendChild(mainContainer);
    
        this.ensureUploadScript();
        init(); // Reinitialize event listeners
        this.ensureToastScript();
        this.updateLinkClasses(document.getElementById('uploadFileLink'));
    }

    async loadCatalogueContent() {
        // Clear the main content
        this.clearMainContent();
    
        // Create the toast container
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        const notificationsList = document.createElement('ul');
        notificationsList.className = 'notifications';
        toastContainer.appendChild(notificationsList);
    
        // Create the main container
        const mainContainer = document.createElement('div');
        mainContainer.className = 'w3-center w3-padding-64';
    
        // Create the header
        const headerContainer = document.createElement('div');
        headerContainer.className = 'w3-center w3-text-black w3-margin-top';
        const headerSpan1 = document.createElement('span');
        headerSpan1.className = 'w3-jumbo w3-hide-small w3-animate-bottom';
        headerSpan1.textContent = 'Catalogue';
        const headerSpan2 = document.createElement('span');
        headerSpan2.className = 'w3-xxxlarge w3-hide-large w3-hide-medium w3-animate-bottom';
        headerSpan2.textContent = 'Catalogue';
        headerContainer.appendChild(headerSpan1);
        headerContainer.appendChild(document.createElement('br'));
        headerContainer.appendChild(headerSpan2);
        headerContainer.appendChild(document.createElement('br'));
    
        // Create the filter section
        const filterContainer = document.createElement('div');
        filterContainer.className = 'w3-left-align w3-margin-left w3-section w3-bottombar w3-padding-16 w3-margin-bottom w3-animate-bottom';
        const filterLabel = document.createElement('span');
        filterLabel.className = 'w3-margin-right w3-hide-small';
        const boldFilterText = document.createElement('b');
        boldFilterText.textContent = 'Filter:';
        filterLabel.appendChild(boldFilterText);
        
        const latestBtn = document.createElement('button');
        latestBtn.className = 'w3-button w3-white';
        latestBtn.id = 'latestBtn';
        latestBtn.textContent = 'Latest';
        
        const pdfBtn = document.createElement('button');
        pdfBtn.className = 'w3-button w3-black';
        pdfBtn.id = 'pdfBtn';
        const pdfIcon = document.createElement('i');
        pdfIcon.className = 'fa fa-file-pdf-o w3-margin-right';
        pdfBtn.appendChild(pdfIcon);
        const pdfText = document.createTextNode('PDF');
        pdfBtn.appendChild(pdfText);
        
        const txtBtn = document.createElement('button');
        txtBtn.className = 'w3-button w3-black';
        txtBtn.id = 'txtBtn';
        const txtIcon = document.createElement('i');
        txtIcon.className = 'fa fa-file-text-o w3-margin-right';
        txtBtn.appendChild(txtIcon);
        const txtText = document.createTextNode('Text');
        txtBtn.appendChild(txtText);
        filterContainer.appendChild(filterLabel);
        filterContainer.appendChild(latestBtn);
        filterContainer.appendChild(pdfBtn);
        filterContainer.appendChild(txtBtn);
    
        // Create the first set of cards container
        const firstCardsContainer = document.createElement('div');
        firstCardsContainer.className = 'w3-row-padding w3-animate-bottom bg';
    
        // Create the second set of cards container
        const secondCardsContainer = document.createElement('div');
        secondCardsContainer.className = 'w3-row-padding w3-animate-bottom second-container';
    
        // Create the pagination container
        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'w3-center w3-padding-16 w3-animate-bottom';
        const prevBtn = document.createElement('button');
        prevBtn.className = 'w3-button w3-black';
        prevBtn.id = 'prevCataloguePageBtn';
        prevBtn.textContent = 'Previous';
        prevBtn.style.marginRight = '16px';
        const pageInfo = document.createElement('span');
        pageInfo.id = 'cataloguePageInfo';
        pageInfo.style.marginRight = '16px';
        const nextBtn = document.createElement('button');
        nextBtn.className = 'w3-button w3-black';
        nextBtn.id = 'nextCataloguePageBtn';
        nextBtn.textContent = 'Next';
        paginationContainer.appendChild(prevBtn);
        paginationContainer.appendChild(pageInfo);
        paginationContainer.appendChild(nextBtn);
    
        // Append all elements to the main container
        mainContainer.appendChild(headerContainer);
        mainContainer.appendChild(filterContainer);
        mainContainer.appendChild(firstCardsContainer);
        mainContainer.appendChild(secondCardsContainer);
        mainContainer.appendChild(paginationContainer);
    
        // Append the toast container and main container to the main content
        this.mainContent.appendChild(toastContainer);
        this.mainContent.appendChild(mainContainer);
    
        // Add event listeners for buttons
        document.getElementById('latestBtn').addEventListener('click', (event) => this.handleButtonClick(event, ''));
        document.getElementById('pdfBtn').addEventListener('click', (event) => this.handleButtonClick(event, 'pdf'));
        document.getElementById('txtBtn').addEventListener('click', (event) => this.handleButtonClick(event, 'txt'));
        document.getElementById('prevCataloguePageBtn').addEventListener('click', () => this.changePage('catalogue', 'prev'));
        document.getElementById('nextCataloguePageBtn').addEventListener('click', () => this.changePage('catalogue', 'next'));
    
        // Fetch catalogue content
        await this.fetchCatalogueContent(this.cataloguePage);
    }

    handleButtonClick(event, fileType) {
        this.updateButtonClasses(event.target);
        this.cataloguePage = 1;
        this.fetchCatalogueContent(this.cataloguePage,fileType);
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

    async changePage(type, direction) {
        if (type === 'catalogue') {
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
    
        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }
    
        // Create rows dynamically and add cards to them
        let row;
        files.forEach((file, index) => {
            if (index % 3 === 0) {
                row = document.createElement('div');
                row.className = 'w3-row-padding';
                container.appendChild(row);
            }
    
            let imageSrc;
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
            } else if (file.filetype === 'pdf') {
                imageSrc = './Frontend/imgs/pdf-file.png'; 
            } else {
                imageSrc = '/Frontend/imgs/nicola.png'; 
            }
    
            const card = document.createElement('div');
            card.className = 'w3-third w3-container w3-center w3-margin-bottom w3-hover-shadow w3-card w3-border w3-round-xlarge';
    
            const img = document.createElement('img');
            img.src = imageSrc;
            img.alt = file.filename;
            img.style.width = '25%';
            card.appendChild(img);
    
            const cardContainer = document.createElement('div');
            cardContainer.className = 'w3-container';
    
            const title = document.createElement('p');
            const boldTitle = document.createElement('b');
            boldTitle.textContent = file.title;
            title.appendChild(boldTitle);
            cardContainer.appendChild(title);
    
            const author = document.createElement('p');
            author.textContent = `Author: ${file.username}`;
            cardContainer.appendChild(author);
    
            const roleElement = document.createElement('p');
            roleElement.style.color = roleColor;
            const boldRole = document.createElement('b');
            boldRole.textContent = role;
            roleElement.appendChild(boldRole);
            cardContainer.appendChild(roleElement);
    
            card.appendChild(cardContainer);
    
            if (file.filetype === 'txt') {
                const readButton = document.createElement('button');
                readButton.className = 'w3-button w3-black w3-margin-bottom';
                readButton.dataset.fileId = file.id;
                readButton.dataset.action = 'read';
                readButton.textContent = 'Read';
                card.appendChild(readButton);
            } else if (file.filetype === 'pdf') {
                const downloadButton = document.createElement('button');
                downloadButton.className = 'w3-button w3-black w3-margin-bottom';
                downloadButton.dataset.fileId = file.id;
                downloadButton.dataset.action = 'download';
                downloadButton.textContent = 'Download';
                card.appendChild(downloadButton);
            }
    
            row.appendChild(card);
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
                    link.download = result.title;
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
        // Create the modal container
        const modal = document.createElement('div');
        modal.id = 'novelModal';
        modal.className = 'w3-modal w3-top';
        modal.style.display = 'block';
    
        // Create the modal content
        const modalContent = document.createElement('div');
        modalContent.className = 'w3-modal-content w3-animate-opacity';
        modalContent.style.position = 'relative';
        modalContent.style.minWidth = '80%';
        modalContent.style.zIndex = '1000';
    
        // Create the close button
        const closeModal = document.createElement('span');
        closeModal.className = 'w3-button w3-black w3-display-middle w3-border w3-round-xxlarge';
        closeModal.id = 'closeModal';
        closeModal.textContent = 'X';
        closeModal.style.fontWeight = 'bold';
    
        // Create the wrapper
        const wrapper = document.createElement('div');
        wrapper.id = 'wrapper';
    
        // Create the container
        const container = document.createElement('div');
        container.id = 'container';
    
        // Create the open-book section
        const section = document.createElement('section');
        section.className = 'open-book';
    
        // Create the header
        const header = document.createElement('header');
        const authorElement = document.createElement('h6');
        authorElement.textContent = `Author: ${author}`;
        header.appendChild(authorElement);
    
        // Create the article
        const article = document.createElement('article');
        const chapterTitle = document.createElement('h2');
        chapterTitle.className = 'chapter-title';
        chapterTitle.textContent = title;
        const fileDataParagraph = document.createTextNode(fileData);
        article.appendChild(chapterTitle);
        article.appendChild(fileDataParagraph);
    
        // Create the footer
        const footer = document.createElement('footer');
        const pageNumbers = document.createElement('ol');
        pageNumbers.id = 'page-numbers';
        const pageNumber1 = document.createElement('li');
        pageNumber1.textContent = '1';
        const pageNumber2 = document.createElement('li');
        pageNumber2.textContent = '2';
        pageNumbers.appendChild(pageNumber1);
        pageNumbers.appendChild(pageNumber2);
        footer.appendChild(pageNumbers);
    
        // Append elements to the section
        section.appendChild(header);
        section.appendChild(article);
        section.appendChild(footer);
    
        // Append elements to the container
        container.appendChild(section);
    
        // Append elements to the wrapper
        wrapper.appendChild(container);
    
        // Append elements to the modal content
        modalContent.appendChild(closeModal);
        modalContent.appendChild(wrapper);
    
        // Append modal content to the modal
        modal.appendChild(modalContent);
    
        // Append the modal to the body
        document.body.appendChild(modal);
    
        // Add event listener to the close button
        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('novelModal').remove();
        });
    
        this.ensureToastScript();
        this.updateLinkClasses(document.getElementById('catalogueLink'));
    }

    updatePageInfo() {
        const cataloguePageInfo = document.getElementById('cataloguePageInfo');
    
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
    clearMainContent() {
        while (this.mainContent.firstChild) {
            this.mainContent.removeChild(this.mainContent.firstChild);
        }
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
        
    // Aggiungi event listener per il logout
    const logoutBtn = document.getElementById('log2');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(event) {
            event.preventDefault();
            logoutUser();
        });
    }
    const logoutBtn1 = document.getElementById('log1');
    if (logoutBtn1) {
        logoutBtn1.addEventListener('click', function(event) {
            event.preventDefault();
            logoutUser();
        });
    }

    // Aggiungi event listener per chiudere la sidebar
    const catalogueLinkMobile = document.getElementById('catalogueLink-mobile');
    if (catalogueLinkMobile) {
        catalogueLinkMobile.addEventListener('click', w3_close);
    }

    const uploadFileLinkMobile = document.getElementById('uploadFileLink-mobile');
    if (uploadFileLinkMobile) {
        uploadFileLinkMobile.addEventListener('click', w3_close);
    }

    const adminPageLinkMobile = document.getElementById('adminPageLink-mobile');
    if (adminPageLinkMobile) {
        adminPageLinkMobile.addEventListener('click', w3_close);
    }
});
