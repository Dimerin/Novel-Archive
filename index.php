<!DOCTYPE html>
<html lang="en">
<head>
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
        background-image: url('imgs/bg.jpg');
        min-height: 100%;
    }

    .w3-bar .w3-button {
        padding: 16px;
    }

    .inline-link {
        display: inline-block;
        margin-right: 10px; 
    }
    
  .w3-dark-red {
        background-color: #4a1c1c; 
        color: white;
  }
</style>

</head>
<body>
    <div class="w3-top">
        <div class="w3-bar w3-black w3-card w3-animate-bottom " id="myNavbar">
            <a href="#home" class="w3-bar-item w3-button w3-wide">
            <img src="imgs/icon.png" alt="Icon" style="width:30px; height:30px; vertical-align:middle; margin-right:5px;">
            Novel Archive</a>
            <div class="w3-right w3-hide-small">
            <a href="#home" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
            <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-usd"></i> PRICING</a>
            <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
            <a href="register.php" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
            <a href="#team" class="w3-bar-item w3-button"><i class="fa fa-user"></i> TEAM</a>
    </div>

    <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="w3_open()">
      <i class="fa fa-bars"></i>
    </a>
    </div>
    </div>
    <nav class="w3-sidebar w3-bar-block w3-black w3-card w3-animate-left w3-hide-medium w3-hide-large" style="display:none" id="mySidebar">
        <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button w3-large w3-padding-16">Close ×</a>
        <a href="index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i> HOME</a>
        <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-usd"></i> PRICING</a>
        <a href="login.php" class="w3-bar-item w3-button"><i class="fa fa-sign-in"></i> LOGIN</a>
        <a href="register.php" class="w3-bar-item w3-button"><i class="fa fa-user-plus"></i> REGISTER</a>
        <a href="#team" class="w3-bar-item w3-button"><i class="fa fa-user"></i> TEAM</a>
    </nav>
    <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
    <div class="w3-display-left w3-text-white" style="padding:48px">
        <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-">Novel Archive</span><br>
        <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom" >Novel Archive</span><br>
        <span class="w3-large w3-animate-bottom">Stop wasting valuable time with projects that just isn't you.</span>
        <p>
        <a href="login.php" class="w3-button w3-white w3-padding-large w3-large w3-margin-top w3-hover-opacity-on inline-link w3-animate-bottom">LOGIN</a>
        <a href="register.php" class="w3-button w3-white w3-padding-large w3-large w3-margin-top w3-hover-opacity-on inline-link w3-animate-bottom">REGISTER</a>
        </p>
  </div> 
  </header>
    <!-- Pricing Section -->
    <div class="w3-container w3-center w3-dark-red" style="padding:128px 16px" id="pricing">
    <h3>PRICING</h3>
    <p class="w3-large">Choose a pricing plan that fits your needs.</p>
    <div class="w3-row-padding w3-center" style="margin-top:64px">
        <div class="w3-half w3-padding">
        <ul class="w3-ul w3-white w3-hover-shadow w3-margin">
            <li class="w3-black w3-xlarge w3-padding-32">Free</li>
            <li class="w3-padding-16"><b>500 MB</b> Novels storage</li>
            <li class="w3-padding-16"><b>10</b> Novels downloads</li>
            <li class="w3-padding-16"><b>Limited</b> Support</li>
            <li class="w3-padding-16">
            <h2 class="w3-wide">$ 0</h2>
            <span class="w3-opacity">per month</span>
            </li>
            <li class="w3-light-grey w3-padding-24">
            <button class="w3-button w3-black w3-padding-large">Sign Up</button>
            </li>
        </ul>
        </div>
        <div class="w3-half w3-padding">
        <ul class="w3-ul w3-white w3-hover-shadow w3-margin">
            <li class="w3-teal w3-xlarge w3-padding-32">Premium</li>
            <li class="w3-padding-16"><b>25GB</b> Novels storage</li>
            <li class="w3-padding-16"><b>Unlimited</b> Novels downloads</li>
            <li class="w3-padding-16"><b>Endless</b> Support</li>
            <li class="w3-padding-16">
            <h2 class="w3-wide">$ 5</h2>
            <span class="w3-opacity">per month</span>
            </li>
            <li class="w3-light-grey w3-padding-24">
            <button class="w3-button w3-black w3-padding-large">Sign Up</button>
            </li>
        </ul>
        </div>
    </div>
</div>
  <!-- Team Section -->
<div class="w3-container" style="padding:128px 16px" id="team">
    <h3 class="w3-center">THE TEAM</h3>
    <p class="w3-center w3-large">The ones who developed this project</p>
    <div class="w3-row-padding w3-grayscale" style="margin-top:64px">
        <div class="w3-third w3-margin-bottom w3-center">
            <div class="w3-card">
                <img src="./imgs/tommaso.png" alt="Tommaso" style="width:60%">
                <div class="w3-container">
                    <h3 class="w3-center">Tommaso Califano</h3>
                    <p class="w3-opacity w3-center">Computer engineering student</p>
                </div>
            </div>
        </div>
        <div class="w3-third w3-margin-bottom w3-center">
            <div class="w3-card">
                <img src="./imgs/nicola.png" alt="Nicola" style="width:60%">
                <div class="w3-container">
                <h3 class="w3-center">Nicola Ramacciotti</h3>
                <p class="w3-opacity w3-center">Computer engineering student</p>
                </div>
            </div>
        </div>
        <div class="w3-third w3-margin-bottom w3-center">
            <div class="w3-card">
                <img src="./imgs/gabriele.png" alt="Gabriele" style="width:60%">
                <div class="w3-container">
                <h3 class="w3-center">Gabriele Suma</h3>
                    <p class="w3-opacity w3-center">Computer engineering student</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->
<footer class="w3-center w3-black w3-padding-64">
  <a href="#home" class="w3-button w3-light-grey"><i class="fa fa-arrow-up w3-margin-right"></i>To the top</a>
  <div class="w3-xlarge w3-section">
    <img src="imgs/cherubino_white.png" alt="cherubino" style="width:100px; height:100px; vertical-align:middle; margin-right:5px;">
  </div>
  <p>Powered by <a href="https://www.ing.unipi.it/it/" title="DII" target="_blank" class="w3-hover-text-green">Università di Pisa</a></p>
</footer>
  <script>
    function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
    } else {
        mySidebar.style.display = 'block';
    }
    }

    // Close the sidebar with the close button
    function w3_close() {
        mySidebar.style.display = "none";
    }
    </script>
</body>
</html>
