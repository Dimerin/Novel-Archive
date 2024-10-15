<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Download File</title>
    <script src="./Frontend/js/download_file.js"></script>
</head>
<body>
    <h1>Download File</h1>
    <form id="downloadForm">
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