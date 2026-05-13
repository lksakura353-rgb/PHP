<?php
require_once '../db_connect.php';

// Fetch graphic cards from DB
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'gpu' AND in_stock = 1 ORDER BY created_at DESC");
$stmt->execute();
$gpuProducts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'light-mode') {
                document.documentElement.classList.add('light-mode');
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.add('light-mode');
                    document.documentElement.classList.remove('light-mode');
                });
            }
        })();
    </script>
    <title>TechNova | Graphic Cards</title>
    <link rel="stylesheet" href="graphic.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Orbitron:wght@400;600;700&family=Outfit:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        .popup-item:first-child {
            background: var(--card-bg) !important;
            color: var(--text-color) !important;
        }

        .popup-item.active {
            background: var(--accent-color) !important;
            color: #000 !important;
        }
    </style>
    <link rel="shortcut icon" type="image/png" href="../tecNove (2).png">
</head>

<body>
    <div class="scroll-progress" id="scroll-progress"></div>
    <nav class="nav-pods">
        <div class="nav-pod logo-pod">
            <img src="../tecNove.png" class="logo" alt="TechNova Logo">
        </div>
        <div class="nav-pod links-pod">
            <div class="nav-links">
                <a href="../index.php">HOME</a>
                <a href="../about.html">ABOUT</a>
                <a href="#">SERVERS</a>
                <a href="#">CONTACT</a>
                <a href="phone.php">MOBILE PHONE</a>
                <a href="#">BUILD PC</a>
            </div>
            <button class="menu-pill" id="menu-toggle">
                <div class="menu-icon">
                    <span></span><span></span><span></span>
                </div>
                <span>Menu</span>
            </button>
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="#">Laptops</a><a href="#">Gaming PCs</a><a href="#">Components</a><a href="#">Accessories</a><a
                    href="#">Sound</a>
            </div>
        </div>
        <div class="nav-pod search-theme-pod">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </div>
            <div class="theme-switch-wrapper">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <div class="slider round"></div>
                </label>
            </div>
        </div>
        <div class="nav-pod location-pod">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
            </svg>
        </div>
        <div class="nav-pod actions-pod">
            <a href="../login.php" class="login-btn-nav">Login</a>
            <div class="divider"></div>
            <div class="cart-btn-nav">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                <span class="cart-count">0</span>
            </div>
        </div>
    </nav>
    <section class="bottom-features" style="margin-top: 40px;">
        <div class="feature-col popup-list-col sticky">
            <div class="popup-item" onclick="window.location.href='Processors.php'" style="cursor: pointer;">Processors
            </div>
            <div class="popup-item active" onclick="window.location.href='graphic.php'" style="cursor: pointer;">
                Graphic Card</div>
            <div class="popup-item" onclick="window.location.href='Motherboards.php'" style="cursor: pointer;">
                Motherboards</div>
            <div class="popup-item" onclick="window.location.href='phone.php'" style="cursor: pointer;">Mobile Phone</div>
            <div class="popup-item">Laptop</div>
            <div class="popup-item">RAM Memory</div>
            <div class="popup-item">Power Supply</div>
            <div class="popup-item">Storage SSD</div>
            <div class="popup-item">CASINGS</div>
            <div class="popup-item">Cables & Connectors</div>
            <div class="popup-item">Cooling Systems</div>
            <div class="popup-item">OS & Software</div>
        </div>
        <div class="right-column" style="display: flex; flex-direction: column; gap: 40px; flex-grow: 1;">
            <div class="product-grid">
                <?php if ($gpuProducts): ?>
                    <?php foreach ($gpuProducts as $product): ?>
                    <div class="product-item">
                        <div class="product-image-wrapper">
                            <img src="../<?php echo htmlspecialchars($product['image']); ?>"
                                alt="<?php echo htmlspecialchars($product['title']); ?>" onerror="this.src='https://via.placeholder.com/300?text=Graphic+Card'">
                            <span class="stock-badge">in stock</span>
                        </div>
                        <div class="product-details">
                            <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                            <p class="product-price">$<?php echo number_format($product['price']); ?></p>
                            <button class="add-to-cart-btn">Add Cart</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 50px; color: var(--text-color);">
                        <h2>No Graphic Cards Found</h2>
                        <p>Stay tuned for more inventory!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <script src="graphic.js"></script>
</body>

</html>
