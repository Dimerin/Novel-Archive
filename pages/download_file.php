<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Download File</title>
    <script>
        async function downloadFile() {
            const fileId = document.getElementById('file_id').value;
            const viewType = document.querySelector('input[name="view_type"]:checked').value;
            const response = await fetch(`../api/download_file.php?id=${fileId}`);
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
    </script>
</head>
<body>
    <h1>Download File</h1>
    <form onsubmit="event.preventDefault(); downloadFile();">
        <fieldset>
            <legend>Inserisci l'ID del file:</legend>
            <label for="file_id">ID del file:</label>
            <input type="text" id="file_id" name="file_id" required>
        </fieldset>
        <br>
        <fieldset>
            <legend>Seleziona il tipo di visualizzazione:</legend>
            <label>
                <input type="radio" name="view_type" value="browser" checked>
                Mostra nel browser
            </label>
            <br>
            <label>
                <input type="radio" name="view_type" value="pdf">
                Scarica come PDF
            </label>
        </fieldset>
        <br>
        <button type="submit">Scarica</button>
    </form>
    <pre id="file_content"></pre>
</body>
</html>