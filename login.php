<?php
session_start();
require_once 'db_connect.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['email_or_phone'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            $is_correct = false;
            
            // 1. Try modern password_verify
            if (password_verify($password, $user['password'])) {
                $is_correct = true;
            } 
            // 2. Fallback for plain text (for initial setup)
            elseif ($password === $user['password']) {
                $is_correct = true;
                // Auto-fix the database: Hash the plain text password now!
                $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $update_stmt->execute([$hashed_pw, $user['id']]);
            }

            if ($is_correct) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_id'] = $user['id'];
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $error = "Invalid credentials. Please try again.";
            }
        } else {
            $error = "Invalid credentials. Please try again.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNova | Admin Portal</title>
    <!-- External Icons and Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style1.css">
    <link rel="shortcut icon" type="image/png" href="tecNove (2).png">
    <style>
        .error-msg {
            background-color: #ffcccc;
            color: #cc0000;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <!-- Animated Background Blobs (The "Plazz") -->
    <div class="blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="main-container" id="container">
        <!-- Left Brand Section - Based on tecNove (1).png -->
        <div class="brand-section">
            <img src="tecNove (1).png" alt="TechNova Logo">
        </div>

        <!-- Right Forms Section -->
        <div class="forms-section">

            <!-- Login Form (Welcome back) -->
            <div class="form-content login-form" id="loginForm">
                <div class="form-header">
                    <span>TechNova | Admin</span>
                    <h2>Admin Access</h2>
                    <p>Enter your credentials to manage shop inventory.</p>
                </div>

                <?php if ($error): ?>
                    <div class="error-msg"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="input-group">
                        <label class="input-label">Admin Username</label>
                        <div class="input-wrapper">
                            <i class="fa-regular fa-user"></i>
                            <input type="text" name="email_or_phone" placeholder="admin" required spellcheck="false">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="options-row">
                        <label class="remember-me">
                            <input type="checkbox">
                            Remember me
                        </label>
                        <a href="#" class="forgot-pass">Forgot Password?</a>
                    </div>

                    <button class="btn-primary" type="submit">Sign in (Admin)</button>
                </form>

                <div class="form-footer">
                   Return to Site? <a href="index.php">Click Here</a>
                </div>
            </div>

        </div>
    </div>

    <!-- Logic for switch and animations -->
    <script src="script2.js"></script>
</body>

</html>
