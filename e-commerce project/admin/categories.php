<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_msg'] = 'داشبۆردی سیستەم تەنها بۆ ئەندامانە.';
    header('Location: ../pages/login.php'); exit();
}
include '../db.php';

// دڵنیابوون لە بوونی خشتەکە
$conn->query("CREATE TABLE IF NOT EXISTS categories (id INT(11) PRIMARY KEY AUTO_INCREMENT, name VARCHAR(100) NOT NULL UNIQUE, description TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");

$message = '';
$is_error = false;

// سڕینەوە
if (isset($_GET['delete_id'])) {
    $did = intval($_GET['delete_id']);
    if ($conn->query("DELETE FROM categories WHERE id=$did")) { $message = 'جۆر سڕایەوە!'; }
    else { $message = 'هەڵە لە سڕینەوە: ' . $conn->error; $is_error = true; }
}

// زیادکردن / نوێکردنەوە
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = !empty($_POST['id']) ? intval($_POST['id']) : null;
    $title = $conn->real_escape_string(trim($_POST['title'] ?? ''));
    if (empty($title)) { $message = 'ناوی جۆر پێویستە!'; $is_error = true; }
    else {
        $sql = $id
            ? "UPDATE categories SET name='$title' WHERE id=$id"
            : "INSERT INTO categories (name) VALUES ('$title')";
        if ($conn->query($sql)) { $message = $id ? 'جۆر نوێ کرایەوە!' : 'جۆر زیادکرا!'; }
        else { $message = 'هەڵە: ' . $conn->error; $is_error = true; }
    }
}

$mode = $_GET['mode'] ?? 'view';
$edit_cat = null;
if (isset($_GET['edit_id'])) {
    $er = $conn->query("SELECT * FROM categories WHERE id=" . intval($_GET['edit_id']));
    if ($er) $edit_cat = $er->fetch_assoc();
    $mode = 'insert';
}
$result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>بەڕێوەبردنی جۆرەکان</title>
<link rel="stylesheet" href="../styles.css">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
html,body{background:#F5F6FF !important;color:#1A1A2E !important}
.page-container{max-width:900px;margin:0 auto;padding:28px 20px;direction:rtl}

/* Header */
.page-header{background:linear-gradient(135deg,#00D4AA,#00A885);padding:28px 32px;border-radius:20px;margin-bottom:26px;box-shadow:0 8px 30px rgba(0,212,170,0.3);position:relative;overflow:hidden}
.page-header::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 20% 50%,rgba(255,255,255,0.14),transparent 60%);pointer-events:none}
.page-header h1{margin:0;color:#fff !important;font-family:'UniSIRWAN',sans-serif;font-size:1.6rem;font-weight:800;display:flex;align-items:center;gap:12px;position:relative;z-index:1}
.page-header p{margin:7px 0 0;color:rgba(255,255,255,0.85);font-family:'UniSIRWAN',sans-serif;font-size:13px;position:relative;z-index:1}

/* Back link */
.back-link{display:inline-flex;align-items:center;gap:7px;margin-bottom:20px;padding:9px 20px;background:rgba(0,212,170,0.08);border:1px solid rgba(0,212,170,0.2);color:#00A885;text-decoration:none;border-radius:10px;font-family:'UniSIRWAN',sans-serif;font-weight:600;font-size:13px;transition:all .3s}
.back-link:hover{background:#00D4AA !important;color:#fff !important;transform:translateY(-2px)}

/* Tab buttons */
.tab-bar{display:flex;gap:10px;margin-bottom:22px;flex-wrap:wrap}
.tab-btn{padding:10px 22px;border:1px solid rgba(0,0,0,0.08);background:#fff !important;color:#4A4A6A;border-radius:12px;cursor:pointer;font-size:14px;font-weight:700;font-family:'UniSIRWAN',sans-serif;text-decoration:none;display:inline-flex;align-items:center;gap:7px;transition:all .3s;box-shadow:0 2px 8px rgba(0,0,0,0.05)}
.tab-btn:hover{background:rgba(0,212,170,0.07) !important;color:#00A885;border-color:rgba(0,212,170,0.25);transform:translateY(-2px)}
.tab-btn.active{background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;border-color:#00D4AA;box-shadow:0 4px 16px rgba(0,212,170,0.35)}

/* Message */
.msg{padding:14px 18px;border-radius:12px;margin-bottom:20px;font-family:'UniSIRWAN',sans-serif;font-weight:600;display:flex;align-items:center;gap:9px;font-size:14px}
.msg.success{background:rgba(0,212,170,0.08) !important;color:#00A885 !important;border:1px solid rgba(0,212,170,0.22)}
.msg.error{background:rgba(255,59,111,0.07) !important;color:#FF3B6F !important;border:1px solid rgba(255,59,111,0.2)}

/* Form card */
.form-card{background:#fff !important;border:1px solid rgba(0,0,0,0.07);border-radius:20px;padding:30px;box-shadow:0 4px 22px rgba(0,0,0,0.07);margin-bottom:24px}
.form-card h2{margin:0 0 22px;color:#1A1A2E !important;font-family:'UniSIRWAN',sans-serif;font-size:1.1rem;font-weight:800;display:flex;align-items:center;gap:9px;padding-bottom:14px;border-bottom:2px solid rgba(0,212,170,0.18)}
.form-group{margin-bottom:18px}
.form-group label{display:flex;align-items:center;gap:7px;margin-bottom:9px;font-weight:600;color:#4A4A6A;font-family:'UniSIRWAN',sans-serif;font-size:14px}
.form-group input{width:100%;padding:13px 16px;border:1.5px solid rgba(0,180,140,0.2) !important;border-radius:12px;font-family:'UniSIRWAN',sans-serif;box-sizing:border-box;font-size:14px;background:#FAFCFC !important;color:#1A1A2E !important;transition:all .3s;direction:rtl}
.form-group input:focus{outline:none;border-color:#00D4AA !important;background:#F0FDFB !important;box-shadow:0 0 0 3px rgba(0,212,170,0.1)}
.form-group input::placeholder{color:#A0A0B8}
.form-buttons{display:flex;gap:10px;margin-top:6px}
.btn-submit{padding:12px 26px;background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;border:none;border-radius:12px;cursor:pointer;font-size:14px;font-weight:800;font-family:'UniSIRWAN',sans-serif;transition:all .3s;display:flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(0,212,170,0.32)}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 7px 22px rgba(0,212,170,0.45);filter:brightness(1.07)}
.btn-cancel{padding:12px 22px;background:#F0F2FF !important;border:1px solid rgba(0,0,0,0.08);color:#6B6BAA;border-radius:12px;cursor:pointer;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:7px;font-family:'UniSIRWAN',sans-serif;font-size:14px;transition:all .3s}
.btn-cancel:hover{background:#FFE8EF !important;border-color:rgba(255,59,111,0.25);color:#FF3B6F}

/* Table card */
.table-card{background:#fff !important;border:1px solid rgba(0,0,0,0.07);border-radius:20px;overflow:hidden;box-shadow:0 4px 22px rgba(0,0,0,0.07)}
.table-card h2{margin:0;padding:20px 24px;color:#1A1A2E !important;font-family:'UniSIRWAN',sans-serif;font-size:1.05rem;font-weight:800;display:flex;align-items:center;gap:9px;border-bottom:1px solid rgba(0,0,0,0.06)}
table{width:100%;border-collapse:collapse}
th,td{padding:13px 20px;text-align:right;border-bottom:1px solid rgba(0,0,0,0.06);font-family:'UniSIRWAN',sans-serif;font-size:14px}
thead{background:linear-gradient(135deg,#00D4AA,#00A885)}
th{color:#fff !important;font-weight:700}
td{color:#4A4A6A}
tbody tr:hover{background:rgba(0,212,170,0.03)}
tbody tr:last-child td{border-bottom:none}
.cat-name{color:#1A1A2E !important;font-weight:700}
.action-cell{display:flex;gap:7px}
.btn-edit{padding:7px 14px;background:rgba(255,184,48,0.09);color:#C47F00;border:1px solid rgba(255,184,48,0.22);border-radius:8px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:5px;font-family:'UniSIRWAN',sans-serif;font-size:12px;font-weight:700;transition:all .25s}
.btn-edit:hover{background:rgba(255,184,48,0.2);transform:translateY(-1px)}
.btn-delete{padding:7px 14px;background:rgba(255,59,111,0.07);color:#FF3B6F;border:1px solid rgba(255,59,111,0.18);border-radius:8px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:5px;font-family:'UniSIRWAN',sans-serif;font-size:12px;font-weight:700;transition:all .25s}
.btn-delete:hover{background:rgba(255,59,111,0.15);transform:translateY(-1px)}
.empty-row{text-align:center;padding:50px 20px;color:#9A9ABB}
.count-badge{background:rgba(0,212,170,0.1);color:#00A885;padding:3px 12px;border-radius:50px;font-size:12px;font-weight:700;margin-right:auto}
</style>
</head>
<body style="background:#F5F6FF !important;">
<iframe src="../includes/navbar.php" style="border:none;width:100%;height:64px;margin:0;padding:0;position:fixed;top:0;z-index:9999"></iframe>
<div class="page-container" style="margin-top:84px">

    <!-- Header -->
    <div class="page-header">
        <h1><i data-lucide="layers" style="width:26px;height:26px"></i> بەڕێوەبردنی جۆرەکان</h1>
        <p>زیادکردن، دەستکاری و سڕینەوەی جۆرەکانی کاڵا</p>
    </div>

    <a href="admin.php" class="back-link"><i data-lucide="arrow-right" style="width:15px;height:15px"></i> گەڕانەوە بۆ داشبۆرد</a>

    <!-- Message -->
    <?php if ($message): ?>
    <div class="msg <?php echo $is_error ? 'error' : 'success'; ?>">
        <i data-lucide="<?php echo $is_error ? 'alert-circle' : 'check-circle'; ?>" style="width:18px;height:18px"></i>
        <?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>

    <!-- Tab Bar -->
    <div class="tab-bar">
        <a href="?mode=insert" class="tab-btn <?php echo $mode==='insert'?'active':''; ?>">
            <i data-lucide="plus-circle" style="width:16px;height:16px"></i> زیادکردنی جۆر
        </a>
        <a href="?mode=view" class="tab-btn <?php echo $mode==='view'?'active':''; ?>">
            <i data-lucide="list" style="width:16px;height:16px"></i> هەموو جۆرەکان
        </a>
    </div>

    <!-- Form -->
    <?php if ($mode === 'insert'): ?>
    <div class="form-card">
        <h2>
            <i data-lucide="<?php echo $edit_cat ? 'edit' : 'plus-circle'; ?>" style="width:20px;height:20px;color:#00D4AA"></i>
            <?php echo $edit_cat ? 'دەستکاریکردنی جۆر' : 'زیادکردنی جۆری نوێ'; ?>
        </h2>
        <form method="POST">
            <?php if ($edit_cat): ?><input type="hidden" name="id" value="<?php echo $edit_cat['id']; ?>"><?php endif; ?>
            <div class="form-group">
                <label><i data-lucide="layers" style="width:15px;height:15px"></i> ناوی جۆر *</label>
                <input type="text" name="title" required placeholder="بۆ نموونە: شەمامک، تریکۆ، پانتۆڵ..." value="<?php echo $edit_cat ? htmlspecialchars($edit_cat['name']) : ''; ?>" autofocus>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn-submit">
                    <i data-lucide="<?php echo $edit_cat ? 'save' : 'plus'; ?>" style="width:16px;height:16px"></i>
                    <?php echo $edit_cat ? 'نوێکردنەوە' : 'زیادکردن'; ?>
                </button>
                <a href="?mode=view" class="btn-cancel"><i data-lucide="x" style="width:15px;height:15px"></i> پاشگەزبوونەوە</a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Table -->
    <?php if ($mode === 'view'): ?>
    <div class="table-card">
        <h2>
            <i data-lucide="list" style="width:20px;height:20px;color:#00D4AA"></i>
            هەموو جۆرەکان
            <?php if ($result): ?>
            <span class="count-badge"><?php echo $result->num_rows; ?> جۆر</span>
            <?php endif; ?>
        </h2>
        <?php if ($result && $result->num_rows > 0): ?>
        <div style="overflow-x:auto">
        <table>
            <thead><tr>
                <th style="width:60px">#</th>
                <th>ناوی جۆر</th>
                <th style="width:150px">بەروار</th>
                <th style="width:160px">کردارەکان</th>
            </tr></thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td class="cat-name"><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo date('Y/m/d', strtotime($row['created_at'])); ?></td>
                <td>
                    <div class="action-cell">
                        <a href="?mode=insert&edit_id=<?php echo $row['id']; ?>" class="btn-edit">
                            <i data-lucide="edit" style="width:13px;height:13px"></i> دەستکاری
                        </a>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('دڵنیایت لە سڕینەوەی ئەم جۆرە؟')">
                            <i data-lucide="trash-2" style="width:13px;height:13px"></i> سڕینەوە
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <?php else: ?>
        <div class="empty-row">
            <i data-lucide="inbox" style="width:36px;height:36px;color:#C0C0D8;display:block;margin:0 auto 14px"></i>
            هیچ جۆرێک نییە — <a href="?mode=insert" style="color:#00A885;font-weight:700">یەکەم زیاد بکە</a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>
<script>lucide.createIcons();</script>
</body>
</html>
