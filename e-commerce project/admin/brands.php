<?php
include '../db.php';
$table_check = $conn->query("SHOW TABLES LIKE 'brands'");
if (!$table_check || $table_check->num_rows == 0) {
    $conn->query("CREATE TABLE brands (id INT(11) PRIMARY KEY AUTO_INCREMENT, brand_title VARCHAR(100) NOT NULL UNIQUE, description TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
}
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'menu';
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if ($conn->query("DELETE FROM brands WHERE id = $delete_id")) { $message = "براند سڕایەوە!"; $mode = 'view'; }
    else { $message = "هەڵە لە سڕینەوە: " . $conn->error; }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : null;
    $title = $conn->real_escape_string($_POST['title']);
    $sql = $id ? "UPDATE brands SET brand_title='$title' WHERE id=$id" : "INSERT INTO brands (brand_title) VALUES ('$title')";
    if ($conn->query($sql)) { $message = $id ? "براند نوێ کرایەوە!" : "براند زیادکرا!"; $mode = 'view'; }
    else { $message = "هەڵە: " . $conn->error; }
}
$result = $conn->query("SELECT * FROM brands ORDER BY id DESC");
$edit_brand = null;
if (isset($_GET['edit_id'])) { $edit_id = intval($_GET['edit_id']); $er = $conn->query("SELECT * FROM brands WHERE id = $edit_id"); $edit_brand = $er->fetch_assoc(); }
?>
<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
<meta charset="UTF-8"><title>براندەکان</title>
<link rel="stylesheet" href="../styles.css">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
html,body{background:#F5F6FF !important;color:#EAEAFF !important}
.ac{max-width:1000px;margin:100px auto 30px;padding:25px;background:#FFFFFF !important;border:1px solid rgba(255,255,255,.07);border-radius:24px;box-shadow:0 20px 60px rgba(0,0,0,.6);direction:rtl}
.mn{display:flex;gap:10px;margin-bottom:25px;flex-wrap:wrap}
.mn a{text-decoration:none}
.mn button{padding:10px 22px;border:1px solid rgba(255,255,255,.1);background:#EEF0FF !important;color:#A0A0C8;border-radius:10px;cursor:pointer;font-size:14px;font-weight:700;font-family:'UniSIRWAN',sans-serif;transition:all .3s;display:inline-flex;align-items:center;gap:7px}
.mn button.active{background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;border-color:#00D4AA}
.mn button:hover{background:rgba(0,212,170,.1) !important;color:#fff}
.bl{display:inline-flex;align-items:center;gap:7px;margin-bottom:20px;padding:10px 20px;background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.2);color:#00D4AA;text-decoration:none;border-radius:10px;font-family:'UniSIRWAN',sans-serif;font-weight:600;transition:all .3s}
.bl:hover{background:#00D4AA !important;color:#060612 !important}
.fg{margin-bottom:18px}
.fg label{display:flex;align-items:center;gap:7px;margin-bottom:8px;font-weight:600;color:#A0A0C8;font-family:'UniSIRWAN',sans-serif}
.fg input{width:100%;padding:12px 16px;border:1px solid rgba(0,212,170,.15) !important;border-radius:10px;font-family:'UniSIRWAN',sans-serif;box-sizing:border-box;background:rgba(255,255,255,.04) !important;color:#EAEAFF !important}
.fg input:focus{outline:none;border-color:#00D4AA !important;box-shadow:0 0 0 3px rgba(0,212,170,.12)}
.fb{display:flex;gap:10px;margin-top:20px}
.bs{padding:12px 24px;background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;border:none;border-radius:10px;cursor:pointer;font-weight:700;font-family:'UniSIRWAN',sans-serif;display:inline-flex;align-items:center;gap:8px}
.bc{padding:12px 24px;background:#EEF0FF !important;border:1px solid rgba(255,255,255,.1);color:#A0A0C8;border-radius:10px;text-decoration:none;display:inline-flex;align-items:center;gap:7px;font-family:'UniSIRWAN',sans-serif;font-weight:700}
.msg{padding:15px;margin-bottom:20px;border-radius:10px;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;gap:8px}
.msg.s{background:rgba(0,230,138,.1) !important;color:#00E68A !important;border:1px solid rgba(0,230,138,.2)}
.msg.e{background:rgba(255,59,111,.1) !important;color:#FF6B91 !important;border:1px solid rgba(255,59,111,.2)}
table{width:100%;border-collapse:collapse;margin-top:20px;background:#FFFFFF !important;border:1px solid rgba(255,255,255,.07);border-radius:16px;overflow:hidden}
th,td{padding:14px;text-align:right;border-bottom:1px solid rgba(255,255,255,.06);font-family:'UniSIRWAN',sans-serif}
th{background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important}
td{color:#A0A0C8}
tbody tr:hover{background:rgba(0,212,170,.04)}
.be,.bd{padding:8px 14px;margin-left:5px;border:none;border-radius:10px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;font-size:13px;font-family:'UniSIRWAN',sans-serif;font-weight:600}
.be{background:rgba(255,184,48,.12);color:#FFB830;border:1px solid rgba(255,184,48,.2)}
.bd{background:rgba(255,59,111,.1);color:#FF6B91;border:1px solid rgba(255,59,111,.2)}
h1{color:#fff !important;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;gap:12px}
h2{color:#fff !important;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;gap:10px;margin-top:0}
</style>
</head>
<body style="background:#F5F6FF !important;">
<iframe src="../includes/navbar.php" style="border:none;width:100%;height:64px;margin:0;padding:0;position:fixed;top:0;z-index:9999"></iframe>
<div class="ac">
<a href="admin.php" class="bl"><i data-lucide="arrow-right" style="width:16px;height:16px"></i> گەڕانەوە</a>
<h1><i data-lucide="tag" style="width:26px;height:26px;color:#00D4AA"></i> براندەکان</h1>
<?php if (isset($message)): ?><div class="msg <?php echo strpos($message,'هەڵە')!==false?'e':'s'; ?>"><i data-lucide="<?php echo strpos($message,'هەڵە')!==false?'alert-circle':'check-circle'; ?>" style="width:18px;height:18px"></i> <?php echo htmlspecialchars($message); ?></div><?php endif; ?>
<div class="mn">
<a href="?mode=insert"><button <?php echo $mode==='insert'?'class="active"':''; ?>><i data-lucide="plus-circle" style="width:16px;height:16px"></i> زیادکردن</button></a>
<a href="?mode=view"><button <?php echo $mode==='view'?'class="active"':''; ?>><i data-lucide="list" style="width:16px;height:16px"></i> بینین</button></a>
</div>
<?php if ($mode==='insert'): ?>
<h2><i data-lucide="<?php echo $edit_brand?'edit':'plus-circle'; ?>" style="width:20px;height:20px;color:#00D4AA"></i> <?php echo $edit_brand?'دەستکاری':'زیادکردنی نوێ'; ?></h2>
<form method="POST">
<?php if ($edit_brand): ?><input type="hidden" name="id" value="<?php echo $edit_brand['id']; ?>"><?php endif; ?>
<div class="fg"><label><i data-lucide="tag" style="width:16px;height:16px"></i> ناوی براند *</label><input type="text" name="title" required placeholder="ناوی براند" value="<?php echo $edit_brand?htmlspecialchars($edit_brand['brand_title']):''; ?>"></div>
<div class="fb"><button type="submit" class="bs"><i data-lucide="<?php echo $edit_brand?'save':'plus'; ?>" style="width:16px;height:16px"></i> <?php echo $edit_brand?'نوێکردنەوە':'زیادکردن'; ?></button><a href="?mode=view" class="bc"><i data-lucide="x" style="width:16px;height:16px"></i> پاشگەزبوونەوە</a></div>
</form>
<?php endif; ?>
<?php if ($mode==='view'): ?>
<h2><i data-lucide="list" style="width:20px;height:20px;color:#00D4AA"></i> هەموو براندەکان</h2>
<?php if ($result && $result->num_rows > 0): ?>
<div style="overflow-x:auto"><table><thead><tr><th>ژمارە</th><th>ناو</th><th>بەروار</th><th>کردار</th></tr></thead><tbody>
<?php while ($row=$result->fetch_assoc()): ?>
<tr><td><?php echo $row['id']; ?></td><td style="color:#fff !important;font-weight:500"><?php echo htmlspecialchars($row['brand_title']); ?></td><td><?php echo date('Y/m/d',strtotime($row['created_at'])); ?></td>
<td><a href="?mode=insert&edit_id=<?php echo $row['id']; ?>" class="be"><i data-lucide="edit" style="width:14px;height:14px"></i> دەستکاری</a><a href="?delete_id=<?php echo $row['id']; ?>" class="bd" onclick="return confirm('دڵنیایت؟')"><i data-lucide="trash-2" style="width:14px;height:14px"></i> سڕینەوە</a></td></tr>
<?php endwhile; ?>
</tbody></table></div>
<?php else: ?><div style="text-align:center;padding:40px;color:#6B6B90"><p>هیچ براندێک نییە. <a href="?mode=insert" style="color:#00D4AA">زیاد بکە</a></p></div><?php endif; ?>
<?php endif; ?>
</div>
<script>lucide.createIcons();</script>
</body></html>
