<div class="w3-top">
    <div class="w3-bar w3-black w3-card w3-animate-bottom" id="myNavbar">
        <a href="/" class="w3-bar-item w3-button w3-wide">
            <img src="./Frontend/imgs/icon.png" alt="Icon" style="width:30px; height:30px; vertical-align:middle; margin-right:5px;">
            Novel Archive
        </a>
        <div class="w3-right w3-hide-small">
            <?php
            if ($current_page == 'homepage') {
                echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                      <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-usd"></i> PRICING</a>
                      <a href="/login" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
                      <a href="/register" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
                      <a href="#team" class="w3-bar-item w3-button"><i class="fa fa-user"></i> TEAM</a>';
            } elseif ($current_page == 'login') {
                echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                      <a href="/register" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>';
            } elseif ($current_page == 'register') {
                echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
                      <a href="/login" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>';
            } else {
                echo '<a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>';
            }
            ?>
        </div>
        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="w3_open()">
            <i class="fa fa-bars"></i>
        </a>
    </div>
</div>
