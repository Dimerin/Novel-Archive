<?php
include '/db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Token and expiration setup
    $token = bin2hex(random_bytes(50));
    $token_expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $conn->prepare("UPDATE users SET token = ?, token_expire = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $token_expire, $email);

    if ($stmt->execute()) {
        // Send email logic here 
        echo "Password reset link has been sent to your email.";
    } else {
        echo "No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
