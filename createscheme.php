<html>
<head>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap2.css"/>
	<script src="js/script.js"></script>
	<script src="js/jquery1.js"></script>
	<script src="js/jquery2.js"></script>
</head>
<body>
<?php include 'navbar2.php' ?>
<hr></hr>
<div id="schemes" style='margin-top:60px;'>
<form id="ship" method="POST">
<table id="scheme" class="table table-bordered table-striped table-hover" style='font-size:12px;'>
<tr id="trsch"><td id="tdsch">
		Enter the scheme:
		</td><td id="tdsch"><input name="scheme" class='form-control' style='width:150px;'/>
</td></tr>
<tr id="trsch"><td id="tdsch">
		Select the type of cost:
		</td>
		<td id="tdsch">
		<?php
		//Cost drop down
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		
			 $sql = "Select id, cost from _cplcostmaster";	
			// $params = array();
			// $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql);		
			 echo"<select name='cost' class='form-control' style='width:150px;'>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<option  value=" .$row["id"]. "> " .$row["cost"]. "</option>";
				}
			echo"</select>";
			?>
</td></tr>
<tr id="trsch"><td id="tdsch">
		Select the calculation base:		
		</td>
		<td id="tdsch">
		<?php
		//Calculation base dropdown
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
			 $sql = "Select id, calcbase from _cplcalcbase";	
			// $params = array();
			// $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql);		
			 echo"<select name='calcbase' class='form-control' style='width:150px;'>";
			 echo "<option  value=" .$row["id"]. "> " .$row["calcbase"]. "</option>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<option  value=" .$row["id"]. "> " .$row["calcbase"]. "</option>";
				}
			echo"</select>";
			?>
	</td></tr>
	<tr id="trsch"><td id="tdsch">
			Enter the rate: 
			</td>
			<td id="tdsch">
			<input name="Rate" class='form-control' style='width:150px;' value=0 />
	</td></tr>
	<tr id="trsch"><td id="tdsch">
			Enter the vat %: 
			</td>
			<td id="tdsch">
			<input name="Vat" class='form-control' style='width:150px;' value=0 />
	</td></tr>
	<?php
	//insert into _cplscheme
	if (isset($_POST['submit'])){
		$scheme= $_POST['scheme'] ?? '';
		$cost= $_POST['cost'] ?? 0;
		$calcbase= $_POST['calcbase'] ?? 0;
		$rate= $_POST['Rate'] ?? 0;
		$vat= $_POST['Vat'] ?? 0;
		$conn = sqlsrv_connect( $servername, $connectioninfo);
		$insscheme="insert into _cplScheme (scheme,Cost_Code,calcbase, rate,vat)
		values ('$scheme', $cost, $calcbase,$rate,$vat)";
		sqlsrv_query($conn, $insscheme) or die(print_r( sqlsrv_errors(), true));
	}
	?>
	</table>
	<button type="submit" class="btn btn-success" name='submit' onclick="update()">SUBMIT</button>
</form>
<hr></hr>
</div>
<script src="script.js">
</script>
<a id="link" href="CostEstHome.php"><<<<<< Go back</a>
</body>
</html>