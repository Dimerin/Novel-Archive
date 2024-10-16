let originalContent; // Define originalContent in a higher scope

function init() {
    const uploadForm = document.getElementById('uploadForm');
    uploadForm.addEventListener('submit', uploadFile);
    document.getElementById('upload_file_radio').addEventListener('change', toggleUploadSection);
    document.getElementById('upload_text_radio').addEventListener('change', toggleUploadSection);   
    toggleUploadSection();
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize originalContent after the DOM is fully loaded
    originalContent = document.getElementById('mainContent').innerHTML;

    var uploadLink = document.getElementById('uploadFileLink');
    if (uploadLink) {
        uploadLink.addEventListener('click', function(event) {
            event.preventDefault();  // Prevent default anchor behavior
            
            document.getElementById('mainContent').innerHTML = `
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
            init();
            // Check if toast.js is already loaded
            if (!document.querySelector('script[src="./Frontend/js/toast.js"]')) {
                var script = document.createElement('script');
                script.src = './Frontend/js/toast.js';
                document.head.appendChild(script);
            } else {
                // Reinitialize notifications if toast.js is already loaded
                initNotifications();
            }
            updateLinkClasses(uploadLink);
        });
    }

    // Function to restore the original content
    window.resetHomePage = function() {
        document.getElementById('mainContent').innerHTML = originalContent;
        initNotifications(); // Reinitialize notifications when switching back to homepage.
        updateLinkClasses(document.getElementById('catalogueLink'));
    };
    const sidebarLinks = document.querySelectorAll('.w3-sidebar .w3-bar-item');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            updateLinkClasses(this);
        });
    });
});


function updateLinkClasses(activeLink) {
    const sidebarLinks = document.querySelectorAll('.w3-sidebar .w3-bar-item');
    sidebarLinks.forEach(link => {
        link.classList.remove('w3-white');
    });
    activeLink.classList.add('w3-white');
}

async function uploadFile(event) {
    event.preventDefault();

    const formData = new FormData();
    const uploadType = document.querySelector('input[name="upload_type"]:checked').value;

    if (uploadType === 'file') {
        const fileInput = document.getElementById('file');
        if (fileInput.files.length === 0) {
            showToast('warning', 'Select a file to upload.');
            return;
        }
        formData.append('file', fileInput.files[0]);
    } else {
        const textContent = document.getElementById('text_content').value;
        if (textContent.trim() === '') {
            showToast('warning', 'Insert text to upload.');
            return;
        }
        formData.append('text_content', textContent);
    }

    formData.append('upload_type', uploadType);

    try {
        const response = await fetch('/api/upload_file', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        showToast(result.status, result.message);
    } catch (error) {
        console.error('Error during file or text upload:', error);
        showToast('error', 'Error during file or text upload.');
    }
}

function toggleUploadSection() {
    const isTextSelected = document.getElementById('upload_text_radio').checked;
    const fileUploadSection = document.getElementById('file_upload_section');
    const textUploadSection = document.getElementById('text_upload_section');

    if (isTextSelected) {
        fileUploadSection.classList.add('hidden');
        textUploadSection.classList.remove('hidden');
    } else {
        fileUploadSection.classList.remove('hidden');
        textUploadSection.classList.add('hidden');
    }
}