<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';

const MEMBER_DISCOUNT_PERCENT = 10;

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function rupiah($amount) { return 'Rp ' . number_format((int)$amount, 0, ',', '.'); }
function is_logged_in() { return isset($_SESSION['user_id']); }
function current_role() { return $_SESSION['role'] ?? 'guest'; }
function is_owner() { return is_logged_in() && current_role() === 'owner'; }
function is_cashier() { return is_logged_in() && current_role() === 'cashier'; }
function is_customer() { return is_logged_in() && current_role() === 'customer'; }
function is_staff() { return is_owner() || is_cashier(); }

function require_login() {
    if (!is_logged_in()) { header('Location: ' . (str_contains($_SERVER['PHP_SELF'], '/owner/') || str_contains($_SERVER['PHP_SELF'], '/cashier/') ? '../login.php' : 'login.php')); exit; }
}
function require_owner() {
    require_login();
    if (!is_owner()) { http_response_code(403); exit('403 - Owner access only.'); }
}
function require_cashier() {
    require_login();
    if (!is_cashier() && !is_owner()) { http_response_code(403); exit('403 - Cashier access only.'); }
}
function discount_percent() { return is_customer() ? MEMBER_DISCOUNT_PERCENT : 0; }
function calc_totals($subtotal) {
    $percent = discount_percent();
    $discount = (int)round($subtotal * $percent / 100);
    return [$percent, $discount, $subtotal - $discount];
}
function flash($message, $type='success') { $_SESSION['flash'] = ['message'=>$message, 'type'=>$type]; }
function redirect_role() {
    if (is_owner()) { header('Location: owner/index.php'); }
    elseif (is_cashier()) { header('Location: cashier/index.php'); }
    else { header('Location: index.php'); }
    exit;
}
function get_cart($conn) {
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return ['items'=>[], 'subtotal'=>0];
    $ids = array_map('intval', array_keys($cart));
    $marks = implode(',', array_fill(0, count($ids), '?'));
    $stmt = mysqli_prepare($conn, "SELECT id,name,description,category,price,image FROM products WHERE is_active=1 AND id IN ($marks)");
    mysqli_stmt_bind_param($stmt, str_repeat('i', count($ids)), ...$ids);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items=[]; $subtotal=0;
    while ($row=mysqli_fetch_assoc($result)) {
        $row['qty'] = max(1, min(20, (int)($cart[$row['id']] ?? 1)));
        $row['line_total'] = $row['price'] * $row['qty'];
        $subtotal += $row['line_total'];
        $items[]=$row;
    }
    $found=array_map('intval', array_column($items,'id'));
    foreach ($ids as $id) if (!in_array($id,$found,true)) unset($_SESSION['cart'][$id]);
    return ['items'=>$items,'subtotal'=>$subtotal];
}
function get_order_items($conn, $order_id) {
    $stmt=mysqli_prepare($conn,"SELECT product_name,unit_price,qty,discount_amount FROM order_items WHERE order_id=?");
    mysqli_stmt_bind_param($stmt,"i",$order_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt),MYSQLI_ASSOC);
}
function average_rating($conn, $product_id) {
    $stmt=mysqli_prepare($conn,"SELECT AVG(rating) avg_rating, COUNT(*) total FROM product_reviews WHERE product_id=?");
    mysqli_stmt_bind_param($stmt,"i",$product_id); mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
function page_header($title, $base='') { $GLOBALS['page_title']=$title; $GLOBALS['base_path']=$base; include __DIR__.'/header.php'; }
