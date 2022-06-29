<?php
session_start();
?>
<html>
<title>
</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="bootstrap1.js"></script>
<script src="bootstrap2.js"></script>
<script src="bootstrap3.js"></script>
<script src="jquery1.js"></script>
<script src="jquery2.js"></script>
<head>
<center>
<?php include 'navbar2.php' ?>
<h2 class="title">KOBIAN SHIPMENT LISTING</h2>
</head>
<body>
<div class="container justify-content-center">
<table><tr><td>
<!----------------------MENU---------------------->
<!------------------------------------------------>
<!--<?php include('menu.php');?></td><td><label style='margin-left:100px;'>
<?php $users=$_SESSION['users']; if  (isset($_SESSION['users'])){ echo 'You are Logged in as:' . $_SESSION['users'];} else {header("locaton: http://localhost:81/deltav2/index.php");} ?></label><td><td><label style="margin-left:10px;"><a href="index.php">sign out</a></label></td></tr></table>
-->
<hr>
<!------------Shipments list grid----------------->
<!------------------------------------------------>

<table id="data-table" class="table table-bordered table-striped table-hover">
<thead>
<tr>
<th>Shipment No.</th>
<th>Status</th>
<th>Print</th>
<th>Delete</th>
</tr>
</thead>
<!-----------------get grid items----------------->
<!------------------------------------------------>
<?php
include 'config.php';
$sqlQuery = "
SELECT distinct shipment_no, (case when active=0 then 'INACTIVE' when active=1 then 'ACTIVE' end) as status from _cplshipmentlines";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );			
$stmt2 = sqlsrv_query($conn,$sqlQuery,$params,$options);
if( $stmt2 === false) {
       //die( print_r( sqlsrv_errors(), true) );
   }
   $row_count = sqlsrv_num_rows($stmt2);
   if ($row_count > 0) {
   while( $row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
echo '
<tr>
<td>'.$row["shipment_no"].'</td>
<td>'.$row["status"].'</td>
<td><a href="print.php?invoice_id='.$row["shipment_no"].'" title="Print Shipment"><span class="glyphicon glyphicon-print"></span></a></td>
<td id="delete"><a href="#" id="'.$row["shipment_no"].'"  title="Delete Shipment"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>
';
}}
?>
</center>
<!-----------------------Send id to be deleted on delete icon-------------->
<!------------------------------------------------------------------------->
<!------------------------------------------------------------------------->
<script>
$(document).ready(function(){
$('#delete a').click(function(){
    var id=$(this).attr('id');
    $.ajax({
        url: 'delete.php',
        type: 'post',
        data: {id: id},
        success: function(result){
            alert('Record successfully deleted');
        }
        })
});
});
</script>
</table>
</div>
</body>
</html>