<?php
include '../db.php';
$columns_check = $conn->query("SHOW COLUMNS FROM products");
$has_brand_id = false; $has_category_id = false;
if ($columns_check) { while ($col = $columns_check->fetch_assoc()) { if ($col['Field'] == 'brand_id') $has_brand_id = true; if ($col['Field'] == 'category_id') $has_category_id = true; } }
if (!$has_brand_id) { $conn->query("ALTER TABLE products ADD COLUMN brand_id INT(11)"); }
if (!$has_category_id) { $conn->query("ALTER TABLE products ADD COLUMN category_id INT(11)"); }
$sql = "SELECT * FROM products WHERE 1=1";
if (isset($_GET['brand']) && !empty($_GET['brand'])) { $sql .= " AND brand_id = " . intval($_GET['brand']); }
if (isset($_GET['category']) && !empty($_GET['category'])) { $sql .= " AND category_id = " . intval($_GET['category']); }
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>??????? - ????? ??????????</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        html, body { background: #F5F6FF !important; color: #1A1A2E !important; }
        .products-header{
            background:linear-gradient(135deg,#060612 0%,#0E1A30 40%,#12082A 100%) !important;
            color:#fff;text-align:center;padding:70px 20px 55px;margin-bottom:0;
            position:relative;overflow:hidden;
        }
        .products-header::before{
            content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;
            background:radial-gradient(circle at 40% 60%,rgba(124,92,252,0.12) 0%,transparent 45%),
                        radial-gradient(circle at 60% 40%,rgba(0,212,170,0.08) 0%,transparent 45%);
            animation:float 8s ease-in-out infinite;
        }
        .products-header h1{color:#fff !important;font-size:2.4rem;margin:0 0 10px;position:relative;z-index:1;display:flex;align-items:center;justify-content:center;gap:12px}
        .products-header p{color:rgba(255,255,255,.5);font-size:1rem;position:relative;z-index:1}
        .empty-icon{
            width:80px;height:80px;background:rgba(0,212,170,0.08);
            border-radius:50%;display:flex;align-items:center;justify-content:center;
            margin:0 auto 20px;color:#00D4AA;
        }
        .meta-tag{
            background:rgba(0,212,170,0.1) !important;padding:3px 10px;border-radius:20px;
            font-size:11px;color:#00D4AA !important;display:inline-flex;align-items:center;gap:4px;
        }
    </style>
</head>
<body style="background:#F5F6FF !important;">
    <iframe src="../includes/navbar.php" style="border:none;width:100%;height:80px;margin:0;padding:0"></iframe>
    <div class="products-header">
        <h1><i data-lucide="shopping-bag" style="width:30px;height:30px;color:#00D4AA"></i> ???????</h1>
        <p>????? ?????????? ???? ?????????</p>
    </div>
    <div class="content" style="background:#F5F6FF !important;">
        <div class="cards-container">
            <?php
            if (!$result) {
                echo '<div style="background:rgba(255,184,48,0.08);border:1px solid rgba(255,184,48,0.2);padding:20px;border-radius:16px;width:100%;text-align:center">';
                echo '<h3 style="color:#FFB830;display:flex;align-items:center;justify-content:center;gap:8px"><i data-lucide="alert-triangle" style="width:22px;height:22px"></i> ????? ????????</h3>';
                echo '<p style="color:#6B6B90;margin:10px 0">' . htmlspecialchars($conn->error) . '</p>';
                echo '</div>';
            } else if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $brand_name = '????????'; $category_name = '????????';
                    if (!empty($row['brand_id'])) { $br = $conn->query("SELECT brand_title FROM brands WHERE id = " . $row['brand_id']); if ($br && $br->num_rows > 0) { $b = $br->fetch_assoc(); $brand_name = htmlspecialchars($b['brand_title']); } }
                    if (!empty($row['category_id'])) { $cr = $conn->query("SELECT name FROM categories WHERE id = " . $row['category_id']); if ($cr && $cr->num_rows > 0) { $c = $cr->fetch_assoc(); $category_name = htmlspecialchars($c['name']); } }
                    $img_src = !empty($row['image']) ? htmlspecialchars($row['image']) : 'placeholder.jpg';
                    $p_price = number_format($row['price'], 2);
                    echo '<div class="card">';
                    echo '  <img src="' . $img_src . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '  <div class="card-overlay"></div>';
                    echo '  <div class="card-price-badge">$' . $p_price . '</div>';
                    echo '  <div class="card-content">';
                    echo '    <h3>' . htmlspecialchars($row['name']) . '</h3>';
                    if (!empty($row['description'])) echo '    <p class="card-description">' . htmlspecialchars($row['description']) . '</p>';
                    echo '    <div class="card-meta"><span class="meta-tag"><i data-lucide="tag" style="width:11px;height:11px"></i> ' . $brand_name . '</span><span class="meta-tag"><i data-lucide="layers" style="width:11px;height:11px"></i> ' . $category_name . '</span></div>';
                    echo '    <div class="card-buttons"><button class="btn btn-add-cart"><i data-lucide="shopping-cart" style="width:15px;height:15px"></i> ???????? ?? ??????</button></div>';
                    echo '  </div>';
                    echo '</div>';
                }
            } else {
                echo '<div style="text-align:center;padding:80px 20px;width:100%"><div class="empty-icon"><i data-lucide="package" style="width:40px;height:40px"></i></div><h3 style="color:#fff">??? ??????? ???????????</h3></div>';
            }
            ?>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
