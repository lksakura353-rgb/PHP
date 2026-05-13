<?php
session_start();
require_once 'db_connect.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | TechNova Premium</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Orbitron:wght@400;600;700&family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
            --accent-glow: 0 0 20px rgba(0, 243, 255, 0.2);
        }

        body {
            background: #050505;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            padding-top: 100px;
        }

        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .cart-header {
            margin-bottom: 40px;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            letter-spacing: 2px;
            background: linear-gradient(90deg, #fff, var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cart-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 40px;
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cart-item {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            border-color: var(--accent-color);
            box-shadow: var(--accent-glow);
            transform: translateX(10px);
        }

        .item-image {
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-image img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .item-price {
            color: var(--accent-color);
            font-size: 1.1rem;
            font-weight: 600;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }

        .qty-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .qty-btn:hover {
            background: var(--accent-color);
            color: #000;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #ff5e5e;
            cursor: pointer;
            padding: 10px;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .remove-btn:hover {
            opacity: 1;
        }

        .cart-summary {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 30px;
            height: fit-content;
            position: sticky;
            top: 120px;
        }

        .summary-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: var(--accent-color);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
            opacity: 0.8;
        }

        .summary-total {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
        }

        .checkout-btn {
            width: 100%;
            background: var(--accent-color);
            color: #000;
            border: none;
            padding: 18px;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 700;
            margin-top: 30px;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .checkout-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.4);
        }

        .empty-cart {
            text-align: center;
            padding: 100px 0;
        }

        .empty-cart svg {
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-cart h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .back-to-shop {
            display: inline-block;
            color: var(--accent-color);
            text-decoration: none;
            border: 1px solid var(--accent-color);
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .back-to-shop:hover {
            background: var(--accent-color);
            color: #000;
        }

        @media (max-width: 968px) {
            .cart-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="nav-pods" style="background: rgba(5,5,5,0.8); backdrop-filter: blur(20px);">
        <div class="nav-pod logo-pod" onclick="window.location.href='index.php'" style="cursor: pointer;">
            <img src="tecNove.png" class="logo" alt="TechNova Logo">
        </div>
        <div class="nav-pod actions-pod">
             <a href="index.php" class="login-btn-nav">Continue Shopping</a>
        </div>
    </nav>

    <div class="cart-container">
        <div class="cart-header">
            <h1>YOUR CART</h1>
            <span><?php echo count($cart); ?> Items</span>
        </div>

        <?php if (empty($cart)): ?>
            <div class="empty-cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                <h2>Your cart is empty</h2>
                <a href="index.php" class="back-to-shop">START SHOPPING</a>
            </div>
        <?php else: ?>
            <div class="cart-grid">
                <div class="cart-items">
                    <?php foreach ($cart as $item): ?>
                        <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                            <div class="item-image">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="">
                            </div>
                            <div class="item-details">
                                <div class="item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                                <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
                                <div class="item-quantity">
                                    <button class="qty-btn" onclick="updateQty('<?php echo $item['id']; ?>', -1)">-</button>
                                    <span><?php echo $item['quantity']; ?></span>
                                    <button class="qty-btn" onclick="updateQty('<?php echo $item['id']; ?>', 1)">+</button>
                                </div>
                            </div>
                            <button class="remove-btn" onclick="removeItem('<?php echo $item['id']; ?>')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h2 class="summary-title">ORDER SUMMARY</h2>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>FREE</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span>Calculated at checkout</span>
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <button class="checkout-btn">PROCEED TO CHECKOUT</button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function updateQty(id, delta) {
            // Logic to update quantity via AJAX
            location.reload(); // Simple reload for now
        }

        function removeItem(id) {
            fetch('cart_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=remove&id=${id}`
            }).then(() => location.reload());
        }
    </script>
</body>
</html>
