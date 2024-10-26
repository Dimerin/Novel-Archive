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

    private function getUserVisibility()
    {
        //$user_id = 1; //TODO: when not testing, comment this line
        $user_id = $_SESSION['user_id']; //TODO: when not testing, uncomment this line
        $stmt = $this->conn->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result($role);
        $stmt->fetch();
        $stmt->close();

        $visibility = $role == 'free' ? 0 : 1;
        return $visibility;
    }
    // FIXME: le due funzioni uploadFile e uploadText sono molto simili, si potrebbe fare una funzione generica
    // get user visibility
    private function uploadFile()
    {
        if (!isset($_FILES['file'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Nessun file caricato.'], 400);
        }

        $file = $_FILES['file'];
        $title = basename($file['name']);
        $filetype = pathinfo($title, PATHINFO_EXTENSION);
        $filedata = file_get_contents($file['tmp_name']);
        $novel_category = $_POST['novel_category'];

        // get user visibility
        #$user_id = 1; //TODO: when not testing, comment this line
        $user_id = $_SESSION['user_id']; //TODO: when not testing, uncomment this line
        // FIXME: passare user_id o ricavarlo da sessione dentro la funzione? 
        $userVisibility = $this->getUserVisibility();

        $selectedVisibility = $novel_category == 'pro' ? 1 : 0;

        if($userVisibility < $selectedVisibility){
            return $this->sendResponse(['status' => 'error', 'message' => 'Non hai i permessi per caricare questo contenuto.'], 403);
        }

        $query = '
            INSERT INTO files (title, filetype, filedata, user_id, visibility)
            VALUES (?, ?, ?, ?, ?)
        ';

        $stmt = $this->conn->prepare( $query);
        $stmt->bind_param("sssii", $title, $filetype, $filedata, $user_id, $visibility);

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
        $title = $_POST['title'];
        $novel_category = $_POST['novel_category'];
        // FIXME: non ricordo che campi si passano dal frontend, modificare i campi in base a quelli passati
        //$title = 'testo_inserito.txt';
        $filetype = 'txt';
        $filedata = $text;


        // get user visibility
        #$user_id = 1; //TODO: when not testing, comment this line
        $user_id = $_SESSION['user_id']; //TODO: when not testing, uncomment this line
        $userVisibility = $this->getUserVisibility();

        $selectedVisibility = $novel_category == 'pro' ? 1 : 0;

        if($userVisibility < $selectedVisibility){
            return $this->sendResponse(['status' => 'error', 'message' => 'Non hai i permessi per caricare questo contenuto.'], 403);
        }
        
        $query = '
            INSERT INTO files (title, filetype, filedata, user_id, visibility)
            VALUES (?, ?, ?, ?, ?)
        ';

        $stmt = $this->conn->prepare($query);
        if (!$stmt) { //TODO: check if this is correct
            throw new Exception("Preparazione della query fallita: " . $this->conn->error);
        }

        $stmt->bind_param("sssii", $title, $filetype, $filedata, $user_id, $selectedVisibility);
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
        //FIXME: con questa funzione downloadFile chiunque entri in possesso del file_id può scaricare il file,
        // bisogna aggiungere un controllo per vedere se l'utente ha i permessi per scaricare il file
        if (!isset($_GET['file_id'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'ID del file non fornito.'], 400);
        }

        $fileId = $_GET['file_id'];
        $stmt = $this->conn->prepare("
            SELECT 
                files.title, 
                files.filetype, 
                files.filedata, 
                users.username 
            FROM 
                files 
            JOIN 
                users 
            ON 
                files.user_id = users.id 
            WHERE 
                files.id = ?");
        $stmt->bind_param("i", $fileId);
        $stmt->execute();
        $stmt->bind_result($title, $filetype, $filedata, $author);
        $stmt->fetch();
        $stmt->close();

        if (!$title || !$filedata) {
            return $this->sendResponse(['status' => 'error', 'message' => 'File non trovato.'], 404);
        }

        $response = [
            'status' => 'success',
            'title' => $title,
            'filetype' => $filetype,
            'author' => $author,
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
        $file_type = isset($_GET['file_type']) ? $_GET['file_type'] : 'both';

        if( $page < 1){
            $page = 1; //FIXME: come controllo la pagina massima da ritornare?
        }
        if( $limit < 1 || $limit > 6){
            $limit = 6;
        }

        $offset = ($page - 1) * $limit;

        $limit +=1; // get one more element to check if there are more pages

        // get user visibility
        $userVisibility = $this->getUserVisibility();
        
        $query = '
            SELECT f.id, f.title, f.filetype, u.username, f.uploaded_at, f.visibility
            FROM files f INNER JOIN users u ON f.user_id = u.id
            WHERE (f.filetype = ? OR ? = "both") AND ? >= f.visibility
            ORDER BY f.uploaded_at DESC
            LIMIT ?, ?
        ';

        $stmt = $this->conn->prepare( $query );
        $stmt->bind_param('ssiii', $file_type, $file_type, $userVisibility, $offset, $limit);

        $stmt->execute();
        $result = $stmt->get_result();

        $files = [];
        $isLastPage = true;

        if( $result->num_rows > 0){
            
            while( $row = $result->fetch_assoc() ){
                $files[] = $row;
            }

            if( count($files) == $limit){
                $isLastPage = false;
                array_pop($files);
            }
        }

        $stmt->close();
        return $this->sendResponse(['status'=> 'success','files'=> $files, 'last-page' => $isLastPage],200);
    }

    private function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}