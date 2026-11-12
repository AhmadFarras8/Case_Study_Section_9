<?php
require_once __DIR__.'/functions.php';
$oid=(int)($_SESSION['last_order_id']??0);
if(!$oid){header('Location: index.php');exit;}
$s=mysqli_prepare($conn,"SELECT * FROM orders WHERE id=?");mysqli_stmt_bind_param($s,'i',$oid);mysqli_stmt_execute($s);$order=mysqli_fetch_assoc(mysqli_stmt_get_result($s));
if(!$order){header('Location: index.php');exit;}
page_header('Order Confirmation');
?>
<div class="card shadow-sm mx-auto" style="max-width:700px"><div class="card-body p-4"><div class="text-center"><div class="display-4">✓</div><h2>Thank you for ordering!</h2><p class="text-muted">Your Kopi Piro order has been received.</p><span class="badge text-bg-warning">Order #<?= $oid ?></span></div><hr>
<?php foreach(get_order_items($conn,$oid) as $it): ?><div class="d-flex justify-content-between"><span><?= e($it['product_name']) ?> × <?= (int)$it['qty'] ?></span><span><?= rupiah($it['unit_price']*$it['qty']-$it['discount_amount']) ?></span></div><?php endforeach; ?>
<hr><div class="d-flex justify-content-between"><span>Subtotal</span><span><?= rupiah($order['subtotal']) ?></span></div><?php if($order['discount']): ?><div class="d-flex justify-content-between text-success"><span>Discount</span><span>-<?= rupiah($order['discount']) ?></span></div><?php endif; ?><div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span><?= rupiah($order['total']) ?></span></div><div class="text-center mt-4"><a class="btn btn-dark" href="index.php">Back to menu</a><?php if(is_customer()): ?> <a class="btn btn-outline-dark" href="my_orders.php">My orders</a><?php endif; ?></div></div></div>
<?php include __DIR__.'/footer.php'; ?>
