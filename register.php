<?php
include 'db/db.php';

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
    <style>
        body,h1,h2,h3,h4,h5,h6 {
            font-family: "Raleway Bold", sans-serif;
        
        }

        body, html {
            height: 100%;
            line-height: 1.8;
        }

    
        .bgimg-1 {
            background-position: center;
            background-size: cover;
            background-image: url('imgs/register.jpg');
            min-height: 100%;;
        }

        .w3-bar .w3-button {
            padding: 16px;
        }
        .w3-animate-bottom {
        animation-duration: 1s; 
        animation-fill-mode: forwards; 
        opacity: 0; 
        }


        .w3-animate-delay-1 {
            animation-delay: 0.5s;  
        }

        .w3-animate-delay-2 {
            animation-delay: 1s;  
        }

        .w3-animate-delay-3 {
            animation-delay: 1.5s; 
        }
    </style>
</head>
 
<body>
<div class="w3-top">
        <div class="w3-bar w3-black w3-card w3-animate-bottom " id="myNavbar">
            <a href="index.php" class="w3-bar-item w3-button w3-wide">
            <img src="imgs/icon.png" alt="Icon" style="width:30px; height:30px; vertical-align:middle; margin-right:5px;">
            Novel Archive</a>
            <div class="w3-right w3-hide-small">
            <a href="index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
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
        <a href="index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
        <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
        <a href="register.php" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
    </nav>
    <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
    <div class="w3-display-left w3-text-white" style="padding:48px">
        <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-">Registration Form</span><br>
        <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom" >Registration Form</span><br>
        <span class="w3-large w3-animate-bottom">Join us! Insert your credential to get access to our service</span>
        <form method="POST" action="" class="w3-animate-bottom">
            <input type="text" class="w3-input w3-border" name="username" placeholder="Username" required><br>
            <input type="email"  class="w3-input w3-border" name="email" placeholder="Email" required><br>
            <input type="password"  class="w3-input w3-border" name="password" placeholder="Password" required><br>
            <button class="w3-button w3-black" type="submit"><i class="fa fa-user-plus"></i> REGISTER</button>
        </form>
    <script>
    // Used to toggle the menu on small screens when clicking on the menu button
    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
        } else {
            mySidebar.style.display = 'block';
        }
    }

    // Close the sidebar with the close button
    function w3_close() {
        const mySidebar = document.getElementById("mySidebar");
            mySidebar.style.display = "none";
        }
    </script>
</body>
</html>
