<?php
    include("config.php");
    $rate = $_POST['rate'] ?? 0;
    $id = $_POST['id'] ?? 0;
    $shipid = $_POST['shipid'] ?? 0;
    $updatecost="update _cplshipmentlines set rate=$rate from _cplshipmentlines where invoicelineid=$id";
    sqlsrv_query($conn, $updatecost) or die(print_r( sqlsrv_errors(), true));
    $updateamount="IF OBJECT_ID('tempdb..#tmpupcst') IS NOT NULL DROP TABLE #tmpupcst
    select code, shipment_no into #tmpupcst from _cplshipmentlines where id=$shipid
    update _cplshipmentlines set unit_amount_kes=cost*$rate,tot_amount_kes=(cost*qty)*$rate from _cplshipmentlines where code=(select code from #tmpupcst) and shipment_no=(select shipment_no from #tmpupcst)";
	sqlsrv_query($conn, $updateamount) or die(print_r( sqlsrv_errors(), true)); 
?>