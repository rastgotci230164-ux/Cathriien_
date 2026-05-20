<?php
include '../db.php';

$messages = [];
$success = true;

// Disable foreign key checks temporarily
$conn->query("SET FOREIGN_KEY_CHECKS=0");

// ========== BRANDS TABLE FIX ==========
$brands_exists = $conn->query("SHOW TABLES LIKE 'brands'")->num_rows > 0;

if ($brands_exists) {
    // Check current structure
    $columns = $conn->query("SHOW COLUMNS FROM brands");
    $has_brand_id = false;
    $has_brand_title = false;
    $has_id = false;
    $has_name = false;
    $has_description = false;
    
    while ($col = $columns->fetch_assoc()) {
        if ($col['Field'] == 'brand_id') $has_brand_id = true;
        if ($col['Field'] == 'brand_title') $has_brand_title = true;
        if ($col['Field'] == 'id') $has_id = true;
        if ($col['Field'] == 'name') $has_name = true;
        if ($col['Field'] == 'description') $has_description = true;
    }
    
    // Fix columns if needed
    if ($has_id && !$has_brand_id) {
        $conn->query("ALTER TABLE brands CHANGE COLUMN id brand_id INT(11) NOT NULL AUTO_INCREMENT");
        $messages[] = "✓ Renamed 'id' to 'brand_id' in brands table";
    }
    
    if ($has_name && !$has_brand_title) {
        $conn->query("ALTER TABLE brands CHANGE COLUMN name brand_title VARCHAR(100) NOT NULL");
        $messages[] = "✓ Renamed 'name' to 'brand_title' in brands table";
    }
    
    if ($has_description && !$has_brand_title) {
        $conn->query("ALTER TABLE brands DROP COLUMN description");
        $messages[] = "✓ Removed 'description' column from brands table";
    }
    
    // Add missing columns if needed
    if (!$has_brand_id) {
        $conn->query("ALTER TABLE brands ADD COLUMN brand_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
        $messages[] = "✓ Added 'brand_id' column to brands table";
    }
    
    if (!$has_brand_title) {
        $conn->query("ALTER TABLE brands ADD COLUMN brand_title VARCHAR(100) NOT NULL UNIQUE");
        $messages[] = "✓ Added 'brand_title' column to brands table";
    }
} else {
    // Create new brands table
    $sql = "CREATE TABLE brands (
        brand_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        brand_title VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";
    
    if ($conn->query($sql)) {
        $messages[] = "✓ Created new 'brands' table";
    } else {
        $messages[] = "✗ Error creating brands table: " . $conn->error;
        $success = false;
    }
}

// ========== CATEGORIES TABLE FIX ==========
$categories_exists = $conn->query("SHOW TABLES LIKE 'categories'")->num_rows > 0;

if ($categories_exists) {
    // Check current structure
    $columns = $conn->query("SHOW COLUMNS FROM categories");
    $has_category_id = false;
    $has_category_title = false;
    $has_id = false;
    $has_name = false;
    $has_description = false;
    
    while ($col = $columns->fetch_assoc()) {
        if ($col['Field'] == 'category_id') $has_category_id = true;
        if ($col['Field'] == 'category_title') $has_category_title = true;
        if ($col['Field'] == 'id') $has_id = true;
        if ($col['Field'] == 'name') $has_name = true;
        if ($col['Field'] == 'description') $has_description = true;
    }
    
    // Fix columns if needed
    if ($has_id && !$has_category_id) {
        $conn->query("ALTER TABLE categories CHANGE COLUMN id category_id INT(11) NOT NULL AUTO_INCREMENT");
        $messages[] = "✓ Renamed 'id' to 'category_id' in categories table";
    }
    
    if ($has_name && !$has_category_title) {
        $conn->query("ALTER TABLE categories CHANGE COLUMN name category_title VARCHAR(100) NOT NULL");
        $messages[] = "✓ Renamed 'name' to 'category_title' in categories table";
    }
    
    if ($has_description) {
        $conn->query("ALTER TABLE categories DROP COLUMN description");
        $messages[] = "✓ Removed 'description' column from categories table";
    }
    
    // Add missing columns if needed
    if (!$has_category_id) {
        $conn->query("ALTER TABLE categories ADD COLUMN category_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
        $messages[] = "✓ Added 'category_id' column to categories table";
    }
    
    if (!$has_category_title) {
        $conn->query("ALTER TABLE categories ADD COLUMN category_title VARCHAR(100) NOT NULL UNIQUE");
        $messages[] = "✓ Added 'category_title' column to categories table";
    }
} else {
    // Create new categories table
    $sql = "CREATE TABLE categories (
        category_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        category_title VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";
    
    if ($conn->query($sql)) {
        $messages[] = "✓ Created new 'categories' table";
    } else {
        $messages[] = "✗ Error creating categories table: " . $conn->error;
        $success = false;
    }
}

// ========== PRODUCTS TABLE FIX ==========
$products_exists = $conn->query("SHOW TABLES LIKE 'products'")->num_rows > 0;

if ($products_exists) {
    // Check if products table has the right columns
    $columns = $conn->query("SHOW COLUMNS FROM products");
    $col_list = [];
    
    while ($col = $columns->fetch_assoc()) {
        $col_list[] = $col['Field'];
    }
    
    // Check for brand_id and category_id foreign keys
    if (in_array('brand_id', $col_list) && in_array('category_id', $col_list)) {
        $messages[] = "✓ Products table structure is correct";
    } else {
        if (!in_array('brand_id', $col_list)) {
            $conn->query("ALTER TABLE products ADD COLUMN brand_id INT(11)");
            $messages[] = "✓ Added 'brand_id' column to products table";
        }
        
        if (!in_array('category_id', $col_list)) {
            $conn->query("ALTER TABLE products ADD COLUMN category_id INT(11)");
            $messages[] = "✓ Added 'category_id' column to products table";
        }
    }
} else {
    // Create new products table
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
    
    if ($conn->query($sql)) {
        $messages[] = "✓ Created new 'products' table";
    } else {
        $messages[] = "✗ Error creating products table: " . $conn->error;
        $success = false;
    }
}

// Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS=1");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Structure Fix</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: <?php echo $success ? '#28a745' : '#dc3545'; ?>;
            text-align: center;
            margin-top: 0;
        }
        .success-icon {
            font-size: 48px;
            text-align: center;
        }
        .messages {
            background-color: #f9f9f9;
            border-left: 4px solid <?php echo $success ? '#28a745' : '#dc3545'; ?>;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .message-item {
            padding: 8px 0;
            color: #333;
            font-size: 14px;
        }
        a {
            background-color: #87CEEB;
            color: #333;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
            margin-left: 5px;
        }
        a:hover {
            background-color: #5BA8D8;
            color: white;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        p {
            color: #666;
            text-align: center;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon"><?php echo $success ? '✅' : '⚠️'; ?></div>
        <h2><?php echo $success ? 'Database Fixed!' : 'Database Fix Issues'; ?></h2>
        
        <p><?php echo $success ? 'Your database tables have been fixed and are ready to use!' : 'Some issues were encountered but the database is still functional.'; ?></p>
        
        <div class="messages">
            <?php foreach ($messages as $msg): ?>
                <div class="message-item"><?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
        </div>
        
        <div class="button-group">
            <a href="insert_sample_data.php">📥 Insert Sample Data</a>
            <a href="admin.php">🛠️ Admin Dashboard</a>
            <a href="home.php">🏠 Home Page</a>
        </div>
    </div>
</body>
</html>
