<html>
<head>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap2.css"/>
</head>
<!-- <script src="js/bootstrap1.js"></script> -->
<!-- <script src="js/bootstrap2.js"></script> -->
<!-- <script src="js/bootstrap3.js"></script> -->
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
<title>
</title>
<script src="js/script.js">
</script>
<body>
<?php include 'navbar2.php' ?>
<div id='schemelist' style='margin-top:60px;'>
<ul><a>Below are the types of schemes:</a></ul>
<hr></hr>
<table class="table table-bordered table-striped table-hover" style='font-size:12px;'>
        <thead>
        <tr>
				<th>Check</th>
        <th>Scheme Name</th>
        <th>Cost Code</th>
				<th>Calculation Base</th>
				<th>Rate/Duty%</th>
				<th>Excise Duty%</th>
				<th>Edit</th>
				<th>Delete</th>		
        </tr>
        </thead>
        <tbody>
		<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		 $results = array('error' => false, 'data' => '');
		
             $sql = "select ce.id as id, Scheme as scheme, cr.cost as cost, cb.calcbase as calcbase, rate, vat  from _cplscheme ce join _cplcostmaster cr 
			 on ce.Cost_Code=cr.id join _cplcalcbase cb on ce.calcbase=cb.id
			 order by scheme";
       
			 $params = array();
			 $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql,$params,$options);		
			 if( $stmt === false) {
					die( print_r( sqlsrv_errors(), true) );
				}
                $row_count = sqlsrv_num_rows($stmt);
				if ($row_count > 0) {
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<tr><form method='post' action='' >"; ?>
					<td><input type='radio' id="radio" name="radio[]" value=<?php echo $row["id"] ;?> /></td>
          <td> <?php echo $row["scheme"] ;?></td>
					<td> <?php echo $row["cost"] ;?></td>
					<td> <?php echo $row["calcbase"] ;?></td>
					<td> <?php echo $row["rate"] ;?></td>
					<td> <?php echo $row["vat"] ;?></td>
					<td><a id="link" href='editscheme.php?edit=<?php echo $row["id"] ;?>'> edit scheme rates</a></td>
					<td><a id="link" href='deletescheme.php?delete=<?php echo $row["id"] ;?>'> delete scheme</a></td>			
					</form></tr>	
				<?php }}
		//sqlsrv_close($conn);
	    ?>
		
        </tbody>
	</table>
	<a id="link" href="CostEstimateHome.php"><<<<<< Go back</a>
</div>
</body>
</html>