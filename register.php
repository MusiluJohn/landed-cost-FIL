<html>
<title>

</title>
<link rel="stylesheet" type="text/css" href="bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="bootsrap2.css"/>
<head>
<center>
<img src="delta_logo.jpg">
<h2>COST ESTIMATES UTILITY</h2>
</center>
</head>
<body>
<center>
<div class="row justify-content-center">
<div class="demo-heading pull">
</div>
<div class="login-form ">
<h4>Registration:</h4>
<form method="post" action="">
<div class="form-group">
<input name="name" id="name" type="text" class="form-control" placeholder="Enter Username" autofocus="" required>
</div>
<script>
     document.getElementById('name').value = "<?php echo $_POST['name'];?>";
</script>
<div class="form-group">
<?php
     //type drop down
     include "config.php";
     $conn = sqlsrv_connect( $servername, $connectioninfo);  
     $sql = "select idAgents,cAgentName from _rtblAgents";	
     // $params = array();
     // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
     $stmt = sqlsrv_query($conn,$sql);
     if ($stmt) {
     echo"<select id='agent' name='agent' class='form-control' required >";
     echo "<option  value='' disabled selected hidden>Select Agent</option>";
          while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
          echo "<option  value='" .$row["cAgentName"]. "'> " .$row["cAgentName"]. "</option>";
          }
     echo"</select>";
     }
     sqlsrv_close($conn);
?>
</div>
<script>
     document.getElementById('add').value = "<?php echo $_POST['add'];?>";
</script>
<div class="form-group">
<input type="password" id="pwd" class="form-control" name="pwd" placeholder="Enter Password">
</div>
<script>
     document.getElementById('pwd').value = "<?php echo $_POST['pwd'];?>";
</script>
<div class="form-group">
<input type="password" class="form-control" name="pwd2" placeholder="Confirm Password">
</div>
<div class="form-group">
<button type="submit" name="login" class="btn btn-info">Register</button>
</div>
</form>
<div class="form-group">
<a href="index.php">Login</a>
</div>
</div>
</div>
</center>
<?php
include("config.php");
if (isset($_POST['login'])){
$name=$_POST['name'];
$password=$_POST['pwd'];
$password2=$_POST['pwd2'];
$agent=$_POST['agent'];
if ($password==$password2) {
$sqlQuery = "insert into _cplusers (password,user_name,sageid)
values('$password','$name',(select idagents from _rtblagents where cAgentName='$agent' and idagents not in (select sageid from _cplusers)))";
sqlsrv_query($conn, $sqlQuery);
echo "<script>alert('$name successfully created. Log in to continue');window.location = 'index.php';</script>";
} else {
    echo "<script>alert('Passwords do not match');</script>";    
}
}
?>
</body>