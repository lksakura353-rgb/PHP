/* Staggered Scroll Animation for Phone Page */
(function() {
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
                    }, index * 50); // Faster stagger for menu
                });
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const popupListCols = document.querySelectorAll('.popup-list-col');
    if (popupListCols.length > 0) {
        popupListCols.forEach(col => {
            const items = col.querySelectorAll('.popup-item');
            items.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                item.style.transition = 'all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            });
            observer.observe(col);
        });
    }

    const productObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const products = entry.target.querySelectorAll('.product-item');
                products.forEach((product, index) => {
                    setTimeout(() => {
                        product.style.opacity = '1';
                        product.style.transform = 'translateY(0)';
                    }, index * 80);
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
})();
