<!DOCTYPE html>
<html lang="en">
<head>
    <title>Homepage</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="./Frontend/imgs/icon.ico">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./Frontend/css/main.css">
    <style>
    
    .bgimg-1 {
        background-image: url('./Frontend/imgs/confirmation.jpg');
    }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../includes/navbar.php'; ?>
    <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
    <div class="w3-display-left w3-text-white" id="confirmationContentId" style="padding:48px">
    </div>
    </header>
    <script src="./Frontend/js/verify_user.js"></script>
    <script src="./Frontend/js/menu.js"></script>
</body>
</html>