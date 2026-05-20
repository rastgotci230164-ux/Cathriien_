<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

// Check if cart_id and action are provided
if (!isset($_POST['cart_id']) || empty($_POST['cart_id']) || !isset($_POST['action'])) {
    header('Location: ../pages/cart.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_id = intval($_POST['cart_id']);
$action = $_POST['action'];

// Get current quantity
$sql = "SELECT quantity FROM cart WHERE id = $cart_id AND user_id = $user_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    header('Location: ../pages/cart.php');
    exit;
}

$row = $result->fetch_assoc();
$current_quantity = $row['quantity'];
$new_quantity = $current_quantity;

if ($action == 'increase') {
    $new_quantity = $current_quantity + 1;
} elseif ($action == 'decrease') {
    $new_quantity = $current_quantity - 1;
}

// If quantity is 0 or less, remove the item
if ($new_quantity <= 0) {
    $delete_sql = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";
    $conn->query($delete_sql);
} else {
    // Update quantity
    $update_sql = "UPDATE cart SET quantity = $new_quantity WHERE id = $cart_id AND user_id = $user_id";
    $conn->query($update_sql);
}

header('Location: ../pages/cart.php');
exit;

$conn->close();
?>
