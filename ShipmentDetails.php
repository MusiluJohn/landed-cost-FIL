<html>
<title>
</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap2.css"/>
<link rel="stylesheet" href="css/style.css">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="bootstrap1.js"></script>
<script src="bootstrap2.js"></script>
<script src="bootstrap3.js"></script>
<script src="jquery1.js"></script>
<script src="jquery2.js"></script>
<body>
<!---Get shipment numbers--->
<div class="container" style="position:relative;">
<label style='margin-top:50px;'>Select Shipment:</label>
<?php
    //get cost
    include("config.php");
    $edit="select distinct ucIDPOrdShipmentNo from invnum where ucIDPOrdShipmentNo is not null or ucIDPOrdShipmentNo<>''";
    $stmt = sqlsrv_query($conn,$edit) or die(print_r( sqlsrv_errors(), true));;	
    echo"<select class='form-control' style='margin-top:10px;height:50px;width:300px;' name='shipno'>";	
        while( $row = sqlsrv_fetch_array( $stmt) ) {
            echo "<option  value=" .$row["ucIDPOrdShipmentNo"]. "> " .$row["ucIDPOrdShipmentNo"]. "</option>";
        }
    echo"</select>";
    sqlsrv_close($conn);
?>
<button class='btn' style='margin-top:10px;margin-left:5px;' id="D">display</button>
</br>
<hr>
</div>
<script>
    $(document).ready(function() {
        $("#div-left").hide();
        $("#div-right").hide();
    });
    $("#D").click(function(){
        $("#div-left").show();
        $("#div-right").show();
    });
</script>
<table>
<tr><td id="main">
<!--------Left div------>
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" id="div-left">
<table>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Mode of Transport: </label></td>
    <td><select class='form-control' style='margin-top:10px;height:30px;'>
    <option>Air</option><option>Sea</option><option>Courier</option>
    </select></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Gross Weight (Kgs): </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Volume (CBM): </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>No. of Packages: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>ETA @ Port [NBI/MSA]: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>ETA @ Office*: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Actual Arrival  @ Port: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Custom Entry No.: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Custom Entry Date: </label></td><td><input class='form-control' type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Custom Pass Date: </label></td><td><input class='form-control' type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>IDF No.: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>How many 20FT Container(s): </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>How many 40FT Container(s): </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>LCL (Put 1 if yes): </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Clearing Agent: </label></td><td><select class='form-control' style='margin-top:10px;height:30px;'>
    <option></option><option></option><option></option>
    </select></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Status: </label></td><td><select class='form-control' style='margin-top:10px;height:30px;'>
    <option>IN TRANSIT</option><option>CLOSED</option>
    </select></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>AWB/BL Date: </label></td><td><input class='form-control' type='date' style='margin-top:10px;'/></td></tr>
</table>
</div>
</td>
<!---------right div----->
<td id="main2">
<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="div-right">
<table>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>COC No.: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>ETD Origin (Date): </label></td><td><input class='form-control' type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Payment Status: </label></td><td><select class='form-control' style='margin-top:10px;height:30px;'>
    <option>UNPAID</option><option>PAID</option>
    </select></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>USD Exchange Rate: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>EUR Exchange Rate: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Freight Charges USD: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Freight Charges EUR: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Other Charges KES: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Insurance Charges KES: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Port Charges KES: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Agency Fees KES: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>KEBS Fees KES: </label></td><td><input class='form-control' type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>AWB/BL No.: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>MI PO No.: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Main Supplier PI No.: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Main Supplier PI Date: </label></td><td><input class='form-control' type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Main Supplier CI No.: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Main Supplier CI Date: </label></td><td><input class='form-control' type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:300px;'><label style='margin-top:10px;'>Main Supplier Pickup No.: </label></td><td><input class='form-control' style='margin-top:10px;'/></td></tr>
</table>
</div>
</td>
<td>
    <table>
        <tr><th>GRV NO</th></tr>
    </table>
</td>
</tr>
</table>
</body>
</html>