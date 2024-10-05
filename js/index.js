
    document.addEventListener('DOMContentLoaded', function() {
    const homeLink = document.querySelector('a[href="#home"]');
    const animatedElements = document.querySelectorAll('.w3-animate-bottom');

    function replayAnimations() {
        animatedElements.forEach(element => {
            element.classList.remove('w3-animate-bottom');
            void element.offsetWidth; // Trigger reflow to restart the animation
            element.classList.add('w3-animate-bottom');
        });
    }

    homeLink.addEventListener('click', function() {
        replayAnimations();
    });

        window.addEventListener('scroll', function() {
            if (window.scrollY === 0) {
                replayAnimations();
            }
        });
    });

    function scrollToSection(direction) {
        const sections = document.querySelectorAll('header, .w3-container, footer');
        let currentSectionIndex = Array.from(sections).findIndex(section => {
            return section.getBoundingClientRect().top >= 0;
        });

        if (direction === 'down') {
            // Scrolling down
            if (currentSectionIndex < sections.length - 1) {
                sections[currentSectionIndex + 1].scrollIntoView({ behavior: 'smooth' });
            }
        } else if (direction === 'up') {
            // Scrolling up
            if (currentSectionIndex > 0) {
                sections[currentSectionIndex - 1].scrollIntoView({ behavior: 'smooth' });
            }
        }
    }
  
    