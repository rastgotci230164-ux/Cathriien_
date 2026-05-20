<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'تکایە پێشتر بچوە ژوورەوە']);
    exit;
}

if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'ناسنامەی کاڵا پێویستە']);
    exit;
}

$user_id    = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);

$product_check = $conn->query("SELECT id, name FROM products WHERE id = $product_id");
if (!$product_check || $product_check->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'کاڵاکە نەدۆزرایەوە']);
    exit;
}

$product = $product_check->fetch_assoc();
$pname   = $product['name'];

$check_cart = $conn->query("SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id");

if ($check_cart && $check_cart->num_rows > 0) {
    $row         = $check_cart->fetch_assoc();
    $new_quantity = $row['quantity'] + 1;
    if ($conn->query("UPDATE cart SET quantity = $new_quantity WHERE id = " . $row['id'])) {
        echo json_encode(['success' => true, 'message' => '✓ ' . $pname . ' — ژمارە نوێکرایەوە']);
    } else {
        echo json_encode(['success' => false, 'message' => 'هەڵە لە نوێکردنەوەی سەبەتە']);
    }
} else {
    if ($conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)")) {
        echo json_encode(['success' => true, 'message' => '✓ ' . $pname . ' زیادکرا بۆ سەبەتە']);
    } else {
        echo json_encode(['success' => false, 'message' => 'هەڵە لە زیادکردنی کاڵا']);
    }
}

$conn->close();
?>
