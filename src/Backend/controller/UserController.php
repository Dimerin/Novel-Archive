<?php
require_once __DIR__ . '/../db/db.php';

class UserController
{
    private $conn;
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function register()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
        if(!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Controlla se l'utente esiste già
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return $this->sendResponse(['status' => 'error', 'message' => 'User already exists.'], 409);
        }
        $stmt->close();

        // Registra il nuovo utente
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $stmt->close();
            return $this->sendResponse(['status' => 'success', 'message' => 'User registered successfully.'], 201);
        } 
        $stmt->close();
        return $this->sendResponse(['status' => 'error', 'message' => 'Registration failed.'], 500);
    }

    public function login()
    {   
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Regenerate session ID upon login to prevent session fixation
        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }

        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        // Controlla se l'utente esiste
        $stmt = $this->conn->prepare( "SELECT id, username, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $stmt->close();
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid email or password.'], 401);

        }

        $stmt->bind_result($id, $username, $hashedPassword, $role);
        $stmt->fetch();
        $stmt->close();

        // Verifica la password
        if (password_verify($password, $hashedPassword)) {
            // Regenerate session ID to prevent fixation after successful login
            session_regenerate_id(true);
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['id'] = $id;

            return $this->sendResponse(['status' => 'success', 'message' => 'Login successful.', 'user' => ['id' => $id, 'username' => $username]], 200);

        } 
        return $this->sendResponse(['status' => 'error', 'message' => 'Invalid email or password.'], 401);
    }
    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Destroy the session
            session_unset();
            session_destroy();
            return $this->sendResponse(['status' => 'success', 'message' => 'Logout successful.'], 200);
        }
        return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
    }

    public function showUsers()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // TODO: ora non è attivo perchè in fase di test
        //if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        //    return $this->sendResponse(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        //}

        if ($_SERVER['REQUEST_METHOD']!== 'GET') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ?  $_GET['page'] : 1;
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? $_GET['limit'] : 10;

        $offset = ($page - 1) * $limit;

        $stmt = $this->conn->prepare("SELECT id, username, email, role FROM users Where role != 'admin' LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $limit);

        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        $stmt->close();

        return $this->sendResponse(['status' => 'success', 'data' => $users], 200);

    }

    public function changeUserRole()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        /* TODO: ora non è attivo perchè in fase di test
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        }*/

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }

        if (!isset($_POST['id']) || !isset($_POST['new_role']) || !isset($_POST['actual_role'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }

        //$email = $_POST['email'];
        $id = $_POST['id'];
        if(!is_numeric($id)) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
        $newRole = $_POST['new_role'];
        $actualRole = $_POST['actual_role'];

        if ($newRole !== 'non-premium' && $newRole !== 'admin' && $newRole !== 'premium') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid role.'], 400);
        }

        if ($actualRole !== 'non-premium'&& $actualRole !== 'admin' && $actualRole !== 'premium') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid role.'], 400);
        }

        if ($actualRole === 'admin') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        }

        if ( $actualRole ===  $newRole ){
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }

        if ( $newRole === 'admin' ){ // TODO: è utile?
            return $this->sendResponse(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        }

        $stmt = $this->conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        //$stmt->close();
        
        if($stmt->num_rows == 0) {
            return $this->sendResponse(['status' => 'error', 'message' => 'User not found.'], 404);
        }
        $stmt->bind_result($role);
        $stmt->fetch();
        $stmt->close();

        if($role === 'admin') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        }
        
        $stmt = $this->conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $id);

        if ($stmt->execute()) {
            $stmt->close();
            return $this->sendResponse(['status' => 'success', 'message' => 'Role changed successfully.'], 200);
        }

        $stmt->close();
        return $this->sendResponse(['status' => 'error', 'message' => 'Role change failed.'], 500);   
    }

    private function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}