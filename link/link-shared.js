/* Shared JS for product pages in link/ directory */
(function() {
    // 1. Initial Theme Application (Prevent Flash)
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light-mode') {
        document.body.classList.add('light-mode');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const checkbox = document.getElementById('checkbox');
        const menuToggle = document.getElementById('menu-toggle');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const scrollProgress = document.getElementById('scroll-progress');

        // Theme Toggle
        if (checkbox) {
            checkbox.checked = (localStorage.getItem('theme') === 'light-mode');
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    document.body.classList.add('light-mode');
                    localStorage.setItem('theme', 'light-mode');
                } else {
                    document.body.classList.remove('light-mode');
                    localStorage.setItem('theme', 'dark-mode');
                }
            });
        }

        // Shared Menu Logic
        if (menuToggle && dropdownMenu) {
            const navLinks = document.querySelector('.nav-links');
            const setupMobileMenu = () => {
                if (window.innerWidth <= 768 && navLinks) {
                    const mobileLinksId = 'mobile-cloned-links';
                    if (!document.getElementById(mobileLinksId)) {
                        const mobileContainer = document.createElement('div');
                        mobileContainer.id = mobileLinksId;
                        mobileContainer.style.borderBottom = '1px solid var(--pod-border)';
                        mobileContainer.style.marginBottom = '10px';
                        mobileContainer.style.paddingBottom = '5px';
                        
                        const links = navLinks.querySelectorAll('a');
                        links.forEach(link => {
                            const clonedLink = link.cloneNode(true);
                            clonedLink.style.display = 'block';
                            clonedLink.style.padding = '10px 15px';
                            mobileContainer.appendChild(clonedLink);
                        });
                        dropdownMenu.prepend(mobileContainer);
                    }
                }
            };

            menuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                setupMobileMenu();
                dropdownMenu.classList.toggle('active');
            });
            document.addEventListener('click', () => dropdownMenu.classList.remove('active'));
            dropdownMenu.addEventListener('click', (e) => e.stopPropagation());

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    const mobileCloned = document.getElementById('mobile-cloned-links');
                    if (mobileCloned) mobileCloned.remove();
                }
            });
        }

        // Scroll Progress
        if (scrollProgress) {
            window.addEventListener('scroll', () => {
                const scrollTotal = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrollValue = (window.scrollY / scrollTotal) * 100;
                scrollProgress.style.width = scrollValue + '%';
            });
        }

        // Sub-navigation Logic for Sidebar (from index.html/about.html patterns)
        const popupItems = document.querySelectorAll('.popup-item');
        popupItems.forEach(item => {
            const text = item.textContent.trim();
            if (text === "Processors") {
                item.style.cursor = "pointer";
                item.addEventListener('click', () => window.location.href = 'Processors.html');
            } else if (text === "Graphic Card") {
                item.style.cursor = "pointer";
                item.addEventListener('click', () => window.location.href = 'graphic.html');
            } else if (text === "Motherboards") {
                item.style.cursor = "pointer";
                item.addEventListener('click', () => window.location.href = 'Motherboards.html');
            } else if (text === "Mobile Phone") {
                item.style.cursor = "pointer";
                item.addEventListener('click', () => window.location.href = 'phone.html');
            }
        });
    });
})();
