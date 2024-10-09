window.addEventListener('load', init);

function init() {
    const downloadForm = document.getElementById('downloadForm');
    downloadForm.addEventListener('submit', downloadFile);
}

async function downloadFile(event) {
    event.preventDefault();
    const fileId = document.getElementById('file_id').value;
    const viewType = document.querySelector('input[name="view_type"]:checked').value;
    const response = await fetch(`/api/download_file?id=${fileId}`);
    const data = await response.json();
    console.log(data);
    //return;

    if (data.status === 'success') {
        if (viewType === 'browser' && data.filetype === 'txt') {
            document.getElementById('file_content').textContent = data.filedata;
        } else if (viewType === 'pdf' && data.filetype === 'pdf') {
            const link = document.createElement('a');
            link.href = `data:application/pdf;base64,${data.filedata}`;
            link.download = data.filename;
            link.click();

            const pdfWindow = window.open("");
            pdfWindow.document.write(
                `<iframe width='100%' height='100%' src='data:application/pdf;base64,${data.filedata}'></iframe>`
            );
        } else {
            alert('Tipo di visualizzazione non supportato per questo file.');
        }
    } else {
        alert(data.message);
    }
}