<?php
session_start();
include '../db.php';

$is_logged_in = isset($_SESSION['user_id']);

// Check if an ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product details
$sql = "SELECT p.*, c.name as category_name, b.brand_title as brand_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        LEFT JOIN brands b ON p.brand_id = b.id 
        WHERE p.id = $product_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    header("Location: home.php");
    exit();
}

$product = $result->fetch_assoc();
$image_db = !empty($product['image']) ? htmlspecialchars($product['image']) : 'images/placeholder.jpg';
$img_path = (strpos($image_db, 'http') === 0 || strpos($image_db, '../') === 0) ? $image_db : '../' . ltrim($image_db, '/');
$name = htmlspecialchars($product['name']);
$desc = htmlspecialchars($product['description']);
$price = number_format($product['price'], 2);
$category = !empty($product['category_name']) ? htmlspecialchars($product['category_name']) : 'نەناسراو';
$brand = !empty($product['brand_name']) ? htmlspecialchars($product['brand_name']) : 'نەناسراو';

// We also need to check cart count for navbar, but navbar iframe handles it.
?>
<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $name; ?> - سپۆڕت زۆن</title>
<link rel="stylesheet" href="../styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
html,body{background:#F5F6FF !important;color:#1A1A2E !important;font-family:'UniSIRWAN',sans-serif;direction:rtl;min-height:100vh}

.product-container{max-width:1100px;margin:40px auto;padding:0 20px}
.back-link{display:inline-flex;align-items:center;gap:8px;color:#00A885;text-decoration:none;font-weight:700;margin-bottom:24px;padding:8px 16px;background:rgba(0,212,170,0.1);border-radius:12px;transition:all .3s}
.back-link:hover{background:#00D4AA;color:#fff}

.product-card{background:#fff;border-radius:24px;box-shadow:0 12px 40px rgba(0,0,0,0.06);display:flex;overflow:hidden;border:1px solid rgba(0,212,170,0.1)}

/* Image Section */
.product-image-section{flex:1;background:linear-gradient(135deg,#F0F8F6,#EBF8FF);display:flex;align-items:center;justify-content:center;padding:40px;position:relative}
.product-image-section::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at center,rgba(0,212,170,0.05) 0%,transparent 70%);pointer-events:none}
.product-image{width:100%;max-width:400px;height:auto;object-fit:cover;border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,0.15);transition:transform .4s}
.product-image:hover{transform:scale(1.03)}

/* Info Section */
.product-info-section{flex:1.2;padding:50px;display:flex;flex-direction:column;justify-content:center}
.product-meta{display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap}
.meta-badge{background:rgba(0,212,170,0.08);color:#00A885;padding:6px 16px;border-radius:50px;font-size:13px;font-weight:700;display:inline-flex;align-items:center;gap:6px;border:1px solid rgba(0,212,170,0.2)}
.meta-badge.brand{background:rgba(124,92,252,0.08);color:#7C5CFC;border-color:rgba(124,92,252,0.2)}

.product-title{font-size:2.2rem;font-weight:800;color:#1A1A2E;margin:0 0 16px;line-height:1.3}
.product-price{font-size:2rem;font-weight:800;color:#00D4AA;margin:0 0 24px;display:flex;align-items:center;gap:10px}
.price-badge{background:linear-gradient(135deg,#00D4AA,#00A885);color:#fff;padding:4px 12px;border-radius:10px;font-size:14px;font-weight:600;box-shadow:0 4px 12px rgba(0,212,170,0.3)}

.product-desc{font-size:1.05rem;color:#6A6A8A;line-height:1.8;margin:0 0 36px;background:rgba(0,0,0,0.01);padding:20px;border-radius:16px;border:1px solid rgba(0,0,0,0.03)}

.product-actions{display:flex;gap:16px;margin-top:auto}
.btn-large{flex:1;padding:16px 20px;border:none;border-radius:16px;font-size:16px;font-weight:800;font-family:'UniSIRWAN',sans-serif;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;transition:all .3s}
.btn-primary{background:linear-gradient(135deg,#00D4AA,#00A885);color:#fff;box-shadow:0 8px 24px rgba(0,212,170,0.35)}
.btn-primary:hover{transform:translateY(-3px);box-shadow:0 12px 30px rgba(0,212,170,0.5)}
.btn-secondary{background:#F0FDFB;color:#00A885;border:2px solid rgba(0,212,170,0.3)}
.btn-secondary:hover{background:#00D4AA;color:#fff;border-color:#00D4AA;transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,212,170,0.3)}

/* Toast */
.toast-notify{position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(80px);background:linear-gradient(135deg,#00D4AA,#00A885);color:#fff;padding:13px 28px;border-radius:50px;font-family:'UniSIRWAN',sans-serif;font-size:15px;font-weight:700;box-shadow:0 8px 28px rgba(0,212,170,0.4);z-index:9999;transition:transform .4s cubic-bezier(0.34,1.36,0.64,1),opacity .3s;opacity:0;display:flex;align-items:center;gap:9px}
.toast-notify.show{transform:translateX(-50%) translateY(0);opacity:1}
.toast-notify.error{background:linear-gradient(135deg,#FF6B6B,#FF3B6F)}

/* Responsive */
@media(max-width:900px){
    .product-card{flex-direction:column}
    .product-image-section{padding:30px}
    .product-info-section{padding:30px}
    .product-title{font-size:1.8rem}
}
@media(max-width:500px){
    .product-info-section{padding:24px}
    .product-title{font-size:1.5rem}
    .product-price{font-size:1.6rem}
    .product-actions{flex-direction:column}
}
</style>
</head>
<body style="background:#F5F6FF !important;">
<iframe src="../includes/navbar.php" style="border:none;width:100%;height:64px;margin:0;padding:0" id="navIframe"></iframe>

<div class="product-container">
    <a href="home.php" class="back-link"><i data-lucide="arrow-right" style="width:18px;height:18px"></i> گەڕانەوە بۆ سەرەتا</a>
    
    <div class="product-card">
        <div class="product-image-section">
            <img src="<?php echo $img_path; ?>" alt="<?php echo $name; ?>" class="product-image">
        </div>
        
        <div class="product-info-section">
            <div class="product-meta">
                <span class="meta-badge"><i data-lucide="layers" style="width:14px;height:14px"></i> <?php echo $category; ?></span>
                <span class="meta-badge brand"><i data-lucide="tag" style="width:14px;height:14px"></i> <?php echo $brand; ?></span>
            </div>
            
            <h1 class="product-title"><?php echo $name; ?></h1>
            
            <div class="product-price">
                $<?php echo $price; ?>
                <span class="price-badge">نرخی تایبەت</span>
            </div>
            
            <div class="product-desc">
                <?php echo nl2br($desc); ?>
            </div>
            
            <div class="product-actions">
                <?php if ($is_logged_in): ?>
                    <button class="btn-large btn-primary" onclick="addToCart(<?php echo $product_id; ?>, '<?php echo addslashes($name); ?>', <?php echo $product['price']; ?>)">
                        <i data-lucide="shopping-cart" style="width:20px;height:20px"></i> خستنە سەبەتەوە
                    </button>
                <?php else: ?>
                    <button class="btn-large btn-secondary" onclick="redirectToLogin()">
                        <i data-lucide="log-in" style="width:20px;height:20px"></i> چوونەژوورەوە بۆ کڕین
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div class="toast-notify" id="toast">
    <i data-lucide="check-circle" style="width:18px;height:18px" id="toastIcon"></i>
    <span id="toastMsg"></span>
</div>

<!-- Login modal for guests -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden;direction:rtl">
            <div style="background:linear-gradient(135deg,#00D4AA,#00A885);padding:28px 24px;text-align:center;position:relative">
                <div style="width:60px;height:60px;background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.35);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:#fff">
                    <i data-lucide="log-in" style="width:28px;height:28px"></i>
                </div>
                <h5 style="color:#fff !important;font-family:'UniSIRWAN',sans-serif;margin:0;font-size:1.3rem;font-weight:800">پێویستە بچیتە ژوورەوە</h5>
                <p style="color:rgba(255,255,255,0.85);font-family:'UniSIRWAN',sans-serif;font-size:13px;margin:6px 0 0">بۆ کڕینی کاڵا، پێویستە ئەکاونتت هەبێت</p>
            </div>
            <div class="modal-body" style="padding:28px;background:#fff;text-align:center">
                <p style="color:#4A4A6A;font-family:'UniSIRWAN',sans-serif;margin-bottom:22px;font-size:14px">
                    ئەگەر ئەکاونتت هەیە، چوونەژوورەوە بکە. ئەگەر نەیەیت، ئەکاونتی نوێ دروستبکە.
                </p>
                <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                    <a href="login.php" style="padding:12px 28px;background:linear-gradient(135deg,#00D4AA,#00A885);color:#fff;text-decoration:none;border-radius:12px;font-family:'UniSIRWAN',sans-serif;font-weight:800;font-size:14px;display:flex;align-items:center;gap:7px;box-shadow:0 4px 14px rgba(0,212,170,0.35);transition:all .2s">
                        <i data-lucide="log-in" style="width:15px;height:15px"></i> چوونەژوورەوە
                    </a>
                    <a href="register.php" style="padding:12px 28px;background:#F0FDFB;color:#00A885;text-decoration:none;border-radius:12px;font-family:'UniSIRWAN',sans-serif;font-weight:800;font-size:14px;display:flex;align-items:center;gap:7px;border:1.5px solid rgba(0,212,170,0.25);transition:all .2s">
                        <i data-lucide="user-plus" style="width:15px;height:15px"></i> دروستکردنی ئەکاونت
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
lucide.createIcons();

function showToast(msg, isError = false) {
    const t = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    t.className = 'toast-notify' + (isError ? ' error' : '');
    lucide.createIcons();
    setTimeout(() => t.classList.add('show'), 10);
    setTimeout(() => t.classList.remove('show'), 3200);
}

function redirectToLogin() {
    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
}

function addToCart(productId, productName, productPrice) {
    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('../api/add_to_cart.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            showToast(data.message, !data.success);
            if (data.success) updateCartCount();
        })
        .catch(() => showToast('هەڵەیەک ڕووی دا', true));
}

function updateCartCount() {
    fetch('../api/get_cart_count.php')
        .then(r => r.json())
        .then(data => {
            const iframe = document.getElementById('navIframe');
            if (iframe && iframe.contentWindow)
                iframe.contentWindow.postMessage({ cartCount: data.count }, '*');
        });
}

window.addEventListener('message',function(e){
  if(e.data&&e.data.drawerOpen!==undefined){
    document.getElementById('navIframe').style.height=e.data.drawerOpen?'auto':'64px';
  }
});
</script>
</body>
</html>
