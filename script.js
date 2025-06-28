document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('nav a'); // Select all navigation links
    const currentPath = window.location.pathname.split('/').pop(); // Get the current page filename (e.g., "index.html")

    navLinks.forEach(link => {
        // First, remove active class and aria-current from all links to ensure only one is active
        link.classList.remove('active');
        link.removeAttribute('aria-current'); // Remove the attribute

        // Get the href value of each link (e.g., "introduction.html")
        const linkPath = link.getAttribute('href'); 

        // Compare the link's href with the current page's filename
        if (linkPath === currentPath) {
            link.classList.add('active'); // Add the 'active' class
            link.setAttribute('aria-current', 'page'); // Add aria-current="page"
        } else if (currentPath === '' && linkPath === 'index.html') {
            // Special case for root URL (e.g., example.com/ might match example.com/index.html)
            link.classList.add('active');
            link.setAttribute('aria-current', 'page');
        }
    });
});