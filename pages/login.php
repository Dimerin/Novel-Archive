<?php
require_once '../db/db.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['user_id'] = $user_id;
            header("Location: dashboard.php");
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
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
            background-image: url('../imgs/login.jpg');
        }       
    </style>
</head>
 
<body>
<div class="w3-top">
        <div class="w3-bar w3-black w3-card w3-animate-bottom " id="myNavbar">
            <a href="../index.php" class="w3-bar-item w3-button w3-wide">
            <img src="../imgs/icon.png" alt="Icon" style="width:30px; height:30px; vertical-align:middle; margin-right:5px;">
            Novel Archive</a>
            <div class="w3-right w3-hide-small">
            <a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="#home" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
            <a href="register.php" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
    </div>
    <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="w3_open()">
      <i class="fa fa-bars"></i>
    </a>
    </div>
    </div>
    <nav class="w3-sidebar w3-bar-block w3-black w3-card w3-animate-left w3-hide-medium w3-hide-large" style="display:none" id="mySidebar">
        <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button w3-large w3-padding-16">Close ×</a>
        <a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
        <a href="#home" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
        <a href="register.php" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
    </nav>
    <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
    <div class="w3-display-left w3-text-white" style="padding:48px">
        <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-">Login</span><br>
        <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom" >Login</span><br>
        <span class="w3-large w3-animate-bottom">Insert you email and password to access.</span>
        <form method="POST" action="" class="w3-animate-bottom">
            <input type="email"  class="w3-input w3-border" name="email" placeholder="Email" required><br>
            <input type="password"  class="w3-input w3-border" name="password" placeholder="Password" required><br>
            <button class="w3-button w3-black w3-animate-bottom" type="submit"><i class="fa fa-sign-in"></i> LOGIN</button>
            <a href="forgot_password.php"  class="w3-animate-bottom">Forgot password?</a>
        </form>
       
  </div> 
  </header>
  <script src="../js/menu.js"></script>
</body>
</html>
