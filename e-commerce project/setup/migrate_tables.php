<?php
include '../db.php';

// Step 1: Drop existing tables if using old structure
$conn->query("DROP TABLE IF EXISTS products");
$conn->query("DROP TABLE IF EXISTS brands");
$conn->query("DROP TABLE IF EXISTS categories");

// Step 2: Create brands table with correct structure
$sql = "CREATE TABLE IF NOT EXISTS brands (
    brand_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    brand_title VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    echo "Error creating brands table: " . $conn->error;
    exit;
}

// Step 3: Create categories table with correct structure
$sql = "CREATE TABLE IF NOT EXISTS categories (
    category_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    category_title VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    echo "Error creating categories table: " . $conn->error;
    exit;
}

// Step 4: Create products table with correct foreign keys
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
    echo ".table-info { background-color: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0; text-align: left; }";
    echo ".table-info h3 { margin-top: 0; color: #2e7d32; }";
    echo ".table-info p { text-align: left; margin: 5px 0; }";
    echo "</style></head>";
    echo "<body>";
    echo "<div class='container'>";
    echo "<div class='success-icon'>✅</div>";
    echo "<h2>Database Tables Created Successfully!</h2>";
    echo "<p>All tables have been created with the correct structure.</p>";
    
    echo "<div class='table-info'>";
    echo "<h3>📦 Brands Table</h3>";
    echo "<p><strong>Column 1:</strong> brand_id (INT(11)) - Primary Key</p>";
    echo "<p><strong>Column 2:</strong> brand_title (VARCHAR(100))</p>";
    echo "</div>";
    
    echo "<div class='table-info'>";
    echo "<h3>📝 Categories Table</h3>";
    echo "<p><strong>Column 1:</strong> category_id (INT(11)) - Primary Key</p>";
    echo "<p><strong>Column 2:</strong> category_title (VARCHAR(100))</p>";
    echo "</div>";
    
    echo "<div class='table-info'>";
    echo "<h3>🛍️ Products Table</h3>";
    echo "<p><strong>Columns:</strong> id, name, description, price, image, brand_id, category_id, created_at</p>";
    echo "</div>";
    
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
    echo "<h2>❌ Error Creating Tables</h2>";
    echo "<div class='error'>";
    echo "<strong>Error Message:</strong><br>" . htmlspecialchars($conn->error);
    echo "</div>";
    echo "<p style='text-align: center; margin-top: 30px;'>";
    echo "<a href='migrate_tables.php'>Try Again →</a>";
    echo "</p>";
    echo "</div>";
    echo "</body></html>";
}

$conn->close();
?>
