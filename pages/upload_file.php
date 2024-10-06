<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Caricamento File o Testo</title>
    <script>
        async function uploadFile(event) {
            event.preventDefault();

            const formData = new FormData();
            const uploadType = document.querySelector('input[name="upload_type"]:checked').value;

            if (uploadType === 'file') {
                const fileInput = document.getElementById('file');
                if (fileInput.files.length === 0) {
                    alert('Seleziona un file da caricare.');
                    return;
                }
                formData.append('file', fileInput.files[0]);
            } else {
                const textContent = document.getElementById('text_content').value;
                if (textContent.trim() === '') {
                    alert('Inserisci del testo da caricare.');
                    return;
                }
                formData.append('text_content', textContent);
            }

            formData.append('upload_type', uploadType);

            try {
                const response = await fetch('../script/upload_file.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                alert(result.message);
            } catch (error) {
                console.error('Errore durante il caricamento:', error);
                alert('Errore durante il caricamento del file o del testo.');
            }
        }
    </script>
</head>
<body>
    <h1>Caricamento File o Testo</h1>
    <form onsubmit="uploadFile(event)">
        <label>
            <input type="radio" name="upload_type" value="file" checked>
            Carica un file
        </label>
        <br>
        <label for="file">Scegli un file:</label>
        <input type="file" name="file" id="file">
        <br><br>
        <label>
            <input type="radio" name="upload_type" value="text">
            Inserisci testo
        </label>
        <br>
        <textarea name="text_content" id="text_content" rows="10" cols="30"></textarea>
        <br><br>
        <button type="submit">Carica</button>
    </form>
</body>
</html>