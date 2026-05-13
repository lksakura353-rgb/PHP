<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['login']) || isset($_POST['email_or_phone']))) {
    $username = $_POST['email_or_phone'] ?? $_POST['username'] ?? '';
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
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
/*Username: admin
Password: admin1212*/
?>