<?php
//require 'config.php';
//require '../db/db.php';
require '../util/FileManager.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fileManager = new FileManager();
    
    if ($_POST['upload_type'] == 'file' && isset($_FILES['file'])) {
        $response = $fileManager->uploadFile($_FILES['file']);
    } elseif ($_POST['upload_type'] == 'text' && isset($_POST['text_content'])) {
        $response = $fileManager->uploadText($_POST['text_content']);
    } else {
        $response = json_encode(['status' => 'error', 'message' => 'Nessun file o testo fornito.']);
    }
    
    header('Content-Type: application/json');
    echo $response;
}
?>