<?php include 'db.php'; ?>
<!DOCTYPE html><html lang="ckb" dir="rtl"><head>
<meta charset="UTF-8"><title>نرخەکان</title>
<link rel="stylesheet" href="../styles.css">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
html,body{background:#F5F6FF !important;color:#1A1A2E !important}
.page-header{background:linear-gradient(135deg,#00D4AA,#00A885) !important;padding:28px 30px;border-radius:20px;margin-bottom:28px;box-shadow:0 8px 30px rgba(0,212,170,0.3);position:relative;overflow:hidden;direction:rtl}
.page-header::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 20% 50%,rgba(255,255,255,0.12),transparent 60%);pointer-events:none}
.page-header h1{margin:0;color:#fff !important;font-family:'UniSIRWAN',sans-serif;font-size:1.7rem;display:flex;align-items:center;gap:12px;position:relative;z-index:1}
.price-page{max-width:1000px;margin:30px auto;padding:0 20px;direction:rtl}
.price-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;margin-top:10px}
.price-card{background:#fff !important;border:1px solid rgba(0,0,0,0.07);border-radius:20px;padding:30px 24px;text-align:center;box-shadow:0 2px 16px rgba(0,0,0,0.07);transition:all .3s;position:relative;overflow:hidden}
.price-card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#00D4AA,#00A885);border-radius:20px 20px 0 0}
.price-card:hover{transform:translateY(-8px);box-shadow:0 16px 40px rgba(0,212,170,0.14);border-color:rgba(0,212,170,0.25)}
.price-card.featured{border-color:rgba(0,212,170,0.35);box-shadow:0 8px 32px rgba(0,212,170,0.18)}
.price-card.featured::before{height:6px}
.plan-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(0,212,170,0.1);color:#00A885;border:1px solid rgba(0,212,170,0.2);padding:5px 14px;border-radius:50px;font-size:12px;font-weight:700;font-family:'UniSIRWAN',sans-serif;margin-bottom:18px}
.plan-name{font-size:1.4rem;font-weight:800;color:#1A1A2E;font-family:'UniSIRWAN',sans-serif;margin-bottom:8px}
.plan-price{font-size:2.6rem;font-weight:900;color:#00A885;font-family:'UniSIRWAN',sans-serif;margin:14px 0}
.plan-price span{font-size:1rem;color:#7A7A9A;font-weight:500}
.plan-features{list-style:none;padding:0;margin:18px 0;text-align:right}
.plan-features li{padding:8px 0;font-family:'UniSIRWAN',sans-serif;font-size:14px;color:#4A4A6A;border-bottom:1px solid rgba(0,0,0,0.05);display:flex;align-items:center;gap:8px;justify-content:flex-end}
.plan-features li:last-child{border-bottom:none}
.plan-features li i{color:#00D4AA;flex-shrink:0}
.btn-plan{display:block;width:100%;padding:13px;background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;border:none;border-radius:12px;cursor:pointer;font-family:'UniSIRWAN',sans-serif;font-weight:800;font-size:15px;transition:all .3s;box-shadow:0 4px 14px rgba(0,212,170,0.3);text-decoration:none;margin-top:10px}
.btn-plan:hover{filter:brightness(1.08);transform:translateY(-2px);box-shadow:0 6px 22px rgba(0,212,170,0.45)}
.section-title{text-align:center;margin-bottom:8px;font-family:'UniSIRWAN',sans-serif;font-size:1.1rem;color:#7A7A9A}
</style></head>
<body style="background:#F5F6FF !important;">
<iframe src="../includes/navbar.php" style="border:none;width:100%;height:64px;margin:0;padding:0"></iframe>
<div class="price-page">
    <div class="page-header"><h1><i data-lucide="wallet" style="width:26px;height:26px"></i> نرخ و پلانەکان</h1></div>
    <p class="section-title">باشترین پلانی گونجاو بۆ خۆت هەڵبژێرە</p>
    <div class="price-grid">
        <div class="price-card">
            <div class="plan-badge"><i data-lucide="star" style="width:13px;height:13px"></i> سەرەتایی</div>
            <div class="plan-name">پلانی بنەڕەت</div>
            <div class="plan-price">بەخۆڕایی<span> / هەمیشە</span></div>
            <ul class="plan-features">
                <li><i data-lucide="check" style="width:15px;height:15px"></i> گەڕانی کاڵاکان</li>
                <li><i data-lucide="check" style="width:15px;height:15px"></i> بینینی نرخەکان</li>
                <li><i data-lucide="check" style="width:15px;height:15px"></i> پەیوەندی کردن</li>
            </ul>
            <a href="register.php" class="btn-plan">دەستپێبکە</a>
        </div>
        <div class="price-card featured">
            <div class="plan-badge"><i data-lucide="zap" style="width:13px;height:13px"></i> ئەندامی</div>
            <div class="plan-name">پلانی ئەندامایەتی</div>
            <div class="plan-price">$9.99<span> / مانگانە</span></div>
            <ul class="plan-features">
                <li><i data-lucide="check" style="width:15px;height:15px"></i> کڕینی کاڵاکان</li>
                <li><i data-lucide="check" style="width:15px;height:15px"></i> داشکاندنی تایبەت</li>
                <li><i data-lucide="check" style="width:15px;height:15px"></i> ئاگادارکردنەوە</li>
                <li><i data-lucide="check" style="width:15px;height:15px"></i> پشتگیری ٢٤/٧</li>
            </ul>
            <a href="register.php" class="btn-plan">ئێستا بەندبە</a>
        </div>
        <div class="price-card">
            <div class="plan-badge"><i data-lucide="crown" style="width:13px;height:13px"></i> پیشەیی</div>
            <div class="plan-name">پلانی پیشەیی</div>
            <div class="plan-price">$24.99<span> / مانگانە</span></div>
            <ul class="plan-features">
                <li><i data-lucide="check" style="width:15px;height:15px"></i> هەموو تایبەتمەندییەکان</li>
                <li><i data-lucide="check" style="width:15px;height:15px"></i> ئەدمینی فرۆشگا</li>
                <li><i data-lucide="check" style="width:15px;height:15px"></i> ڕاپۆرتی تایبەت</li>
                <li><i data-lucide="check" style="width:15px;height:15px"></i> API دەستپێگەیشتن</li>
            </ul>
            <a href="register.php" class="btn-plan">دەستپێبکە</a>
        </div>
    </div>
</div>
<script>lucide.createIcons();</script>
</body></html>
