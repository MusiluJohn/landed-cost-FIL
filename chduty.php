<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
          $code = $_POST['code'] ?? '';
          $value=$_POST['duty'] ?? 0;
          $update="update _cplshipmentlines set cost= $value,duty_modified_date=getdate()
          from _cplshipmentlines ts 
          join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
          join _cplcostmaster cr on ts.costcode=cr.id join stkitem st on ts.stkcode=st.StockLink
          where tr.id=cast($query as int)  and ts.active='True' and cr.cost='Duty' and st.serviceitem<>1
          and ts.code='$code'";
        sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));
    sqlsrv_close($conn)

?>