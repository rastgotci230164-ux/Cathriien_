<?php
include '../db.php';

// Create cart table
$sql = "CREATE TABLE IF NOT EXISTS cart (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    quantity INT(11) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";

if ($conn->query($sql)) {
    echo "✅ Cart table created successfully or already exists.<br>";
} else {
    echo "❌ Error creating cart table: " . $conn->error . "<br>";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Setup Cart Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            text-align: center;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #87CEEB;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #5BA8D8;
            color: white;
        }
    </style>
</head>
<body>
    <a href="home.php">Go to Home Page →</a>
</body>
</html>
