<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
require_once 'db_connect.php';

if (!isset($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$id = $_GET['id'];
$message = "";
$error = "";

// Fetch current product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: admin_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $link = $_POST['link'];
    $image_path = $product['image']; 

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $target_dir = "image/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_name = basename($_FILES['image_file']['name']);
        $target_file = $target_dir . time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $file_name);
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            $error = "Failed to upload image.";
        }
    } else if (!empty($_POST['image_path'])) {
        $image_path = $_POST['image_path']; 
    }

    if (!$error && !empty($title) && !empty($price)) {
        try {
            $stmt = $pdo->prepare("UPDATE products SET title = ?, price = ?, image = ?, link = ?, category = ? WHERE id = ?");
            $stmt->execute([$title, $price, $image_path, $link, $category, $id]);
            $message = "Inventory updated successfully!";
            
            // Refresh
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
        } catch (PDOException $e) {
            $error = "Update Failed: " . $e->getMessage();
        }
    } else if (!$error) {
        $error = "Mandatory fields Title and Price are missing.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNova | Modify Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6;
            --secondary: #6366f1;
            --dark-bg: #030712;
            --card-bg: #111827;
            --sidebar-bg: #0f172a;
            --text-main: #f8fafc;
            --text-secondary: #94a3b8;
            --success: #10b981;
            --danger: #ef4444;
            --border: rgba(255, 255, 255, 0.05);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .logo-section { padding: 30px; display: flex; align-items: center; gap: 12px; }
        .logo-section img { width: 35px; }
        .logo-section h2 { font-family: 'Montserrat', sans-serif; font-size: 1.1rem; }

        .nav-items { padding: 0 15px; display: flex; flex-direction: column; gap: 5px; }
        .nav-link {
            padding: 12px 15px; border-radius: 12px; text-decoration: none;
            color: var(--text-secondary); display: flex; align-items: center; gap: 12px; transition: 0.2s;
        }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.03); color: var(--text-main); }
        .nav-link.active { background: var(--primary); color: white; }

        /* Content */
        .main-content {
            margin-left: 260px; flex-grow: 1; padding: 60px 40px;
            display: flex; flex-direction: column; align-items: center;
        }

        .form-card {
            width: 100%; max-width: 650px; background: var(--card-bg);
            border: 1px solid var(--border); border-radius: 24px; padding: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .header-title { text-align: center; margin-bottom: 40px; }
        .header-title h1 { font-family: 'Montserrat'; font-size: 1.8rem; margin-bottom: 10px; }
        .header-title p { color: var(--text-secondary); font-size: 0.95rem; }

        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 10px; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; }

        input[type="text"], input[type="number"], select {
            width: 100%; padding: 14px 18px; border-radius: 12px;
            background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border);
            color: #ffffff; outline: none; font-family: inherit; font-size: 0.95rem;
            transition: all 0.3s;
        }
        select option {
            background-color: var(--card-bg);
            color: #ffffff;
            padding: 10px;
        }
        input:focus { border-color: var(--primary); background: rgba(255, 255, 255, 0.05); }

        .preview-area {
            display: flex; align-items: center; gap: 20px;
            background: rgba(255,255,255,0.02); padding: 15px; border-radius: 16px;
            border: 1px solid var(--border); margin-bottom: 25px;
        }
        .preview-area img { width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 8px; padding: 5px; }

        .btn-submit {
            width: 100%; padding: 16px; border-radius: 14px; border: none;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; font-weight: 700; font-size: 1rem; cursor: pointer;
            margin-top: 20px; transition: 0.3s;
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(139, 92, 246, 0.4); }

        .status-msg { padding: 15px; border-radius: 12px; margin-bottom: 25px; text-align: center; font-weight: 500; }
        .success { background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.2); }
        .error { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); }

        .back-nav { margin-top: 30px; text-decoration: none; color: var(--text-secondary); font-size: 0.85rem; display: flex; align-items: center; gap: 8px; transition: 0.3s; }
        .back-nav:hover { color: var(--text-main); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="tecNove.png" alt="Logo">
            <h2>TechNova</h2>
        </div>
        <div class="nav-items">
            <a href="admin_dashboard.php" class="nav-link"><i class="fa-solid fa-chart-pie"></i> Analytics</a>
            <a href="add_item.php" class="nav-link"><i class="fa-solid fa-circle-plus"></i> New Listing</a>
            <a href="index.php" class="nav-link"><i class="fa-solid fa-globe"></i> Marketplace</a>
        </div>
        <div style="margin-top: auto; padding-bottom: 30px;" class="nav-items">
             <a href="admin_auth.php?logout=1" class="nav-link" style="color: var(--danger);"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="form-card">
            <div class="header-title">
                <h1>Edit Listing</h1>
                <p>Update specifications and pricing for <strong><?php echo htmlspecialchars($product['title']); ?></strong></p>
            </div>

            <?php if($message): ?> <div class="status-msg success"><i class="fa-solid fa-check-circle"></i> <?php echo $message; ?></div> <?php endif; ?>
            <?php if($error): ?> <div class="status-msg error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?></div> <?php endif; ?>

            <form action="edit_item.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                
                <div class="preview-area">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="product" onerror="this.src='https://via.placeholder.com/100'">
                    <div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase;">Current Visual Asset</div>
                        <div style="font-weight: 600; font-size: 0.9rem;"><?php echo basename($product['image']); ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Price (USD)</label>
                        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="gpu" <?php echo ($product['category'] == 'gpu') ? 'selected' : ''; ?>>Graphic Card (GPU)</option>
                            <option value="processor" <?php echo ($product['category'] == 'processor') ? 'selected' : ''; ?>>Processor (CPU)</option>
                            <option value="motherboard" <?php echo ($product['category'] == 'motherboard') ? 'selected' : ''; ?>>Motherboard</option>
                            <option value="phone" <?php echo ($product['category'] == 'phone') ? 'selected' : ''; ?>>Mobile Phone</option>
                            <option value="ram" <?php echo ($product['category'] == 'ram') ? 'selected' : ''; ?>>Memory / RAM</option>
                            <option value="storage" <?php echo ($product['category'] == 'storage') ? 'selected' : ''; ?>>Storage / SSD</option>
                            <option value="other" <?php echo ($product['category'] == 'other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Internal Link</label>
                    <input type="text" name="link" value="<?php echo htmlspecialchars($product['link']); ?>">
                </div>

                <div class="form-group">
                    <label>Replace Image (File)</label>
                    <input type="file" name="image_file" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Replace Image (URL)</label>
                    <input type="text" name="image_path" placeholder="https://cdn.example.com/new_image.png">
                </div>

                <button type="submit" class="btn-submit">COMMIT CHANGES</button>
            </form>

            <a href="admin_dashboard.php" class="back-nav"><i class="fa-solid fa-arrow-left"></i> Return to Inventory</a>
        </div>
    </div>

</body>
</html>
