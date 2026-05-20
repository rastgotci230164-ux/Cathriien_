<?php
include '../db.php';

// Step 1: Disable foreign key checks to allow dropping tables
$conn->query("SET FOREIGN_KEY_CHECKS=0");

// Step 2: Drop existing old tables to avoid conflicts
$conn->query("DROP TABLE IF EXISTS products");
$conn->query("DROP TABLE IF EXISTS brands");
$conn->query("DROP TABLE IF EXISTS categories");

// Step 3: Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS=1");

// Step 4: Create brands table with correct structure
$sql = "CREATE TABLE brands (
    brand_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    brand_title VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    echo "Error creating brands table: " . $conn->error;
    exit;
}

// Step 5: Create categories table with correct structure
$sql = "CREATE TABLE categories (
    category_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_title VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if (!$conn->query($sql)) {
    echo "Error creating categories table: " . $conn->error;
    exit;
}

// Step 6: Create products table with correct foreign keys
$sql = "CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(500),
    brand_id INT(11),
    category_id INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
) ENGINE=InnoDB";

if ($conn->query($sql) === TRUE) {
    echo "<html>";
    echo "<head><style>";
    echo "body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }";
    echo ".container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }";
    echo "h2 { color: #28a745; text-align: center; margin-top: 0; }";
    echo "p { color: #666; text-align: center; line-height: 1.6; }";
    echo "a { background-color: #87CEEB; color: #333; padding: 12px 30px; border-radius: 4px; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 20px; margin-left: 10px; }";
    echo ".success-icon { font-size: 48px; text-align: center; }";
    echo ".table-info { background-color: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0; text-align: left; }";
    echo ".table-info h3 { margin-top: 0; color: #2e7d32; }";
    echo ".table-info p { text-align: left; margin: 5px 0; font-size: 14px; }";
    echo ".button-group { text-align: center; margin-top: 30px; }";
    echo "</style></head>";
    echo "<body>";
    echo "<div class='container'>";
    echo "<div class='success-icon'>✅</div>";
    echo "<h2>Database Tables Migrated Successfully!</h2>";
    echo "<p>All old tables have been dropped and recreated with the correct structure.</p>";
    
    echo "<div class='table-info'>";
    echo "<h3>📦 Brands Table</h3>";
    echo "<p>✓ brand_id (INT(11)) - Primary Key, Auto Increment</p>";
    echo "<p>✓ brand_title (VARCHAR(100)) - Unique</p>";
    echo "<p>✓ created_at (TIMESTAMP)</p>";
    echo "</div>";
    
    echo "<div class='table-info'>";
    echo "<h3>📝 Categories Table</h3>";
    echo "<p>✓ category_id (INT(11)) - Primary Key, Auto Increment</p>";
    echo "<p>✓ category_title (VARCHAR(100)) - Unique</p>";
    echo "<p>✓ created_at (TIMESTAMP)</p>";
    echo "</div>";
    
    echo "<div class='table-info'>";
    echo "<h3>🛍️ Products Table</h3>";
    echo "<p>✓ id (INT) - Primary Key</p>";
    echo "<p>✓ name, description, price, image</p>";
    echo "<p>✓ brand_id (FK to brands)</p>";
    echo "<p>✓ category_id (FK to categories)</p>";
    echo "</div>";
    
    echo "<div class='button-group'>";
    echo "<a href='insert_sample_data.php'>📥 Insert Sample Data</a>";
    echo "<a href='admin.php'>🛠️ Admin Dashboard</a>";
    echo "</div>";
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
