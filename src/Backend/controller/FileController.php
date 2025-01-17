<?php
require_once __DIR__ . '/../utils/dbManager.php';
require_once __DIR__ . '/../utils/Logger.php';

class FileController
{
    private $conn;
    private $logger;

    public function __construct()
    {
        $this->conn = dbManager::getInstance()->getConnection();
        $this->logger = Logger::getInstance();
    }

    public function upload()
    {
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
            $this->logger->error('upload', 'CSFR Token missing.', 401);
            return $this->sendResponse(['status' => 'error', 'message' => 'Parameters missing.'], 401);
        }

        // Controlla se il tipo di upload non è specificato
        if (!isset($_POST['upload_type']) || !isset($_POST['novel_category']) || !is_string($_POST["novel_category"])) {
            $this->logger->error('upload', 'Upload type not specified.', 400);
            return $this->sendResponse(['status' => 'error', 'message' => 'Upload type not specified.'], 400);
        }
        
        if ($_POST['upload_type'] === 'file' && isset($_FILES['file'])) {
            $file = $_FILES['file'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $this->logger->error('upload', 'File upload error code: ' . $file['error'], 400);
                return $this->sendResponse(["status" => "error", "message" => "File upload failed."]);
            }

            if($file['size'] <= 0 || $file['size'] > 1024*1024*2){
                $this->logger->error('upload', 'File size not supported.', 400);
                return $this->sendResponse(["status"=>"error", "message" => "File size not supported"]);
            }

            if(empty($file["name"])){
                $this->logger->error('upload', 'File name not supported.', 400);
                return $this->sendResponse(["status"=>"error", "message" => "File name not supported"]);
            }

            $filetype = pathinfo($file["name"], PATHINFO_EXTENSION);
            $title = pathinfo( $file["name"], PATHINFO_FILENAME);
            $title = preg_replace('/[^\w\-\.]/', '_', $title);
            $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
            $title = substr($title, 0, 255);
            $filedata = file_get_contents($file['tmp_name']);
        } elseif ($_POST['upload_type'] === 'text' && isset($_POST['text_content']) && isset($_POST['title']) 
                && !empty($_POST['text_content']) && !empty($_POST['title'])) {
            $title = $_POST['title'];
            $title = htmlspecialchars($title,ENT_QUOTES, 'UTF-8');
            $filedata = $_POST['text_content'];
            $filedata = htmlspecialchars($filedata, ENT_QUOTES, 'UTF-8');
            $filetype = 'txt';
        } else {
            return $this->sendResponse(['status' => 'error', 'message' => 'No file or text inserted.'], 400);
        }

        if(!in_array($filetype, ["txt","pdf"])){
            $this->logger->error('upload', 'File type not supported.', 400);
            return $this->sendResponse(["status"=>"error", "message" => "File type not supported"]);
        }
        
        $novel_category = $_POST['novel_category'];
        if(!in_array($novel_category, ["free", "pro"])){
            $this->logger->error('upload', 'File type not supported.', 400);
            return $this->sendResponse(["status"=>"error", "message" => "File type not supported"]);
        }
        $user_id = $_SESSION['user_id'];
        $userVisibility = $this->getUserVisibility();

        $selectedVisibility = $novel_category === 'pro' ? 1 : 0;

        if($userVisibility < $selectedVisibility){
            $this->logger->error('upload', 'Missing file permissions.', 403);
            return $this->sendResponse(['status' => 'error', 'message' => 'Missing file permissions.'], 403);
        }
        
        $query = '
            INSERT INTO files (title, filetype, filedata, user_id, visibility)
            VALUES (?, ?, ?, ?, ?)
        ';

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            $this->logger->error('upload', 'Query preparation failed.', 500);
            return $this->sendResponse(['status' => 'error', 'message' => 'Query preparation failed.'], 500);
        }

        $stmt->bind_param("sssii", $title, $filetype, $filedata, $user_id, $selectedVisibility);
        if ($stmt->execute()) {
            $stmt->close();
            $this->logger->info('upload', 'Novel uploaded successfully', 201);
            return $this->sendResponse(['status' => 'success', 'message' => 'Novel uploaded successfully'], 201);
        }
        $stmt->close();
        $this->logger->error('upload', 'Novel upload has failed', 500);
        return $this->sendResponse(['status' => 'error', 'message' => 'Novel upload has failed'], 500);
    }

    private function getUserVisibility()
    {
        $user_id = $_SESSION['user_id'];
        $stmt = $this->conn->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result($role);
        $stmt->fetch();
        $stmt->close();

        $visibility = $role === 'free' ? 0 : 1;
        return $visibility;
    }
        
    public function downloadFile()
    {   
        if (!isset($_POST['file_id'])) {
            $this->logger->error('downloadFile', 'File ID not provided', 400);
            return $this->sendResponse(['status' => 'error', 'message' => 'File ID not provided'], 400);
        }

        $fileId = $_POST['file_id'];
        $stmt = $this->conn->prepare("
            SELECT 
                files.title, 
                files.filetype, 
                files.filedata, 
                users.username,
                files.visibility
            FROM 
                files 
            INNER JOIN
                users 
            ON 
                files.user_id = users.id 
            WHERE 
                files.id = ?");
        $stmt->bind_param("i", $fileId);
        $stmt->execute();
        $stmt->bind_result($title, $filetype, $filedata, $author, $visibility);
        $stmt->fetch();
        $stmt->close();

        $userVisibility = $this->getUserVisibility();

        if($visibility > $userVisibility){
            $this->logger->error('downloadFile', 'Missing download file permissions.', 403);
            return $this->sendResponse(['status' => 'error', 'message' => 'Missing download file permissions.'], 403);
        }

        if (!$title || !$filedata) {
            $this->logger->error('downloadFile', 'File not found.', 404);
            return $this->sendResponse(['status' => 'error', 'message' => 'File not found.'], 404);
        }

        $this->logger->info('downloadFile', 'Novel uploaded successfully.', 200);
        $response = [
            'status' => 'success',
            'title' => $title,
            'filetype' => $filetype,
            'author' => $author,
            'filedata' => $filetype === 'txt' ? $filedata : base64_encode($filedata)
        ];

        return $this->sendResponse($response);
    }

    public function showFiles()
    {
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
        $this->logger->info('showFiles', 'Files retrieved successfully.', 200);
        return $this->sendResponse(['status'=> 'success','files'=> $files, 'last-page' => $isLastPage],200);
    }

    private function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}