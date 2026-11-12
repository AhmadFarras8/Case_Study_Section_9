<?php
require_once __DIR__.'/functions.php';
$products=mysqli_query($conn,"SELECT p.*, (SELECT AVG(rating) FROM product_reviews r WHERE r.product_id=p.id) avg_rating, (SELECT COUNT(*) FROM product_reviews r WHERE r.product_id=p.id) review_count FROM products p WHERE p.is_active=1 ORDER BY p.category,p.name");
$percent=discount_percent();
page_header('Menu');
?>
<section class="hero mb-4"><div class="row align-items-center"><div class="col-lg-8"><p class="text-uppercase small mb-2">Coffee · comfort · community</p><h1 class="display-5 fw-bold">A little cup of happiness.</h1><p class="lead mb-0">Find your daily favourite at Kopi Piro.</p></div><div class="col-lg-4 text-lg-end mt-3 mt-lg-0"><span class="badge bg-warning text-dark fs-6">Freshly made for you</span></div></div></section>
<?php if($percent): ?><div class="alert alert-success">Member benefit active: <strong><?= $percent ?>% discount</strong> at checkout.</div>
<?php elseif(!is_logged_in()): ?><div class="alert alert-light border">Register or log in as a customer to receive <strong><?= MEMBER_DISCOUNT_PERCENT ?>% off</strong>. Guests pay regular prices.</div><?php endif; ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h2 class="section-title">Our Menu</h2><span class="text-muted small">Coffee, food & non-caffeine</span></div>
<div class="row g-4">
<?php while($p=mysqli_fetch_assoc($products)): ?>
<div class="col-6 col-md-4 col-lg-3"><div class="card h-100 shadow-sm">
<?php if($p['image'] && file_exists(__DIR__.'/uploads/product_images/'.$p['image'])): ?><img class="card-img-top" src="uploads/product_images/<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>"><?php else: ?><div class="card-img-top bg-body-secondary d-flex align-items-center justify-content-center fs-1">☕</div><?php endif; ?>
<div class="card-body d-flex flex-column"><span class="badge text-bg-light align-self-start mb-2"><?= e(str_replace('_',' ',$p['category'])) ?></span><h5><?= e($p['name']) ?></h5><p class="small text-muted flex-grow-1"><?= e($p['description']) ?></p>
<div class="small text-warning mb-2">★ <?= $p['avg_rating'] ? number_format($p['avg_rating'],1) : 'No rating' ?> <span class="text-muted">(<?= (int)$p['review_count'] ?>)</span></div>
<?php if($percent): ?><div><small class="text-muted text-decoration-line-through"><?= rupiah($p['price']) ?></small><div class="price"><?= rupiah($p['price']-round($p['price']*$percent/100)) ?></div></div><?php else: ?><div class="price"><?= rupiah($p['price']) ?></div><?php endif; ?>
<form method="post" action="cart.php" class="d-flex gap-2 mt-3"><input type="hidden" name="action" value="add"><input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>"><input class="form-control form-control-sm" style="width:68px" type="number" name="qty" min="1" max="20" value="1"><button class="btn btn-dark btn-sm flex-grow-1">Add</button></form>
<?php if(is_customer()): ?><form method="post" action="review.php" class="mt-2 border-top pt-2"><input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>"><div class="d-flex gap-2"><select class="form-select form-select-sm" name="rating" aria-label="Rating" style="max-width:95px"><option value="5">5 ★</option><option value="4">4 ★</option><option value="3">3 ★</option><option value="2">2 ★</option><option value="1">1 ★</option></select><button class="btn btn-outline-secondary btn-sm flex-grow-1">Rate product</button></div></form><?php endif; ?>
</div></div></div>
<?php endwhile; ?>
</div>
<?php include __DIR__.'/footer.php'; ?>
