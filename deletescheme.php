<?php
include("config.php");
$conn = sqlsrv_connect( $servername, $connectioninfo);
$id=$_GET['delete'];
$delete="delete from _cplscheme where id=$id";
sqlsrv_query($conn, $delete) or die(print_r( sqlsrv_errors(), true));
$status = "Record deleted successfully. </br></br>";
echo '<p style="color:#FF0000;">'.$status.'</p>';
?>
<a>Click</a>
<a id="link" href="schemes.php">Go Back</a>