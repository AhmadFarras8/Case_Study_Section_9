<?php
require_once __DIR__.'/functions.php';
if(!is_customer()){flash('Please log in as a customer to rate products.','warning');header('Location: login.php');exit;}
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: index.php');exit;}
$productId=(int)($_POST['product_id']??0);$rating=(int)($_POST['rating']??0);
if($rating<1||$rating>5){flash('Please choose a rating from 1 to 5.','danger');header('Location: index.php');exit;}
$check=mysqli_prepare($conn,"SELECT id FROM products WHERE id=? AND is_active=1");mysqli_stmt_bind_param($check,'i',$productId);mysqli_stmt_execute($check);
if(!mysqli_fetch_assoc(mysqli_stmt_get_result($check))){flash('Product not found.','danger');header('Location: index.php');exit;}
$userId=(int)$_SESSION['user_id'];
$stmt=mysqli_prepare($conn,"INSERT INTO product_reviews(product_id,user_id,rating) VALUES(?,?,?) ON DUPLICATE KEY UPDATE rating=VALUES(rating)");
mysqli_stmt_bind_param($stmt,'iii',$productId,$userId,$rating);mysqli_stmt_execute($stmt);
flash('Thank you for rating this product.');header('Location: index.php');exit;
