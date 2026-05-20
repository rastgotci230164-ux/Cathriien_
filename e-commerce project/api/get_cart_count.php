<?php
session_start();
include '../db.php';

$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart_result = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
    if ($cart_result) {
        $row = $cart_result->fetch_assoc();
        $cart_count = $row['total'] ? $row['total'] : 0;
    }
}

header('Content-Type: application/json');
echo json_encode(['count' => $cart_count]);

$conn->close();
?>
