<!DOCTYPE html>
<html>
    <head>
    <title>Dashboard</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./Frontend/css/toast.css">
        <link rel="icon" href="./Frontend/imgs/icon.ico">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="./Frontend/css/main.css">
        <link rel="stylesheet" href="./Frontend/css/book.css">
        <link rel="stylesheet" href="./Frontend/css/dashboard.css">
        <script src="./Frontend/js/navbar.js"></script>
        <script src="./Frontend/js/logout.js"></script>
        <script src="./Frontend/js/upload_file.js"></script>
        <?php if ($_SESSION['role'] == 'admin') : ?>
            <script src="./Frontend/js/dashboard_admin.js"></script>
        <?php else : ?>
            <script src="./Frontend/js/dashboard_user.js"></script>
        <?php endif; ?>

    </head>

<body>
    <?php require __DIR__ . '/../includes/navbar.php';?>
    <div class="toast-container">
        <ul class="notifications"></ul>
    </div>
    <input type="hidden" name="csrf" id="csrf" value="<?php echo $_SESSION['csrf_token']; ?>">
    <div class="w3-main bgimg-1" id="mainContent">       
    </div>
</body>
</html>