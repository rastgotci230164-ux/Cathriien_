<?php
session_start();
include '../db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user_id = $_SESSION['user_id'];
$sql = "SELECT cart.id as cart_id, cart.quantity, products.id, products.name, products.price, products.image 
        FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = $user_id";
$result = $conn->query($sql);
$total_price = 0;
?>
<!DOCTYPE html><html lang="ckb" dir="rtl"><head>
<meta charset="UTF-8"><title>سەبەتەی کڕین</title>
<link rel="stylesheet" href="../styles.css">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<style>
html,body{background:#F5F6FF !important;color:#1A1A2E !important}
.page-header{background:linear-gradient(135deg,#00D4AA,#00A885) !important;padding:28px 30px;border-radius:20px;margin-bottom:28px;box-shadow:0 8px 30px rgba(0,212,170,0.3);position:relative;overflow:hidden;direction:rtl}
.page-header::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 20% 50%,rgba(255,255,255,0.12),transparent 60%);pointer-events:none}
.page-header h1{margin:0;color:#fff !important;font-family:'UniSIRWAN',sans-serif;font-size:1.7rem;display:flex;align-items:center;gap:12px;position:relative;z-index:1}
.cart-page{max-width:900px;margin:30px auto;padding:0 20px;direction:rtl}
.cart-item{display:flex;align-items:center;padding:20px;border-radius:16px;margin-bottom:14px;background:#fff !important;border:1px solid rgba(0,0,0,0.07);gap:20px;box-shadow:0 2px 12px rgba(0,0,0,0.07);direction:rtl;transition:all .3s}
.cart-item:hover{transform:translateY(-3px);box-shadow:0 8px 28px rgba(0,212,170,0.12);border-color:rgba(0,212,170,0.22)}
.cart-item img{border-radius:12px;border:1px solid rgba(0,0,0,0.07);width:100px;height:100px;object-fit:cover;flex-shrink:0}
.cart-item-details{flex:1;text-align:right}
.cart-item-details h3{margin:0 0 6px;color:#1A1A2E !important;font-family:'UniSIRWAN',sans-serif;font-size:16px;font-weight:800}
.cart-item-details p{margin:4px 0;color:#4A4A6A;text-align:right;font-family:'UniSIRWAN',sans-serif;font-size:14px}
.price-highlight{color:#00A885 !important;font-weight:800}
.quantity-controls{display:flex;align-items:center;gap:8px;margin:8px 0}
.quantity-btn{width:32px;height:32px;border:1.5px solid rgba(0,212,170,0.3);background:#F0FDFB !important;color:#00A885;border-radius:8px;cursor:pointer;font-size:16px;font-weight:800;transition:all .2s;display:flex;align-items:center;justify-content:center}
.quantity-btn:hover{background:#00D4AA !important;color:#fff;border-color:#00D4AA}
.quantity-display{background:#F5F6FF;border:1px solid rgba(0,0,0,0.08);padding:4px 16px;border-radius:8px;font-weight:800;color:#1A1A2E;font-family:'UniSIRWAN',sans-serif}
.btn-remove{background:#FFF0F0 !important;color:#FF3B6F !important;border:1px solid rgba(255,59,111,0.2);padding:9px 18px;border-radius:10px;cursor:pointer;font-family:'UniSIRWAN',sans-serif;font-weight:700;font-size:13px;transition:all .3s;display:flex;align-items:center;gap:6px}
.btn-remove:hover{background:#FF3B6F !important;color:#fff !important;transform:translateY(-2px);box-shadow:0 4px 14px rgba(255,59,111,0.3)}
.cart-total{margin-top:24px;padding:24px 28px;background:#fff !important;border:1px solid rgba(0,0,0,0.07);border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,0.08);direction:rtl;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:15px}
.cart-total h2{color:#1A1A2E !important;font-family:'UniSIRWAN',sans-serif;margin:0;display:flex;align-items:center;gap:10px;font-size:1.3rem}
.total-amount{color:#00A885 !important;font-size:1.6rem;font-weight:800}
.btn-continue{background:rgba(0,212,170,0.08) !important;border:1px solid rgba(0,212,170,0.25);color:#00A885 !important;text-decoration:none;padding:11px 22px;border-radius:10px;font-family:'UniSIRWAN',sans-serif;font-weight:700;transition:all .3s;display:inline-flex;align-items:center;gap:7px}
.btn-continue:hover{background:#00D4AA !important;color:#fff !important;transform:translateY(-2px)}
.btn-checkout{background:linear-gradient(135deg,#00D4AA,#00A885) !important;color:#fff !important;text-decoration:none;padding:11px 24px;border-radius:10px;font-family:'UniSIRWAN',sans-serif;font-weight:800;transition:all .3s;display:inline-flex;align-items:center;gap:7px;box-shadow:0 4px 16px rgba(0,212,170,0.35)}
.btn-checkout:hover{transform:translateY(-2px);box-shadow:0 6px 24px rgba(0,212,170,0.5);filter:brightness(1.08)}
.empty-cart{text-align:center;padding:80px 20px;background:#fff;border-radius:20px;box-shadow:0 2px 16px rgba(0,0,0,0.07)}
.empty-cart-icon{width:90px;height:90px;background:rgba(0,212,170,0.08);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 22px;color:#00D4AA}
.empty-cart h2{color:#1A1A2E !important;font-family:'UniSIRWAN',sans-serif;margin-bottom:10px}
.empty-cart p{color:#7A7A9A;font-family:'UniSIRWAN',sans-serif;margin-bottom:22px}
.btn-print{background:#F0F8FF !important;border:1.5px solid rgba(0,120,255,0.2);color:#1A6FD4 !important;text-decoration:none;padding:11px 22px;border-radius:10px;font-family:'UniSIRWAN',sans-serif;font-weight:700;transition:all .3s;display:inline-flex;align-items:center;gap:7px;cursor:pointer;font-size:14px}
.btn-print:hover{background:#1A6FD4 !important;color:#fff !important;transform:translateY(-2px);box-shadow:0 4px 14px rgba(26,111,212,0.3)}

/* ===== Print / PDF styles ===== */
@media print{
  body > *:not(#printReceipt){display:none !important}
  #printReceipt{display:block !important}
  @page{margin:15mm;size:A4}
}
#printReceipt{display:none}
</style></head>
<body style="background:#F5F6FF !important;">
<iframe src="../includes/navbar.php" style="border:none;width:100%;height:64px;margin:0;padding:0"></iframe>
<div class="cart-page">
    <div class="page-header"><h1><i data-lucide="shopping-cart" style="width:26px;height:26px"></i> سەبەتەی کڕین</h1></div>
    <?php if ($result && $result->num_rows > 0): ?>
        <div class="cart-items">
            <?php while ($row = $result->fetch_assoc()):
                $subtotal = $row['price'] * $row['quantity']; $total_price += $subtotal;
                $image = !empty($row['image']) ? htmlspecialchars($row['image']) : 'placeholder.jpg'; ?>
                <div class="cart-item">
                    <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="cart-item-details">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p>نرخ: <span class="price-highlight">$<?php echo number_format($row['price'],2); ?></span></p>
                        <div class="quantity-controls">
                            <form method="POST" action="../api/update_quantity.php" style="display:inline"><input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>"><input type="hidden" name="action" value="decrease"><button type="submit" class="quantity-btn">-</button></form>
                            <span class="quantity-display"><?php echo $row['quantity']; ?></span>
                            <form method="POST" action="../api/update_quantity.php" style="display:inline"><input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>"><input type="hidden" name="action" value="increase"><button type="submit" class="quantity-btn">+</button></form>
                        </div>
                        <p><strong>کۆ: <span class="price-highlight">$<?php echo number_format($subtotal,2); ?></span></strong></p>
                    </div>
                    <div class="cart-item-actions">
                        <form method="POST" action="../api/remove_from_cart.php"><input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>"><button type="submit" class="btn-remove"><i data-lucide="trash-2" style="width:15px;height:15px"></i> سڕینەوە</button></form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="cart-total">
            <h2><i data-lucide="wallet" style="width:24px;height:24px;color:#00D4AA"></i> کۆی گشتی: <span class="total-amount">$<?php echo number_format($total_price,2); ?></span></h2>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <a href="home.php" class="btn-continue"><i data-lucide="arrow-right" style="width:15px;height:15px"></i> بەردەوامبوون</a>
                <button onclick="printReceipt()" class="btn-print"><i data-lucide="file-text" style="width:15px;height:15px"></i> پرینت / PDF</button>
                <a href="checkout.php" class="btn-checkout"><i data-lucide="credit-card" style="width:15px;height:15px"></i> پارەدان</a>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <div class="empty-cart-icon"><i data-lucide="shopping-cart" style="width:50px;height:50px"></i></div>
            <h2>سەبەتەکەت بەتاڵە</h2>
            <p>دەست بکە بە کڕین بۆ زیادکردنی کاڵا!</p>
            <a href="home.php" class="btn-continue"><i data-lucide="shopping-bag" style="width:15px;height:15px"></i> دەست بکە بە کڕین</a>
        </div>
    <?php endif; ?>
</div>

<!-- ===== پرینتی راپۆرت ===== -->
<div id="printReceipt" dir="rtl" style="font-family:'UniSIRWAN',Arial,sans-serif;max-width:750px;margin:0 auto;padding:30px;color:#1A1A2E">

  <!-- سەرپەڕە -->
  <div style="text-align:center;margin-bottom:30px;border-bottom:3px solid #00D4AA;padding-bottom:22px">
    <div style="width:64px;height:64px;background:linear-gradient(135deg,#00D4AA,#00A885);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
      <svg xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='none' stroke='#fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' viewBox='0 0 24 24'><path d='M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z'/><line x1='3' y1='6' x2='21' y2='6'/><path d='M16 10a4 4 0 0 1-8 0'/></svg>
    </div>
    <h1 style="margin:0 0 6px;font-size:22px;font-weight:800;color:#1A1A2E">کۆگای ئەلیکترۆنی</h1>
    <p style="margin:0;color:#6B6B90;font-size:13px">پسووڵەی کڕین</p>
    <p style="margin:6px 0 0;color:#9A9ABB;font-size:12px">
      <?php echo date('Y/m/d — H:i'); ?> | بەکارهێنەر: <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
    </p>
  </div>

  <!-- خشتەی کاڵاکان -->
  <table style="width:100%;border-collapse:collapse;margin-bottom:24px">
    <thead>
      <tr style="background:linear-gradient(135deg,#00D4AA,#00A885);color:#fff">
        <th style="padding:12px 14px;text-align:right;font-size:13px;border-radius:8px 0 0 0">#</th>
        <th style="padding:12px 14px;text-align:right;font-size:13px">ناوی کاڵا</th>
        <th style="padding:12px 14px;text-align:center;font-size:13px">ژمارە</th>
        <th style="padding:12px 14px;text-align:center;font-size:13px">نرخ / دانە</th>
        <th style="padding:12px 14px;text-align:center;font-size:13px;border-radius:0 8px 0 0">کۆ</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // دووبارە query دەکەین چونکە resultی سەرەوە خواردراوە
        $print_sql = "SELECT cart.quantity, products.name, products.price
                      FROM cart JOIN products ON cart.product_id = products.id
                      WHERE cart.user_id = $user_id ORDER BY products.name";
        $pr = $conn->query($print_sql);
        $row_num = 1; $grand = 0;
        if ($pr) while ($r = $pr->fetch_assoc()):
          $sub = $r['price'] * $r['quantity']; $grand += $sub;
          $bg = $row_num % 2 === 0 ? '#F8FFFE' : '#FFFFFF';
      ?>
      <tr style="background:<?php echo $bg; ?>;border-bottom:1px solid rgba(0,212,170,0.1)">
        <td style="padding:11px 14px;font-size:13px;color:#8A8AAA"><?php echo $row_num++; ?></td>
        <td style="padding:11px 14px;font-size:13px;font-weight:700;color:#1A1A2E"><?php echo htmlspecialchars($r['name']); ?></td>
        <td style="padding:11px 14px;font-size:13px;text-align:center"><?php echo $r['quantity']; ?></td>
        <td style="padding:11px 14px;font-size:13px;text-align:center;color:#00A885;font-weight:700">$<?php echo number_format($r['price'],2); ?></td>
        <td style="padding:11px 14px;font-size:13px;text-align:center;font-weight:800;color:#1A1A2E">$<?php echo number_format($sub,2); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- کۆی گشتی -->
  <div style="background:linear-gradient(135deg,#00D4AA,#00A885);border-radius:14px;padding:20px 24px;display:flex;justify-content:space-between;align-items:center;color:#fff;margin-bottom:28px">
    <span style="font-size:16px;font-weight:800">کۆی گشتی پارەدان</span>
    <span style="font-size:22px;font-weight:900">$<?php echo number_format($grand,2); ?></span>
  </div>

  <!-- خەتی خوارەوە -->
  <div style="text-align:center;border-top:2px dashed rgba(0,212,170,0.3);padding-top:18px">
    <p style="margin:0;color:#9A9ABB;font-size:12px">سوپاس بۆ کڕینت — کۆگای ئەلیکترۆنی</p>
    <p style="margin:4px 0 0;color:#B0B0CC;font-size:11px"><?php echo date('Y'); ?> &copy; هەموو مافەکان پارێزراون</p>
  </div>
</div>

<script>
lucide.createIcons();
function printReceipt() {
    window.print();
}
</script>
</body></html>
