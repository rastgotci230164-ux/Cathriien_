<?php
$host = '127.0.0.1'; $db_user = 'root'; $db_password = ''; $db_name = 'e-commerce';
echo "<!DOCTYPE html><html lang='ckb' dir='rtl'><head><meta charset='UTF-8'>";
echo "<link rel='stylesheet' href='styles.css'>";
echo "<script src='https://unpkg.com/lucide@latest/dist/umd/lucide.js'></script>";
echo "<style>";
echo "html, body { background: #F5F6FF !important; color: #1A1A2E !important; }";
echo ".container{max-width:800px;margin:40px auto;padding:30px;background:#FFFFFF !important;border:1px solid rgba(255,255,255,0.07);border-radius:24px;box-shadow:0 20px 60px rgba(0,0,0,0.6)}";
echo ".check{padding:16px;margin:10px 0;border-radius:10px;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;gap:10px}";
echo ".pass{background:rgba(0,230,138,0.08) !important;color:#00E68A !important;border:1px solid rgba(0,230,138,0.2)}";
echo ".fail{background:rgba(255,59,111,0.08) !important;color:#FF6B91 !important;border:1px solid rgba(255,59,111,0.2)}";
echo "h1{color:#fff !important;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;justify-content:center;gap:12px}";
echo "h3{color:#fff !important;font-family:'UniSIRWAN',sans-serif}";
echo "ul{direction:rtl;text-align:right} li{margin:8px 0;font-family:'UniSIRWAN',sans-serif;color:#A0A0C8;padding:6px 12px;background:rgba(255,255,255,0.03);border-radius:10px}";
echo "</style></head><body style='background:#F5F6FF !important;'><div class='container'>";
echo "<h1><i data-lucide='database' style='width:28px;height:28px;color:#00D4AA'></i> ??????? ????????</h1>";

$conn_test = new mysqli($host, $db_user, $db_password);
if ($conn_test->connect_error) {
    echo "<div class='check fail'><i data-lucide='x-circle' style='width:20px;height:20px'></i> ???????? ???????? ?? MySQL ?????: " . htmlspecialchars($conn_test->connect_error) . "</div>"; echo "<script>lucide.createIcons();</script></body></html>"; exit;
} else {
    echo "<div class='check pass'><i data-lucide='check-circle' style='width:20px;height:20px'></i> ???????? ?? MySQL ????????? ???</div>";
}

$db_result = $conn_test->query("SHOW DATABASES LIKE 'e-commerce'");
if ($db_result && $db_result->num_rows > 0) {
    echo "<div class='check pass'><i data-lucide='check-circle' style='width:20px;height:20px'></i> ????????? 'e-commerce' ????</div>";
} else {
    echo "<div class='check fail'><i data-lucide='x-circle' style='width:20px;height:20px'></i> ????????? 'e-commerce' ????</div>";
    echo "<p style='font-family:UniSIRWAN,sans-serif;color:#A0A0C8'>?????????? ????????...</p>";
    if ($conn_test->query("CREATE DATABASE `e-commerce`")) {
        echo "<div class='check pass'><i data-lucide='check-circle' style='width:20px;height:20px'></i> ????????? 'e-commerce' ????????</div>";
    } else {
        echo "<div class='check fail'><i data-lucide='x-circle' style='width:20px;height:20px'></i> ???? ?? ?????????? ????????: " . $conn_test->error . "</div>"; echo "<script>lucide.createIcons();</script></body></html>"; exit;
    }
}

if (!$conn_test->select_db($db_name)) {
    echo "<div class='check fail'><i data-lucide='x-circle' style='width:20px;height:20px'></i> ???????? ???????? ???????????: " . $conn_test->error . "</div>"; echo "<script>lucide.createIcons();</script></body></html>"; exit;
} else {
    echo "<div class='check pass'><i data-lucide='check-circle' style='width:20px;height:20px'></i> ????????? 'e-commerce' ??????????</div>";
}

$table_result = $conn_test->query("SHOW TABLES LIKE 'products'");
if ($table_result && $table_result->num_rows > 0) {
    echo "<div class='check pass'><i data-lucide='check-circle' style='width:20px;height:20px'></i> ????? 'products' ????</div>";
    $fields = $conn_test->query("DESCRIBE products");
    echo "<h3 style='margin-top:20px;display:flex;align-items:center;gap:8px'><i data-lucide='table' style='width:20px;height:20px;color:#00D4AA'></i> ???????? ????:</h3><ul>";
    while ($field = $fields->fetch_assoc()) {
        echo "<li><strong style='color:#00D4AA'>" . $field['Field'] . "</strong> <span style='color:#6B6B90'>(" . $field['Type'] . ")</span></li>";
    }
    echo "</ul>";
} else {
    echo "<div class='check fail'><i data-lucide='x-circle' style='width:20px;height:20px'></i> ????? 'products' ????</div>";
    echo "<p style='font-family:UniSIRWAN,sans-serif;color:#A0A0C8'>?????????? ????...</p>";
    $create_table = "CREATE TABLE products (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255) NOT NULL, description TEXT, price DECIMAL(10, 2) NOT NULL, image VARCHAR(500), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
    if ($conn_test->query($create_table)) {
        echo "<div class='check pass'><i data-lucide='check-circle' style='width:20px;height:20px'></i> ????? 'products' ????????</div>";
    } else {
        echo "<div class='check fail'><i data-lucide='x-circle' style='width:20px;height:20px'></i> ???? ?? ?????????? ????: " . $conn_test->error . "</div>"; echo "<script>lucide.createIcons();</script></body></html>"; exit;
    }
}

echo "<hr style='border:none;border-top:1px solid rgba(255,255,255,0.06);margin:30px 0'>";
echo "<h2 style='color:#00E68A !important;text-align:center;font-family:UniSIRWAN,sans-serif;display:flex;align-items:center;justify-content:center;gap:10px'><i data-lucide='check-circle-2' style='width:24px;height:24px'></i> ????? ?? ????????!</h2>";
echo "<p style='text-align:center;margin-top:20px'><a href='admin.php' style='background:linear-gradient(135deg,#00D4AA,#00A885);color:#fff;padding:14px 30px;border-radius:10px;text-decoration:none;font-weight:bold;font-family:UniSIRWAN,sans-serif;display:inline-flex;align-items:center;gap:8px;transition:all 0.3s;box-shadow:0 4px 15px rgba(0,212,170,0.3)'><i data-lucide='layout-dashboard' style='width:18px;height:18px'></i> ??? ?? ???????? ??????</a></p>";
$conn_test->close();
?>
</div><script>lucide.createIcons();</script></body></html>
