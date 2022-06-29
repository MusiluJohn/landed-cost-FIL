<?php
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
          $vat=$_POST['vat'] ?? 0;
          $rate=$_POST['rate'] ?? 0;
        $ship_no="declare @ship as varchar(100)
        set @ship=(select shipment_no from _cplshipmentmaster where id=$query) 
        update _cplShipment set Vat=$vat from _cplShipment where cShipmentNo=@ship";
        sqlsrv_query($conn, $ship_no) or die(print_r( sqlsrv_errors(), true));
    sqlsrv_close($conn);

?>