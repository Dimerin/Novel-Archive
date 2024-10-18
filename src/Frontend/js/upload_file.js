// Init upload file page

function init() {
    const uploadForm = document.getElementById('uploadForm');
    uploadForm.addEventListener('submit', uploadFile);
    document.getElementById('upload_file_radio').addEventListener('change', toggleUploadSection);
    document.getElementById('upload_text_radio').addEventListener('change', toggleUploadSection);   
    toggleUploadSection();
}

async function uploadFile(event) { //TODO: Implement the backend API endpoint for file upload
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
        const title = document.getElementById('title').value;
        const textContent = document.getElementById('text_content').value;
        if (title.trim() === '') {
            showToast('warning', 'Insert a title.');
            return;
        }
        if (textContent.trim() === '') {
            showToast('warning', 'Insert text to upload.');
            return;
        }
        formData.append('title', title);
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