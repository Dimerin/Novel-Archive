<?php 
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION["username"])) {
            header("Location: /dashboard");
            exit();
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./Frontend/css/main.css">
    <link rel="stylesheet" href="./Frontend/css/toast.css">
    <link rel="icon" href="./Frontend/imgs/icon.ico">
    <style>
        .bgimg-1 {
            background-image: url('./Frontend/imgs/register.jpg');
        }

    </style>
    <script src="./Frontend/js/register.js"></script>
</head>
 
<body>
    <div class="toast-container">
        <ul class="notifications"></ul>
    </div>
    <?php require __DIR__ . '/../includes/navbar.php'; ?>
    <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
    <div class="w3-display-left w3-text-white" style="padding: 48px">
        <span class="w3-jumbo w3-hide-small w3-animate-bottom">Registration</span><br>
        <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom" >Registration</span><br>
        <span class="w3-large w3-animate-bottom">Insert your credentials to create your account.</span>
        <form id="registerForm" class="w3-animate-bottom">
            <input type="text" class="w3-input w3-border" name="username" placeholder="Username" required><br>
            <input type="email"  class="w3-input w3-border" name="email" placeholder="Email" required><br>
            <input type="password"  class="w3-input w3-border" name="password" placeholder="Password" required><br>
            <button class="w3-button w3-black w3-animate-bottom" type="submit"><i class="fa fa-user-plus"></i> REGISTER</button>
        </form>
    </div>
    </header>
    <script src="./Frontend/js/menu.js"></script>
    <script src="./Frontend/js/toast.js"></script>
</body>
</html>
