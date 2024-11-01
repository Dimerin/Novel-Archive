<?php

const MAX_LOGIN_ATTEMPTS = 3;

class UserService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Return error if the user is not found, if reversed is true return error if the user is found
    public function checkUserExistence($email, $reversed = false)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $rows_check = $stmt->num_rows > 0;
        $stmt->close();

        if ($reversed && $rows_check)
            throw new UserNotFoundException("User already exists.");
        else if (!$reversed && !$rows_check)
            throw new UserNotFoundException("User not found.");
    }

    public function setUserStatus($email, $status)
    {
        // Aggiorna lo stato dell'utente
        $stmt = $this->conn->prepare("UPDATE users SET active = ? WHERE email = ?");
        $stmt->bind_param("is", $status, $email);
        $executed = $stmt->execute();
        $stmt->store_result();
        
        $rows_check = $stmt->affected_rows === 1;   
        $stmt->close();

        if (!$rows_check || !$executed)
            throw new DatabaseException("Error updating user status.");
    }

    public function updateUserRole($id, $newRole)
    {
        $stmt = $this->conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $id);
        $executed = $stmt->execute();
        $stmt->close();
        return $executed;
    }

    public function trackLoginAttempts($email)
    {


        // Check if the user has reached the maximum number of login attempts
        $stmt = $this->conn->prepare("SELECT attemps, last_attempt FROM login_attempts WHERE email = ?");
        $stmt->bind_param("ss", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($attempts, $last_attempt);
        $stmt->close();

        if ($attempts>= MAX_LOGIN_ATTEMPTS && strtotime($last_attempt) > strtotime('-2 minute'))
            throw new TooManyLoginAttemptsException("Too many login attempts.");
        else
            // Update the login attempts
            $stmt = $this->conn->prepare("INSERT INTO login_attempts (email, attemps, last_attempt) VALUES (?, 1, NOW())
                                            ON DUPLICATE KEY UPDATE attemps = attemps + 1, last_attempt = NOW()");
            $stmt->bind_param("s", $email);
            $executed = $stmt->execute();
            $stmt->close();
            if (!$executed)
            throw new DatabaseException("Error updating login attempts.");
    }


}
