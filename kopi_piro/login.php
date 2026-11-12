<?php
require_once __DIR__.'/functions.php';
if(is_logged_in()){redirect_role();}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $email=trim($_POST['email']??'');$pw=$_POST['password']??'';
 $s=mysqli_prepare($conn,"SELECT id,name,password,role FROM users WHERE email=?");mysqli_stmt_bind_param($s,'s',$email);mysqli_stmt_execute($s);$u=mysqli_fetch_assoc(mysqli_stmt_get_result($s));
 if($u&&password_verify($pw,$u['password'])){session_regenerate_id(true);$_SESSION['user_id']=$u['id'];$_SESSION['name']=$u['name'];$_SESSION['role']=$u['role'];redirect_role();}
 $error='Incorrect email or password.';
}
page_header('Login');
?>
<div class="row justify-content-center"><div class="col-md-4"><div class="card shadow-sm"><div class="card-body p-4"><h2 class="text-center">Welcome back</h2><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post"><label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" required autofocus><label class="form-label">Password</label><input class="form-control mb-4" type="password" name="password" required><button class="btn btn-dark w-100">Login</button></form><p class="small text-center mt-3 mb-0">New to Kopi Piro? <a href="register.php">Register</a></p></div></div></div></div>
<?php include __DIR__.'/footer.php'; ?>
