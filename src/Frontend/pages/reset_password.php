<?php
require_once '../db/db.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ?, token = NULL WHERE token = ?");
        $stmt->bind_param("ss", $new_password, $token);

        if ($stmt->execute()) {
            echo "Password has been reset.";
        } else {
            echo "Error resetting password.";
        }

        $stmt->close();
    }
} else {
    echo "Invalid token.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST" action="">
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
