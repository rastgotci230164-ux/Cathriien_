<?php
include '../db.php';

header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html dir="rtl"><head><meta charset="UTF-8">
<title>زیادکردنی داتای تاقیکردنەوە</title>
<style>
body{font-family:"Segoe UI",sans-serif;background:#F5F6FF;padding:40px;direction:rtl}
.card{background:#fff;border-radius:16px;padding:28px;max-width:700px;margin:0 auto;box-shadow:0 4px 24px rgba(0,0,0,0.08)}
h2{color:#00A885;margin-bottom:20px}
.ok{color:#00A885;margin:6px 0;font-size:14px}
.err{color:#FF3B6F;margin:6px 0;font-size:14px}
.btn{display:inline-block;margin-top:22px;padding:12px 28px;background:linear-gradient(135deg,#00D4AA,#00A885);color:#fff;text-decoration:none;border-radius:10px;font-weight:700}
</style></head><body><div class="card"><h2>✦ زیادکردنی ٢٠ کاڵای وەرزشی</h2>';

// ===== 1. Categories =====
$cats = ['شەمامک', 'تریکۆ', 'پانتۆڵ', 'کەڵاو', 'گۆرەوی'];
$cat_ids = [];
foreach ($cats as $cat) {
    $c = $conn->real_escape_string($cat);
    $conn->query("INSERT IGNORE INTO categories (name) VALUES ('$c')");
    $r = $conn->query("SELECT id FROM categories WHERE name='$c'");
    if ($r && $r->num_rows > 0) $cat_ids[$cat] = $r->fetch_assoc()['id'];
}

// ===== 2. Products (20 items — Unsplash sport images) =====
$products = [
    // شەمامک
    ['نایک ئێیر مێکس وەرزشی', 'شەمامکی پڕ ئەمەرتی بۆ بازی و ماراتۆن، تەکنەلۆجیای ئێیر کوشن', 89.99, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80', 'شەمامک'],
    ['ئادیداس ئولترابووست', 'شەمامکی کوردی پیاوانە، ئاسایش و خێرایی بۆ دوومەندی', 95.00, 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=600&q=80', 'شەمامک'],
    ['پووما سووێدە', 'شەمامکی سووێدە بۆ ژینگەی ناوەوە، سووک و بایەخدار', 72.50, 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=600&q=80', 'شەمامک'],
    ['ریبۆک نانۆ X1', 'شەمامکی CrossFit و ئەگزەرسایز، دیزاینی مۆدێرن', 110.00, 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=600&q=80', 'شەمامک'],

    // تریکۆ
    ['تریکۆی فووتبالی بارسا', 'تریکۆی فووتبالی ئۆریجینال، پارچەی هوا پێدەدات', 55.00, 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=600&q=80', 'تریکۆ'],
    ['تریکۆی ئێن بی ئێ', 'تریکۆی فووتبالی ئەمریکی بۆ گەشتی ئایەندە', 65.00, 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600&q=80', 'تریکۆ'],
    ['تریکۆی نایک دری فیت', 'تریکۆی وەرزشی پیاوانە، پارچەی لەبارەوە رووماڵ', 48.00, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80', 'تریکۆ'],
    ['تریکۆی ئادیداس Climalite', 'تریکۆی دووران پیاوانە، ئاسایش لە ئاووی بارانی', 52.00, 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=600&q=80', 'تریکۆ'],
    ['تریکۆی زینانە ۲ لایەن', 'تریکۆی ئایەندەی زینانە بۆ یۆگا و بازی', 44.00, 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&q=80', 'تریکۆ'],

    // پانتۆڵ
    ['پانتۆڵی وەرزشی نایک', 'پانتۆڵی تاقمی نایک، کارگیرتنی بەرز بۆ ئەگزەرسایز', 58.00, 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?w=600&q=80', 'پانتۆڵ'],
    ['پانتۆڵی شۆرتی وەرزشی', 'شۆرتی تاقمی بۆ دوومەندی و سووکایەتی کردن', 35.00, 'https://images.unsplash.com/photo-1591195853828-11db59a44f43?w=600&q=80', 'پانتۆڵ'],
    ['پانتۆڵی کومپرێس', 'پانتۆڵی فشار بۆ چاکبوونی گیانی دوای ئەگزەرسایز', 42.00, 'https://images.unsplash.com/photo-1547483238-f400e65ccd56?w=600&q=80', 'پانتۆڵ'],
    ['پانتۆڵی ترەینینگ ئادیداس', 'پانتۆڵی سووکی تاقمی ئادیداس بۆ ژینگەی ناوەوە', 49.00, 'https://images.unsplash.com/photo-1483721310020-03333e577078?w=600&q=80', 'پانتۆڵ'],
    ['شۆرتی زینانەی یۆگا', 'شۆرتی زینانەی نەرم بۆ یۆگا و پیلاتیز', 32.00, 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600&q=80', 'پانتۆڵ'],

    // کەڵاو
    ['کەڵاوی وەرزشی نایک', 'کەڵاوی ئافتاو بۆ دوومەندی و ئەگزەرسایزی دەرەوە', 25.00, 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&q=80', 'کەڵاو'],
    ['کەڵاوی تاقمی ئادیداس', 'کەڵاوی Snapback بۆ وەرزش و ژیانی ڕۆژانە', 22.00, 'https://images.unsplash.com/photo-1529958030586-3aae4ca485ff?w=600&q=80', 'کەڵاو'],
    ['کەڵاوی پووما بیسبۆڵ', 'کەڵاوی بیسبۆڵ ئۆریجینال ستایلدار', 28.00, 'https://images.unsplash.com/photo-1556306535-0f09a537f0a3?w=600&q=80', 'کەڵاو'],

    // گۆرەوی
    ['گۆرەوی نایک Grip', 'گۆرەوی وەرزشی کۆتن بۆ پیادەروان و دوومەندی', 12.00, 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?w=600&q=80', 'گۆرەوی'],
    ['گۆرەوی کومپرێس زانۆ', 'گۆرەوی کومپرێس بۆ چاکبوونی خوێنگەڕانی زانۆ', 18.00, 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&q=80', 'گۆرەوی'],
    ['گۆرەوی سووکی تاقمی', 'گۆرەوی نازک بۆ ژیانی ڕۆژانە و وەرزش', 9.99, 'https://images.unsplash.com/photo-1612540139150-4b3b3b3b3b3b?w=600&q=80', 'گۆرەوی'],
];

$added = 0;
$errors = 0;
foreach ($products as $p) {
    [$name, $desc, $price, $img, $cat_name] = $p;
    $n    = $conn->real_escape_string($name);
    $d    = $conn->real_escape_string($desc);
    $pr   = floatval($price);
    $im   = $conn->real_escape_string($img);
    $cid  = isset($cat_ids[$cat_name]) ? intval($cat_ids[$cat_name]) : 'NULL';

    $sql = "INSERT INTO products (name, description, price, image, category_id)
            VALUES ('$n', '$d', $pr, '$im', $cid)";
    if ($conn->query($sql)) {
        echo '<p class="ok">✓ زیادکرا: <strong>' . htmlspecialchars($name) . '</strong> — $' . $price . '</p>';
        $added++;
    } else {
        echo '<p class="err">✗ هەڵە: ' . htmlspecialchars($name) . ' — ' . $conn->error . '</p>';
        $errors++;
    }
}

$conn->close();
echo "<hr style='margin:22px 0;border-color:rgba(0,212,170,0.2)'>
<p style='color:#1A1A2E;font-weight:700;font-size:15px'>📦 کۆی زیادکراو: <span style='color:#00A885'>$added کاڵا</span>" . ($errors ? " | ❌ هەڵە: <span style='color:#FF3B6F'>$errors</span>" : '') . "</p>
<a href='home.php' class='btn'>🏠 بڕۆ بۆ پەرەی سەرەکی</a>
</div></body></html>";
?>
