<?php
require_once __DIR__ . '/../utils/dbManager.php';
require_once __DIR__ . '/../utils/PostMan.php';
require_once __DIR__ . '/../utils/TokenService.php';
require_once __DIR__ . '/../utils/UserService.php';
require_once __DIR__ . '/../utils/ErrorHandler.php';

$errorHandler = new ErrorHandler();

const ACTIVE = 1;
const INACTIVE = 0;

const URL_PSW_RST_PAGE = 'https://localhost/reset_password';
const URL_REGISTER_PAGE = 'https://localhost/verify_user';

class UserController
{
    private $conn, $postman;
    private $token_service, $user_service;
    private $psw_rst_page_url, $register_page_url;

    public function __construct()
    {
        $this->conn = dbManager::getInstance()->getConnection();
        $this->postman = new PostMan();
        $this->token_service = new TokenService($this->conn);
        $this->user_service = new UserService($this->conn);
    }
    
    private function checkPasswordFormat($password)
    {
        // Check Password length and format
        // Password must be at least 16 characters long and contain at least one uppercase letter, one lowercase letter, and one number
        $password_error = false;
        if (strlen($password) < 16) {
            $password_error = 'Password must be at least 16 characters long.';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $password_error =  'Password must contain at least one uppercase letter.';
        } elseif (!preg_match('/[a-z]/', $password)) {
            $password_error = 'Password must contain at least one lowercase letter.';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $password_error = 'Password must contain at least one number.';
        }

        if ($password_error !== false)
            throw new InvalidRequestException($password_error);
        return false;
    }
    private function checkEmailFormat($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidRequestException('Invalid email format.');
        }
    }

    public function register()
    {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
                throw new InvalidRequestException('Invalid request.');
            }
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Check the email format
            $this->checkEmailFormat($email);
            
            // Check the password format
            $this->checkPasswordFormat($password);
            // Check if the user already exists
            $this->user_service->checkUserExistence($email, true);
            // Generate token
            $token = $this->token_service->generateToken(100);
            // Registra il nuovo utente
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            $check = $stmt->execute();
            // Store the token in the tokens table
            $this->token_service->storeToken($token, $email);
            // Send an email with the OTP
            $to = $email;
            $subject = 'Verify your email address';
            $message = "link for Otp: ".URL_REGISTER_PAGE."?email=".$email."&token=".$token;           
            $this->postman->send($email, $subject, $message);
            $stmt->close();

            return $this->sendResponse(['status' => 'success', 'message' => 'User registered successfully.'], 201);
        
        } catch (InvalidTokenException $e) {
            throw new RegistrationException;
            throw $e; // Lascia che ErrorHandler gestisca l'eccezione
        }catch (Exception $e) {
            throw $e; // Lascia che ErrorHandler gestisca l'eccezione
        }
      }

    public function verifyUser()
    {
        try{
            if($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['token']) || !isset($_GET['email']))
                throw new InvalidRequestException('Invalid request.');

            $receive_token = $_GET['token'];
            $email = $_GET['email'];
            
            // Check the email format
            $this->checkEmailFormat($email);
            // get the token from the database
            $this->token_service->checkToken($receive_token, $email, 'register');
            // Update the user's status to verified
            $this->user_service->setUserStatus($email, ACTIVE); 
            // Delete the token from the tokens table
            $this->token_service->deleteToken($email, 'register');

            return $this->sendResponse(['status' => 'success', 'message' => 'User verificated successfully.'], 201);
        }catch (Exception $e) {
            throw $e; // Lascia che ErrorHandler gestisca l'eccezione
        }  
    }

    public function initResetPassword()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['email']))
                throw new InvalidRequestException('Invalid request.');
            
            $email = $_POST['email'];
            // Check the email format
            $this->checkEmailFormat($email);
            // Check if the user already exists
            $this->user_service->checkUserExistence($email, true);
            // Generate token
            $token = $this->token_service->generateToken(100);
            // Store the token in the tokens table
            $this->token_service->storeToken($token, $email, 'reset');
            //Disable the user
            $this->user_service->setUserStatus($email, INACTIVE);            
            // Send email with token
            $to = $email;
            $subject = 'Password Reset Request';
            $message = "Your OTP for password reset:".URL_PSW_RST_PAGE."?email=".$email."&token=".$token;
        
            $this->postman->send($to, $subject, $message);
        
            return $this->sendResponse(['status' => 'success', 'message' => 'OTP sent to your email.'], 200);
        } catch (Exception $e) {
            throw $e; // Lascia che ErrorHandler gestisca l'eccezione
        }
    }
    
    public function resetPassword()
    {
        try{
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['token']) || !isset($_POST['email']) ||
            !isset($_POST['new_password']) || !isset($_POST['conf_new_password'])) {
            return $this->sendResponse(['status' => 'error', 'message' => 'Invalid request.'], 400);
            }
    
            $receive_token = $_POST['token'];
            $email = $_POST['email'];
            $new_password = $_POST['new_password'];
            $conf_new_password = $_POST['conf_new_password'];

            // Check the email format
            $this->checkEmailFormat($email);
            // Check the password format
            $this->checkPasswordFormat($new_password);
            $this->token_service->checkToken($receive_token, $email, 'reset');
            // Check if the user exists
            $this->user_service->checkUserExistence($email);
            // Check if the new password and confirm new password match
            if ($new_password !== $conf_new_password)
                throw new PasswordMismatchException('Passwords do not match.');
            // Hash the password
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            // Update the password
            $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            $executed = $stmt->execute();
            $stmt->close();
    
            if ($executed == false) {
                return $this->sendResponse(['status' => 'error', 'message' => 'Password reset failed.'], 500);
            }
            // Delete the token from the tokens table
            $this->token_service->deleteToken($email, 'reset');
            //ENABLE the user
            $this->user_service->setUserStatus($email, ACTIVE);

            return $this->sendResponse(['status' => 'success', 'message' => 'Password reset successfully.'], 200);
        } catch (Exception $e) {
            throw $e; // Lascia che ErrorHandler gestisca l'eccezione
        }
        
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
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['email']) || !isset($_POST['password']))
                throw new InvalidRequestException('Invalid request.');

            $email = $_POST['email'];
            $password = $_POST['password'];
    
            // Check the email format
            $this->checkEmailFormat($email);    
            // Controlla se l'utente esiste
            $stmt = $this->conn->prepare( "SELECT id, username, password, role, active FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
    
            if ($stmt->num_rows == 0) {
                $stmt->close();
                throw new UserNotFoundException('Invalid email or password.');
    
            }
            $stmt->bind_result($id, $username, $hashedPassword, $role, $status);
            $stmt->fetch();
            $stmt->close();
    
            if ($status == INACTIVE)
                throw new UserNotFoundException('User is not verified.');

            //Check if the user is timeouted
            $this->user_service->checkLoginAttempts($email);
    
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

        } catch (Exception $e) {
            // Login failed, update the login attempts
            $this->user_service->updateLoginAttempts($email);
            //if the user is timeouted, the function will throw an exception and return, so the previous exception will not be thrown
            throw $e; // Lascia che ErrorHandler gestisca l'eccezione
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