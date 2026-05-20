<?php
// Database Configuration (without specifying database name first)
$host = '127.0.0.1';
$db_user = 'root';
$db_password = '';
$db_name = 'e-commerce';

// Create connection WITHOUT selecting a database first
$conn = new mysqli($host, $db_user, $db_password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Create database if it doesn't exist
$create_db = "CREATE DATABASE IF NOT EXISTS `e-commerce`";
if (!$conn->query($create_db)) {
    echo "<h2 style='color: red;'>❌ Error creating database: " . htmlspecialchars($conn->error) . "</h2>";
    exit;
}

// Step 2: Select the database
if (!$conn->select_db($db_name)) {
    echo "<h2 style='color: red;'>❌ Error selecting database: " . htmlspecialchars($conn->error) . "</h2>";
    exit;
}

// Step 3: Create brands table
$sql = "CREATE TABLE IF NOT EXISTS brands (
    brand_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    brand_title VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Step 4: Create categories table
$sql = "CREATE TABLE IF NOT EXISTS categories (
    category_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    category_title VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Step 5: Create products table with brand and category references
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(500),
    brand_id INT(11),
    category_id INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "<html>";
    echo "<head><style>";
    echo "body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }";
    echo ".container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }";
    echo "h2 { color: #28a745; text-align: center; margin-top: 0; }";
    echo "p { color: #666; text-align: center; line-height: 1.6; }";
    echo "a { background-color: #87CEEB; color: #333; padding: 12px 30px; border-radius: 4px; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 20px; }";
    echo ".success-icon { font-size: 48px; text-align: center; }";
    echo "</style></head>";
    echo "<body>";
    echo "<div class='container'>";
    echo "<div class='success-icon'>✅</div>";
    echo "<h2>Database Setup Successful!</h2>";
    echo "<p>The database <strong>'e-commerce'</strong> and tables <strong>'brands'</strong>, <strong>'categories'</strong>, and <strong>'products'</strong> have been created with the correct structure.</p>";
    echo "<p style='text-align: center; margin-top: 30px;'>";
    echo "<a href='insert_sample_data.php'>Insert Sample Data →</a> | ";
    echo "<a href='admin.php'>Go to Admin Dashboard →</a>";
    echo "</p>";
    echo "</div>";
    echo "</body></html>";
} else {
    echo "<html>";
    echo "<head><style>";
    echo "body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }";
    echo ".container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }";
    echo "h2 { color: #dc3545; text-align: center; margin-top: 0; }";
    echo "p { color: #666; text-align: center; line-height: 1.6; }";
    echo ".error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 20px 0; }";
    echo "a { background-color: #87CEEB; color: #333; padding: 12px 30px; border-radius: 4px; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 20px; }";
    echo "</style></head>";
    echo "<body>";
    echo "<div class='container'>";
    echo "<h2>❌ Error Creating Table</h2>";
    echo "<div class='error'>";
    echo "<strong>Error Message:</strong><br>" . htmlspecialchars($conn->error);
    echo "</div>";
    echo "<p style='text-align: center; margin-top: 30px;'>";
    echo "<a href='setup.php'>Try Again →</a>";
    echo "</p>";
    echo "</div>";
    echo "</body></html>";
}

$conn->close();
?>
