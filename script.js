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

/* Location Scroll Logic */
const locationBtn = document.getElementById('location-btn');
if (locationBtn) {
    locationBtn.addEventListener('click', () => {
        const mapSection = document.getElementById('map-section');
        if (mapSection) {
            mapSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Add a brief highlight effect
            mapSection.style.boxShadow = '0 0 30px var(--accent-color)';
            setTimeout(() => {
                mapSection.style.boxShadow = '';
            }, 2000);
        }
    });
}

/* Menu Toggle Logic */
const menuToggle = document.getElementById('menu-toggle');
const dropdownMenu = document.getElementById('dropdown-menu');
const navLinks = document.querySelector('.nav-links');

if (menuToggle && dropdownMenu) {
    // Clone nav links for mobile if they don't exist in dropdown
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

    // Close menu when clicking anywhere else
    document.addEventListener('click', () => {
        dropdownMenu.classList.remove('active');
    });

    // Prevent closing when clicking inside the menu
    dropdownMenu.addEventListener('click', (e) => {
        e.stopPropagation();
    });

    // Handle resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            const mobileCloned = document.getElementById('mobile-cloned-links');
            if (mobileCloned) mobileCloned.remove();
        }
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


/* Cart Sidebar Logic */
const cartSidebar = document.getElementById('cart-sidebar');
const cartToggleBtn = document.getElementById('cart-toggle-btn');
const closeCartBtn = document.getElementById('close-cart');
const cartSidebarItems = document.getElementById('cart-sidebar-items');
const cartSidebarTotal = document.getElementById('cart-sidebar-total');
const cartCount = document.querySelector('.cart-count');
const cartBtn = document.querySelector('.cart-btn-nav');

const toggleCart = (show) => {
    if (show) {
        cartSidebar.classList.add('active');
        refreshCartSidebar();
    } else {
        cartSidebar.classList.remove('active');
    }
};

if (cartToggleBtn) cartToggleBtn.addEventListener('click', () => toggleCart(true));
if (closeCartBtn) closeCartBtn.addEventListener('click', () => toggleCart(false));

const refreshCartSidebar = () => {
    fetch('cart_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=get_cart'
    })
    .then(response => response.json())
    .then(data => {
        if (cartSidebarItems) {
            if (data.items.length === 0) {
                cartSidebarItems.innerHTML = '<p style="text-align: center; opacity: 0.5; margin-top: 50px;">Your cart is empty</p>';
            } else {
                cartSidebarItems.innerHTML = data.items.map(item => `
                    <div class="cart-sidebar-item">
                        <img src="${item.image}" alt="">
                        <div class="cart-sidebar-item-info">
                            <div class="cart-sidebar-item-title">${item.title}</div>
                            <div class="cart-sidebar-item-price">$${parseFloat(item.price).toFixed(2)} x ${item.quantity}</div>
                        </div>
                    </div>
                `).join('');
            }
        }
        if (cartSidebarTotal) {
            cartSidebarTotal.textContent = `$${parseFloat(data.total).toFixed(2)}`;
        }
        if (cartCount) {
            cartCount.textContent = data.cart_count;
        }
    });
};

/* Product Add to Cart Logic */
const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');

addToCartBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const productId = btn.getAttribute('data-id');
        const productTitle = btn.getAttribute('data-title');
        const productPrice = btn.getAttribute('data-price');
        const productImage = btn.getAttribute('data-image');

        if (!productId) return;

        // Send AJAX request to add item to session cart
        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('id', productId);
        formData.append('title', productTitle);
        formData.append('price', productPrice);
        formData.append('image', productImage);

        fetch('cart_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Update cart count UI
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                }

                // Button animation feedback
                const originalText = btn.textContent;
                btn.textContent = 'Added!';
                btn.classList.add('added');
                
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.classList.remove('added');
                }, 1500);

                // Nav cart icon animation
                if (cartBtn) {
                    cartBtn.classList.add('pulse');
                    setTimeout(() => cartBtn.classList.remove('pulse'), 500);
                }

                // Auto open sidebar on add
                toggleCart(true);
            }
        })
        .catch(error => console.error('Error adding to cart:', error));
    });
});

/* Search & Filtering Logic */
const searchInput = document.getElementById('product-search');
if (searchInput) {
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        const allProducts = document.querySelectorAll('.product-item');
        
        let foundAny = false;
        allProducts.forEach(product => {
            const title = product.querySelector('.product-title').textContent.toLowerCase();
            if (title.includes(query)) {
                product.style.display = 'flex';
                product.style.opacity = '1';
                product.style.transform = 'scale(1)';
                foundAny = true;
            } else {
                product.style.display = 'none';
                product.style.opacity = '0';
                product.style.transform = 'scale(0.95)';
            }
        });

        // Optional: Show "No products found" message if none match
        const grid = document.querySelector('.product-grid');
        let noResultsMsg = document.getElementById('no-results-msg');
        
        if (!foundAny) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'no-results-msg';
                noResultsMsg.style.gridColumn = '1 / -1';
                noResultsMsg.style.textAlign = 'center';
                noResultsMsg.style.padding = '40px';
                noResultsMsg.style.opacity = '0.5';
                noResultsMsg.textContent = 'No premium hardware matches your search.';
                if (grid) grid.appendChild(noResultsMsg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
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

/* Smooth Scroll for Navigation Links */
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

/* Image Upload Logic */
const imageInput = document.getElementById('image-input');
const imageDisplay = document.getElementById('image-display');

if (imageInput && imageDisplay) {
    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imageDisplay.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Uploaded Image';
                imageDisplay.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
    });
}
