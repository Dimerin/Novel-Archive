<?php

const MAX_LOGIN_ATTEMPTS = 3;
const TRY_INTERVAL_TIME = 5;
const TIMEOUT_TIME = 5;
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

    private function differenceInMinutes($first_dt, $second_dt)
    {
        $interval = $first_dt->diff($second_dt);
        $interval_seconds = $interval->i * 60 + $interval->s; // Convert the difference to seconds
        return $interval_seconds / 60; // Convert the difference to minutes
    }

    private function getLoginParams($email)
    {
        $stmt = $this->conn->prepare("SELECT first_attempt, last_attempt, timeouted, attempts FROM login_attempts WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($first_attempt, $last_attempt, $timeouted, $attempts);
        $stmt->fetch();
        

        // If the user has never logged in, return default values
        if ($stmt->num_rows == 0)
        {
            $stmt->close();
            return [
                'try_time' => 0,
                'timeout_time' => 0,
                'timeouted' => false,
                'attempts' => 0
            ];
        }     

        // Convert the timestamps to DateTime objects
        $first_attempt_dt = new DateTime($first_attempt);
        $last_attempt_dt = new DateTime($last_attempt);
        $currentDateTime = new DateTime('now', new DateTimeZone('Europe/Rome'));

        // Calculate the time between the first and last login attempt
        $try_time = differenceInMinutes($first_attempt_dt, $last_attempt_dt);
        $timeout_time = differenceInMinutes($last_attempt_dt, $currentDateTime);

        return [
            'try_time' => $try_time,
            'timeout_time' => $timeout_time,
            'timeouted' => $timeouted,
            'attempts' => $attempts
        ];
    }

    public function checkLoginAttempts($email)
    {
        // Get the login parameters
        $params = $this->getLoginParams($email);

        // If the user has been timed out, reset the login attempts if the timeout time has passed
        if($params['timeouted'] == true && $params['timeout_time'] < TIMEOUT_TIME)
            throw new TooManyLoginAttemptsException("Too many login attempts.");
        else if($params['timeouted'] == true && $params['timeout_time'] >= TIMEOUT_TIME)
        {
            $stmt = $this->conn->prepare("UPDATE login_attempts SET timeouted = 0, attempts = 1, first_attempt = NOW(), last_attempt = NOW() WHERE email = ?");
            $stmt->bind_param("s", $email);
            $executed = $stmt->execute();
            $stmt->close();
            if (!$executed)
                throw new DatabaseException("Error updating login attempts.");
            return;
        }
           
    }

    public function updateLoginAttempts($email)
    {
        //get Login parameters
        $params = $this->getLoginParams($email);
        error_log("updateLoginAttempts chiamato con email: $email");

        $params['attempts'] +=1;
        // The attempt is registered only if the last attempt was made more than 5 minutes ago
        if($params['try_time'] < TRY_INTERVAL_TIME && $params['try_time'] > 0 && $params['attempts'] >= MAX_LOGIN_ATTEMPTS)
        {
            // Set the user as timed out
            $stmt = $this->conn->prepare("UPDATE login_attempts SET timeouted = 1, last_attempt = NOW() WHERE email = ?");
            $stmt->bind_param("s", $email);
            $executed = $stmt->execute();
            $stmt->close();
            if (!$executed)
                throw new DatabaseException("Error updating login attempts.");

            throw new TooManyLoginAttemptsException("Too many login attempts.");
        }else{
            // Update the login attempts
            $stmt = $this->conn->prepare("INSERT INTO login_attempts (email, attempts) VALUES (?, 1)
                ON DUPLICATE KEY UPDATE attempts = attempts + 1, last_attempt = NOW()");
            $stmt->bind_param("s", $email);
            $executed = $stmt->execute();
            $stmt->close();
            if (!$executed)
                throw new DatabaseException("Error updating login attempts.");
        }
        
    }


}
