<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: home.php"); exit(); }
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'هەموو خانەکان پێویستن!';
    } elseif (strlen($username) < 3) {
        $error = 'ناوی بەکارهێنەر دەبێت لانیکەم ٣ پیت بێت!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'ئیمەیڵ دروست نییە!';
    } elseif (strlen($password) < 6) {
        $error = 'تێپەڕوشە دەبێت لانیکەم ٦ پیت بێت!';
    } elseif ($password !== $password_confirm) {
        $error = 'تێپەڕوشەکان یەک ناگرنەوە!';
    } else {
        include '../db.php';
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) {
            $error = 'ناوی بەکارهێنەر یان ئیمەیڵ پێشتر بەکارهاتووە!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($insert_stmt->execute()) {
                $success = 'خۆتۆمارکردن سەرکەوتوو بوو! ئێستا دەتوانیت <a href="login.php">چوونەژوورەوە بکەیت</a>';
            } else {
                $error = 'هەڵە لە تۆمارکردندا: ' . $conn->error;
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>خۆتۆمارکردن</title>
<link rel="stylesheet" href="../styles.css">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
html,body{background:#F5F6FF !important;color:#1A1A2E !important;min-height:100vh}
.page-wrapper{display:flex;align-items:center;justify-content:center;min-height:calc(100vh - 68px);padding:28px 20px;background:#F5F6FF}
.auth-card{max-width:480px;width:100%;background:#fff !important;border-radius:24px;border:1px solid rgba(0,0,0,0.07);box-shadow:0 8px 40px rgba(0,0,0,0.1);direction:rtl;overflow:hidden;animation:fadeInUp 0.45s ease}
.auth-header{background:linear-gradient(135deg,#00D4AA,#00A885);padding:32px 36px 28px;text-align:center;position:relative;overflow:hidden}
.auth-header::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 30% 50%,rgba(255,255,255,0.15),transparent 60%);pointer-events:none}
.auth-icon{width:64px;height:64px;background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.35);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:#fff;position:relative;z-index:1}
.auth-header h1{margin:0;color:#fff !important;font-family:'UniSIRWAN',sans-serif;font-size:1.7rem;font-weight:800;position:relative;z-index:1}
.auth-header p{margin:6px 0 0;color:rgba(255,255,255,0.85);font-family:'UniSIRWAN',sans-serif;font-size:14px;position:relative;z-index:1}
.auth-body{padding:32px 36px 36px}
.form-group{margin-bottom:18px}
.form-group label{display:flex;align-items:center;gap:7px;margin-bottom:9px;color:#4A4A6A;font-weight:600;font-size:14px;font-family:'UniSIRWAN',sans-serif}
.form-group input{width:100%;padding:13px 16px;border:1.5px solid rgba(0,180,140,0.2) !important;border-radius:12px;box-sizing:border-box;font-size:14px;font-family:'UniSIRWAN',sans-serif;background:#FAFCFC !important;color:#1A1A2E !important;transition:all 0.3s;direction:rtl}
.form-group input::placeholder{color:#A0A0B8 !important}
.form-group input:focus{outline:none;border-color:#00D4AA !important;background:#F0FDFB !important;box-shadow:0 0 0 3px rgba(0,212,170,0.1)}
.btn-submit{width:100%;padding:14px;background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;border:none;border-radius:12px;font-weight:800;cursor:pointer;font-size:16px;font-family:'UniSIRWAN',sans-serif;transition:all 0.3s;margin-top:6px;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 18px rgba(0,212,170,0.35)}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(0,212,170,0.5);filter:brightness(1.06)}
.message{padding:13px 16px;margin-bottom:18px;border-radius:12px;font-size:14px;font-family:'UniSIRWAN',sans-serif;display:flex;align-items:center;justify-content:center;gap:8px;animation:slideIn 0.3s ease}
.error{background:rgba(255,59,111,0.07) !important;color:#FF3B6F !important;border:1px solid rgba(255,59,111,0.18)}
.success{background:rgba(0,212,170,0.07) !important;color:#00A885 !important;border:1px solid rgba(0,212,170,0.2)}
.success a{color:#00A885 !important;font-weight:800}
.divider{border:none;border-top:1px solid rgba(0,0,0,0.07);margin:22px 0}
.auth-footer{text-align:center;margin-top:4px}
.auth-footer p{color:#7A7A9A;font-size:14px;font-family:'UniSIRWAN',sans-serif;margin:0}
.auth-footer a{color:#00A885 !important;text-decoration:none;font-weight:800;transition:all 0.15s}
.auth-footer a:hover{color:#00D4AA !important;text-decoration:underline}
@keyframes fadeInUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
@keyframes slideIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
</style>
</head>
<body style="background:#F5F6FF !important;">
<iframe src="../includes/navbar.php" style="border:none;width:100%;height:64px;margin:0;padding:0"></iframe>
<div class="page-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon"><i data-lucide="user-plus" style="width:30px;height:30px"></i></div>
            <h1>خۆتۆمارکردن</h1>
            <p>ئەکاونتی نوێ دروست بکە</p>
        </div>
        <div class="auth-body">
            <?php if ($error): ?>
                <div class="message error"><i data-lucide="alert-circle" style="width:17px;height:17px"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="message success"><i data-lucide="check-circle" style="width:17px;height:17px"></i> <?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label><i data-lucide="user" style="width:15px;height:15px"></i> ناوی بەکارهێنەر</label>
                    <input type="text" name="username" placeholder="ناوەکەت بنووسە" required minlength="3">
                </div>
                <div class="form-group">
                    <label><i data-lucide="mail" style="width:15px;height:15px"></i> ئیمەیڵ</label>
                    <input type="email" name="email" placeholder="ئیمەیڵەکەت بنووسە" required>
                </div>
                <div class="form-group">
                    <label><i data-lucide="lock" style="width:15px;height:15px"></i> تێپەڕوشە</label>
                    <input type="password" name="password" placeholder="تێپەڕوشەکەت بنووسە" required minlength="6">
                </div>
                <div class="form-group">
                    <label><i data-lucide="shield-check" style="width:15px;height:15px"></i> دووبارەکردنەوەی تێپەڕوشە</label>
                    <input type="password" name="password_confirm" placeholder="دووبارە بنووسە" required minlength="6">
                </div>
                <button type="submit" name="register" class="btn-submit">
                    <i data-lucide="user-plus" style="width:17px;height:17px"></i> خۆتۆمارکردن
                </button>
            </form>
            <hr class="divider">
            <div class="auth-footer">
                <p>پێشتر ئەکاونتت هەیە؟ <a href="login.php">چوونەژوورەوە</a></p>
            </div>
        </div>
    </div>
</div>
<script>lucide.createIcons();</script>
</body>
</html>
