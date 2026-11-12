<?php
require_once __DIR__.'/functions.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $action=$_POST['action']??'';
 if($action==='add'){
  $pid=(int)($_POST['product_id']??0);$qty=max(1,min(20,(int)($_POST['qty']??1)));
  $s=mysqli_prepare($conn,"SELECT id,name FROM products WHERE id=? AND is_active=1");mysqli_stmt_bind_param($s,'i',$pid);mysqli_stmt_execute($s);$p=mysqli_fetch_assoc(mysqli_stmt_get_result($s));
  if($p){$_SESSION['cart'][$pid]=min(20,($_SESSION['cart'][$pid]??0)+$qty);flash($p['name'].' added to your cart.');}else flash('Product unavailable.','danger');
  header('Location: index.php');exit;
 }
 if($action==='update'){
  if(isset($_POST['remove_id']))unset($_SESSION['cart'][(int)$_POST['remove_id']]);
  else foreach(($_POST['qty']??[]) as $id=>$q){$id=(int)$id;$q=(int)$q;if($q<=0)unset($_SESSION['cart'][$id]);elseif(isset($_SESSION['cart'][$id]))$_SESSION['cart'][$id]=min(20,$q);}
  flash('Cart updated.');header('Location: cart.php');exit;
 }
 if($action==='checkout'){
  $cart=get_cart($conn);
  if(!$cart['items']){flash('Your cart is empty.','danger');header('Location: cart.php');exit;}
  $customerName=is_logged_in()?$_SESSION['name']:trim($_POST['customer_name']??'');
  if($customerName===''){flash('Please enter your name.','danger');header('Location: cart.php');exit;}
  [$percent,$discount,$total]=calc_totals($cart['subtotal']);
  mysqli_begin_transaction($conn);
  try{
   $uid=is_logged_in()?(int)$_SESSION['user_id']:null;
   $s=mysqli_prepare($conn,"INSERT INTO orders(user_id,customer_name,subtotal,discount,total,status) VALUES(?,?,?,?,?,'pending')");
   mysqli_stmt_bind_param($s,'isiii',$uid,$customerName,$cart['subtotal'],$discount,$total);mysqli_stmt_execute($s);$oid=mysqli_insert_id($conn);
   $ins=mysqli_prepare($conn,"INSERT INTO order_items(order_id,product_id,product_name,unit_price,qty,discount_amount) VALUES(?,?,?,?,?,?)");
   foreach($cart['items'] as $it){$lineDiscount=(int)round($it['line_total']*$percent/100);$pid=(int)$it['id'];$name=$it['name'];$price=(int)$it['price'];$qty=(int)$it['qty'];mysqli_stmt_bind_param($ins,'iisiii',$oid,$pid,$name,$price,$qty,$lineDiscount);mysqli_stmt_execute($ins);}
   mysqli_commit($conn);unset($_SESSION['cart']);$_SESSION['last_order_id']=$oid;header('Location: order_success.php');exit;
  }catch(Throwable $e){mysqli_rollback($conn);flash('Could not place the order. Please try again.','danger');header('Location: cart.php');exit;}
 }
}
$cart=get_cart($conn);[$percent,$discount,$total]=calc_totals($cart['subtotal']);page_header('Your Cart');
?>
<h2 class="section-title mb-3">Your Cart</h2>
<?php if(!$cart['items']): ?><div class="alert alert-light border">Your cart is empty. <a href="index.php">Browse the menu</a>.</div>
<?php else: ?><div class="row g-4"><div class="col-lg-8"><div class="card shadow-sm"><div class="card-body"><form method="post"><input type="hidden" name="action" value="update"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead><tbody>
<?php foreach($cart['items'] as $it): ?><tr><td><?= e($it['name']) ?></td><td><?= rupiah($it['price']) ?></td><td><input class="form-control form-control-sm" style="width:75px" type="number" min="0" max="20" name="qty[<?= (int)$it['id'] ?>]" value="<?= (int)$it['qty'] ?>"></td><td><?= rupiah($it['line_total']) ?></td><td><button class="btn btn-sm btn-outline-danger" name="remove_id" value="<?= (int)$it['id'] ?>">Remove</button></td></tr><?php endforeach; ?>
</tbody></table></div><button class="btn btn-outline-secondary btn-sm">Update cart</button></form></div></div></div>
<div class="col-lg-4"><div class="card shadow-sm"><div class="card-header text-bg-dark">Order Summary</div><div class="card-body"><div class="d-flex justify-content-between"><span>Subtotal</span><span><?= rupiah($cart['subtotal']) ?></span></div><?php if($percent): ?><div class="d-flex justify-content-between text-success"><span>Member discount (<?= $percent ?>%)</span><span>-<?= rupiah($discount) ?></span></div><?php endif; ?><hr><div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span><?= rupiah($total) ?></span></div>
<form method="post" class="mt-3"><input type="hidden" name="action" value="checkout"><?php if(!is_logged_in()): ?><label class="form-label">Your name</label><input class="form-control mb-3" name="customer_name" maxlength="100" required><p class="small text-muted">Guest checkout uses regular prices. <a href="login.php">Log in</a> to receive the member discount.</p><?php else: ?><p class="small text-muted">Ordering as <?= e($_SESSION['name']) ?></p><?php endif; ?><button class="btn btn-success w-100">Place Order</button></form></div></div></div></div><?php endif; ?>
<?php include __DIR__.'/footer.php'; ?>
