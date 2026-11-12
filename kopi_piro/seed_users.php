<?php
// Run once after importing database.sql, then DELETE this file.
require_once __DIR__.'/functions.php';
$accounts=[
 ['Kopi Piro Owner','owner@kopipiro.test','owner123','owner'],
 ['Kopi Piro Cashier','cashier@kopipiro.test','cashier123','cashier']
];
foreach($accounts as [$name,$email,$password,$role]){
 $hash=password_hash($password,PASSWORD_DEFAULT);
 $s=mysqli_prepare($conn,"INSERT INTO users(name,email,password,role) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE name=VALUES(name),password=VALUES(password),role=VALUES(role)");
 mysqli_stmt_bind_param($s,'ssss',$name,$email,$hash,$role);mysqli_stmt_execute($s);
}
echo 'Seed accounts created. Delete seed_users.php now.';
