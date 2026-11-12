<?php
require_once __DIR__.'/functions.php';
if(is_logged_in()){redirect_role();}
$error='';$name='';$email='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $name=trim($_POST['name']??'');$email=trim($_POST['email']??'');$pw=$_POST['password']??'';$confirm=$_POST['confirm']??'';
 if($name===''||strlen($name)>100)$error='Enter a valid name.';
 elseif(!filter_var($email,FILTER_VALIDATE_EMAIL))$error='Enter a valid email.';
 elseif(strlen($pw)<6)$error='Password must be at least 6 characters.';
 elseif($pw!==$confirm)$error='Passwords do not match.';
 else{
  $hash=password_hash($pw,PASSWORD_DEFAULT);$role='customer';$s=mysqli_prepare($conn,"INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)");mysqli_stmt_bind_param($s,'ssss',$name,$email,$hash,$role);
  try{mysqli_stmt_execute($s);session_regenerate_id(true);$_SESSION['user_id']=mysqli_insert_id($conn);$_SESSION['name']=$name;$_SESSION['role']=$role;flash('Welcome! Your 10% member discount is active.');header('Location: index.php');exit;}catch(mysqli_sql_exception $ex){$error='That email is already registered.';}
 }
}
page_header('Register');
?>
<div class="row justify-content-center"><div class="col-md-5"><div class="card shadow-sm"><div class="card-body p-4"><h2>Create an account</h2><p class="text-muted">Register as a customer and receive 10% off.</p><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post"><label class="form-label">Name</label><input class="form-control mb-3" name="name" maxlength="100" value="<?= e($name) ?>" required><label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" value="<?= e($email) ?>" required><label class="form-label">Password</label><input class="form-control mb-3" type="password" name="password" minlength="6" required><label class="form-label">Confirm password</label><input class="form-control mb-3" type="password" name="confirm" minlength="6" required><button class="btn btn-dark w-100">Register</button></form><p class="small mt-3 mb-0">Already registered? <a href="login.php">Login</a></p></div></div></div></div>
<?php include __DIR__.'/footer.php'; ?>
