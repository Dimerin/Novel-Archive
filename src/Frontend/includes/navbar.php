<?php 
    require_once __DIR__ . '/../../router.php';
    $current_page = Router::getInstance()->getCurrentPage();

?>

<div class="w3-top">
    <div class="w3-bar w3-black w3-card w3-animate-bottom" id="myNavbar">
        <a href="/" class="w3-bar-item w3-button w3-wide" id="HomeLink">
            <img src="./Frontend/imgs/icon.png" alt="Icon" style="width:30px; height:30px; vertical-align:middle; margin-right:5px;">
            Novel Archive
        </a>
        <div class="w3-right w3-hide-medium w3-hide-small">
            <?php
                switch($current_page) {
                    case 'homepage':
                        echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                                <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-usd"></i> PRICING</a>
                                <a href="/login" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
                                <a href="/register" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
                                <a href="#team" class="w3-bar-item w3-button"><i class="fa fa-user"></i> TEAM</a>';
                    break;
                    case 'dashboard':

                        echo '<a href="#" id="username" class="w3-button w3-bar-item"><b>' . htmlspecialchars($_SESSION['username']) . '</b></a>
                              <a href="#" class="w3-button w3-bar-item" id="role"><b>';
                        switch ($_SESSION['role']) {
                            case 'free':
                                echo '<span style="color: lightgreen;">Free Plan</span>';
                                break;
                            case 'pro':
                                echo '<span style="color: yellow;">Pro Plan</span>';
                                break;
                            case 'admin':
                                echo '<span style="color: red;">Admin</span>';
                                break;
                            default:
                                echo '<span style="color: black;">Unknown Role</span>';
                                break;
                        }
                        echo '</b></a>
                              <a href="#" class="w3-bar-item w3-button" id="catalogueLink"><i class="fa fa-th-list fa-fw"></i> CATALOGUE</a>
                              <a href="#" class="w3-bar-item w3-button" id="uploadFileLink"><i class="fa fa-upload fa-fw"></i> UPLOAD NOVEL</a>';
                        if ($_SESSION['role'] === 'admin') {
                            echo '<a href="#" class="w3-bar-item w3-button" id="adminPageLink"><i class="fa fa-users fa-fw" aria-hidden="true"></i> MANAGE USERS</a>';
                        }
                        echo '<a href="#" class="w3-bar-item w3-button" onclick="logoutUser()"><i class="fa fa-sign-out fa-fw"></i> LOGOUT</a>';
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
        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large" onclick="w3_open()">
            <i class="fa fa-bars"></i>
        </a>
    </div>
</div>
<nav class="w3-sidebar w3-bar-block w3-black w3-card w3-animate-left w3-hide-large" style="display:none" id="mySidebar">
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
            case 'dashboard':

                echo '<a href="#" id="username" class="w3-button w3-bar-item"><b>User: ' . htmlspecialchars($_SESSION['username']) . '</b></a>
                      <a href="#" class="w3-button w3-bar-item" id="role"><b>';
                switch ($_SESSION['role']) {
                    case 'free':
                        echo '<span style="color: lightgreen;">Free Plan</span>';
                        break;
                    case 'pro':
                        echo '<span style="color: yellow;">Pro Plan</span>';
                        break;
                    case 'admin':
                        echo '<span style="color: red;">Admin User</span>';
                        break;
                    default:
                        echo '<span style="color: black;">Unknown Role</span>';
                        break;
                }
                echo '</b></a>
                      <a href="#" onclick="w3_close()" class="w3-bar-item w3-button" id="catalogueLink-mobile"><i class="fa fa-th-list fa-fw"></i> CATALOGUE</a>
                      <a href="#" onclick="w3_close()" class="w3-bar-item w3-button" id="uploadFileLink-mobile"><i class="fa fa-upload fa-fw"></i> UPLOAD NOVEL</a>';
                if ($_SESSION['role'] === 'admin') {
                    echo '<a href="#" onclick="w3_close()" class="w3-bar-item w3-button" id="adminPageLink-mobile"><i class="fa fa-users fa-fw" aria-hidden="true"></i> MANAGE USERS</a>';
                }
                echo '<a href="#" class="w3-bar-item w3-button" onclick="logoutUser()"><i class="fa fa-sign-out fa-fw"></i> LOGOUT</a>';
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