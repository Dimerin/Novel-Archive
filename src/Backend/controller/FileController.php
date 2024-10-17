<?php

require_once __DIR__ . '/../utils/dbManager.php';

class FileController
{
    private $conn;
    private $db;

    public function __construct()
    {
        $this->db = new dbManager();
        $this->conn = $this->db->getConnection();
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Metodo non consentito.'], 405);
        }

        // Controlla se il tipo di upload non è specificato
        if (!isset($_POST['upload_type'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Tipo di upload non specificato.'], 400);
        }

        if ($_POST['upload_type'] == 'file' && isset($_FILES['file'])) {
            return $this->uploadFile();
        } elseif ($_POST['upload_type'] == 'text' && isset($_POST['text_content'])) {
            return $this->uploadText();
        } else {
            return $this->sendResponse(['status' => 'error', 'message' => 'Nessun file o testo fornito.'], 400);
        }
    }

    private function uploadFile()
    {
        if (!isset($_FILES['file'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Nessun file caricato.'], 400);
        }

        $file = $_FILES['file'];
        $filename = basename($file['name']);
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        $filedata = file_get_contents($file['tmp_name']);

        $stmt = $this->conn->prepare("INSERT INTO files (filename, filetype, filedata) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $filename, $filetype, $filedata);

        if ($stmt->execute()) {
            $stmt->close();
            return $this->sendResponse(['status' => 'success', 'message' => 'File caricato con successo.'], 201);
        } else {
            $stmt->close();
            return $this->sendResponse(['status' => 'error', 'message' => 'Caricamento del file fallito.'], 500);
        }
    }

    private function uploadText()
    {
        if (!isset($_POST['text_content'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Nessun testo fornito.'], 400);
        }
        
        $text = $_POST['text_content'];
        $filename = 'testo_inserito.txt';
        $filetype = 'txt';
        $filedata = $text;

        $stmt = $this->conn->prepare("INSERT INTO files (filename, filetype, filedata) VALUES (?, ?, ?)");
        if (!$stmt) { //TODO: check if this is correct
            throw new Exception("Preparazione della query fallita: " . $this->conn->error);
        }

        $stmt->bind_param("sss", $filename, $filetype, $filedata);
        if ($stmt->execute()) {
            $stmt->close();
            return $this->sendResponse(['status' => 'success', 'message' => 'Testo caricato con successo.'], 201);
        } else {
            $stmt->close();
            return $this->sendResponse(['status' => 'error', 'message' => 'Caricamento del testo fallito.'], 500);
        }
    }

    public function downloadFile()
    {
        if (!isset($_GET['file_id'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'ID del file non fornito.'], 400);
        }

        $fileId = $_GET['file_id'];
        $stmt = $this->conn->prepare("SELECT filename, filetype, filedata FROM files WHERE id = ?");
        $stmt->bind_param("i", $fileId);
        $stmt->execute();
        $stmt->bind_result($filename, $filetype, $filedata);
        $stmt->fetch();
        $stmt->close();

        if (!$filename || !$filedata) {
            return $this->sendResponse(['status' => 'error', 'message' => 'File non trovato.'], 404);
        }

        $response = [
            'status' => 'success',
            'filename' => $filename,
            'filetype' => $filetype,
            'filedata' => $filetype === 'txt' ? $filedata : base64_encode($filedata)
        ];

        return $this->sendResponse($response);
    }

    public function showFiles(){
        if( session_status() == PHP_SESSION_NONE ){
            session_start();
        }

        if( $_SERVER["REQUEST_METHOD"] != "GET" ){
            return $this->sendResponse(['status' => 'error', 'message' => 'Metodo non consentito.'], 405);
        }

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) :1;
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) :10;
        $file_type = isset($_GET['file_type']) ? $_GET['file_type'] : 'txt';
        
        if( $page < 1){
            $page = 1; //FIXME: come controllo la pagina massima da ritornare?
        }
        if( $limit < 1 || $limit > 10){
            $limit = 10;
        }

        $offset = ($page - 1) * $limit;
        $stmt = $this->conn->prepare('SELECT id, filename, filetype FROM files WHERE filetype = ? LIMIT ?, ?');
        $stmt->bind_param('sii', $file_type, $offset, $limit);

        $stmt->execute();
        $result = $stmt->get_result();

        $files = [];

        if( $result->num_rows > 0){
            while( $row = $result->fetch_assoc() ){
                $files[] = $row;
            }
        }

        $stmt->close();
        return $this->sendResponse(['status'=> 'success','files'=> $files],200);

    }

    private function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}