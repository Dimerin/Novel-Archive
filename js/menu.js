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