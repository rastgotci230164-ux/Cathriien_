<?php
// Database Configuration
$host = '127.0.0.1';
$db_user = 'root';
$db_password = '';
$db_name = 'e-commerce';

// Create connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

?>
