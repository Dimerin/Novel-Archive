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
        } else {
            $stmt->close();
            return $this->sendResponse(['status' => 'error', 'message' => 'Registration failed.'], 500);
        }
    }

    public function login()
    {   
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Controlla se l'utente esiste
        $stmt = $this->conn->prepare(query: "SELECT id, username, password, role FROM users WHERE email = ?");
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
            $_SESSION['username'] = $username;
            $_SESSION['role'] =$role;

            return $this->sendResponse(['status' => 'success', 'message' => 'Login successful.', 'user' => ['id' => $id, 'username' => $username]], 200);

        } else {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid email or password.'], 401);
        }
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
        else {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
    }

    private function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}