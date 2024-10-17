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
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./Frontend/css/main.css">
<link rel="stylesheet" href="./Frontend/css/toast.css">
<script src="./Frontend/js/menu.js"></script>
<script src="./Frontend/js/logout.js"></script>
<script src="./Frontend/js/upload_file.js"></script>
<script src="./Frontend/js/dashboard.js"></script>

<style>
        .bgimg-1 {
            background-image: url('./Frontend/imgs/sidebar.jpg');
        }
        .bgimg-2{
            background-image: url('./Frontend/imgs/dash.jpg');
        }      
</style>
<!-- Sidebar/menu -->
</head>
<body>
    <nav class="w3-sidebar w3-collapse w3-black w3-animate-left bgimg-1" style="z-index:3;width:300px;" id="mySidebar"><br>
    <div class="w3-container">
        <a href="#" onclick="w3_close_dash()" class="w3-hide-large w3-right w3-jumbo w3-padding w3-hover-grey" title="close menu">
        <i class="fa fa-remove w3-xxlarge"></i>
        </a>
        <img src="./Frontend/imgs/icon.png" alt="Icon" style="width:35%; vertical-align:middle; margin-right:5px;">
        <h2><b>Novel Archive</b></h2><br><br>
        <h3 id="username"><b>User: <?php echo $_SESSION["username"]?></b></h3>
        <h4 class="w3-hover-opacity" id="role"><b><?php
            switch ($_SESSION["role"]) {
                case 'non-premium':
                    echo '<span style="color: lightgreen;">Free Plan</span>';
                    break;
                case 'premium':
                    echo '<span style="color: yellow;">Pro Plan</span>';
                    break;
                case 'admin':
                    echo '<span style="color: red;">Admin User</span>';
                    break;
                default:
                    echo '<span style="color: black;">Unknown Role</span>';
                    break;
            }
        ?></b></h4>
    </div>
    <div class="w3-bar-block">
        <a href="#" onclick="w3_close_dash();" id="catalogueLink" class="w3-bar-item w3-button w3-padding w3-white"><i class="fa fa-th-list fa-fw w3-margin-right"></i>CATALOGUE</a> 
        <a href="#" onclick="w3_close_dash()" id="uploadFileLink" class="w3-bar-item w3-button w3-padding"><i class="fa fa-upload fa-fw w3-margin-right"></i>UPLOAD NOVEL</a>
        <?php if($_SESSION["role"] == 'admin') { echo '
        <a href="#" onclick="w3_close_dash()" id="adminPageLink" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw w3-margin-right" aria-hidden="true"></i>MANAGE USERS</a>';
        } ?>
        <a href="#" onclick="logoutUser(); w3_close_dash();" class="w3-bar-item w3-button w3-padding"><i class="fa fa-sign-out fa-fw w3-margin-right"></i>LOGOUT</a>
    </div>

    </nav>
    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close_dash()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>
        <div class="w3-main bgimg-2" style="margin-left:300px" id="main">
            <span class="w3-button w3-hide-large w3-xxlarge w3-hover-text-grey" onclick="w3_open_dash()"><i class="fa fa-bars"></i></span>
        <!-- !PAGE CONTENT! -->
            <div class="w3-main " id="mainContent">
                <header id="dashboard">
                    <div class="w3-container">
                    <h1><b>Catalogue</b></h1>
                        <div class="w3-section w3-bottombar w3-padding-16">
                        <span class="w3-margin-right">Filter:</span> 
                        <button class="w3-button w3-black">Latest</button>
                        <button class="w3-button w3-white"><i class="fa fa-file-pdf-o w3-margin-right"></i>PDF</button>
                        <button class="w3-button w3-white w3-hide-small"><i class="fa fa-file-text-o w3-margin-right"></i>Text</button>
                        </div>
                </div>
                </header>
            
                <!-- First Novels Grid-->
                <div class="w3-row-padding bg">
                    <div class="w3-third w3-container w3-center w3-margin-bottom">
                    <img src="./Frontend/imgs/text-file.png" alt="Norway" style="width:20%" class="w3-hover-opacity">
                        <div class="w3-container">
                            <p><b>Lorem Ipsum</b></p>
                            <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
                        </div>
                        <button class="w3-button w3-black w3-margin-bottom">Download</button>
                        <button class="w3-button w3-black w3-margin-bottom">Read</button>
                    </div>
                    <div class="w3-third w3-container w3-center w3-margin-bottom">
                        <img src="./Frontend/imgs/text-file.png" alt="Norway" style="width:20%" class="w3-hover-opacity">
                            <div class="w3-container">
                                <p><b>Lorem Ipsum</b></p>
                                <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
                            </div>
                        <button class="w3-button w3-black w3-margin-bottom">Download</button>
                        <button class="w3-button w3-black w3-margin-bottom">Read</button>
                    </div>
                    <div class="w3-third w3-container w3-center w3-margin-bottom">
                        <img src="./Frontend/imgs/text-file.png" alt="Norway" style="width:20%" class="w3-hover-opacity">
                            <div class="w3-container">
                                <p><b>Lorem Ipsum</b></p>
                                <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
                            </div>
                        <button class="w3-button w3-black w3-margin-bottom">Download</button>
                        <button class="w3-button w3-black w3-margin-bottom">Read</button>
                    </div>
                </div>

                    <!-- Second Novels Grid-->
                <div class="w3-row-padding">
                    <div class="w3-third w3-container w3-center w3-margin-bottom">
                        <img src="./Frontend/imgs/pdf-file.png" alt="Norway" style="width:30%" class="w3-hover-opacity">
                            <div class="w3-container">
                                <p><b>Lorem Ipsum</b></p>
                                <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
                            </div>
                        <button class="w3-button w3-black w3-margin-bottom">Download</button>
                        <button class="w3-button w3-black w3-margin-bottom">Read</button>
                    </div>
                    <div class="w3-third w3-container w3-center w3-margin-bottom">
                        <img src="./Frontend/imgs/pdf-file.png" alt="Norway" style="width:30%" class="w3-hover-opacity">
                            <div class="w3-container">
                                <p><b>Lorem Ipsum</b></p>
                                <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
                            </div>
                            <button class="w3-button w3-black w3-margin-bottom">Download</button>
                            <button class="w3-button w3-black w3-margin-bottom">Read</button>
                    </div>
                    <div class="w3-third w3-container w3-center w3-margin-bottom">
                        <img src="./Frontend/imgs/pdf-file.png" alt="Norway" style="width:30%" class="w3-hover-opacity">
                            <div class="w3-container">
                                <p><b>Lorem Ipsum</b></p>
                                <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
                            </div>
                            <button class="w3-button w3-black w3-margin-bottom">Download</button>
                            <button class="w3-button w3-black w3-margin-bottom">Read</button>
                    </div>
                </div>
                    <!-- Pagination -->
                <div class="w3-center w3-padding-32">
                    <div class="w3-bar">
                    <a href="#" class="w3-bar-item w3-button w3-hover-black">«</a>
                    <a href="#" class="w3-bar-item w3-black w3-button">1</a>
                    <a href="#" class="w3-bar-item w3-button w3-hover-black">2</a>
                    <a href="#" class="w3-bar-item w3-button w3-hover-black">3</a>
                    <a href="#" class="w3-bar-item w3-button w3-hover-black">4</a>
                    <a href="#" class="w3-bar-item w3-button w3-hover-black">»</a>
                    </div>
                </div>
        </div>
    </div>
</body>
</html>