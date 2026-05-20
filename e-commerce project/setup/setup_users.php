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

// Create users table if it doesn't exist
$create_users_table = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($create_users_table) === TRUE) {
    echo "<h2 style='color: green;'>✅ Users table created or already exists!</h2>";
} else {
    echo "<h2 style='color: red;'>❌ Error creating users table: " . $conn->error . "</h2>";
}

// Check if table was created
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result && $result->num_rows > 0) {
    echo "<h3>Users Table Structure:</h3>";
    $fields = $conn->query("DESCRIBE users");
    echo "<ul>";
    while ($field = $fields->fetch_assoc()) {
        echo "<li><strong>" . $field['Field'] . "</strong> (" . $field['Type'] . ") - " . ($field['Null'] === 'NO' ? 'NOT NULL' : 'Nullable') . "</li>";
    }
    echo "</ul>";
}

echo "<p><a href='home.php'>Back to Home</a></p>";

$conn->close();
?>
