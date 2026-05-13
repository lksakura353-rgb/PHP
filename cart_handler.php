<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $product_id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $price = $_POST['price'] ?? 0;
    $image = $_POST['image'] ?? '';

    if ($product_id) {
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product_id,
                'title' => $title,
                'price' => $price,
                'image' => $image,
                'quantity' => 1
            ];
        }
    }
    echo json_encode(['status' => 'success', 'cart_count' => get_cart_count()]);
    exit;
}

if ($action === 'remove') {
    $product_id = $_POST['id'] ?? '';
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    echo json_encode(['status' => 'success', 'cart_count' => get_cart_count()]);
    exit;
}

if ($action === 'get_cart') {
    echo json_encode([
        'items' => $_SESSION['cart'],
        'cart_count' => get_cart_count(),
        'total' => get_cart_total()
    ]);
    exit;
}

function get_cart_count() {
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

function get_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
?>
