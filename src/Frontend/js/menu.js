  // Used to toggle the menu on small screens when clicking on the menu button
  function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
    } else {
        mySidebar.style.display = 'block';
    }
    }

    // Close the sidebar with the close button
    function w3_close() {
    const mySidebar = document.getElementById("mySidebar");
        mySidebar.style.display = "none";
    }

    function w3_open_dash() {
        document.getElementById("mySidebar").style.display = "block";
        document.getElementById("myOverlay").style.display = "block";
    }
     
    function w3_close_dash() {
        document.getElementById("mySidebar").style.display = "none";
        document.getElementById("myOverlay").style.display = "none";
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Aggiungi event listener per aprire la sidebar
        const openSidebarBtn = document.querySelector('.w3-bar-item.w3-button.w3-right.w3-hide-large');
        if (openSidebarBtn) {
            openSidebarBtn.addEventListener('click', w3_open);
        }
    
        // Aggiungi event listener per chiudere la sidebar
        const closeSidebarBtn = document.querySelector('.w3-bar-item.w3-button.w3-large.w3-padding-16');
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', w3_close);
        }
    });