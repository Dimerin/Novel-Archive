<?php
require_once '../db/db.php';

$db = new Database();
$conn = $db->getConnection();

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

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgotten password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<style>
    .bgimg-1 {
        background-image: url('../imgs/fpsw.jpg');
    }
</style>
<body>
    <?php include '../includes/navbar.php'; ?>
    <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
        <div class="w3-display-left w3-text-white" style="padding:48px">
            <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-">Reset your password</span><br>
            <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom" >Login</span><br>
            <span class="w3-large w3-animate-bottom">Please, insert your email to reset your password.</span>
        <form method="POST" action="">
            <input type="email" class="w3-input w3-border w3-animate-bottom" name="email" placeholder="Email" required><br>
            <button type="submit" class="w3-button w3-black w3-animate-bottom"><i class="fa fa-envelope"></i> Send Reset Link</button>
        </form>
        </div>
    </header>
    <script src="../js/menu.js"></script>
</body>
</html>