<?php 
    require_once __DIR__ . '/../../router.php';
    $current_page = Router::getInstance()->getCurrentPage();
?>

<div class="w3-top">
    <div class="w3-bar w3-black w3-card w3-animate-bottom" id="myNavbar">
        <a href="/" class="w3-bar-item w3-button w3-wide">
            <img src="./Frontend/imgs/icon.png" alt="Icon" style="width:30px; height:30px; vertical-align:middle; margin-right:5px;">
            Novel Archive
        </a>
        <div class="w3-right w3-hide-small">
            <?php
                switch($current_page) {
                    case 'homepage':
                        echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                                <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-usd"></i> PRICING</a>
                                <a href="/login" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
                                <a href="/register" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
                                <a href="#team" class="w3-bar-item w3-button"><i class="fa fa-user"></i> TEAM</a>';
                    break;
                    
                    case 'login':
                            echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                            <a href="/register" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>';
                    break;
                    case 'register':
                        echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                        <a href="/login" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>';
                    break;
                    case 'logout':
                        echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                        <a href="/login" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
                        <a href="/register" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>';
                    break;
                    default:                
                        echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>';
                    break;
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
        switch($current_page) {
            case 'homepage':
                echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                      <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-usd"></i> PRICING</a>
                      <a href="/login" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
                      <a href="/register" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
                      <a href="#team" class="w3-bar-item w3-button"><i class="fa fa-user"></i> TEAM</a>';
            break;

            case 'login':
                    echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                    <a href="/register" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>';
            break;
            case 'register':
                echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                <a href="/login" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>';
            break;
            case 'logout':
                echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                <a href="/login" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
                <a href="/register" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>';
            break;
            default:                
                echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>';
            break;
            }
        ?>
</nav>