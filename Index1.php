<?php
session_start();
?>
<html>
<title>

</title>
<link rel="stylesheet" type="text/css" href="bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="bootsrap2.css"/>
<head>
<center>
<img src="">
<h2></h2>
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
<div class="form-group">
<input name="name" id="name" type="text" class="form-control" placeholder="Username" autofocus="" required>
</div>
<div class="form-group">
<input type="password" class="form-control" name="pwd" placeholder="Password" required>
</div>
<div class="form-group">
<select id="company" name="company" class="form-control">
    <option hidden>select company</option>
    <option>FIL</option>
    <option>PSL</option>
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
include("config.php");
if (isset($_POST['login'])){
$name=$_POST['name'];
$password=$_POST['pwd'];
$_SESSION['company']=$_POST['company'];
$sqlQuery = "select id, user_name FROM _cplusers WHERE user_name='$name' AND password='$password'";
$user=sqlsrv_query($conn, $sqlQuery);
$row=sqlsrv_num_rows($user);
if($row>0) {
    $loginError = "Invalid email or password!";
    echo "<script> alert('$loginError'); </script>";
} else {
    while( $rows = sqlsrv_fetch_array( $user, SQLSRV_FETCH_ASSOC) ) {
     $_SESSION['users']=$rows['first_name']; 
     $_SESSION['userid'] = $rows['id'];
    header("Location:main.php");
    }
}}
?>
<html>