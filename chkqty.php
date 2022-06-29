<?php
//require_once("insert.php");
include("config.php");
session_start();
$conn = sqlsrv_connect( $servername, $connectioninfo);
$query=$_POST['shipno'] ?? 0;
$id=$_POST['id'] ?? 0;
$qty=$_POST['qty'] ?? 0;

$query="select distinct (case when isnull(rec_qty,0)+$qty>qty then 'Received quantity cannot exceed ordered qty'
else '' end) as value
--into #tmpcost 
from  _cplshipmentlines cs
 join _cplshipmentmaster tm
on cs.shipment_no=tm.shipment_no 
where tm.id=cast($query as int) and invoicelineid=$id";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );			
$stmt = sqlsrv_query($conn,$query,$params,$options) or die(print_r( sqlsrv_errors(), true));		
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    echo $row['value'];
}
?>