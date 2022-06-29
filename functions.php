<?php

function insertArray($row){
    include './connect.php';
    $sql = "INSERT INTO _cplpayroll(
    Code,
	Amount,
	DebitAccount,
	DebitBank,
    DebitBranch,
	EmployeeAccount,
	EmployeeBank,
	EmployeeBranch,
	EmployeeName,
	Refernce,
	ProcessingReference,
	Site,
	Month,
	Year)VALUES(? ,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $params = array($row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],
    $row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13]);
    echo $row[0]."****".$row[1]."****".$row[2]."****".$row[3]."****".$row[4]."****".$row[5];
    $stmt = sqlsrv_query( $conn, $sql,$params);
    if( $stmt === false ) {
        if( ($errors = sqlsrv_errors() ) != null) {
            foreach( $errors as $error ) {;
                echo " <div class='alert alert-danger' role='alert'>
                    Login Failed due to".$error[ 'message']."
                </div>";
            }
        }
    }
    sqlsrv_close($conn);
}