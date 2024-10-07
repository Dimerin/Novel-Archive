<div class="w3-top">
<div class="w3-bar w3-black w3-card w3-animate-bottom " id="myNavbar">
    <a href="homepage.php" class="w3-bar-item w3-button w3-wide">
    <img src="../imgs/icon.png" alt="Icon" style="width:30px; height:30px; vertical-align:middle; margin-right:5px;">
    Novel Archive</a>
    <div class="w3-right w3-hide-small">
        <?php
        if(basename($_SERVER['PHP_SELF']) == 'homepage.php') { 
            echo ' <a href="#home" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME </a>
                <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-usd"></i> PRICING</a>
                <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
                <a href="register.php" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
                <a href="#team" class="w3-bar-item w3-button"><i class="fa fa-user"></i> TEAM</a>';
        }
        else if(basename($_SERVER['PHP_SELF']) == 'login.php') {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="register.php" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>';
        }
        else if(basename($_SERVER['PHP_SELF']) == 'register.php') {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>';
        }
        else if(basename($_SERVER['PHP_SELF']) == 'dashboard.php') {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-sign-out"></i> LOGOUT</a>';
        }
        else if(basename($_SERVER['PHP_SELF']) == 'forgot_password.php') {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>';
        }
        else if(basename($_SERVER['PHP_SELF']) == 'upload_file.php')  {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
             <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>';
        }
        ?>
    </div>
<a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="w3_open()">
<i class="fa fa-bars"></i>
</a>
</div>
</div>
<nav class="w3-sidebar w3-bar-block w3-black w3-card w3-animate-left w3-hide-medium w3-hide-large" style="display:none" id="mySidebar">
<a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button w3-large w3-padding-16">Close ×</a>
<?php
        if(basename($_SERVER['PHP_SELF']) == 'homepage.php') { 
            echo ' <a href="#home" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME </a>
                <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-usd"></i> PRICING</a>
                <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
                <a href="register.php" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
                <a href="#team" class="w3-bar-item w3-button"><i class="fa fa-user"></i> TEAM</a>';
        }
        else if(basename($_SERVER['PHP_SELF']) == 'login.php') {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="register.php" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>';
        }
        else if(basename($_SERVER['PHP_SELF']) == 'register.php') {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>';
        }
        else if(basename($_SERVER['PHP_SELF']) == 'dashboard.php') {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-sign-out"></i> LOGOUT</a>';
        }
        else if(basename($_SERVER['PHP_SELF']) == 'forgot_password.php') {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>';
        }
        
        else if(basename($_SERVER['PHP_SELF']) == 'upload_file.php')  {
            echo '<a href="../index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
             <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>';
        }
        ?>
</nav>
