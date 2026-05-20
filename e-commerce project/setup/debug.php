<?php
include '../db.php';

echo "<h2 style='color: #333; font-family: Arial;'>📊 Database Diagnostic Report</h2>";

// Show databases
echo "<h3>Available Databases:</h3>";
$dbs = $conn->query("SHOW DATABASES");
echo "<ul>";
while($db = $dbs->fetch_assoc()) {
    echo "<li>" . $db['Database'] . "</li>";
}
echo "</ul>";

// Show current database tables
echo "<h3>Tables in 'e-commerce' database:</h3>";
$tables = $conn->query("SHOW TABLES");
if ($tables->num_rows > 0) {
    echo "<ul>";
    while($table = $tables->fetch_assoc()) {
        $tname = array_values($table)[0];
        echo "<li><strong>" . $tname . "</strong>";
        
        // Show columns
        $cols = $conn->query("DESCRIBE " . $tname);
        echo "<ul>";
        while($col = $cols->fetch_assoc()) {
            echo "<li>" . $col['Field'] . " (" . $col['Type'] . ")</li>";
        }
        echo "</ul></li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ No tables found in e-commerce database</p>";
}

// Check products table data
echo "<h3>Data in 'products' table:</h3>";
$result = $conn->query("SELECT COUNT(*) as count FROM products");
if ($result) {
    $count = $result->fetch_assoc();
    echo "<p><strong>Total rows:</strong> " . $count['count'] . "</p>";
    
    if ($count['count'] > 0) {
        echo "<p><strong>Sample data:</strong></p>";
        $sample = $conn->query("SELECT * FROM products LIMIT 1");
        $row = $sample->fetch_assoc();
        echo "<pre style='background: #f4f4f4; padding: 10px; border-radius: 5px;'>";
        print_r($row);
        echo "</pre>";
    }
} else {
    echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
}

$conn->close();
?>
