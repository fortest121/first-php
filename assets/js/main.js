// For Header Nav Menu -----------
// -------------------------------

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileNavPanel = document.getElementById('mobile-nav-panel');
    const openIcon = document.getElementById('menu-icon-open');
    const closeIcon = document.getElementById('menu-icon-close');
    const categoryToggles = document.querySelectorAll('.category-toggle');

    // Handle menu placement (desktop vs mobile)
    const manageMenuPlacement = () => {
        if (window.innerWidth >= 1024) {
            mobileNavPanel.classList.remove('open');
            openIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    };

    // Mobile Menu Toggle
    menuToggle.addEventListener('click', () => {
        const isOpen = mobileNavPanel.classList.toggle('open');
        openIcon.classList.toggle('hidden', isOpen);
        closeIcon.classList.toggle('hidden', !isOpen);
        if (!isOpen) {
            // Close all submenus when menu is closed
            closeAllSubmenus();
        }
    });

    // Mobile Submenu Toggle
    categoryToggles.forEach(toggle => {
        toggle.addEventListener('click', e => {
            const submenu = toggle.parentElement.querySelector('.mobile-submenu');
            if (submenu && window.innerWidth < 1024) {
                e.preventDefault();
                const isOpen = submenu.classList.contains('open');
                closeAllSubmenus();
                if (!isOpen) {
                    submenu.classList.add('open');
                    toggle.querySelector('.mobile-arrow').classList.add('rotate-180');
                } else {
                    // If the submenu is already open, leave it closed
                    submenu.classList.remove('open');
                    toggle.querySelector('.mobile-arrow').classList.remove('rotate-180');
                }
            }
        });
    });

    // Function to close all submenus
    const closeAllSubmenus = () => {
        categoryToggles.forEach(toggle => {
            const submenu = toggle.parentElement.querySelector('.mobile-submenu');
            if (submenu && submenu.classList.contains('open')) {
                submenu.classList.remove('open');
                toggle.querySelector('.mobile-arrow').classList.remove('rotate-180');
            }
        });
    };

    // Initialize + resize behavior
    window.addEventListener('resize', manageMenuPlacement);
    manageMenuPlacement();
});

// ------ Header End Here ----------------


// For Hero Section -----------
// -------------------------------
// Water ripple effect on mouse hover
const rippleContainer = document.getElementById('ripple-container');

// Find the ripple container
const rippleContainer = document.querySelector('section');

// Trigger ripple effect on mouse move
rippleContainer.addEventListener('mousemove', (e) => {
    // Create ripple element
    const ripple = document.createElement('div');
    ripple.classList.add('ripple', 'ripple-darkish-white'); // Add ripple class for styling

    // Randomize the size of the ripple
    const size = Math.random() * 50 + 60; // Size between 60px and 110px

    // Calculate ripple position based on mouse position (no offset)
    const x = e.clientX - size / 2; // Position ripple so it starts under the mouse
    const y = e.clientY - size / 2; // Position ripple so it starts under the mouse

    // Apply styles to ripple
    ripple.style.left = `${x}px`;
    ripple.style.top = `${y}px`;
    ripple.style.width = `${size}px`;
    ripple.style.height = `${size}px`;

    // Add ripple to the container
    rippleContainer.appendChild(ripple);

    // Remove ripple after animation completes (1.5s duration)
    setTimeout(() => {
        ripple.remove();
    }, 1500);
});