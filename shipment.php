<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="bootstrap1.js"></script>
    <script src="bootstrap2.js"></script>
    <script src="bootstrap3.js"></script>
    <script src="jquery1.js"></script>
    <script src="jquery2.js"></script>
</head>

<body>
<center>
</center>
<table id="body" class="table table-bordered table-striped table-hover">
<tr><td>
<a>If the shipment number does not exist "Please type shipment number below and click SELECT":</a>
Enter shipment number: <input  name="ship" id="ship"/>
<button name='update' id='update' type="button" >CREATE</button>
<script>
$(document).ready(function(){
$('#update').click(function(){         
                    $.ajax({
                            url: 'createship.php',
                            type: 'POST',
                            data: {ship: $('#ship').val()
                            },
                            success: function(){
                                alert('Shipment successfully created and linked');
                            }
                            })
                    })});
</script>
</td></tr>
<tr><td>
<a>If the shipment number exists select from the below list:</a>
	<form id="ship" method="POST">
    <?php
		
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		
			 $sql = "Select shipment_no from _cplshipmentmaster";	
			// $params = array();
			// $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql);		
             echo"<select name='PO'>";
             //echo "<option  value=" .$row["shipment_no"]. "> " .$row["shipment_no"]. "</option>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<option  value=" .$row["shipment_no"]. "> " .$row["shipment_no"]. "</option>";
				}
            echo"</select>";
            if (isset($_POST['PO'])){
                $ship1= $_POST['PO'];
                $conn = sqlsrv_connect( $servername, $connectioninfo);
                $insship="update _cplshipmentlines set shipment_no='$ship1' from _cplshipmentlines 
                where shipment_no is null";
                $update="update _btblinvoicelines set ucIDPOrdTxSTShipmentNo='$ship1'
                from _btblinvoicelines join _cplshipmentlines on _btblinvoicelines.idinvoicelines=_cplshipmentlines.invoicelineid
                where _cplshipmentlines.shipment_no='$ship1'";
                sqlsrv_query($conn, $insship) or die(print_r( sqlsrv_errors(), true));
                sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));
                $status1 = "shipment number successfully linked to record. You will be redirected to PO to add another PO line</br></br>";
                echo '<p style="color:#FF0000;">'.$status1.'</p>';
                $status = "";
                header("refresh:2; url=Index.php");
            }
                sqlsrv_close($conn);
	    ?>
<button type="submit" >SELECT</button>
</form>
</td></tr>
<a id="link" href="CostEstHome.php"><<<<<< Go back</a>
</body>
</html>