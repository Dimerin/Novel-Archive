<?php
    //echo __DIR__; // path: /var/www/html/Backend/api
    require_once __DIR__. '/../db/db.php';

    $db = new Database();
    $conn = $db->getConnection();

    // TODO: controllare se utente esiste già e varie 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $response = json_encode(
                ['status' => 'success', 'message' => 'User registered successfully.']
            );
            
        } else {
            $response = json_encode(
                ['status' => 'error', 'message' => 'Registration failed.']
            );
        }

        $stmt->close();
        header('Content-Type: application/json');
        echo $response;
    }
?>