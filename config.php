
<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
// $company=$_SESSION['company'];
$servername = "MUSILU" ;
//$servername = "192.168.1.2" ;
$db=$_SESSION['db'];
$connectioninfo = array( "Database"=>"$db", "UID"=>"sa", "PWD"=>"john");
//$connectioninfo = array( "Database"=>"$db", "UID"=>"sa", "PWD"=>"P@ssw0rd");
$conn = sqlsrv_connect( $servername, $connectioninfo);
//$warehouse = "AUTOMOBILE";
if ($conn) {echo "";

}else{
echo "Connection Failed<br/>";
//die(print_r( sqlsrv_errors(), true));

}
?>