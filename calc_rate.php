<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
          $rate=$_POST['rate'] ?? 0;
        $updaterate="update _cplshipmentlines set rate=$rate from _cplshipmentlines ts join _cplshipmentmaster tr on
        ts.shipment_no=tr.shipment_no 
        where tr.id=$query ";
        $updatetotals="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
        select code,stkcode,round(sum(isnull(Cost,0))+(max(amount)*max(qty)*max(rate)),2) as totcost 
        into #totals
        from _cplshipmentlines ts
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        where tr.id=$query
        group by code,stkcode
        
        update _cplshipmentlines set totals=tts.totcost from _cplshipmentlines ts  
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        join #totals tts on ts.stkcode=tts.stkcode
        where tr.id=$query"; 
        sqlsrv_query($conn, $updaterate) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $updatetotals) or die(print_r( sqlsrv_errors(), true));

        //actual factor
        $update_rate="declare @ship as varchar(100)
        set @ship=(select shipment_no from _cplshipmentmaster where id=$query) 
        update _cplShipment set rate=$rate from _cplShipment where cShipmentNo=@ship";
        sqlsrv_query($conn, $update_rate) or die(print_r( sqlsrv_errors(), true));
    sqlsrv_close($conn);

?>