<?php include '../db.php'; ?>
<!DOCTYPE html><html lang="ckb" dir="rtl"><head>
<meta charset="UTF-8"><title>پەیوەندی</title>
<link rel="stylesheet" href="../styles.css">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
html,body{background:#F5F6FF !important;color:#1A1A2E !important}
.page-header{background:linear-gradient(135deg,#00D4AA,#00A885) !important;padding:28px 30px;border-radius:20px;margin-bottom:28px;box-shadow:0 8px 30px rgba(0,212,170,0.3);position:relative;overflow:hidden;direction:rtl}
.page-header::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 20% 50%,rgba(255,255,255,0.12),transparent 60%);pointer-events:none}
.page-header h1{margin:0;color:#fff !important;font-family:'UniSIRWAN',sans-serif;font-size:1.7rem;display:flex;align-items:center;gap:12px;position:relative;z-index:1}
.page-header p{margin:8px 0 0;color:rgba(255,255,255,0.85);font-family:'UniSIRWAN',sans-serif;font-size:14px;position:relative;z-index:1;text-align:right}
.contact-page{max-width:900px;margin:30px auto;padding:0 20px;direction:rtl}
.contact-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;margin-bottom:30px}
.contact-card{background:#fff !important;border:1px solid rgba(0,0,0,0.07);border-radius:18px;padding:28px 24px;text-align:center;box-shadow:0 2px 14px rgba(0,0,0,0.07);transition:all .3s;position:relative;overflow:hidden}
.contact-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#00D4AA,#00A885)}
.contact-card:hover{transform:translateY(-6px);box-shadow:0 14px 36px rgba(0,212,170,0.13);border-color:rgba(0,212,170,0.22)}
.contact-icon{width:64px;height:64px;background:rgba(0,212,170,0.08);border:2px solid rgba(0,212,170,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:#00D4AA;transition:all .3s}
.contact-card:hover .contact-icon{background:#00D4AA;color:#fff;border-color:#00D4AA;transform:scale(1.08)}
.contact-card h3{color:#1A1A2E !important;font-family:'UniSIRWAN',sans-serif;font-size:16px;font-weight:800;margin:0 0 8px}
.contact-card p{color:#4A4A6A;font-family:'UniSIRWAN',sans-serif;font-size:14px;margin:0;line-height:1.6}
.contact-card a{color:#00A885;text-decoration:none;font-weight:700}
.contact-card a:hover{text-decoration:underline}
</style></head>
<body style="background:#F5F6FF !important;">
<iframe src="../includes/navbar.php" style="border:none;width:100%;height:64px;margin:0;padding:0"></iframe>
<div class="contact-page">
    <div class="page-header">
        <h1><i data-lucide="phone" style="width:26px;height:26px"></i> پەیوەندیمان پێوە بکە</h1>
        <p>هەر پرسیارێکت هەیە خۆشحاڵ دەبین کە یارمەتیت بدەین</p>
    </div>
    <div class="contact-grid">
        <div class="contact-card">
            <div class="contact-icon"><i data-lucide="phone" style="width:28px;height:28px"></i></div>
            <h3>ژمارەی تەلەفۆن</h3>
            <p><a href="tel:+9647501234567">+964 750 123 4567</a></p>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i data-lucide="mail" style="width:28px;height:28px"></i></div>
            <h3>ئیمەیڵ</h3>
            <p><a href="mailto:info@store.com">info@store.com</a></p>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i data-lucide="map-pin" style="width:28px;height:28px"></i></div>
            <h3>ناونیشان</h3>
            <p>هەولێر، کوردستان، عێراق</p>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i data-lucide="clock" style="width:28px;height:28px"></i></div>
            <h3>کاتی کار</h3>
            <p>دووشەممە - جومعە<br>٩ی بەیانی - ٥ی ئێوارە</p>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i data-lucide="message-circle" style="width:28px;height:28px"></i></div>
            <h3>واتسئاپ</h3>
            <p><a href="#">+964 750 123 4567</a></p>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i data-lucide="instagram" style="width:28px;height:28px"></i></div>
            <h3>ئینستاگرام</h3>
            <p><a href="#">@store_kurdish</a></p>
        </div>
    </div>
</div>
<script>lucide.createIcons();</script>
</body></html>
