
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
  
    