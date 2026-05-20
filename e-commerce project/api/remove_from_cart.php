<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

// Check if cart_id is provided
if (!isset($_POST['cart_id']) || empty($_POST['cart_id'])) {
    header('Location: ../pages/cart.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_id = intval($_POST['cart_id']);

// Delete item from cart (only if it belongs to the current user)
$sql = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";

if ($conn->query($sql)) {
    header('Location: ../pages/cart.php');
} else {
    echo "Error removing item from cart";
}

$conn->close();
?>
