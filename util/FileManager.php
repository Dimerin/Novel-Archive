<?php
require_once '../db/db.php';

class FileManager {
    private $conn;

    public function __construct() {
        //TODO: non funziona la connessione al db
        // devo fare la connessione al db in ogni metodo
        
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function uploadFile($file) {
        $db = new Database();
        $this->conn = $db->getConnection();

        $filename = basename($file['name']);
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        $filedata = file_get_contents($file['tmp_name']);

        $stmt = $this->conn->prepare("INSERT INTO files (filename, filetype, filedata) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $filename, $filetype, $filedata);
        $stmt->execute();
        $stmt->close();
        
        return json_encode(
            ['status' => 'success', 'message' => 'File caricato con successo.']
        );
    }

    public function uploadText($text) {
        $filename = 'testo_inserito.txt';
        $filetype = 'txt';
        $filedata = $text;
        $db = new Database();
        $this->conn = $db->getConnection();
        $stmt = $this->conn->prepare("INSERT INTO files (filename, filetype, filedata) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Preparazione della query fallita: " . $this->conn->error);
        }
        $stmt->bind_param("sss", $filename, $filetype, $filedata);
        $stmt->execute();
        $stmt->close();

        return json_encode(
            ['status' => 'success', 'message' => 'Testo caricato con successo.']
        );
    }

    public function downloadFile($id) {
        $db = new Database();
        $this->conn = $db->getConnection();
        
        $stmt = $this->conn->prepare("SELECT filename, filetype, filedata FROM files WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($filename, $filetype, $filedata);
        $stmt->fetch();
        $stmt->close();

        if ($filename && $filedata) {
            if ($filetype === 'txt') {

                $response = [
                    'status' => 'success',
                    'filename' => $filename,
                    'filetype' => $filetype,
                    'filedata' => $filedata
                ];

                header('Content-Type: application/json');
                return json_encode($response);
            } else {
                $response = [
                    'status' => 'success',
                    'filename' => $filename,
                    'filetype' => $filetype,
                    'filedata' => base64_encode($filedata)
                ];
                header('Content-Type: application/json');
                return json_encode($response);
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'File non trovato'
            ];
            header('Content-Type: application/json');
            return json_encode($response);
        }
    }

    public function __destruct() {
        //$this->conn->close();
    }
}
?>