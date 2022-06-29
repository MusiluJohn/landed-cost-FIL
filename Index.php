<?php
session_start();

?>
<html>
<title>

</title>
<link rel="stylesheet" type="text/css" href="bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="bootstrap2.css"/>
<head>
<link rel="stylesheet" href="style.css"/>
<center>
<img src="clogo.png" style='width:240;height:240;margin-top:20px;'>
<h1>LOGISTICS PORTAL</h1>
</center>
</head>
<body style="background-color: ghostwhite;">
<center>
<div class="row justify-content-center">
<div class="demo-heading pull">
</div>
<div class="login-form ">
<h4>User Login:</h4>
<form method="post" action="">
<div class="form-group">
<!-- <?php if ($loginError ) { ?>  -->
<div class="alert alert-warning"><?php echo $loginError; ?></div>
<!-- <?php } ?>  -->
</div>
<div class="container" id="container" name="container">
<input  name="uname" id="uname" type="text"  placeholder="Enter username" autofocus="" required>
<input  type="password"  id="pwd" name="pwd" placeholder="Enter password" required>
</div>
<div class="form-group">
<select type="database" class="form-control" name="database" id="database">
<option>FIL-COST-EST</option><option>PSL-COST-EST</option>
</select>
</div>
<div class="form-group">
<button type="submit" name="login" class="btn btn-info">Login</button>
</div>
</form>
<div class="form-group">
<a href="register.php">Register</a>
</div>
</div>
</div>
</center>
</body>
<?php
if (isset($_POST['login'])){
$name=$_POST['uname'];
$password=$_POST['pwd'];
$_SESSION['db']=$_POST['database'];
include("config.php");
$sqlQuery = "select id, user_name FROM _cplusers WHERE user_name='$name' AND password='$password'";
$user=sqlsrv_query($conn, $sqlQuery);
$row=sqlsrv_num_rows($user);
if(!isset($user)) {
    $loginError = "Invalid email or password!";
    echo "<script> alert('$loginError'); </script>";
} else {
    while( $rows = sqlsrv_fetch_array( $user, SQLSRV_FETCH_ASSOC) ) {
     $_SESSION['users']=$rows['user_name']; 
     $_SESSION['userid'] = $rows['id'];
    header("Location:Module.php");
    }
}}
?>
<html>