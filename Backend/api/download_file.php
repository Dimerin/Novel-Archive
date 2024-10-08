<?php
require '../db/db.php';
require '../utils/fileManager.php';

if (isset($_GET['id'])) {
    $fileManager = new FileManager();
    $response = $fileManager->downloadFile(intval($_GET['id']));
    
    header('Content-Type: application/json');
    echo $response;
} else {
    $response = json_encode(['status' => 'error', 'message' => 'ID del file non fornito.']);
    header('Content-Type: application/json');
    echo $response;
}
?>