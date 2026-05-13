<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
require_once 'db_connect.php';

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $link = $_POST['link'] ?: 'index.php'; // Default link
    $image_path = ""; 

    // Basic file upload handling for images
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
    } else {
        $image_path = $_POST['image_path'] ?: "image/placeholder.png"; 
    }

    if (!$error && !empty($title) && !empty($price)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (title, price, image, link, category) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $price, $image_path, $link, $category]);
            $message = "Product listing created successfully!";
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    } else if (!$error) {
        $error = "Title and Price are mandatory fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNova | Add Inventory</title>
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
        .logo-section h2 { font-family: 'Montserrat', sans-serif; font-size: 1.1rem; letter-spacing: 1px; }

        .nav-items { padding: 0 15px; display: flex; flex-direction: column; gap: 5px; }
        .nav-link {
            padding: 12px 15px; border-radius: 12px; text-decoration: none;
            color: var(--text-secondary); display: flex; align-items: center; gap: 12px; transition: 0.2s;
        }
        .nav-link:hover, .nav-link.active { background: rgba(255, 255, 255, 0.03); color: var(--text-main); }
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
        input:focus, select:focus { border-color: var(--primary); background: rgba(255, 255, 255, 0.05); }

        .file-input-wrapper {
            position: relative; border: 2px dashed var(--border); border-radius: 12px; padding: 20px;
            text-align: center; cursor: pointer; transition: 0.3s;
        }
        .file-input-wrapper:hover { border-color: var(--primary); background: rgba(139, 92, 246, 0.05); }
        .file-input-wrapper input[type="file"] { position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer; }
        .file-icon { font-size: 1.5rem; color: var(--primary); margin-bottom: 10px; display: block; }

        .btn-submit {
            width: 100%; padding: 16px; border-radius: 14px; border: none;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; font-weight: 700; font-size: 1rem; cursor: pointer;
            margin-top: 20px; transition: 0.3s;
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(139, 92, 246, 0.4); }

        .status-msg { padding: 15px; border-radius: 12px; margin-bottom: 25px; text-align: center; font-weight: 500; font-size: 0.95rem; }
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
            <a href="add_item.php" class="nav-link active"><i class="fa-solid fa-circle-plus"></i> New Listing</a>
            <a href="index.php" class="nav-link"><i class="fa-solid fa-globe"></i> Marketplace</a>
        </div>
        <div style="margin-top: auto; padding-bottom: 30px;" class="nav-items">
             <a href="admin_auth.php?logout=1" class="nav-link" style="color: var(--danger);"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="form-card">
            <div class="header-title">
                <h1>Manage Inventory</h1>
                <p>Register a new hardware component to the store listings.</p>
            </div>

            <?php if($message): ?> <div class="status-msg success"><i class="fa-solid fa-check-circle"></i> <?php echo $message; ?></div> <?php endif; ?>
            <?php if($error): ?> <div class="status-msg error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?></div> <?php endif; ?>

            <form action="add_item.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="title" placeholder="e.g. NVIDIA GeForce RTX 5080Ti" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Base Price (USD)</label>
                        <input type="number" step="0.01" name="price" placeholder="999.00" required>
                    </div>
                    <div class="form-group">
                        <label>Stock Category</label>
                        <select name="category">
                            <option value="gpu">Graphic Card (GPU)</option>
                            <option value="processor">Processor (CPU)</option>
                            <option value="motherboard">Motherboard</option>
                            <option value="phone">Mobile Phone</option>
                            <option value="ram">Memory / RAM</option>
                            <option value="storage">Storage / SSD</option>
                            <option value="other">General Hardware</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Internal Navigation Link</label>
                    <input type="text" name="link" placeholder="Default: index.php">
                </div>

                <div class="form-group">
                    <label>Visual Asset</label>
                    <div class="file-input-wrapper">
                        <i class="fa-solid fa-cloud-arrow-up file-icon"></i>
                        <div style="font-weight: 600; margin-bottom: 5px;">Drop image here or click</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">SVG, PNG, JPG or WEBP</div>
                        <input type="file" name="image_file" accept="image/*">
                    </div>
                </div>

                <div style="text-align: center; margin: 0 0 25px 0; color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;">- OR PROVIDE URL -</div>

                <div class="form-group">
                    <input type="text" name="image_path" placeholder="https://cdn.example.com/product.png">
                </div>

                <button type="submit" class="btn-submit">EXPAND INVENTORY</button>
            </form>

            <a href="admin_dashboard.php" class="back-nav"><i class="fa-solid fa-arrow-left"></i> Discard and Exit</a>
        </div>
    </div>

</body>
</html>
