<?php
$page_title = $page_title ?? 'Menu';
$base_path = $base_path ?? '';
$cartCount = array_sum($_SESSION['cart'] ?? []);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?> | Kopi Piro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= $base_path ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark site-nav">
<div class="container">
<a class="navbar-brand fw-bold" href="<?= $base_path ?>index.php">☕ Kopi Piro</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="mainNav">
<ul class="navbar-nav me-auto">
<li class="nav-item"><a class="nav-link" href="<?= $base_path ?>index.php">Menu</a></li>
<?php if (is_customer()): ?><li class="nav-item"><a class="nav-link" href="<?= $base_path ?>my_orders.php">My Orders</a></li><?php endif; ?>
<?php if (is_owner()): ?>
<li class="nav-item"><a class="nav-link" href="<?= $base_path ?>owner/index.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="<?= $base_path ?>owner/products/index.php">Products</a></li>
<li class="nav-item"><a class="nav-link" href="<?= $base_path ?>owner/orders/index.php">All Orders</a></li>
<li class="nav-item"><a class="nav-link" href="<?= $base_path ?>owner/reports/index.php">Reports</a></li>
<?php elseif (is_cashier()): ?><li class="nav-item"><a class="nav-link" href="<?= $base_path ?>cashier/orders.php">Assigned Orders</a></li><?php endif; ?>
</ul>
<div class="d-flex align-items-center gap-2 flex-wrap">
<a class="btn btn-outline-light btn-sm" href="<?= $base_path ?>cart.php">Cart (<?= $cartCount ?>)</a>
<?php if (is_logged_in()): ?>
<span class="text-white small"><?= e($_SESSION['name']) ?> <span class="badge bg-warning text-dark"><?= e(current_role()) ?></span></span>
<a class="btn btn-sm btn-outline-light" href="<?= $base_path ?>logout.php">Logout</a>
<?php else: ?>
<a class="btn btn-sm btn-light" href="<?= $base_path ?>login.php">Login</a><a class="btn btn-sm btn-warning" href="<?= $base_path ?>register.php">Register</a>
<?php endif; ?>
</div></div></div></nav>
<main class="container py-4">
<?php if (!empty($_SESSION['flash'])): $f=$_SESSION['flash']; unset($_SESSION['flash']); ?>
<div class="alert alert-<?= e($f['type']) ?> alert-dismissible fade show"><?= e($f['message']) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
