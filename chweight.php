<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
          $value=$_POST['weight'] ?? 0;
          echo $value;
          $update="update stkitem set ufIIWeight=$value from stkitem where StockLink=$query";
        sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));
    sqlsrv_close($conn)

?>