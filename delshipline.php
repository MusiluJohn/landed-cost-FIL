<?php
include("config.php");

if(isset($_POST["submit"])) {
    print_r($_POST["lines"]);
    foreach ($_POST["lines"] as $key => $value){
        $id=$_POST["lines"][$key];
        $update="IF OBJECT_ID('tempdb..#tmpdel') IS NOT NULL DROP TABLE #tmpdel
        select code, shipment_no into #tmpdel from _cplshipmentlines where id=$id
        delete from _cplshipmentlines where code=(select code from #tmpdel) and shipment_no=(select shipment_no from #tmpdel)";
        $update2="update _btblinvoicelines set ucIDPOrdTxSTShipmentNo='' from _btblinvoicelines where idinvoicelines=
        (select invoicelineid from _cplshipmentlines where id=$id)";
        sqlsrv_query($conn, $update2) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));   
    }
header("Location:CostEstHome.php");
}
?>
