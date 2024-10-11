<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./Frontend/css/main.css">
    <style>
    
    .bgimg-1 {
        background-image: url('./Frontend/imgs/bg.jpg');
    }
    .bgimg-2 {
        background-image: url('./Frontend/imgs/pricing.jpg');
        color: white;
    }
    .bgimg-3 {       
        background-image: url('./Frontend/imgs/team.jpg');
    }  
    #home .scroll-arrows .fa {
        color: white;
    }
    @media (max-width: 768px) {
        .scroll-arrows {
            display: none;
        }
    }
   
</style>

</head>
<body>
    <?php require __DIR__ . '/../includes/navbar.php'; ?>
    <header class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
    <div class="w3-display-left w3-text-white" style="padding:48px">
        <span class="w3-jumbo w3-hide-small w3-animate-bottom w3-animate-delay-1">Novel Archive</span><br>
        <span class="w3-xxlarge w3-hide-large w3-hide-medium w3-animate-bottom w3-animate-delay-1">Novel Archive</span><br>
        <span class="w3-xlarge w3-animate-bottom w3-animate-delay-2">A place to inspire and be inspired</span>
        <p class="w3-animate-bottom w3-animate-delay-3">
            <a href="./Backend/pages/login" class="w3-button w3-white w3-padding-large w3-large w3-margin-top w3-hover-opacity-on inline-link">LOGIN</a>
            <a href="./Backend/pages/register" class="w3-button w3-white w3-padding-large w3-large w3-margin-top w3-hover-opacity-on inline-link">REGISTER</a>
        </p>
    </div>
    <div class="scroll-arrows">
        <i class="fa fa-arrow-down" onclick="scrollToSection('down')"></i>
    </div>
  </header>
    <!-- Pricing Section -->
    <div class="w3-container w3-center bgimg-2" style="padding:128px 16px; position: relative;" id="pricing">
        <h3>PRICING</h3>
        <p class="w3-large">Choose a pricing plan that fits your needs.</p>
        <div class="w3-row-padding w3-center" style="margin-top:46px">
            <div class="w3-half w3-padding-small">
                <ul class="w3-ul w3-white w3-hover-shadow w3-margin">
                    <li class="w3-khaki w3-xlarge w3-padding-32">Free</li>
                    <li class="w3-padding-12"><b>500 MB</b> Novels storage</li>
                    <li class="w3-padding-12"><b>10</b> Novels downloads</li>
                    <li class="w3-padding-12"><b>Limited</b> Support</li>
                    <li class="w3-padding-12">
                        <h2 class="w3-wide">$ 0</h2>
                        <span class="w3-opacity">per month</span>
                    </li>
                    <li class="w3-light-grey w3-padding-24">
                        <button class="w3-button w3-black w3-padding-large">Sign Up</button>
                    </li>
                </ul>
            </div>
            <div class="w3-half w3-padding-small">
                <ul class="w3-ul w3-white w3-hover-shadow w3-margin">
                    <li class="w3-teal w3-xlarge w3-padding-32">Premium</li>
                    <li class="w3-padding-12"><b>25 GB</b> Novels storage</li>
                    <li class="w3-padding-12"><b>Unlimited</b> Novels downloads</li>
                    <li class="w3-padding-12"><b>Endless</b> Support</li>
                    <li class="w3-padding-12">
                        <h2 class="w3-wide">$ 5</h2>
                        <span class="w3-opacity">per month</span>
                    </li>
                    <li class="w3-light-grey w3-padding-24">
                        <button class="w3-button w3-black w3-padding-large">Sign Up</button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="scroll-arrows">
            <i class="fa fa-arrow-up" onclick="scrollToSection('up')"></i>
            <i class="fa fa-arrow-down" onclick="scrollToSection('down')"></i>
        </div>
    </div>
<!-- Team Section -->
<div class="w3-container bgimg-3" style="padding:128px 16px; position: relative;" id="team">
        <h3 class="w3-center">THE TEAM</h3>
        <p class="w3-center w3-large">The ones who developed this project</p>
        <div class="w3-row-padding w3-grayscale" style="margin-top:64px">
            <div class="w3-third w3-margin-bottom w3-center">
                <div class="w3-card">
                    <img src="./Frontend/imgs/tommaso.png" alt="Tommaso" style="width:60%">
                    <div class="w3-container">
                        <h3 class="w3-center">Tommaso Califano</h3>
                        <p class="w3-opacity w3-center">Computer engineering student</p>
                    </div>
                </div>
            </div>
            <div class="w3-third w3-margin-bottom w3-center">
                <div class="w3-card">
                    <img src="./Frontend/imgs/nicola.png" alt="Nicola" style="width:60%">
                    <div class="w3-container">
                        <h3 class="w3-center">Nicola Ramacciotti</h3>
                        <p class="w3-opacity w3-center">Computer engineering student</p>
                    </div>
                </div>
            </div>
            <div class="w3-third w3-margin-bottom w3-center">
                <div class="w3-card">
                    <img src="./Frontend/imgs/gabriele.png" alt="Gabriele" style="width:60%">
                    <div class="w3-container">
                        <h3 class="w3-center">Gabriele Suma</h3>
                        <p class="w3-opacity w3-center">Computer engineering student</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-arrows">
            <i class="fa fa-arrow-up" onclick="scrollToSection('up')"></i>
            <i class="fa fa-arrow-down" onclick="scrollToSection('down')"></i>
        </div>
    </div>
<!-- Footer -->
<footer class="w3-center w3-black w3-padding-64" id="footer">
  <a href="#home" class="w3-button w3-light-grey"><i class="fa fa-arrow-up w3-margin-right"></i>To the top</a>
  <div class="w3-xlarge w3-section">
    <img src="./Frontend/imgs/cherubino_white.png" alt="cherubino" style="width:100px; height:100px; vertical-align:middle; margin-right:5px;">
  </div>
  <p>Powered by <a href="https://www.ing.unipi.it/it/" title="DII" target="_blank" class="w3-hover-text-green">Università di Pisa</a></p>
</footer>
    <script src="./Frontend/js/index.js"></script>
    <script src="./Frontend/js/menu.js"></script>
</body>
</html>
