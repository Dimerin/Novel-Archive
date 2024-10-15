<!DOCTYPE html>
<html lang="it">
<head>
    <title>Upload content</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./Frontend/css/main.css">
    <script src="./Frontend/js/upload_file.js"></script>
    <script src="./Frontend/js/menu.js"></script>
</head>
<style>
        .bgimg-1 {
            background-image: url('./Frontend/imgs/upload_file.jpg');
        }
    </style>
</head>
    <body>
        <?php include './Frontend/includes/navbar.php'; ?>
        <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
            <div class="w3-display-left w3-text-black" style="padding:48px">
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
        </header>
    </body>
</html>
