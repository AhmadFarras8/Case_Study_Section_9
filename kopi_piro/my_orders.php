<?php
require_once __DIR__.'/functions.php';
if(!is_customer()){header('Location: login.php');exit;}
$s=mysqli_prepare($conn,"SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");mysqli_stmt_bind_param($s,'i',$_SESSION['user_id']);mysqli_stmt_execute($s);$orders=mysqli_fetch_all(mysqli_stmt_get_result($s),MYSQLI_ASSOC);
page_header('My Orders');
?>
<h2 class="section-title mb-3">My Orders</h2>
<?php if(!$orders): ?><div class="alert alert-light border">You have not placed any orders yet. <a href="index.php">Browse the menu</a>.</div><?php endif; ?>
<?php foreach($orders as $o): ?><div class="card shadow-sm mb-3"><div class="card-header d-flex justify-content-between"><strong>Order #<?= (int)$o['id'] ?></strong><span><?= e($o['created_at']) ?> · <?= e($o['status']) ?></span></div><div class="card-body"><?php foreach(get_order_items($conn,$o['id']) as $it): ?><div class="d-flex justify-content-between"><span><?= e($it['product_name']) ?> × <?= (int)$it['qty'] ?></span><span><?= rupiah($it['unit_price']*$it['qty']-$it['discount_amount']) ?></span></div><?php endforeach; ?><hr><div class="d-flex justify-content-between"><span>Subtotal</span><span><?= rupiah($o['subtotal']) ?></span></div><?php if($o['discount']): ?><div class="d-flex justify-content-between text-success"><span>Discount</span><span>-<?= rupiah($o['discount']) ?></span></div><?php endif; ?><div class="d-flex justify-content-between fw-bold"><span>Total</span><span><?= rupiah($o['total']) ?></span></div></div></div><?php endforeach; ?>
<?php include __DIR__.'/footer.php'; ?>
