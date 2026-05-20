<?php
session_start();
include '../db.php';
$search_term = isset($_GET['search']) ? trim($conn->real_escape_string($_GET['search'])) : '';
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>سەرەتا - کۆگای ئەلیکترۆنی</title>
<link rel="stylesheet" href="../styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
html,body{background:#F5F6FF !important;color:#1A1A2E !important;font-family:'UniSIRWAN',sans-serif;direction:rtl}

/* Hero */
.hero-section{background:linear-gradient(135deg,#EBF8FF 0%,#EEF0FF 40%,#F5EAFF 70%,#F0F8FF 100%) !important;text-align:center;padding:70px 20px 55px;border-bottom:1px solid rgba(0,212,170,0.12);position:relative;overflow:hidden}
.hero-section::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 30% 70%,rgba(0,212,170,0.1) 0%,transparent 50%),radial-gradient(circle at 70% 30%,rgba(124,92,252,0.08) 0%,transparent 50%);pointer-events:none}
.hero-section h1{color:#1A1A2E !important;font-size:2.6rem;font-weight:800;margin-bottom:14px;position:relative;z-index:1}
.hero-section p{color:#4A4A6A;font-size:1.1rem;position:relative;z-index:1;margin:0}
.hero-accent{color:#00D4AA !important;-webkit-text-fill-color:#00D4AA !important}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(0,212,170,0.1);border:1px solid rgba(0,212,170,0.25);padding:7px 18px;border-radius:50px;color:#00A885;font-size:13px;margin-bottom:22px;position:relative;z-index:1}

/* Filter bar */
.filter-bar{background:#fff !important;padding:14px 28px;display:flex;gap:14px;align-items:center;border-bottom:1px solid rgba(0,0,0,0.07);flex-wrap:wrap;direction:rtl;box-shadow:0 2px 10px rgba(0,0,0,0.05)}
.filter-label{color:#8A8AA8;font-size:14px;display:flex;align-items:center;gap:6px}
.filter-bar .dropdown .btn{background:rgba(0,212,170,0.08) !important;color:#00A885 !important;border:1px solid rgba(0,212,170,0.2) !important;border-radius:50px;padding:8px 18px;font-family:'UniSIRWAN',sans-serif;font-size:13px;font-weight:600;display:flex;align-items:center;gap:6px;transition:all .3s}
.filter-bar .dropdown .btn:hover{background:#00D4AA !important;color:#fff !important;border-color:#00D4AA !important}
.filter-bar .dropdown-menu{direction:rtl;text-align:right;font-family:'UniSIRWAN',sans-serif;background:#fff !important;border:1px solid rgba(0,212,170,0.15) !important;box-shadow:0 10px 40px rgba(0,0,0,0.1)}
.filter-bar .dropdown-menu .dropdown-item{color:#4A4A6A !important;transition:all .15s}
.filter-bar .dropdown-menu .dropdown-item:hover,.filter-bar .dropdown-menu .dropdown-item.active{background:#00D4AA !important;color:#fff !important}

/* Search info */
.search-info-bar{text-align:center;padding:13px;background:rgba(0,212,170,0.06);border-bottom:1px solid rgba(0,212,170,0.12);font-family:'UniSIRWAN',sans-serif;color:#00A885;direction:rtl;display:flex;align-items:center;justify-content:center;gap:8px;font-size:14px}

/* Cards grid */
.cards-container{display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:26px;padding:36px 32px;max-width:1380px;margin:0 auto;width:100%;direction:rtl}
@keyframes cardIn{from{opacity:0;transform:translateY(24px) scale(0.97)}to{opacity:1;transform:translateY(0) scale(1)}}

/* Card */
.card{background:#fff !important;border-radius:20px;overflow:hidden;border:1px solid rgba(0,0,0,0.07);box-shadow:0 2px 16px rgba(0,0,0,0.07);transition:transform .3s cubic-bezier(0.34,1.36,0.64,1),box-shadow .3s,border-color .3s;animation:cardIn .45s ease forwards;opacity:0;display:flex;flex-direction:column;position:relative}
.card:hover{transform:translateY(-10px);box-shadow:0 24px 56px rgba(0,212,170,0.14),0 4px 18px rgba(0,0,0,0.09);border-color:rgba(0,212,170,0.26)}
.card-img-wrap{position:relative;overflow:hidden;flex-shrink:0}
.card-img-wrap::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,transparent 55%,rgba(0,0,0,0.15) 100%);opacity:0;transition:opacity .3s}
.card:hover .card-img-wrap::after{opacity:1}
.card img{width:100%;height:215px;object-fit:cover;display:block;transition:transform .55s ease}
.card:hover img{transform:scale(1.06)}
.card-price-badge{position:absolute;top:12px;left:12px;z-index:2;background:linear-gradient(135deg,#00D4AA,#00A885);color:#fff !important;-webkit-text-fill-color:#fff !important;font-size:14px;font-weight:800;font-family:'UniSIRWAN',sans-serif;padding:5px 14px;border-radius:50px;box-shadow:0 4px 14px rgba(0,212,170,0.45)}
.card-content{padding:16px 16px 4px;direction:rtl;text-align:right;flex:1;display:flex;flex-direction:column;gap:6px}
.card-content h3{margin:0;font-size:16px;color:#1A1A2E !important;font-family:'UniSIRWAN',sans-serif;font-weight:800;line-height:1.4;transition:color .2s}
.card:hover .card-content h3{color:#00A885 !important}
.card-description{margin:0;font-size:12px;color:#8A8AAA;line-height:1.6;font-family:'UniSIRWAN',sans-serif;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.card-meta{display:flex;gap:6px;flex-wrap:wrap;margin-top:2px}
.meta-tag{background:rgba(0,212,170,0.07) !important;padding:3px 10px;border-radius:50px;font-size:11px;color:#00A885 !important;display:inline-flex;align-items:center;gap:4px;border:1px solid rgba(0,212,170,0.18);font-family:'UniSIRWAN',sans-serif;font-weight:600}
.card-buttons{display:flex;gap:8px;padding:12px 16px 18px;margin-top:auto}
.btn-add-cart{flex:1;padding:12px 10px;border:none;font-size:14px;font-weight:800;font-family:'UniSIRWAN',sans-serif;cursor:pointer;border-radius:12px;transition:all .22s;display:flex;align-items:center;justify-content:center;gap:7px;background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;box-shadow:0 4px 14px rgba(0,212,170,0.3)}
.btn-add-cart:hover{filter:brightness(1.08);box-shadow:0 6px 24px rgba(0,212,170,0.5);transform:translateY(-2px)}
.btn-login-to-buy{flex:1;padding:12px 10px;border:1.5px solid rgba(0,212,170,0.3);font-size:13px;font-weight:800;font-family:'UniSIRWAN',sans-serif;cursor:pointer;border-radius:12px;transition:all .22s;display:flex;align-items:center;justify-content:center;gap:7px;background:#F0FDFB !important;color:#00A885 !important}
.btn-login-to-buy:hover{background:#00D4AA !important;color:#fff !important;border-color:#00D4AA;transform:translateY(-2px);box-shadow:0 4px 14px rgba(0,212,170,0.35)}
.btn-view{padding:12px;border:1.5px solid rgba(0,212,170,0.2);font-size:14px;cursor:pointer;border-radius:12px;transition:all .22s;display:flex;align-items:center;justify-content:center;background:#F0F8F6 !important;color:#00A885 !important;min-width:48px;max-width:48px}
.btn-view:hover{background:#00D4AA !important;color:#fff !important;border-color:#00D4AA;transform:translateY(-2px)}

/* Empty state */
.empty-state{text-align:center;padding:80px 20px;width:100%;direction:rtl}
.empty-state h3{color:#1A1A2E !important;font-size:1.4rem;margin-bottom:12px;font-family:'UniSIRWAN',sans-serif}
.empty-state p{color:#7A7A9A;font-family:'UniSIRWAN',sans-serif}
.empty-icon{width:80px;height:80px;margin:0 auto 22px;background:rgba(0,212,170,0.08);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#00D4AA}

/* Toast */
.toast-notify{position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(80px);background:linear-gradient(135deg,#00D4AA,#00A885);color:#fff;padding:13px 28px;border-radius:50px;font-family:'UniSIRWAN',sans-serif;font-size:15px;font-weight:700;box-shadow:0 8px 28px rgba(0,212,170,0.4);z-index:9999;transition:transform .4s cubic-bezier(0.34,1.36,0.64,1),opacity .3s;opacity:0;display:flex;align-items:center;gap:9px}
.toast-notify.show{transform:translateX(-50%) translateY(0);opacity:1}
.toast-notify.error{background:linear-gradient(135deg,#FF6B6B,#FF3B6F)}

/* ===== RESPONSIVE ===== */

/* Tablet: 2–3 columns */
@media(max-width:1100px){
  .cards-container{grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;padding:28px 20px}
}

/* Mobile: 2 columns */
@media(max-width:640px){
  .cards-container{grid-template-columns:repeat(2,1fr);gap:12px;padding:16px 12px}
  .hero-section{padding:40px 16px 32px}
  .hero-section h1{font-size:1.6rem}
  .hero-section p{font-size:.95rem}
  .hero-badge{font-size:11px;padding:6px 14px;margin-bottom:14px}
  .filter-bar{padding:10px 14px;gap:8px}
  .filter-label{display:none}
  .filter-bar .dropdown .btn{padding:7px 14px;font-size:12px}
  .search-info-bar{font-size:12px;padding:10px}
  .card img{height:150px}
  .card-content{padding:10px 10px 2px;gap:4px}
  .card-content h3{font-size:13px}
  .card-description{font-size:11px;-webkit-line-clamp:1}
  .meta-tag{font-size:10px;padding:2px 8px}
  .card-price-badge{font-size:11px;padding:3px 10px;top:8px;left:8px}
  .card-buttons{padding:8px 10px 12px;gap:6px}
  .btn-add-cart,.btn-login-to-buy{font-size:12px;padding:9px 6px;gap:5px;border-radius:10px}
  .btn-view{min-width:38px;max-width:38px;border-radius:10px;padding:9px 6px}
  .empty-state{padding:50px 16px}
  .empty-icon{width:60px;height:60px}
}

/* Very small phones */
@media(max-width:380px){
  .cards-container{gap:10px;padding:12px 10px}
  .card img{height:130px}
  .card-content h3{font-size:12px}
  .btn-add-cart,.btn-login-to-buy{font-size:11px;gap:4px}
}
</style>
</head>
<body style="background:#F5F6FF !important;">
<iframe src="../includes/navbar.php" style="border:none;width:100%;height:64px;margin:0;padding:0" id="navIframe"></iframe>
<script>
// Adjust iframe height when mobile drawer opens
window.addEventListener('message',function(e){
  if(e.data&&e.data.drawerOpen!==undefined){
    document.getElementById('navIframe').style.height=e.data.drawerOpen?'auto':'64px';
  }
});
</script>

<!-- Hero -->
<div class="hero-section">
    <div class="hero-badge"><i data-lucide="sparkles" style="width:14px;height:14px"></i> باشترین کاڵاکان لێرەن</div>
    <h1>بەخێربێیت بۆ <span class="hero-accent">سپۆڕت - زۆن </span></h1>
    <p>لێرە دەتوانیت باشترین کاڵاکان بە نرخێکی گونجاو بکڕیت</p>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <span class="filter-label"><i data-lucide="sliders-horizontal" style="width:15px;height:15px"></i> فلتەر بەپێی جۆر:</span>
    <div class="dropdown">
        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i data-lucide="layers" style="width:14px;height:14px"></i>
            <?php
                if (isset($_GET['category']) && !empty($_GET['category'])) {
                    $cur_cat = $conn->query("SELECT name FROM categories WHERE id=" . intval($_GET['category']));
                    echo $cur_cat && $cur_cat->num_rows > 0 ? htmlspecialchars($cur_cat->fetch_assoc()['name']) : 'جۆرەکان';
                } else { echo 'هەموو جۆرەکان'; }
            ?>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item <?php echo !isset($_GET['category']) ? 'active' : ''; ?>" href="home.php">هەموو جۆرەکان</a></li>
            <?php
                $cat_q = $conn->query("SELECT * FROM categories ORDER BY name");
                if ($cat_q) while ($cat = $cat_q->fetch_assoc()) {
                    $active = (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'active' : '';
                    echo '<li><a class="dropdown-item ' . $active . '" href="home.php?category=' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</a></li>';
                }
            ?>
        </ul>
    </div>
</div>

<?php if (!empty($search_term)): ?>
<div class="search-info-bar">
    <i data-lucide="search" style="width:15px;height:15px"></i>
    ئەنجامی گەڕان بۆ: "<strong><?php echo htmlspecialchars($search_term); ?></strong>"
</div>
<?php endif; ?>

<!-- Cards -->
<div class="cards-container">
<?php
    $sql = "SELECT * FROM products WHERE 1=1";
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $sql .= " AND category_id = " . intval($_GET['category']);
    }
    if (!empty($search_term)) {
        $sql .= " AND (name LIKE '%$search_term%' OR description LIKE '%$search_term%')";
    }
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0):
        $delay = 0;
        while ($row = $result->fetch_assoc()):
            $image_db = !empty($row['image']) ? htmlspecialchars($row['image']) : 'images/placeholder.jpg';
            $image_src = (strpos($image_db, 'http') === 0 || strpos($image_db, '../') === 0) ? $image_db : '../' . ltrim($image_db, '/');
            $name  = htmlspecialchars($row['name']);
            $desc  = htmlspecialchars($row['description']);
            $price = number_format($row['price'], 2);
            $id    = intval($row['id']);
            $cat_tag = '';
            if (!empty($row['category_id'])) {
                $cr = $conn->query("SELECT name FROM categories WHERE id=" . intval($row['category_id']));
                if ($cr && $cr->num_rows > 0)
                    $cat_tag = '<span class="meta-tag"><i data-lucide="layers" style="width:11px;height:11px"></i> ' . htmlspecialchars($cr->fetch_assoc()['name']) . '</span>';
            }
?>
    <div class="card" style="animation-delay:<?php echo $delay * 0.08; ?>s">
        <div class="card-img-wrap">
            <img src="<?php echo $image_src; ?>" alt="<?php echo $name; ?>">
            <div class="card-price-badge">$<?php echo $price; ?></div>
        </div>
        <div class="card-content">
            <h3><?php echo $name; ?></h3>
            <p class="card-description"><?php echo $desc; ?></p>
            <?php if ($cat_tag): ?><div class="card-meta"><?php echo $cat_tag; ?></div><?php endif; ?>
        </div>
        <div class="card-buttons">
            <?php if ($is_logged_in): ?>
                <button class="btn-add-cart" onclick="addToCart(<?php echo $id; ?>, '<?php echo addslashes($name); ?>', <?php echo $row['price']; ?>)">
                    <i data-lucide="shopping-cart" style="width:15px;height:15px"></i> زیادکە
                </button>
            <?php else: ?>
                <button class="btn-login-to-buy" onclick="redirectToLogin()">
                    <i data-lucide="log-in" style="width:15px;height:15px"></i> کڕین
                </button>
            <?php endif; ?>
            <a href="product.php?id=<?php echo $id; ?>" class="btn-view" title="زیاتر ببینە"><i data-lucide="eye" style="width:14px;height:14px"></i></a>
        </div>
    </div>
<?php
            $delay++;
        endwhile;
    else:
?>
    <div class="empty-state">
        <div class="empty-icon"><i data-lucide="package" style="width:38px;height:38px"></i></div>
        <h3>هیچ کاڵایەک نەدۆزرایەوە</h3>
        <p>هیچ کاڵایەک بۆ ئەم فلتەرە نییە.</p>
    </div>
<?php endif; $conn->close(); ?>
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

// --- Toast ---
function showToast(msg, isError = false) {
    const t = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    t.className = 'toast-notify' + (isError ? ' error' : '');
    lucide.createIcons();
    setTimeout(() => t.classList.add('show'), 10);
    setTimeout(() => t.classList.remove('show'), 3200);
}

// --- Guest: show login modal ---
function redirectToLogin() {
    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
}

// --- Add to cart (logged-in only) ---
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

// --- Update cart badge in navbar ---
function updateCartCount() {
    fetch('../api/get_cart_count.php')
        .then(r => r.json())
        .then(data => {
            const iframe = document.getElementById('navIframe');
            if (iframe && iframe.contentWindow)
                iframe.contentWindow.postMessage({ cartCount: data.count }, '*');
        });
}
</script>
</body>
</html>
