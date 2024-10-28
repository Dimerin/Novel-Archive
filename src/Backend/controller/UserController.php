<?php
require_once __DIR__ . '/../utils/dbManager.php';
require_once __DIR__ . '/../utils/PostMan.php';

const ACTIVE = 1;
const INACTIVE = 0;

class UserController
{
    private $conn;

    public function __construct()
    {
        $this->conn = dbManager::getInstance()->getConnection();
    }

    private function generate_token(int $n_bytes = 32)
    {
        return bin2hex(random_bytes($n_bytes));
    }

    private function checkUserExistence($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $rows_check = $stmt->num_rows > 0;
        $stmt->close();

        // If the user is found, return true; otherwise, return false
        return $rows_check;
    }

    private function storeToken($token, $email, $purpose = 'register')
    {
        $stmt = $this->conn->prepare("INSERT INTO tokens (email, token, purpose) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $purpose);
        $executed = $stmt->execute();
        $stmt->close();
        
        return $executed;
    }

    private function deleteToken($email, $purpose)
    {
        $stmt = $this->conn->prepare("DELETE FROM tokens WHERE email = ? AND purpose = ?");
        $stmt->bind_param("ss", $email, $purpose);
        $executed = $stmt->execute();
        $stmt->close();

        return $executed;
    }

    private function checkToken($token, $email, $purpose)
    {
        $true_created_at = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $stmt = $this->conn->prepare("SELECT * FROM tokens WHERE token = ? AND email = ? AND purpose = ? AND created_at > ?");
        $stmt->bind_param("ssss", $token, $email, $purpose, $true_created_at);
        $executed = $stmt->execute();
        $stmt->store_result();
        $rows_check = $stmt->num_rows >= 0;
        $stmt->close();

        return $executed && $rows_check;
    }

    private function setUserStatus($email, $status)
    {
        // Aggiorna lo stato dell'utente
        $stmt = $this->conn->prepare("UPDATE users SET active = ? WHERE email = ?");
        $stmt->bind_param("is", $status, $email);
        $executed = $stmt->execute();
        $stmt->store_result();
        
        $rows_check = $stmt->affected_rows === 1;   
        $stmt->close();

        return $rows_check && $executed;
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
        
        if($this->checkUserExistence($email) == false)
            return $this->sendResponse(['status' => 'error', 'message' => 'User already exists.'], 409);

        // Generate token
        $token = $this->generate_token(100);

        // Registra il nuovo utente
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $check = $stmt->execute();
        
        // Store the token in the tokens table
        if(storeToken($token, $email) == false) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Registration failed.'], 500);
        }

        // Send an email with the OTP
        $postman = new PostMan();
        $to = $email;
        $subject = 'Verify your email address';
        $message = "link for Otp: https://localhost/api/verify_user?email=".$email."&token=".$token;
        
        $postman->send($email, $subject, $message);

        $stmt->close();
        return $this->sendResponse(['status' => 'success', 'message' => 'User registered successfully.'], 201);
    }

    public function verifyUser()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
        if(!isset($_GET['token']) || !isset($_GET['email']) ) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }

        $receive_token = $_GET['token'];
        $email = $_GET['email'];
        
        // get the token from the database
        if($this->checkToken($receive_token, $email, 'register') == false) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid OTP.'], 401);
        }

        // Update the user's status to verified
        try{
            $this->switchUserStatus($email);
        } catch (Exception $e) {
            return $this->sendResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
            
        // Delete the token from the tokens table
        if($this->deleteToken($email, 'register') == false) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Failed to verify user.'], 500);
        }

        return $this->sendResponse(['status' => 'success', 'message' => 'User verificated successfully.'], 201);
    }

    public function initResetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
        if (!isset($_POST['email'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Email is required.'], 400);
        }
    
        $email = $_POST['email'];
    
        // Check if the user exists
        if($this->checkUserExistence($email) == false)
            return $this->sendResponse(['status' => 'error', 'message' => 'User already exists.'], 409);
    
        // Generate token
        $token = $this->generate_token(100);

        // Store the token in the tokens table
        if ($this->storeToken($token, $email, 'reset') == false) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Failed to initiate password reset.'], 500);
        }

        //Disable the user
        try {
            $this->setUserStatus($email, INACTIVE);
        } catch (Exception $e) {
            return $this->sendResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
        
        // Send email with token
        $postman = new PostMan();
        $to = $email;
        $subject = 'Password Reset Request';
        $message = "Your OTP for password reset: https://localhost/api/validate_reset_pwd?email=".$email."&token=".$token;
    
        $postman->send($to, $subject, $message);
    
        return $this->sendResponse(['status' => 'success', 'message' => 'OTP sent to your email.'], 200);
    }
    
    public function validateResetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
    
        if (!isset($_GET['token']) || !isset($_GET['email'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
    
        $receive_otp = $_GET['token'];
        $email = $_GET['email'];
    
        // Check if the token is valid
        if ($this->checkToken($receive_otp, $email, 'reset') == false) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid OTP.'], 401);
        }

        // Delete the token from the tokens table
        if($this->deleteToken($email, 'reset') == false) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Failed to verify user.'], 500);
        }

        return $this->sendResponse(['status' => 'success', 'message' => 'Token is valid'], 200);
    }

    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
    
        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
    
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Check if the user exists
        if ($this->checkUserExistence($email) == false)
            return $this->sendResponse(['status' => 'error', 'message' => 'User not found.'], 404);
    
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the password
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $executed = $stmt->execute();
        $stmt->close();

        if ($executed == false) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Password reset failed.'], 500);
        }
        
        return $this->sendResponse(['status' => 'success', 'message' => 'Password reset successfully.'], 200);
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
            $_SESSION['user_id'] = $id;

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

        $limit++; // To check if there are more pages

        $stmt = $this->conn->prepare("SELECT id, username, email, role FROM users Where role != 'admin' LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $limit);

        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        $isLastPage = true;

        if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            if (count($users) == $limit) {
                $isLastPage = false;
                array_pop($users);
            }
        }

        $stmt->close();

        return $this->sendResponse(['status' => 'success', 'data' => $users, 'last-page' => $isLastPage], 200);

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

        if ($newRole !== 'free' && $newRole !== 'admin' && $newRole !== 'pro') {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid role.'], 400);
        }

        if ($actualRole !== 'free'&& $actualRole !== 'admin' && $actualRole !== 'pro') {
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

        if($role === $newRole) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }
        
        if($role !== $actualRole) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
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