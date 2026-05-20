<?php
include '../db.php';

// Get all columns and data from products table
$result = $conn->query("SELECT * FROM products LIMIT 5");

if (!$result) {
    echo "<h3 style='color: red;'>Error: " . $conn->error . "</h3>";
} else {
    echo "<h3>Products Table Structure and Data:</h3>";
    
    if ($result->num_rows == 0) {
        echo "<p style='color: red;'>⚠️ Table is empty - no products found</p>";
    } else {
        // Get field info
        $fields = $result->fetch_fields();
        echo "<h4>Column Names:</h4>";
        echo "<ul>";
        foreach ($fields as $field) {
            echo "<li><strong>" . $field->name . "</strong> (" . $field->type . ")</li>";
        }
        echo "</ul>";
        
        // Display actual data
        echo "<h4>Sample Data (first 5 rows):</h4>";
        $result = $conn->query("SELECT * FROM products LIMIT 5");
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        
        // Header
        $row = $result->fetch_assoc();
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<th>" . $key . "</th>";
        }
        echo "</tr>";
        
        // Data rows
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
}

$conn->close();
?>
