<?php
session_start();
require_once 'db_connect.php';

/*
nav bar eke & anith action button saha items
Pod 1: Logo line 39
Pod 2: Navigation line 50
Pod 3: Search & Theme Switch line 77
Pod 4: Location line 97
Pod 5: Actions (Login & Cart) line 105
*/

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (function () {
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
    <title>TechNova | Premium Computer Shop</title>
    <link rel="stylesheet" href="style.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Orbitron:wght@400;600;700&family=Outfit:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="tecNove (2).png">
</head>

<body>

    <div class="scroll-progress" id="scroll-progress"></div>

    <nav class="nav-pods">
        <!-- Pod 1: Logo -->
        <div class="nav-pod logo-pod">
            <img src="tecNove.png" class="logo" alt="TechNova Logo">
        </div>

        <!-- Pod 2: Navigation Links & Menu -->
        <div class="nav-pod links-pod">
            <div class="nav-links">
                <a href="index.php">HOME</a>
                <a href="about.html">ABOUT</a>
                <a href="server.html">SERVERS</a>
                <a href="contact.html">CONTACT</a>
                <a href="link/phone.php">MOBILE PHONE</a>
                <a href="#">BUILD PC</a>
            </div>
            <button class="menu-pill" id="menu-toggle">
                <div class="menu-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <span>Menu</span>
            </button>
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="#">Laptops</a>
                <a href="#">Gaming PCs</a>
                <a href="#">Components</a>
                <a href="#">Accessories</a>
                <a href="#">Sound</a>
            </div>
        </div>

        <!-- Pod 3: Search & Theme Switch -->
        <div class="nav-pod search-theme-pod">
            <div class="search-container">
                <input type="text" id="product-search" placeholder="Search premium hardware..." />
                <div class="search-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </div>
            </div>
            <div class="theme-switch-wrapper">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <div class="slider round"></div>
                </label>
            </div>
        </div>

        <!-- Pod 4: Location -->
        <div class="nav-pod location-pod" id="location-btn" style="cursor: pointer;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
            </svg>
        </div>

        <!-- Pod 5: Actions (Login & Cart) -->
        <div class="nav-pod actions-pod">
            <?php if (isset($_SESSION['admin_logged_in'])): ?>
                <a href="admin_dashboard.php" class="login-btn-nav">Admin</a>
            <?php else: ?>
                <a href="login.php" class="login-btn-nav">Login</a>
            <?php endif; ?>
            <div class="divider"></div>
            <div class="cart-btn-nav" id="cart-toggle-btn" style="cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                <span class="cart-count">
                    <?php
                    $count = 0;
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item)
                            $count += $item['quantity'];
                    }
                    echo $count;
                    ?>
                </span>
            </div>
        </div>
    </nav>

    <!-- Infinite Carousel Section -->
    <!-- Nonstop rollvena noties bar eka -->
    <div class="carousel-container">
        <div class="carousel-track">
            <div class="carousel-item">⚡🚨AMD Ryzen 9 9950X (up to 5.7Ghz 16-cores 32-threads) 64M Cache. Processor
            </div>
            <div class="carousel-item">📢 The Samsung Galaxy S26 Ultra is expected to be officially announced on
                Wednesday, February 25, 2026</div>
            <div class="carousel-item">🚨 The Intel Core Ultra 9 285K is a high-end, unlocked desktop processor from
                Intel's 2nd generation Core Ultra (formerly "Arrow Lake") series</div>
            <div class="carousel-item">💀 The Oppo Find X9 Pro is a high-end Android flagship smartphone, launched in
                late 2025</div>
            <div class="carousel-item">⚡⚡ The NVIDIA GeForce RTX 5090 is a high-performance, enthusiast-class graphics
                card based on the Blackwell architecture that was released on January 30, 2025 ⚡⚡</div>
            <div class="carousel-item">🚨 The Apple iPhone 17 Pro Max is Apple's flagship smartphone released in
                September 2025</div>
            <!-- Duplicate Set -->
            <div class="carousel-item">⚡🚨AMD Ryzen 9 9950X (up to 5.7Ghz 16-cores 32-threads) 64M Cache. Processor
            </div>
            <div class="carousel-item">📢 The Samsung Galaxy S26 Ultra is expected to be officially announced on
                Wednesday, February 25, 2026</div>
            <div class="carousel-item">🚨 The Intel Core Ultra 9 285K is a high-end, unlocked desktop processor from
                Intel's 2nd generation Core Ultra (formerly "Arrow Lake") series</div>
            <div class="carousel-item">💀 The Oppo Find X9 Pro is a high-end Android flagship smartphone, launched in
                late 2025</div>
            <div class="carousel-item">⚡⚡ The NVIDIA GeForce RTX 5090 is a high-performance, enthusiast-class graphics
                card based on the Blackwell architecture that was released on January 30, 2025 ⚡⚡</div>
            <div class="carousel-item">🚨 The Apple iPhone 17 Pro Max is Apple's flagship smartphone released in
                September 2025</div>
        </div>
    </div>

    <!--VIDEO set eka-->
    <!-- Hero Section: Video 1 -->
    <section class="hero-video-section">
        <video autoplay muted loop playsinline class="main-video">
            <source src="ADVANCE YOUR BUSINESS EXPERIENCE WITH THE WORLD'S MOST ADVANCED PROCESSOR.mp4"
                type="video/mp4">
        </video>
        <div class="video-placeholder">TechNova | Premium</div>
        <div class="video-placeholder1">Premium Shop</div>
    </section>

    <!-- Bottom Features Section -->
    <!-- alavena nav bar sysytem -->
    <section class="bottom-features">
        <!-- Left: Popup List -->
        <div class="feature-col popup-list-col sticky">
            <div class="popup-item" onclick="window.location.href='link/Processors.php'" style="cursor: pointer;">
                Processors</div>
            <div class="popup-item" onclick="window.location.href='link/graphic.php'" style="cursor: pointer;">Graphic
                Card</div>
            <div class="popup-item" onclick="window.location.href='link/Motherboards.php'" style="cursor: pointer;">
                Motherboards</div>
            <div class="popup-item" onclick="window.location.href='link/phone.php'" style="cursor: pointer;">Mobile
                Phone</div>
            <div class="popup-item" onclick="window.location.href='#'" style="cursor: pointer;">Laptop</div>
            <div class="popup-item" onclick="window.location.href='#'" style="cursor: pointer;">RAM Memory</div>
            <div class="popup-item" onclick="window.location.href='#'" style="cursor: pointer;">Power Supply</div>
            <div class="popup-item" onclick="window.location.href='#'" style="cursor: pointer;">Storage SSD</div>
            <div class="popup-item" onclick="window.location.href='#'" style="cursor: pointer;">CASINGS</div>
            <div class="popup-item" onclick="window.location.href='#'" style="cursor: pointer;">Cables & Connectors
            </div>
            <div class="popup-item" onclick="window.location.href='#'" style="cursor: pointer;">Cooling Systems</div>
            <div class="popup-item" onclick="window.location.href='#'" style="cursor: pointer;">OS & Software</div>
        </div>

        <!-- Right: Content Wrapper -->
        <div class="right-column" style="display: flex; flex-direction: column; gap: 40px; flex-grow: 1;">

            <!-- Product Grid -->
            <!-- Product Grid eka item vala list polima -->
            <div class="product-grid">
                <div class="product-item" onclick="window.location.href='link/link.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/0sIFk0MI5W2JSmrooRxpsCkERe6uoqn7.png" alt="Product Image">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">@
                        <h3 class="product-title">AMD Ryzen 7 9800X3D (Up to 5.2 GHz 8-Cores 16-Treads ) 96M Cache</h3>
                        <p class="product-price">$499.00</p>
                        <button class="add-to-cart-btn" data-id="h1" data-title="AMD Ryzen 7 9800X3D"
                            data-price="499.00" data-image="image/0sIFk0MI5W2JSmrooRxpsCkERe6uoqn7.png"
                            onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link1.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/GeForce-RTX-5090-WINDFORCE-OC-32.png" alt="RTX 5090">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">AORUS GeForce RTX™ 5090 MASTER 32GB GDDR7 512bit</h3>
                        <p class="product-price">$1999.00</p>
                        <button class="add-to-cart-btn" data-id="h2" data-title="AORUS GeForce RTX™ 5090 MASTER"
                            data-price="1999.00" data-image="image/GeForce-RTX-5090-WINDFORCE-OC-32.png"
                            onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link2.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/Gigabyte-Radeon-RX-9060-XT-GAMIN.png" alt="RX 9060 XT">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">GIGABYTE Radeon™ RX 9060 XT OC Edition 16GB GDDR6</h3>
                        <p class="product-price">$899.00</p>
                        <button class="add-to-cart-btn" data-id="h3" data-title="GIGABYTE Radeon™ RX 9060 XT"
                            data-price="899.00" data-image="image/Gigabyte-Radeon-RX-9060-XT-GAMIN.png"
                            onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link3.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/LwteIH3VEIJTxozpY6uAEzIawd91YvAF.png" alt="RX 9070 XT">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">ASUS Prime Gaming Radeon™ RX 9070 XT OC Edition 16GB GDDR6</h3>
                        <p class="product-price">$299.00</p>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link4.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/YRx84j0nsS5GPdsfuY3cqrFW9E8WrIyT.png" alt="Intel 13th Gen i7">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Intel 13th Gen i7</h3>
                        <p class="product-price">$149.00</p>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link5.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/aDY9t7cRhhgW2OzoWzgrIICSdgfrDR23.png" alt="RTX 5080 16GB">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">ASUS Prime Gaming Geforce RTX 5080 16GB GDDR7</h3>
                        <p class="product-price">$189.00</p>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link6.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/qWLGRyQhfhNP2v6f368GPIqtIHje430V.png" alt="Intel® Core Ultra 9 Processor ">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Intel® Core Ultra 9 Processor 285K</h3>
                        <p class="product-price">$249.00</p>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link7.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/y022zuJlr2CZzqp48LeLMsyzlGSPRFD9.png" alt="INTEL CORE I7 14700F">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">INTEL CORE I7 14700F (33M CACHE, UP TO 5.40 GHZ) </h3>
                        <p class="product-price">$229.00</p>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link8.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/samsungm2.png" alt="SAMSUNG 990 PRO">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">SAMSUNG 990 PRO PCIe 4.0 NVMe SSD 1TB</h3>
                        <p class="product-price">$249.00</p>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link9.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/oJj7XFo9BCNkdBnqTuciiw7oz5cbGMnZ.png" alt="CORSAIR VENGEANCE 32GB">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">CORSAIR VENGEANCE 32GB (2x16GB) DDR5 5200MHz Kit</h3>
                        <p class="product-price">$249.00</p>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link10.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/abxdDm5HrrojfYmyq66JEizKVWaQ5tk6.png" alt="MSI Pro B760M">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">MSI Pro B760M-A WIFI DDR5</h3>
                        <p class="product-price">$199.00</p>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
                <div class="product-item" onclick="window.location.href='link/link11.html'" style="cursor: pointer;">
                    <div class="product-image-wrapper">
                        <img src="image/5ywcIqXX1rKqPLhtMfVztFUsdXNc1cf1.png" alt="ASUS ROG STRIX X870E">
                        <span class="stock-badge">in stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">ASUS ROG STRIX X870E-H GAMING WIFI 7</h3>
                        <p class="product-price">$299.00</p>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation();">Add Cart</button>
                    </div>
                </div>
            </div>

            <!-- Right: Content Wrapper -->
            <div class="right-column" style="display: flex; flex-direction: column; gap: 40px; flex-grow: 1;">

                <!-- Product Grid -->
                <div class="product-grid">
                    <?php foreach ($products as $p):
                        // Map categories to specific pages
                        $item_link = $p['link'];
                        if ($p['category'] === 'gpu')
                            $item_link = 'link/graphic.php';
                        elseif ($p['category'] === 'processor')
                            $item_link = 'link/Processors.php';
                        elseif ($p['category'] === 'motherboard')
                            $item_link = 'link/Motherboards.php';
                        elseif ($p['category'] === 'phone')
                            $item_link = 'link/phone.php';
                        ?>
                        <div class="product-item"
                            onclick="window.location.href='<?php echo htmlspecialchars($item_link); ?>'"
                            style="cursor: pointer;">
                            <div class="product-image-wrapper">
                                <img src="<?php echo htmlspecialchars($p['image']); ?>"
                                    alt="<?php echo htmlspecialchars($p['title']); ?>">
                                <?php if ($p['in_stock']): ?>
                                    <span class="stock-badge">in stock</span>
                                <?php else: ?>
                                    <span class="stock-badge" style="background: #ff5e5e;">out of stock</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-details">
                                <h3 class="product-title"><?php echo htmlspecialchars($p['title']); ?></h3>
                                <p class="product-price">$<?php echo number_format($p['price'], 2); ?></p>
                                <button class="add-to-cart-btn" data-id="<?php echo $p['id']; ?>"
                                    data-title="<?php echo htmlspecialchars($p['title']); ?>"
                                    data-price="<?php echo $p['price']; ?>"
                                    data-image="<?php echo htmlspecialchars($p['image']); ?>"
                                    onclick="event.stopPropagation();">Add Cart</button>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($products)): ?>
                        <p style="grid-column: 1/-1; text-align: center; padding: 40px; color: rgba(255,255,255,0.5);">No
                            dynamic products found. Add them from the Admin panel!</p>
                    <?php endif; ?>
                </div>

                <!-- Promo Grid Section -->
                <div class="promo-grid">
                    <!-- Promo 1: Video -->
                    <div class="promo-item">
                        <video autoplay muted loop playsinline class="promo-media"
                            style="width: 100%; height: 100%; object-fit: cover;">
                            <source src="Introducing Galaxy S26 Ultra - Galaxy AI - Samsung.mp4" type="video/mp4">
                        </video>
                        <div class="promo-content">
                            <div class="promo-text top-right">
                                <h3 class="tag">KING OF</h3>
                                <h2>SAMSUNG S26 Ultra</h2>
                            </div>
                            <a href="link/s26.html" class="shop-btn bottom-left">SHOP NOW</a>
                        </div>
                    </div>

                    <!-- Promo 2: Image -->
                    <div class="promo-item">
                        <img src="Vivo-X300-series-launched-in-Chi.png" alt="Vivo X300 Series" class="promo-media">
                        <div class="promo-content">
                            <div class="promo-text top-right">
                                <h3 class="tag">CAMARA KING</h3>
                                <h2>Vivo X300 Pro</h2>
                            </div>
                            <a href="link/x300.html" class="shop-btn bottom-left">SHOP NOW</a>
                        </div>
                    </div>

                    <!-- Promo 3: Full Width Image -->
                    <div class="promo-item full-width">
                        <video autoplay muted loop playsinline class="promo-media"
                            style="width: 100%; height: 100%; object-fit: cover;">
                            <source src="0301.mp4" type="video/mp4">
                        </video>
                        <div class="promo-content">
                            <a href="link/phone.php" class="shop-btn bottom-center">SHOP NOW</a>
                        </div>
                    </div>
                </div>

            </div>
    </section>
    <!-- Info & Footer Section -->
    <section class="info-section">
        <div class="info-grid">
            <!-- Image Upload Block -->
            <div class="info-block image-upload-block" id="image-block">
                <div id="image-display" class="image-display">
                    <span>image</span>
                </div>
                <input type="file" id="image-input" accept="image/*" style="display: none;">
                <button class="upload-trigger-btn" onclick="document.getElementById('image-input').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    Upload Image
                </button>
            </div>

            <!-- Map Block -->
            <div class="info-block map-block" id="map-section">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15845.895318534833!2d79.86665795493226!3d6.833633830209421!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25b0785121b8f%3A0x6bba46c5a083a696!2sDehiwala-Mount%20Lavinia!5e0!3m2!1sen!2slk!4v1710515152543!5m2!1sen!2slk"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                <div class="map-label">MAP VIEW</div>
            </div>
        </div>

        <!-- Detailed Footer Navigation Grid -->
        <div class="footer-nav-grid">
            <div class="footer-col">
                <h4>SHOP CATEGORIES</h4>
                <ul>
                    <li><a href="link/Processors.php">Processors</a></li>
                    <li><a href="link/graphic.php">Graphics Cards</a></li>
                    <li><a href="link/Motherboards.php">Motherboards</a></li>
                    <li><a href="link/phone.php">Mobile Phones</a></li>
                    <li><a href="#">Laptops</a></li>
                    <li><a href="#">PC Builds</a></li>
                    <li><a href="#">Accessories</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>CUSTOMER CARE</h4>
                <ul>
                    <li><a href="#">Track Order</a></li>
                    <li><a href="#">Warranty Info</a></li>
                    <li><a href="#">Return Policy</a></li>
                    <li><a href="#">Service Centers</a></li>
                    <li><a href="about.html">About TechNova</a></li>
                    <li><a href="contact.html">Contact Support</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>QUICK ACTIONS</h4>
                <ul>
                    <li><a href="login.php">My Account</a></li>
                    <li><a href="#">Wishlist</a></li>
                    <li><a href="#">Compare Items</a></li>
                    <li><a href="#">Current Deals</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
            <div class="footer-col newsletter-col">
                <h4>TECH INSIDER</h4>
                <p>Subscribe for exclusive deals and latest hardware launches.</p>
                <div class="newsletter-form">
                    <input type="email" placeholder="Email address">
                    <button>JOIN</button>
                </div>
                <div class="payment-methods">
                    <img src="https://cdn-icons-png.flaticon.com/512/349/349221.png" alt="Visa">
                    <img src="https://cdn-icons-png.flaticon.com/512/349/349228.png" alt="Mastercard">
                    <img src="https://cdn-icons-png.flaticon.com/512/174/174861.png" alt="PayPal">
                    <img src="https://cdn-icons-png.flaticon.com/512/196/196070.png" alt="Amex">
                </div>
            </div>
        </div>

        <!-- Location Bar -->
        <div class="location-bar">
            <div class="locations-info">
                <p><span class="icon">➤</span> Dehiwala Showroom & Service Center No 110A Galle Road,</p>
                <p><span class="icon">📍</span> Dehiwala-Mount Lavinia</p>
                <p>Colombo 03 Showroom No 37 School Lane, Colombo 03</p>
            </div>
            <div class="brand-tag">
                TechNova | Premium
            </div>
        </div>

        <!-- Main Footer Bar -->
        <footer class="footer-bar">
            <div class="footer-left">
                Nanotek Computer Solutions &copy; 2026 | All Rights Reserved. Find us on
            </div>
            <div class="social-links">
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384060.png" alt="YouTube"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/3046/3046121.png" alt="TikTok"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter/X"></a>
            </div>
        </footer>

        <!-- Developer Credit -->
        <div class="developer-credit">
            Designed & developed by <a href="https://en.wikipedia.org/wiki/Centella_asiatica"
                target="_blank">www.gotokola.com</a>
        </div>
    </section>

    <div class="cart-sidebar" id="cart-sidebar">
        <div class="cart-sidebar-header">
            <h2>Your Cart</h2>
            <button class="close-cart" id="close-cart">&times;</button>
        </div>
        <div class="cart-sidebar-items" id="cart-sidebar-items">
            <!-- Items populated by JS -->
            <p style="text-align: center; opacity: 0.5; margin-top: 50px;">Your cart is empty</p>
        </div>
        <div class="cart-sidebar-footer">
            <div class="cart-total-row">
                <span>Total</span>
                <span id="cart-sidebar-total">$0.00</span>
            </div>
            <a href="cart.php" class="view-full-cart">Checkout Now</a>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>