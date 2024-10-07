<?php
require_once '../db/db.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .bgimg-1 {
            background-image: url('../imgs/register.jpg');
        }

    </style>
</head>
 
<body>
<?php include '../includes/navbar.php'; ?>
    <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
    <div class="w3-display-left w3-text-white" style="padding:48px">
        <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-">Registration Form</span><br>
        <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom" >Registration Form</span><br>
        <span class="w3-large w3-animate-bottom">Join us! Insert your credential to get access to our service</span>
        <form method="POST" action="" class="w3-animate-bottom">
            <input type="text" class="w3-input w3-border" name="username" placeholder="Username" required><br>
            <input type="email"  class="w3-input w3-border" name="email" placeholder="Email" required><br>
            <input type="password"  class="w3-input w3-border" name="password" placeholder="Password" required><br>
            <button class="w3-button w3-black w3-animate-bottom" type="submit"><i class="fa fa-user-plus"></i> REGISTER</button>
        </form>
    <script src="../js/menu.js"></script>
</body>
</html>
