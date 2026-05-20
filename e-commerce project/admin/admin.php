<?php
session_start();

// ---- پاراستنی داشبۆرد: تەنها ئەندامی چوونەژوورەبوو ----
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_msg'] = 'داشبۆردی سیستەم تەنها بۆ ئەندامانە. تکایە پێشتر بچووە ژوورەوە.';
    header('Location: ../pages/login.php');
    exit();
}

include '../db.php';
$table_check = $conn->query("SHOW TABLES LIKE 'products'");
if (!$table_check || $table_check->num_rows == 0) {
    $conn->query("CREATE TABLE products (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255) NOT NULL, description TEXT, price DECIMAL(10,2) NOT NULL, image VARCHAR(500), brand_id INT(11), category_id INT(11), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
} else {
    $columns_check = $conn->query("SHOW COLUMNS FROM products");
    $has_brand_id = false; $has_category_id = false;
    if ($columns_check) { while ($col = $columns_check->fetch_assoc()) { if ($col['Field']=='brand_id') $has_brand_id=true; if ($col['Field']=='category_id') $has_category_id=true; } }
    if (!$has_brand_id) $conn->query("ALTER TABLE products ADD COLUMN brand_id INT(11)");
    if (!$has_category_id) $conn->query("ALTER TABLE products ADD COLUMN category_id INT(11)");
}
$table_check = $conn->query("SHOW TABLES LIKE 'categories'");
if (!$table_check || $table_check->num_rows == 0) {
    $conn->query("CREATE TABLE categories (id INT(11) PRIMARY KEY AUTO_INCREMENT, name VARCHAR(100) NOT NULL UNIQUE, description TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
}
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'menu';
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if ($conn->query("DELETE FROM products WHERE id = $delete_id")) { $message = "کاڵا سڕایەوە!"; } else { $message = "هەڵە لە سڕینەوە: " . $conn->error; }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : null;
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $image = $conn->real_escape_string($_POST['image']);
    $brand_id = isset($_POST['brand_id']) && !empty($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
    $category_id = isset($_POST['category_id']) && !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    if ($id) { $sql = "UPDATE products SET name='$name', description='$description', price=$price, image='$image', brand_id=" . ($brand_id ? $brand_id : 'NULL') . ", category_id=" . ($category_id ? $category_id : 'NULL') . " WHERE id=$id"; }
    else { $sql = "INSERT INTO products (name, description, price, image, brand_id, category_id) VALUES ('$name', '$description', $price, '$image', " . ($brand_id ? $brand_id : 'NULL') . ", " . ($category_id ? $category_id : 'NULL') . ")"; }
    if ($conn->query($sql)) { $message = $id ? "کاڵا نوێ کرایەوە!" : "کاڵا زیادکرا!"; unset($_POST); $mode = 'insert'; } else { $message = "هەڵە: " . $conn->error; }
}
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
if (!$result) { $message = "هەڵە لە هێنانی کاڵاکان: " . $conn->error; $result = null; }
$edit_product = null;
if (isset($_GET['edit_id'])) { $edit_id = intval($_GET['edit_id']); $edit_result = $conn->query("SELECT * FROM products WHERE id = $edit_id"); $edit_product = $edit_result->fetch_assoc(); }
?>
<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>داشبۆردی ئەدمین</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        html, body { background: #F5F6FF !important; color: #1A1A2E !important; }
        .admin-container{max-width:1200px;margin:0 auto;padding:20px;direction:rtl}

        /* ---- header ---- */
        .admin-header{
            background: linear-gradient(135deg,#00D4AA 0%,#00A885 100%) !important;
            color:#fff;padding:28px 30px;border-radius:20px;
            margin-bottom:25px;position:relative;overflow:hidden;
            box-shadow: 0 8px 30px rgba(0,212,170,0.3);
        }
        .admin-header::before{content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(circle at 30% 70%,rgba(255,255,255,0.12) 0%,transparent 50%);pointer-events:none;}
        .admin-header h1{margin:0;text-align:right;color:#fff !important;font-family:'UniSIRWAN',sans-serif;font-size:1.7rem;display:flex;align-items:center;gap:12px;position:relative;z-index:1}

        /* ---- form section ---- */
        .form-section{
            background:#FFFFFF !important;border:1px solid rgba(0,0,0,0.07);padding:30px;
            border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,0.08);margin-bottom:25px;
        }
        .form-section h2{margin-top:0;color:#1A1A2E !important;border-bottom:2px solid #00D4AA;padding-bottom:12px;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;gap:10px}
        .form-group{margin-bottom:18px}
        .form-group label{display:flex;align-items:center;gap:7px;margin-bottom:8px;font-weight:600;color:#4A4A6A;font-family:'UniSIRWAN',sans-serif}
        .form-group input,.form-group textarea,.form-group select{
            width:100%;padding:12px 16px;border:1px solid rgba(0,180,140,0.2) !important;border-radius:10px;
            font-family:'UniSIRWAN',sans-serif;box-sizing:border-box;transition:all .3s;
            background:#FAFCFC !important;color:#1A1A2E !important;
        }
        .form-group textarea{resize:vertical;min-height:100px}
        .form-group input:focus,.form-group textarea:focus,.form-group select:focus{outline:none;border-color:#00D4AA !important;box-shadow:0 0 0 3px rgba(0,212,170,0.12);}
        .form-group select{background:#fff !important;color:#1A1A2E !important}
        .form-group select option{background:#fff !important;color:#1A1A2E !important}
        .form-buttons{display:flex;gap:10px}
        .btn-submit{
            padding:12px 28px;background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;border:none;
            border-radius:10px;cursor:pointer;font-size:14px;font-weight:700;
            font-family:'UniSIRWAN',sans-serif;transition:all .3s;display:flex;align-items:center;gap:8px;
            box-shadow: 0 4px 14px rgba(0,212,170,0.35);
        }
        .btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,212,170,0.45);filter:brightness(1.08)}
        .btn-reset{
            padding:12px 28px;background:#F0F2FF !important;border:1px solid rgba(0,0,0,0.08);color:#4A4A6A;
            border-radius:10px;cursor:pointer;font-weight:700;text-decoration:none;
            display:flex;align-items:center;gap:8px;font-family:'UniSIRWAN',sans-serif;transition:all .3s;
        }
        .btn-reset:hover{background:#FFE8E8 !important;border-color:#FF6B91;color:#FF3B6F}

        /* ---- messages ---- */
        .message{padding:15px;border-radius:10px;margin-bottom:20px;font-weight:600;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;gap:8px;animation:slideIn .3s ease}
        .message.success{background:rgba(0,212,170,0.08) !important;color:#00A885 !important;border:1px solid rgba(0,212,170,0.25)}
        .message.error{background:rgba(255,59,111,0.08) !important;color:#FF3B6F !important;border:1px solid rgba(255,59,111,0.2)}

        /* ---- table ---- */
        .products-table{width:100%;border-collapse:collapse;background:#FFFFFF !important;border:1px solid rgba(0,0,0,0.07);border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08)}
        .products-table thead{background:linear-gradient(135deg,#00D4AA,#00A885) !important}
        .products-table th,.products-table td{padding:14px;text-align:right;border-bottom:1px solid rgba(0,0,0,0.06);font-family:'UniSIRWAN',sans-serif}
        .products-table th{color:#fff !important;font-weight:600}
        .products-table td{color:#4A4A6A}
        .products-table tbody tr{transition:all .15s}
        .products-table tbody tr:hover{background:rgba(0,212,170,0.04)}
        .products-table img{width:70px;height:70px;object-fit:cover;border-radius:10px;border:1px solid rgba(0,0,0,0.08)}
        .action-buttons{display:flex;gap:8px}
        .btn-edit,.btn-delete{
            padding:8px 14px;border:none;border-radius:10px;cursor:pointer;font-size:13px;
            text-decoration:none;display:inline-flex;align-items:center;gap:6px;font-family:'UniSIRWAN',sans-serif;
            transition:all .3s;font-weight:600;
        }
        .btn-edit{background:rgba(255,184,48,0.1);color:#D4920A;border:1px solid rgba(255,184,48,0.25)}
        .btn-edit:hover{background:rgba(255,184,48,0.2);transform:translateY(-2px)}
        .btn-delete{background:rgba(255,59,111,0.08);color:#FF3B6F;border:1px solid rgba(255,59,111,0.2)}
        .btn-delete:hover{background:rgba(255,59,111,0.15);transform:translateY(-2px)}
        .no-products{text-align:center;padding:50px;color:#8A8AA8;font-family:'UniSIRWAN',sans-serif}
        .image-preview{margin-top:10px;max-width:200px}
        .image-preview img{max-width:100%;height:auto;border-radius:10px;border:1px solid rgba(0,0,0,0.08)}

        /* ---- nav/mode buttons ---- */
        .back-link{
            display:inline-flex;align-items:center;gap:7px;margin-bottom:20px;padding:10px 22px;
            background:rgba(0,212,170,0.08);border:1px solid rgba(0,212,170,0.2);color:#00A885;
            text-decoration:none;border-radius:10px;font-family:'UniSIRWAN',sans-serif;font-weight:600;
            transition:all .3s;
        }
        .back-link:hover{background:#00D4AA !important;color:#fff !important;transform:translateY(-2px)}
        .mode-buttons{display:flex;gap:12px;margin-bottom:25px;flex-wrap:wrap}
        .mode-btn{
            padding:11px 24px;border:1px solid rgba(0,0,0,0.08);background:#FFFFFF !important;
            color:#4A4A6A;border-radius:12px;cursor:pointer;font-size:14px;
            font-weight:700;transition:all .3s;text-decoration:none;display:inline-flex;
            align-items:center;gap:8px;font-family:'UniSIRWAN',sans-serif;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .mode-btn:hover{background:rgba(0,212,170,0.06) !important;color:#00A885;border-color:rgba(0,212,170,0.3);transform:translateY(-2px);box-shadow:0 4px 14px rgba(0,212,170,0.15)}
        .mode-btn.active{background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;border-color:#00D4AA;box-shadow:0 4px 16px rgba(0,212,170,0.35)}
        .menu-center{text-align:center;padding:60px 20px}
        .menu-center h2{color:#1A1A2E !important;margin-bottom:35px;font-family:'UniSIRWAN',sans-serif;font-size:1.5rem}
        .price-col{color:#00A885 !important;font-weight:700}
    </style>
</head>
<body style="background:#F5F6FF !important;">
    <iframe src="../includes/navbar.php" style="border:none;width:100%;height:80px;margin:0;padding:0;position:fixed;top:0;left:0;z-index:9998"></iframe>
    <div class="admin-container" style="margin-top:100px">
        <div class="admin-header"><h1><i data-lucide="layout-dashboard" style="width:26px;height:26px"></i> داشبۆردی ئەدمین - بەڕێوەبردنی کاڵاکان</h1></div>

        <?php if ($mode === 'menu'): ?>
            <div class="menu-center">
                <h2>چی دەتەوێت بکەیت؟</h2>
                <div class="mode-buttons" style="justify-content:center">
                    <a href="admin.php?mode=insert" class="mode-btn"><i data-lucide="plus-circle" style="width:18px;height:18px"></i> زیادکردنی کاڵا</a>
                    <a href="admin.php?mode=view" class="mode-btn"><i data-lucide="list" style="width:18px;height:18px"></i> بینینی هەموو کاڵاکان</a>
                    <a href="categories.php" class="mode-btn"><i data-lucide="layers" style="width:18px;height:18px"></i> بەڕێوەبردنی جۆرەکان</a>
                </div>
            </div>
        <?php else: ?>
            <div class="mode-buttons">
                <a href="admin.php?mode=menu" class="mode-btn"><i data-lucide="arrow-right" style="width:16px;height:16px"></i> گەڕانەوە بۆ مێنیو</a>
                <a href="admin.php?mode=insert" class="mode-btn <?php echo $mode==='insert'?'active':''; ?>"><i data-lucide="plus-circle" style="width:16px;height:16px"></i> زیادکردنی کاڵا</a>
                <a href="admin.php?mode=view" class="mode-btn <?php echo $mode==='view'?'active':''; ?>"><i data-lucide="list" style="width:16px;height:16px"></i> بینینی هەموو</a>
                <a href="categories.php" class="mode-btn"><i data-lucide="layers" style="width:16px;height:16px"></i> جۆرەکان</a>
            </div>
            <a href="../pages/home.php" class="back-link"><i data-lucide="home" style="width:16px;height:16px"></i> گەڕانەوە بۆ سەرەتا</a>
            <?php if (isset($message)): ?>
                <div class="message <?php echo strpos($message,'هەڵە')===false?'success':'error'; ?>">
                    <i data-lucide="<?php echo strpos($message,'هەڵە')===false?'check-circle':'alert-circle'; ?>" style="width:18px;height:18px"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($mode === 'insert'): ?>
        <div class="form-section">
            <h2><?php echo $edit_product ? '<i data-lucide="edit" style="width:20px;height:20px"></i> دەستکاریی کاڵا' : '<i data-lucide="plus-circle" style="width:20px;height:20px"></i> زیادکردنی کاڵای نوێ'; ?></h2>
            <form method="POST">
                <?php if ($edit_product): ?><input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>"><?php endif; ?>
                <div class="form-group">
                    <label for="name"><i data-lucide="package" style="width:16px;height:16px"></i> ناوی کاڵا *</label>
                    <input type="text" id="name" name="name" required placeholder="ناوی کاڵا بنووسە" value="<?php echo $edit_product ? htmlspecialchars($edit_product['name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="description"><i data-lucide="file-text" style="width:16px;height:16px"></i> وەسف</label>
                    <textarea id="description" name="description" placeholder="وەسفی کاڵا بنووسە"><?php echo $edit_product ? htmlspecialchars($edit_product['description']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price"><i data-lucide="dollar-sign" style="width:16px;height:16px"></i> نرخ *</label>
                    <input type="number" id="price" name="price" step="0.01" required placeholder="نرخ" value="<?php echo $edit_product ? htmlspecialchars($edit_product['price']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="brand_id"><i data-lucide="tag" style="width:16px;height:16px"></i> براند</label>
                    <select id="brand_id" name="brand_id">
                        <option value="">-- براندێک هەڵبژێرە --</option>
                        <?php $bq = $conn->query("SELECT id, brand_title FROM brands ORDER BY brand_title");
                        if ($bq && $bq->num_rows > 0) { while ($b = $bq->fetch_assoc()) { $sel = ($edit_product && $edit_product['brand_id']==$b['id'])?'selected':''; echo '<option value="'.$b['id'].'" '.$sel.'>'.htmlspecialchars($b['brand_title']).'</option>'; } } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_id"><i data-lucide="layers" style="width:16px;height:16px"></i> جۆر</label>
                    <select id="category_id" name="category_id">
                        <option value="">-- جۆرێک هەڵبژێرە --</option>
                        <?php $cq = $conn->query("SELECT id, name FROM categories ORDER BY name");
                        if ($cq && $cq->num_rows > 0) { while ($c = $cq->fetch_assoc()) { $sel = ($edit_product && $edit_product['category_id']==$c['id'])?'selected':''; echo '<option value="'.$c['id'].'" '.$sel.'>'.htmlspecialchars($c['name']).'</option>'; } } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image"><i data-lucide="image" style="width:16px;height:16px"></i> لینکی وێنە *</label>
                    <input type="url" id="image" name="image" required placeholder="https://example.com/image.jpg" value="<?php echo $edit_product ? htmlspecialchars($edit_product['image']) : ''; ?>" onchange="previewImage()">
                    <?php if ($edit_product && !empty($edit_product['image'])): ?>
                        <div class="image-preview"><img src="<?php echo htmlspecialchars($edit_product['image']); ?>" alt="پێشبینین"></div>
                    <?php endif; ?>
                    <div id="imagePreview" class="image-preview"></div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn-submit"><?php echo $edit_product ? '<i data-lucide="save" style="width:16px;height:16px"></i> نوێکردنەوە' : '<i data-lucide="plus" style="width:16px;height:16px"></i> زیادکردن'; ?></button>
                    <?php if ($edit_product): ?><a href="admin.php" class="btn-reset"><i data-lucide="x" style="width:16px;height:16px"></i> پاشگەزبوونەوە</a><?php endif; ?>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($mode === 'view'): ?>
        <div class="form-section">
            <h2><i data-lucide="list" style="width:20px;height:20px"></i> هەموو کاڵاکان</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <div style="overflow-x:auto">
                <table class="products-table">
                    <thead><tr><th>ژمارە</th><th>وێنە</th><th>ناو</th><th>وەسف</th><th>نرخ</th><th>براند</th><th>جۆر</th><th>کردارەکان</th></tr></thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo !empty($row['image'])?'<img src="'.htmlspecialchars($row['image']).'" alt="'.htmlspecialchars($row['name']).'">':'<span style="color:var(--text-muted)">بێ وێنە</span>'; ?></td>
                            <td style="color:#fff;font-weight:500"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars(strlen($row['description'])>50?substr($row['description'],0,50).'...':$row['description']); ?></td>
                            <td class="price-col">$<?php echo number_format($row['price'],2); ?></td>
                            <td><?php if(!empty($row['brand_id'])){$br=$conn->query("SELECT brand_title FROM brands WHERE id=".$row['brand_id']);if($br&&$br->num_rows>0){$b=$br->fetch_assoc();echo htmlspecialchars($b['brand_title']);}}else{echo '<span style="color:var(--text-muted)">نییە</span>';} ?></td>
                            <td><?php if(!empty($row['category_id'])){$cr=$conn->query("SELECT name FROM categories WHERE id=".$row['category_id']);if($cr&&$cr->num_rows>0){$c=$cr->fetch_assoc();echo htmlspecialchars($c['name']);}}else{echo '<span style="color:var(--text-muted)">نییە</span>';} ?></td>
                            <td><div class="action-buttons">
                                <a href="admin.php?edit_id=<?php echo $row['id']; ?>" class="btn-edit"><i data-lucide="edit" style="width:14px;height:14px"></i> دەستکاری</a>
                                <a href="admin.php?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('دڵنیایت لە سڕینەوەی ئەم کاڵایە؟');"><i data-lucide="trash-2" style="width:14px;height:14px"></i> سڕینەوە</a>
                            </div></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                </div>
            <?php else: ?>
                <div class="no-products">هیچ کاڵایەک نەدۆزرایەوە. <a href="admin.php" style="color:var(--primary-light)">یەکەم کاڵا زیاد بکە</a></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <script>
    lucide.createIcons();
    function previewImage(){var u=document.getElementById('image').value,d=document.getElementById('imagePreview');if(u){d.innerHTML='<img src="'+u+'" alt="پێشبینین" onerror="this.parentElement.innerHTML=\'<p style=color:var(--accent)>لینکی وێنە هەڵەیە</p>\'">';}else{d.innerHTML='';}}
    </script>
</body>
</html>
