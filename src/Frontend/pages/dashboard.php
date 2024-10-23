<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["username"])) {
        header("Location: /login");
        exit();
    }
 ?>
<!DOCTYPE html>
<html>
<head>
<div>
<title>Dashboard</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="./Frontend/imgs/icon.ico">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./Frontend/css/main.css">
<link rel="stylesheet" href="./Frontend/css/toast.css">
<link rel="stylesheet" href="./Frontend/css/book.css">
<script src="./Frontend/js/menu.js"></script>
<script src="./Frontend/js/logout.js"></script>
<script src="./Frontend/js/upload_file.js"></script>
<script src="./Frontend/js/dashboard.js"></script>


<style>
        .bgimg-1 {
            background-image: url('./Frontend/imgs/dash.jpg');
        }
      
</style>
</head>

<body>
    <?php require __DIR__ . '/../includes/navbar.php';?>
    <div class="w3-main bgimg-1" id="mainContent">       
    </div>
</body>
</html>