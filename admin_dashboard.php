<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
require_once 'db_connect.php';

// Handle delete action
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header('Location: admin_dashboard.php');
    exit;
}

// Category filter
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch products based on filter or all
$sql = "SELECT * FROM products";
$params = [];
if ($category_filter) {
    $sql .= " WHERE category = ?";
    $params[] = $category_filter;
}
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Statistics calculations
$totalProducts = count($products);
$inStock = count(array_filter($products, function($p) { return $p['in_stock']; }));
$totalValuation = array_reduce($products, function($carry, $item) { return $carry + $item['price']; }, 0);
$avgPrice = $totalProducts > 0 ? $totalValuation / $totalProducts : 0;

// Category Analysis for Pie Charts
// 1. Stock Share (By Product Count)
$category_stmt = $pdo->query("SELECT category, COUNT(*) as count FROM products GROUP BY category");
$category_counts = $category_stmt->fetchAll();
$categories_json = json_encode(array_column($category_counts, 'category'));
$counts_json = json_encode(array_column($category_counts, 'count'));

// 2. Value Share (By Total Price per Category)
$value_stmt = $pdo->query("SELECT category, SUM(price) as total_value FROM products GROUP BY category");
$category_values = $value_stmt->fetchAll();
$value_categories_json = json_encode(array_column($category_values, 'category'));
$values_json = json_encode(array_column($category_values, 'total_value'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNova | Pie Analytics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-glow: rgba(139, 92, 246, 0.3);
            --secondary: #6366f1;
            --accent: #d946ef;
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
            overflow-x: hidden;
        }

        /* Sidebar Style */
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

        .logo-wrap {
            padding: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-wrap img { width: 35px; }

        .logo-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.2rem;
            letter-spacing: 1px;
            font-weight: 700;
            color: white;
        }

        .menu-section { padding: 0 15px; margin-bottom: 25px; }
        .menu-label { font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; display: block; padding-left: 15px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 12px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .nav-link:hover { color: var(--text-main); background: rgba(255, 255, 255, 0.03); }
        .nav-link.active { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            padding-top: 100px;
        }

        /* Top Bar */
        .top-bar {
            position: fixed;
            top: 0;
            right: 0;
            left: 260px;
            height: 70px;
            background: rgba(3, 7, 18, 0.8);
            backdrop-filter: blur(16px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            border-bottom: 1px solid var(--border);
            z-index: 80;
        }

        /* Pie View Layout */
        .pie-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }

        .pie-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 30px;
            text-align: center;
        }

        .pie-card h3 { margin-bottom: 25px; font-size: 1.2rem; font-weight: 600; text-align: left; display: flex; align-items: center; gap: 10px; }

        .chart-box { height: 350px; position: relative; }

        /* Stats Strip */
        .stats-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-mini {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
        }

        .stat-mini h4 { font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 5px; }
        .stat-mini div { font-size: 1.4rem; font-weight: 700; color: white; }

        /* Table */
        .table-section {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
        }

        .table-header {
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
        }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px 25px; font-size: 0.8rem; color: var(--text-secondary); background: rgba(255,255,255,0.01); }
        td { padding: 18px 25px; border-bottom: 1px solid var(--border); }

        .prod-img { width: 40px; height: 40px; border-radius: 8px; background: white; padding: 5px; object-fit: contain; }
        .stock-badge { font-size: 0.7rem; padding: 4px 10px; border-radius: 12px; font-weight: 600; }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

        .action-btns { display: flex; gap: 8px; }
        .btn-icon { width: 30px; height: 30px; border-radius: 6px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); text-decoration: none; }
        .btn-icon:hover { background: rgba(255,255,255,0.05); }

        @media (max-width: 1024px) {
            .pie-grid { grid-template-columns: 1fr; }
            .stats-strip { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-wrap">
            <img src="tecNove.png" alt="Logo">
            <span class="logo-text">TechNova</span>
        </div>

        <div class="menu-section">
            <span class="menu-label">Main Panel</span>
            <div class="nav-items">
                <a href="admin_dashboard.php" class="nav-link active"><i class="fa-solid fa-chart-pie"></i> <span>Pie Analytics</span></a>
                <a href="index.php" class="nav-link"><i class="fa-solid fa-eye"></i> <span>View Site</span></a>
            </div>
        </div>

        <div class="menu-section">
            <span class="menu-label">Actions</span>
            <div class="nav-items">
                <a href="add_item.php" class="nav-link"><i class="fa-solid fa-plus-circle"></i> <span>Add New Item</span></a>
            </div>
        </div>

        <div class="menu-section" style="margin-top: auto; padding-bottom: 20px;">
            <a href="admin_auth.php?logout=1" class="nav-link" style="color: var(--danger);">
                <i class="fa-solid fa-sign-out"></i> <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div style="font-weight: 600; font-size: 1.1rem; color: var(--text-secondary);">Pie Overview Dashboard</div>
            <div style="font-size: 0.85rem; opacity: 0.6;"><i class="fa-solid fa-clock"></i> <span id="clock-display"></span></div>
        </div>

        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 1.8rem; margin-bottom: 10px;">Analysis Overview</h1>
            <p style="color: var(--text-secondary);">Comparing Inventory Distribution and Financial Weight per Category.</p>
        </div>

        <!-- Stats Strip -->
        <div class="stats-strip">
            <div class="stat-mini">
                <h4>PRODUCTS</h4>
                <div><?php echo $totalProducts; ?></div>
            </div>
            <div class="stat-mini">
                <h4>STOCK %</h4>
                <div><?php echo round(($inStock / ($totalProducts ?: 1)) * 100); ?>%</div>
            </div>
            <div class="stat-mini">
                <h4>AVG PRICE</h4>
                <div>$<?php echo number_format($avgPrice); ?></div>
            </div>
            <div class="stat-mini">
                <h4>VALUATION</h4>
                <div>$<?php echo number_format($totalValuation / 1000, 1); ?>K</div>
            </div>
        </div>

        <!-- Pie Charts View -->
        <div class="pie-grid">
            <div class="pie-card">
                <h3><i class="fa-solid fa-layer-group" style="color: var(--primary);"></i> Inventory Vol. (By Units)</h3>
                <div class="chart-box">
                    <canvas id="volPie"></canvas>
                </div>
            </div>
            <div class="pie-card">
                <h3><i class="fa-solid fa-dollar-sign" style="color: var(--accent);"></i> Financial Dist. (By Value)</h3>
                <div class="chart-box">
                    <canvas id="valuePie"></canvas>
                </div>
            </div>
        </div>

        <!-- Inventory List -->
        <div class="table-section">
            <div class="table-header">
                <h4 style="font-size: 1.1rem;">Quick Inventory Reference</h4>
                <div style="display:flex; gap:10px;">
                    <a href="add_item.php" style="background: var(--primary); padding: 8px 15px; border-radius: 10px; color: white; text-decoration: none; font-size: 0.85rem; font-weight: 600;">NEW PRODUCT</a>
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(array_slice($products, 0, 10) as $p): ?>
                        <tr>
                            <td style="display: flex; align-items: center; gap: 12px;">
                                <img src="<?php echo htmlspecialchars($p['image']); ?>" class="prod-img" onerror="this.src='https://via.placeholder.com/60?text=Item'">
                                <div style="font-weight: 500; font-size: 0.9rem;"><?php echo htmlspecialchars($p['title']); ?></div>
                            </td>
                            <td><span style="text-transform: capitalize; opacity: 0.7; font-size: 0.85rem;"><?php echo htmlspecialchars($p['category']); ?></span></td>
                            <td><span style="font-weight: 700; color: #818cf8;">$<?php echo number_format($p['price']); ?></span></td>
                            <td>
                                <span class="stock-badge <?php echo $p['in_stock'] ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo $p['in_stock'] ? 'IN STOCK' : 'OUT'; ?>
                                </span>
                            </td>
                            <td class="action-btns">
                                <a href="edit_item.php?id=<?php echo $p['id']; ?>" class="btn-icon"><i class="fa-solid fa-edit"></i></a>
                                <a href="admin_dashboard.php?delete_id=<?php echo $p['id']; ?>" class="btn-icon" onclick="return confirm('Delete item?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Clock
        setInterval(() => {
            document.getElementById('clock-display').innerText = new Date().toLocaleTimeString();
        }, 1000);

        const colors = ['#8b5cf6', '#6366f1', '#d946ef', '#10b981', '#f59e0b', '#ef4444', '#3b82f6'];

        // Pie Chart 1: Volume
        new Chart(document.getElementById('volPie'), {
            type: 'pie',
            data: {
                labels: <?php echo $categories_json; ?>,
                datasets: [{
                    data: <?php echo $counts_json; ?>,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#111827'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#94a3b8', padding: 20 } }
                }
            }
        });

        // Pie Chart 2: Value
        new Chart(document.getElementById('valuePie'), {
            type: 'pie',
            data: {
                labels: <?php echo $value_categories_json; ?>,
                datasets: [{
                    data: <?php echo $values_json; ?>,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#111827'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#94a3b8', padding: 20 } }
                }
            }
        });
    </script>
</body>
</html>
