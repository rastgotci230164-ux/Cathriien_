<?php
include '../db.php';

// Arrays of sample data
$brands = [
    ['title' => 'Apple'],
    ['title' => 'Samsung'],
    ['title' => 'Sony'],
    ['title' => 'Intel'],
    ['title' => 'Nike']
];

$categories = [
    ['title' => 'Electronics'],
    ['title' => 'Smartphones'],
    ['title' => 'Computers'],
    ['title' => 'Sports & Fitness'],
    ['title' => 'Audio']
];

$products = [
    ['name' => 'iPhone 15 Pro', 'description' => 'Latest iPhone with advanced camera and processor', 'price' => 999.99, 'image' => 'https://via.placeholder.com/300?text=iPhone+15+Pro', 'brand_id' => 1, 'category_id' => 2],
    ['name' => 'Samsung Galaxy S24', 'description' => 'Flagship Android smartphone with 5G support', 'price' => 899.99, 'image' => 'https://via.placeholder.com/300?text=Galaxy+S24', 'brand_id' => 2, 'category_id' => 2],
    ['name' => 'Sony WH-1000XM5', 'description' => 'Premium noise-cancelling wireless headphones', 'price' => 399.99, 'image' => 'https://via.placeholder.com/300?text=Sony+Headphones', 'brand_id' => 3, 'category_id' => 5],
    ['name' => 'MacBook Pro 16"', 'description' => 'Powerful laptop for professionals with M3 Pro chip', 'price' => 1999.99, 'image' => 'https://via.placeholder.com/300?text=MacBook+Pro', 'brand_id' => 1, 'category_id' => 3],
    ['name' => 'Intel Core i9', 'description' => 'High-performance desktop processor for gaming and workstations', 'price' => 589.99, 'image' => 'https://via.placeholder.com/300?text=Intel+i9', 'brand_id' => 4, 'category_id' => 3],
    ['name' => 'Nike Air Max 270', 'description' => 'Comfortable and stylish running shoes', 'price' => 129.99, 'image' => 'https://via.placeholder.com/300?text=Nike+Shoes', 'brand_id' => 5, 'category_id' => 4],
    ['name' => 'Samsung 4K Smart TV', 'description' => '55-inch 4K QLED Smart TV with Quantum Processor', 'price' => 699.99, 'image' => 'https://via.placeholder.com/300?text=Samsung+TV', 'brand_id' => 2, 'category_id' => 1],
    ['name' => 'Apple AirPods Pro', 'description' => 'Wireless earbuds with Active Noise Cancellation', 'price' => 249.99, 'image' => 'https://via.placeholder.com/300?text=AirPods+Pro', 'brand_id' => 1, 'category_id' => 5],
    ['name' => 'Sony Alpha A7IV', 'description' => 'Professional mirrorless camera for photography', 'price' => 1998.00, 'image' => 'https://via.placeholder.com/300?text=Sony+Camera', 'brand_id' => 3, 'category_id' => 1],
    ['name' => 'Nike Running Shorts', 'description' => 'Lightweight and breathable shorts for running', 'price' => 49.99, 'image' => 'https://via.placeholder.com/300?text=Nike+Shorts', 'brand_id' => 5, 'category_id' => 4],
    ['name' => 'Samsung Galaxy Buds', 'description' => 'True wireless earbuds with immersive sound', 'price' => 179.99, 'image' => 'https://via.placeholder.com/300?text=Galaxy+Buds', 'brand_id' => 2, 'category_id' => 5],
    ['name' => 'iPad Pro 12.9"', 'description' => 'Powerful tablet for creative professionals', 'price' => 1099.99, 'image' => 'https://via.placeholder.com/300?text=iPad+Pro', 'brand_id' => 1, 'category_id' => 3]
];

// Insert brands
$brands_inserted = 0;
foreach ($brands as $brand) {
    // Check if brand already exists
    $check = $conn->query("SELECT brand_id FROM brands WHERE brand_title = '" . $conn->real_escape_string($brand['title']) . "'");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO brands (brand_title) VALUES ('" . $conn->real_escape_string($brand['title']) . "')";
        if ($conn->query($sql)) {
            $brands_inserted++;
        }
    }
}

// Insert categories
$categories_inserted = 0;
foreach ($categories as $category) {
    // Check if category already exists
    $check = $conn->query("SELECT category_id FROM categories WHERE category_title = '" . $conn->real_escape_string($category['title']) . "'");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO categories (category_title) VALUES ('" . $conn->real_escape_string($category['title']) . "')";
        if ($conn->query($sql)) {
            $categories_inserted++;
        }
    }
}

// Get brand and category IDs from database
$brand_mapping = [];
$brands_result = $conn->query("SELECT brand_id, brand_title FROM brands");
while ($row = $brands_result->fetch_assoc()) {
    $brand_mapping[$row['brand_title']] = $row['brand_id'];
}

$category_mapping = [];
$categories_result = $conn->query("SELECT category_id, category_title FROM categories");
while ($row = $categories_result->fetch_assoc()) {
    $category_mapping[$row['category_title']] = $row['category_id'];
}

// Insert products
$products_inserted = 0;
foreach ($products as $product) {
    // Get brand_id and category_id from mapping
    $brand_id = isset($brand_mapping[$brands[$product['brand_id']-1]['title']]) ? $brand_mapping[$brands[$product['brand_id']-1]['title']] : null;
    $category_id = isset($category_mapping[$categories[$product['category_id']-1]['title']]) ? $category_mapping[$categories[$product['category_id']-1]['title']] : null;
    
    // Check if product already exists
    $check = $conn->query("SELECT id FROM products WHERE name = '" . $conn->real_escape_string($product['name']) . "'");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO products (name, description, price, image, brand_id, category_id) VALUES ('" 
            . $conn->real_escape_string($product['name']) . "', '" 
            . $conn->real_escape_string($product['description']) . "', " 
            . $product['price'] . ", '" 
            . $conn->real_escape_string($product['image']) . "', " 
            . ($brand_id ? $brand_id : 'NULL') . ", " 
            . ($category_id ? $category_id : 'NULL') . ")";
        
        if ($conn->query($sql)) {
            $products_inserted++;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sample Data Insert</title>
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
            color: #28a745;
            text-align: center;
            margin-top: 0;
        }
        .success-icon {
            font-size: 48px;
            text-align: center;
        }
        p {
            color: #666;
            text-align: center;
            line-height: 1.6;
            margin: 15px 0;
        }
        .stats {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .stat-item {
            padding: 8px 0;
            color: #2e7d32;
            font-weight: bold;
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
        }
        a:hover {
            background-color: #5BA8D8;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✅</div>
        <h2>Sample Data Inserted Successfully!</h2>
        
        <div class="stats">
            <div class="stat-item">📦 Brands Inserted: <?php echo $brands_inserted; ?></div>
            <div class="stat-item">📝 Categories Inserted: <?php echo $categories_inserted; ?></div>
            <div class="stat-item">🛍️ Products Inserted: <?php echo $products_inserted; ?></div>
        </div>
        
        <p style="color: #333; margin-top: 30px;">Your e-commerce database is now populated with sample data including:</p>
        <ul style="text-align: left; color: #666; margin: 0 auto; width: fit-content;">
            <li>5 Brand entries (Apple, Samsung, Sony, Intel, Nike)</li>
            <li>5 Category entries (Electronics, Smartphones, Computers, Sports & Fitness, Audio)</li>
            <li>12 Product entries with brand and category assignments</li>
        </ul>
        
        <p style="color: #333; margin-top: 30px;">You can now:</p>
        <p><a href="home.php" style="display: inline-block;">View Home Page →</a></p>
        <p><a href="admin.php" style="display: inline-block;">Go to Admin Dashboard →</a></p>
    </div>
</body>
</html>
