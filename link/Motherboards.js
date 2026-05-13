/* Theme Toggle Logic */
const checkbox = document.getElementById('checkbox');
const currentTheme = localStorage.getItem('theme');

if (currentTheme) {
    document.body.classList.add(currentTheme);
    if (currentTheme === 'light-mode') {
        checkbox.checked = true;
    }
}

checkbox.addEventListener('change', () => {
    if (checkbox.checked) {
        document.body.classList.add('light-mode');
        localStorage.setItem('theme', 'light-mode');
    } else {
        document.body.classList.remove('light-mode');
        localStorage.setItem('theme', 'dark-mode');
    }
});

/* Menu Toggle Logic */
const menuToggle = document.getElementById('menu-toggle');
const dropdownMenu = document.getElementById('dropdown-menu');

if (menuToggle && dropdownMenu) {
    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('active');
    });

    // Close menu when clicking anywhere else
    document.addEventListener('click', () => {
        dropdownMenu.classList.remove('active');
    });

    // Prevent closing when clicking inside the menu
    dropdownMenu.addEventListener('click', (e) => {
        e.stopPropagation();
    });
}

/* Staggered Scroll Animation for Popup List */
const observerOptions = {
    threshold: 0.1
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const items = entry.target.querySelectorAll('.popup-item');
            items.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 200);
            });
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

const popupListCols = document.querySelectorAll('.popup-list-col');
if (popupListCols.length > 0) {
    popupListCols.forEach(col => {
        // Initial state for animation
        const items = col.querySelectorAll('.popup-item');
        items.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            item.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        });
        observer.observe(col);
    });
}

/* Staggered Scroll Animation for Product Grid */
const productObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const products = entry.target.querySelectorAll('.product-item');
            products.forEach((product, index) => {
                setTimeout(() => {
                    product.style.opacity = '1';
                    product.style.transform = 'translateY(0)';
                }, index * 100);
            });
            productObserver.unobserve(entry.target);
        }
    });
}, observerOptions);

const productGrids = document.querySelectorAll('.product-grid');
if (productGrids.length > 0) {
    productGrids.forEach(grid => {
        productObserver.observe(grid);
    });
}

const promoGrids = document.querySelectorAll('.promo-grid');
if (promoGrids.length > 0) {
    promoGrids.forEach(grid => {
        const promoItems = grid.querySelectorAll('.promo-item');
        promoItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(30px)';
        });

        const promoObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const items = entry.target.querySelectorAll('.promo-item');
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, index * 200);
                    });
                    promoObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        promoObserver.observe(grid);
    });
}

/* Product Add to Cart Logic */
const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
addToCartBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();

        // Update cart count
        if (cartCount) {
            let count = parseInt(cartCount.textContent);
            cartCount.textContent = count + 1;
        }

        // Button animation
        const originalText = btn.textContent;
        btn.textContent = 'Added!';
        btn.style.background = 'var(--accent-color)';
        btn.style.color = '#000';

        setTimeout(() => {
            btn.textContent = originalText;
            btn.style.background = '';
            btn.style.color = '';
        }, 1500);

        // Nav cart icon animation
        if (cartBtn) {
            cartBtn.style.transform = 'scale(1.2)';
            setTimeout(() => {
                cartBtn.style.transform = 'scale(1)';
            }, 200);
        }
    });
});

/* Search interaction (Placeholder for now) */
const searchBox = document.querySelector('.search-box');
if (searchBox) {
    searchBox.addEventListener('click', () => {
        alert('Search system activated! (Ready for integration)');
    });
}

/* Scroll Progress Logic */
window.addEventListener('scroll', () => {
    const scrollProgress = document.getElementById('scroll-progress');
    const scrollTotal = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrollValue = (window.scrollY / scrollTotal) * 100;
    if (scrollProgress) {
        scrollProgress.style.width = scrollValue + '%';
    }
});

/* Optional: Smooth Scroll for Navigation Links */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

/* Cart Logic */
const cartBtn = document.querySelector('.cart-btn-nav');
const cartCount = document.querySelector('.cart-count');
if (cartBtn && cartCount) {
    cartBtn.addEventListener('click', () => {
        let count = parseInt(cartCount.textContent);
        cartCount.textContent = count + 1;

        // Simple scale animation
        cartBtn.style.transform = 'scale(1.2)';
        setTimeout(() => {
            cartBtn.style.transform = 'scale(1)';
        }, 200);
    });
}
