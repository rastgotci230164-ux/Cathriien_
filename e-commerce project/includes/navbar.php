<?php
session_start();
include '../db.php';

$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart_result = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
    if ($cart_result) {
        $row = $cart_result->fetch_assoc();
        $cart_count = $row['total'] ? intval($row['total']) : 0;
    }
}
?>
<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../styles.css">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
*{box-sizing:border-box;margin:0;padding:0}
html,body{background:transparent !important;font-family:'UniSIRWAN',sans-serif}

/* ===== Navbar ===== */
.navbar{
    display:flex;justify-content:space-between;align-items:center;
    background:linear-gradient(135deg,#00D4AA 0%,#00A885 100%) !important;
    padding:0 24px;height:64px;width:100%;
    box-shadow:0 4px 24px rgba(0,212,170,0.4);
    position:relative;overflow:hidden;
}
.navbar::before{content:'';position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.07),transparent);pointer-events:none}

/* Logo */
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0}
.logo-ring{width:36px;height:36px;border-radius:50%;border:2px solid rgba(255,255,255,0.6);object-fit:cover;transition:all .2s}
.logo-ring:hover{border-color:#fff;transform:scale(1.08)}
.logo-text{color:#fff;font-size:15px;font-weight:800;font-family:'UniSIRWAN',sans-serif;white-space:nowrap}

/* Center links */
.nav-links{display:flex;gap:2px;align-items:center;flex-direction:row-reverse}
.nav-links a{text-decoration:none;color:rgba(255,255,255,0.9);font-size:13px;font-weight:700;font-family:'UniSIRWAN',sans-serif;padding:7px 12px;border-radius:8px;transition:all .2s;display:flex;align-items:center;gap:5px;white-space:nowrap}
.nav-links a:hover{background:rgba(255,255,255,0.18);color:#fff}
.nav-links a i{width:14px !important;height:14px !important}
.nav-admin-pill{background:rgba(0,0,0,0.22) !important;border:1px solid rgba(255,255,255,0.3) !important;color:#fff !important;border-radius:50px !important;font-size:12px !important}
.nav-admin-pill:hover{background:rgba(0,0,0,0.38) !important}

/* Right section */
.nav-right{display:flex;align-items:center;gap:8px;flex-direction:row-reverse;flex-shrink:0}

/* Search */
.search-form{display:flex;align-items:center;position:relative}
.search-icon-abs{position:absolute;right:12px;color:rgba(255,255,255,0.7);pointer-events:none;z-index:2;width:14px;height:14px}
.search-input{padding:7px 36px 7px 14px;border:1.5px solid rgba(255,255,255,0.32);border-radius:50px;width:160px;font-size:13px;font-family:'UniSIRWAN',sans-serif;background:rgba(255,255,255,0.14) !important;color:#fff !important;direction:rtl;transition:all .25s}
.search-input::placeholder{color:rgba(255,255,255,0.6) !important}
.search-input:focus{outline:none;border-color:rgba(255,255,255,0.75);width:210px;background:rgba(255,255,255,0.22) !important}

/* Auth buttons */
.btn-nav{padding:7px 15px;border-radius:50px;text-decoration:none;font-weight:700;font-size:12px;font-family:'UniSIRWAN',sans-serif;transition:all .2s;display:flex;align-items:center;gap:5px;white-space:nowrap;border:1.5px solid rgba(255,255,255,0.45);background:rgba(0,0,0,0.2) !important;color:#fff !important}
.btn-nav:hover{background:rgba(0,0,0,0.38) !important}
.btn-nav-light{background:rgba(255,255,255,0.16) !important;border-color:rgba(255,255,255,0.3) !important}
.btn-nav-light:hover{background:rgba(255,255,255,0.28) !important}
.btn-nav i{width:13px !important;height:13px !important}

/* Cart badge */
.nav-badge{background:#fff;color:#00A885;border-radius:50%;padding:1px 6px;font-size:10px;font-weight:800;margin-right:2px;vertical-align:top}

/* Hamburger */
.hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;padding:8px;background:rgba(255,255,255,0.15);border-radius:8px;border:1px solid rgba(255,255,255,0.25);transition:all .2s}
.hamburger span{width:20px;height:2px;background:#fff;border-radius:2px;transition:all .3s}
.hamburger.open span:nth-child(1){transform:rotate(45deg) translate(5px,5px)}
.hamburger.open span:nth-child(2){opacity:0}
.hamburger.open span:nth-child(3){transform:rotate(-45deg) translate(5px,-5px)}

/* Mobile drawer */
.mobile-menu{display:none;flex-direction:column;gap:6px;padding:16px 20px;background:linear-gradient(180deg,#00C49E,#009A7A);width:100%;position:fixed;top:64px;right:0;left:0;z-index:8888;box-shadow:0 8px 24px rgba(0,0,0,0.2);max-height:calc(100vh - 64px);overflow-y:auto}
.mobile-menu.open{display:flex}
.mobile-menu a{display:flex;align-items:center;gap:8px;padding:12px 16px;color:#fff;text-decoration:none;font-family:'UniSIRWAN',sans-serif;font-weight:700;font-size:14px;border-radius:12px;background:rgba(255,255,255,0.1);transition:all .2s}
.mobile-menu a:hover{background:rgba(255,255,255,0.22)}
.mobile-menu a i{width:16px !important;height:16px !important}
.mobile-search{display:flex;gap:8px;align-items:center;margin-bottom:4px}
.mobile-search input{flex:1;padding:10px 14px;border:1.5px solid rgba(255,255,255,0.35);border-radius:12px;background:rgba(255,255,255,0.15) !important;color:#fff !important;font-family:'UniSIRWAN',sans-serif;font-size:14px;direction:rtl}
.mobile-search input::placeholder{color:rgba(255,255,255,0.6) !important}
.mobile-search input:focus{outline:none;border-color:#fff;background:rgba(255,255,255,0.25) !important}
.mobile-search button{padding:10px 18px;background:rgba(0,0,0,0.25) !important;border:1px solid rgba(255,255,255,0.3);color:#fff;border-radius:12px;cursor:pointer;font-family:'UniSIRWAN',sans-serif;font-weight:700;font-size:13px;display:flex;align-items:center;gap:6px}
.mobile-divider{height:1px;background:rgba(255,255,255,0.18);margin:4px 0}

/* ===== Responsive breakpoints ===== */
@media(max-width:768px){
    .nav-links{display:none}
    .search-form{display:none}
    .nav-right .btn-nav{display:none}
    .hamburger{display:flex}
    .navbar{padding:0 16px}
}
@media(min-width:769px){
    .mobile-menu{display:none !important}
    .hamburger{display:none !important}
}
@media(max-width:400px){
    .logo-text{display:none}
    .logo-ring{width:32px;height:32px}
}
</style>
</head>
<body>
<nav class="navbar">
    <!-- Logo -->
    <a href="../pages/home.php" target="_parent" class="nav-logo">
        <img src="../logobrand/logo.jpg" alt="لۆگۆ" class="logo-ring">
        <span class="logo-text"> سپۆرت زۆن</span>
    </a>

    <!-- Desktop Center Links -->
    <div class="nav-links">
        <a href="../pages/home.php" target="_parent"><i data-lucide="home"></i> پەرەی سەرەکی</a>
        <a href="../pages/cart.php" target="_parent"><i data-lucide="shopping-cart"></i> سەبەتە<?php if ($cart_count > 0): ?><span class="nav-badge"><?php echo $cart_count; ?></span><?php endif; ?></a>
        <a href="../pages/contact.php" target="_parent"><i data-lucide="phone"></i> پەیوەندی</a>
        <a href="../admin/admin.php" target="_parent" class="nav-admin-pill"><i data-lucide="layout-dashboard"></i> داشبۆرد</a>
        <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="../pages/register.php" target="_parent"><i data-lucide="user-plus"></i> تۆمارکردن</a>
        <?php endif; ?>
    </div>

    <!-- Desktop Right: Search + Auth -->
    <div class="nav-right">
        <form class="search-form" method="GET" action="../pages/home.php" target="_parent">
            <i data-lucide="search" class="search-icon-abs"></i>
            <input type="text" name="search" class="search-input" placeholder="گەڕان بکە..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </form>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="../pages/manage_account.php" target="_parent" class="btn-nav btn-nav-light"><i data-lucide="user"></i> ئەکاونت</a>
            <a href="../pages/logout.php" target="_parent" class="btn-nav"><i data-lucide="log-out"></i> دەرچون</a>
        <?php else: ?>
            <a href="../pages/login.php" target="_parent" class="btn-nav"><i data-lucide="log-in"></i> چوونەژوورەوە</a>
        <?php endif; ?>
        <!-- Hamburger -->
        <div class="hamburger" id="hamburger" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
    </div>
</nav>

<!-- Mobile Drawer -->
<div class="mobile-menu" id="mobileMenu">
    <!-- Mobile Search -->
    <form class="mobile-search" method="GET" action="../pages/home.php" target="_parent">
        <input type="text" name="search" placeholder="گەڕان بکە..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit"><i data-lucide="search" style="width:15px;height:15px"></i> گەڕان</button>
    </form>
    <div class="mobile-divider"></div>
    <a href="../pages/home.php" target="_parent"><i data-lucide="home"></i> پەرەی سەرەکی</a>
    <a href="../pages/cart.php" target="_parent"><i data-lucide="shopping-cart"></i> سەبەتە<?php if ($cart_count > 0): ?> <span style="background:#fff;color:#00A885;border-radius:50px;padding:1px 8px;font-size:11px;font-weight:800"><?php echo $cart_count; ?></span><?php endif; ?></a>
    <a href="../pages/contact.php" target="_parent"><i data-lucide="phone"></i> پەیوەندی</a>
    <a href="../admin/admin.php" target="_parent"><i data-lucide="layout-dashboard"></i> داشبۆردی سیستەم</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="mobile-divider"></div>
        <a href="../pages/manage_account.php" target="_parent"><i data-lucide="user"></i> بەڕێوەبردنی ئەکاونت</a>
        <a href="../pages/logout.php" target="_parent"><i data-lucide="log-out"></i> دەرچون</a>
    <?php else: ?>
        <div class="mobile-divider"></div>
        <a href="../pages/login.php" target="_parent"><i data-lucide="log-in"></i> چوونەژوورەوە</a>
        <a href="../pages/register.php" target="_parent"><i data-lucide="user-plus"></i> تۆمارکردن</a>
    <?php endif; ?>
</div>

<script>
lucide.createIcons();
function toggleMenu(){
    const h=document.getElementById('hamburger');
    const m=document.getElementById('mobileMenu');
    h.classList.toggle('open');
    m.classList.toggle('open');
}
// Close drawer if clicking outside
document.addEventListener('click',function(e){
    const h=document.getElementById('hamburger');
    const m=document.getElementById('mobileMenu');
    if(!h.contains(e.target)&&!m.contains(e.target)){
        h.classList.remove('open');
        m.classList.remove('open');
    }
});
// Cart count update from parent
window.addEventListener('message',function(e){
    if(e.data&&e.data.cartCount!==undefined){
        document.querySelectorAll('.nav-badge').forEach(b=>{
            b.textContent=e.data.cartCount;
            b.style.display=e.data.cartCount>0?'inline':'none';
        });
    }
});
</script>
</body>
</html>
