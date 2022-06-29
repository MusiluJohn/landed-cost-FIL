<html>
    <head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
    </head>
    <script src="script.js">
</script>
    <body>
    <ul><button onclick="openwin()" class="btn btn-success">Create scheme</button></ul>
    <div id="schemes">
        <table id="scheme" class="table table-bordered table-striped table-hover" style='font-size:12px;'>
        <form method="POST">
            <tr id="trsch">
            <td id="tdsch">
            Scheme:
            </td>
            <td id="tdsch">
            <?php
            //get scheme
            include("config.php");
            $conn = sqlsrv_connect( $servername, $connectioninfo);
            $id=$_GET['edit'];
            $edit="select scheme from _cplscheme where id=$id";
            $stmt = sqlsrv_query($conn,$edit) or die(print_r( sqlsrv_errors(), true));		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo " <input id=schemename value=" .$row["scheme"]. " name='schemename' disabled class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
            ?>
            </td></tr>
            <tr id="trsch">
            <td id="tdsch">
            Cost Code:
            </td>
            <td id="tdsch">
            <?php
            //get cost
            include("config.php");
            $conn = sqlsrv_connect( $servername, $connectioninfo);
            $id=$_GET['edit'];
            $edit="select  cost from _cplscheme join _cplcostmaster on _cplscheme.cost_code=_cplcostmaster.id where _cplscheme.id=$id";
            $stmt = sqlsrv_query($conn,$edit) or die(print_r( sqlsrv_errors(), true));;		
				while( $row = sqlsrv_fetch_array( $stmt) ) {
					echo "<input  value=" .$row["cost"]. " name='cost' disabled class='form-control' style='width:150px;'/>";
				}
            echo"</select>";
            sqlsrv_close($conn);
            ?>
            </td></tr>
            <tr id="trsch">
            <td id="tdsch">
            Calculation Base:
            </td>
            <td id="tdsch">
            <?php
            //get calculation base
            include("config.php");
            $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql = "Select id, calcbase from _cplcalcbase";    
            // $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             $stmt = sqlsrv_query($conn,$sql);      
             echo"<select id='calcbase' name='calcbase' class='form-control' style='width:150px;'>";
             echo "<option  value=4> N/A</option>";
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    echo "<option  value=" .$row["id"]. "> " .$row["calcbase"]. "</option>";
                }
            echo"</select>";
            ?>
            </td></tr>
            <tr id="trsch">
            <td id="tdsch">
            Rate:
            </td>
            <td id="tdsch">
            <?php
            //get rate
            include("config.php");
            $conn = sqlsrv_connect( $servername, $connectioninfo);
            $id=$_GET['edit'];
            $edit="select rate from _cplscheme where id=$id";
            $stmt = sqlsrv_query($conn,$edit) or die(print_r( sqlsrv_errors(), true));		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo " <input id=rate value=" .$row["rate"]. " name='rate' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
            ?>
            </td></tr>
            <tr id="trsch">
            <td id="tdsch">
            Excise Duty%:
            </td>
            <td id="tdsch">
            <?php
            //get rate
            include("config.php");
            $conn = sqlsrv_connect( $servername, $connectioninfo);
            $id=$_GET['edit'];
            $edit="select vat from _cplscheme where id=$id";
            $stmt = sqlsrv_query($conn,$edit) or die(print_r( sqlsrv_errors(), true));		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo " <input id='excise' value=" .$row["vat"]. " name='excise' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
            ?>
            </td></tr>
        </table>
        <button type="submit" name='submit' class="btn btn-success">SUBMIT</button>
    </form>
    </div>
    <?php
    //update scheme
    include("config.php");
    $conn = sqlsrv_connect( $servername, $connectioninfo);
    $id=$_GET['edit'];
	if (isset($_POST['submit'])){
		$rate= $_POST['rate'] ?? 0;
        $calcbase=$_POST['calcbase'] ?? 4;
        $excise=$_POST['excise'] ?? 0;
        $insscheme="update _cplscheme set rate=$rate, calcbase=isnull($calcbase,4),vat=$excise  from _cplscheme where id=$id";
		sqlsrv_query($conn, $insscheme) or die(print_r( sqlsrv_errors(), true));
		$status1 = "Record successfully edited in the database</br></br>";
		echo '<p style="color:#FF0000;">'.$status1.'</p>';
	}
	?>
    <a id="link" href="CostEstHome.php"><<<<<< Go back</a>
    </body>
</html>
