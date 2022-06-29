<?php
    // session_start();
    $serverName = "."; //serverName\instanceName
    $database = "FIL";
    $connectionInfo = array( "Database"=>$database, "UID"=>"sa", "PWD"=>"john");
    $conn = sqlsrv_connect( $serverName, $connectionInfo); 
    if ( $conn ) {
        $connStatus = "Connection established to SQL Server.<br />";
   }else{
        $connStatus = "Connection could not be established to SQL Server.<br />";
        die( print_r( sqlsrv_errors(), true));
    }
    // echo $connStatus;


