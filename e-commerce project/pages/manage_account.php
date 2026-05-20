<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
include '../db.php';
$error = ''; $success = ''; $user_info = [];
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id); $stmt->execute();
$result = $stmt->get_result(); $user_info = $result->fetch_assoc(); $stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_username'])) {
    $new_username = $_POST['new_username'] ?? '';
    if (empty($new_username)) { $error = 'ناوی بەکارهێنەر بەتاڵ نابێت!'; }
    elseif (strlen($new_username) < 3) { $error = 'ناوی بەکارهێنەر دەبێت لانیکەم ٣ پیت بێت!'; }
    elseif ($new_username === $user_info['username']) { $error = 'ناوی نوێ دەبێت جیاواز بێت لە ناوی ئێستا!'; }
    else {
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $check_stmt->bind_param("si", $new_username, $user_id); $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) { $error = 'ئەم ناوە پێشتر بەکارهاتووە!'; }
        else {
            $update_stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_username, $user_id);
            if ($update_stmt->execute()) { $_SESSION['username'] = $new_username; $user_info['username'] = $new_username; $success = 'ناوی بەکارهێنەر نوێ کرایەوە!'; }
            else { $error = 'هەڵە لە نوێکردنەوەدا: ' . $conn->error; }
            $update_stmt->close();
        }
        $check_stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) { $error = 'هەموو خانەکانی وشەی نهێنی  پێویستن!'; }
    elseif (strlen($new_password) < 6) { $error = 'وشەی نهێنی نوێ دەبێت لانیکەم ٦ پیت بێت!'; }
    elseif ($new_password !== $confirm_password) { $error = 'وشەی نهێنییەکان یەک ناگرنەوە!'; }
    else {
        $pwd_stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $pwd_stmt->bind_param("i", $user_id); $pwd_stmt->execute();
        $pwd_result = $pwd_stmt->get_result(); $pwd_row = $pwd_result->fetch_assoc(); $pwd_stmt->close();
        if (!password_verify($current_password, $pwd_row['password'])) { $error = 'تێپەڕوشەی ئێستا هەڵەیە!'; }
        elseif ($current_password === $new_password) { $error = 'تێپەڕوشەی نوێ دەبێت جیاواز بێت!'; }
        else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $pwd_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $pwd_update->bind_param("si", $hashed, $user_id);
            if ($pwd_update->execute()) { $success = 'تێپەڕوشە نوێ کرایەوە!'; }
            else { $error = 'هەڵە لە نوێکردنەوەی تێپەڕوشە: ' . $conn->error; }
            $pwd_update->close();
        }
    }
}
$conn->close();
?>
<!DOCTYPE html><html lang="ckb" dir="rtl"><head>
<meta charset="UTF-8"><title>بەڕێوەبردنی ئەکاونت</title>
<link rel="stylesheet" href="../styles.css">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
html,body{background:#F5F6FF !important;color:#1A1A2E !important}
.page-header{background:linear-gradient(135deg,#00D4AA,#00A885) !important;padding:28px 30px;border-radius:20px;margin-bottom:28px;box-shadow:0 8px 30px rgba(0,212,170,0.3);position:relative;overflow:hidden;direction:rtl}
.page-header::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 20% 50%,rgba(255,255,255,0.12),transparent 60%);pointer-events:none}
.page-header h1{margin:0;color:#fff !important;font-family:'UniSIRWAN',sans-serif;font-size:1.7rem;display:flex;align-items:center;gap:12px;position:relative;z-index:1}
.manage-container{max-width:700px;margin:30px auto;padding:0 20px;direction:rtl}
.section{margin-bottom:20px;padding:26px;background:#fff !important;border:1px solid rgba(0,0,0,0.07);border-radius:18px;box-shadow:0 2px 14px rgba(0,0,0,0.07);border-right:4px solid #00D4AA;transition:all .3s}
.section:hover{border-right-color:#7C5CFC;box-shadow:0 6px 24px rgba(0,212,170,0.1)}
.section h2{color:#1A1A2E !important;margin-top:0;font-size:16px;border-bottom:1px solid rgba(0,0,0,0.07);padding-bottom:12px;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;gap:10px}
.form-group{margin-bottom:16px}
.form-group label{display:flex;align-items:center;gap:7px;margin-bottom:8px;color:#4A4A6A;font-weight:600;font-family:'UniSIRWAN',sans-serif;font-size:14px}
.form-group input{width:100%;padding:12px 16px;border:1px solid rgba(0,180,140,0.2) !important;border-radius:10px;box-sizing:border-box;font-size:14px;font-family:'UniSIRWAN',sans-serif;transition:all .3s;background:#FAFCFC !important;color:#1A1A2E !important}
.form-group input:focus{outline:none;border-color:#00D4AA !important;box-shadow:0 0 0 3px rgba(0,212,170,0.12)}
.form-group input:disabled{background:#F0F2FF !important;color:#8A8AA8 !important;cursor:not-allowed}
.form-group .info{padding:12px 16px;background:rgba(0,212,170,0.06) !important;border-radius:10px;color:#1A1A2E !important;font-size:14px;border:1px solid rgba(0,212,170,0.12);font-family:'UniSIRWAN',sans-serif;font-weight:600}
.btn-update{padding:12px 24px;background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;border:none;border-radius:10px;font-weight:700;cursor:pointer;font-size:14px;font-family:'UniSIRWAN',sans-serif;transition:all .3s;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(0,212,170,0.3)}
.btn-update:hover{transform:translateY(-2px);box-shadow:0 6px 22px rgba(0,212,170,0.45);filter:brightness(1.08)}
.message{padding:14px;margin-bottom:20px;border-radius:10px;text-align:center;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;justify-content:center;gap:8px;animation:slideIn .3s ease}
.error{background:rgba(255,59,111,0.08) !important;color:#FF3B6F !important;border:1px solid rgba(255,59,111,0.2)}
.success{background:rgba(0,212,170,0.08) !important;color:#00A885 !important;border:1px solid rgba(0,212,170,0.25)}
.back-link{text-align:center;margin-top:24px}
.back-link a{color:#00A885 !important;text-decoration:none;font-weight:700;font-family:'UniSIRWAN',sans-serif;display:inline-flex;align-items:center;gap:6px;transition:all .15s;padding:10px 20px;background:rgba(0,212,170,0.08);border:1px solid rgba(0,212,170,0.2);border-radius:10px}
.back-link a:hover{background:#00D4AA !important;color:#fff !important}
</style></head>
<body style="background:#F5F6FF !important;">
<iframe src="../includes/navbar.php" style="border:none;width:100%;height:64px;margin:0;padding:0"></iframe>
<div class="manage-container">
    <div class="page-header"><h1><i data-lucide="settings" style="width:26px;height:26px"></i> بەڕێوەبردنی ئەکاونت</h1></div>
    <?php if ($error): ?><div class="message error"><i data-lucide="alert-circle" style="width:18px;height:18px"></i> <?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if ($success): ?><div class="message success"><i data-lucide="check-circle" style="width:18px;height:18px"></i> <?php echo htmlspecialchars($success); ?></div><?php endif; ?>
    <div class="section">
        <h2><i data-lucide="id-card" style="width:20px;height:20px;color:#00D4AA"></i> زانیاری ئەکاونت</h2>
        <div class="form-group"><label><i data-lucide="user" style="width:15px;height:15px"></i> ناوی بەکارهێنەر:</label><div class="info"><?php echo htmlspecialchars($user_info['username']); ?></div></div>
        <div class="form-group"><label><i data-lucide="mail" style="width:15px;height:15px"></i> ئیمەیڵ:</label><div class="info"><?php echo htmlspecialchars($user_info['email']); ?></div></div>
    </div>
    <div class="section">
        <h2><i data-lucide="edit" style="width:20px;height:20px;color:#00A885"></i> نوێکردنەوەی ناو</h2>
        <form method="POST">
            <div class="form-group"><label><i data-lucide="user" style="width:15px;height:15px"></i> ناوی ئێستا:</label><input type="text" value="<?php echo htmlspecialchars($user_info['username']); ?>" disabled></div>
            <div class="form-group"><label><i data-lucide="user-check" style="width:15px;height:15px"></i> ناوی نوێ:</label><input type="text" name="new_username" placeholder="ناوی نوێ بنووسە" required minlength="3"></div>
            <button type="submit" name="update_username" class="btn-update"><i data-lucide="save" style="width:16px;height:16px"></i> نوێکردنەوەی ناو</button>
        </form>
    </div>
    <div class="section">
        <h2><i data-lucide="lock" style="width:20px;height:20px;color:#7C5CFC"></i> نوێکردنەوەی وشەی نهێنی</h2>
        <form method="POST">
            <div class="form-group"><label><i data-lucide="key" style="width:15px;height:15px"></i> وشەی نهێنی ئێستا:</label><input type="password" name="current_password" placeholder="وشەی نهێنی ئێستا" required></div>
            <div class="form-group"><label><i data-lucide="lock" style="width:15px;height:15px"></i> وشەی نهێنی نوێ:</label><input type="password" name="new_password" placeholder="وشەی نهێنی نوێ" required minlength="6"></div>
            <div class="form-group"><label><i data-lucide="shield-check" style="width:15px;height:15px"></i> دووبارەکردنەوە:</label><input type="password" name="confirm_password" placeholder="وشەی نهێنی نوێ دووبارە" required minlength="6"></div>
            <button type="submit" name="update_password" class="btn-update"><i data-lucide="save" style="width:16px;height:16px"></i> نوێکردنەوەی وشەی نهێنی</button>
        </form>
    </div>
    <div class="back-link"><a href="home.php"><i data-lucide="arrow-right" style="width:16px;height:16px"></i> گەڕانەوە بۆ سەرەتا</a></div>
</div>
<script>lucide.createIcons();</script>
</body></html>
