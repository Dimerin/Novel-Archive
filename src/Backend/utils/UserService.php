<?php

class UserService
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function checkUserExistence($email)
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

    public function setUserStatus($email, $status)
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

    public function updateUserRole($id, $newRole)
    {
        $stmt = $this->conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $id);
        $executed = $stmt->execute();
        $stmt->close();
        return $executed;
    }
}
