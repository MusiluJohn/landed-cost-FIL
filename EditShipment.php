<?php
session_start();
include("php_functions.php");
?>
<html>
<title>
</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap2.css"/>
<!-- <script src="bootstrap1.js"></script> -->
<!-- <script src="bootstrap2.js"></script> -->
<!-- <script src="bootstrap3.js"></script> -->
<script src="jquery1.js"></script>
<script src="jquery2.js"></script>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<body>
<?php include 'navbar2.php' ?> 
<!---Get shipment numbers--->
<div class="container" >
<div style="width:3000px;height:200px;">
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
<form method="GET" action="">
<label style='margin-top:50px;'>Select Shipment number:</label>
<?php
          include("config.php");
		  if (isset($_GET['s'])){
            $_SESSION['shipno']=$_GET['shipnos'];
          }
?>
<?php
    //get shipment number
    include("config.php");
    $edit="select cShipmentNo from _cplshipment";
    $stmt = sqlsrv_query($conn,$edit) or die(print_r( sqlsrv_errors(), true));;	
    echo"<select class='form-control select2' style='margin-top:10px;height:50px;width:190px;' name='shipnos' id='shipnos'>";	
        while( $row = sqlsrv_fetch_array( $stmt) ) {
            echo "<option  value=" .$row["cShipmentNo"]. "> " .$row["cShipmentNo"]. "</option>";
        }
    echo"</select>";
    sqlsrv_close($conn);
?>
<script>
    $('.select2').select2();
</script>
<!-- <script type="text/javascript">
    document.getElementById('shipnos').value = "<?php echo $_GET['shipnos'];?>";
</script> -->
<button class='btn btn-success' style='margin-top:10px;margin-left:5px;' id="S" name="s" type="submit">SELECT</button>
<button class='btn btn-success' style='margin-top:10px;margin-left:5px;margin-left:350px;' id="update" name="update" type="button">UPDATE SHIPMENT</button>
</div>
</div>
<script>
$(document).ready(function(){
$('#update').click(function(){         
                    $.ajax({
                            url: 'updateship.php',
                            type: 'POST',
                            data: {shipid: $('#shipid').val(),shipno: $('#shipno').val(),mode: $('#mode').val(), weight: $('#weight').val(),volume: $('#volume').val(), 
                            packages: $('#packages').val(), portdate: $('#portdate').val(),officedate: $('#officedate').val(),
                            arrdate: $('#arrdate').val(),customno: $('#customno').val(),customdate: $('#customdate').val()
                            ,passdate: $('#passdate').val(),idfno: $('#idfno').val(),twentyft: $('#twentyft').val(),fortyft: $('#fortyft').val()
                            ,lcl: $('#lcl').val(),clagent: $('#clagent').val(),status: $('#status').val(),awb: $('#awb').val()
                            ,coc: $('#coc').val(),etddate: $('#etddate').val(),paystatus: $('#paystatus').val(),usdrate: $('#usdrate').val()
                            ,eurrate: $('#eurrate').val(),awbno: $('#awbno').val(),mino: $('#mino').val()
                            ,pino: $('#pino').val(),pidate: $('#pidate').val(),cino: $('#cino').val(),cidate: $('#cidate').val()
                            ,pickupno: $('#pickupno').val(), gbprate: $('#gbprate').val(),dutypaid: $('#payduty').val(),dateduty: $('#dateduty').val(),
                            lcnum:$('#lcno').val(),lcdate:$('#lcdate').val()},
                            success: function(){
                                window.location.reload();
                                alert('Shipment successfully updated');
                            }
                            })
                    })});
</script>
<hr class="solid">
<div class="content flow" id="div-left">
<div class="even-columns">
<div class="col">
<!--------Left div------>
<table style="margin-left:60px;">
<tr><td style='width:100px;'><label hidden >Shipment ID: </label></td>
    <td style='width:100px;'><input hidden class='form-control' name='shipid' id='shipid' value=<?php shipmentid();?>     type='number'/></td></td></tr> 
    <tr><td style='width:100px;'><label >Shipment No: </label></td>
    <td style='width:100px;'><input name='shipno' id='shipno' value='<?php shipmentno(); ?>' class='form-control' type='text' /></td></td></tr>   
    </div>
    </div>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Mode of Transport: </label></td>
    <td><select name='mode' id='mode' class='form-select' style='margin-top:10px;height:30px;width:180px;'>
    <option hidden><?php transport(); ?></option><option>Air</option><option>Sea</option><option>Courier</option>
    </select></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Gross Weight (Kgs): </label></td><td>
    <input name='weight' id='weight' class='form-control' type='number' value=<?php grossweight();?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Volume (CBM): </label></td><td><input name='volume' id='volume' class='form-control' type='number' value=<?php volume();?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>No. of Packages: </label></td><td><input name='packages' id='packages' class='form-control' type='number' value=<?php packages();?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>ETA @ Port [NBI/MSA]: </label></td><td><input name='portdate' id='portdate' type='date' value=<?php etadate(); ?> class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>ETA @ Office*: </label></td><td><input name='officedate' id='officedate' type='date' value=<?php etaoffice(); ?> class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Actual Arrival  @ Port: </label></td><td><input name='arrdate' id='arrdate' type='date' value=<?php echo arrivaldate(); ?> class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Custom Entry No.: </label></td><td><input name='customno' id='customno' value='<?php customentrynumber(); ?>' class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Custom Entry Date: </label></td><td><input name='customdate' id='customdate' value=<?php echo CustomEntryDate(); ?> class='form-control' type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Custom Pass Date: </label></td><td><input name='passdate' id='passdate' value=<?php echo CustompassDate(); ?> class='form-control' type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>IDF No.: </label></td><td><input name='idfno' id='idfno' class='form-control'value='<?php idfno(); ?>'style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>How many 20FT Container(s): </label></td><td><input name='twentyft' id='twentyft' class='form-control' value=<?php twentyft(); ?> type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>How many 40FT Container(s): </label></td><td><input name='fortyft' id='fortyft' class='form-control' value=<?php fortyft(); ?> type='number' value=0 style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>LCL (Put 1 if yes): </label></td><td><input name='lcl' id='lcl' class='form-control' type='number' value=<?php lcl(); ?>  style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Clearing Agent: </label></td><td><select name='clagent' id='clagent' class='form-select' style='margin-top:10px;height:30px;'>
    <option hidden><?php agent(); ?></option>BESTFAST CARGO(KENYA) LTD<option>UNICARGO GLOBAL LOGISTICS LTD</option><option>INDUS LOGISTICS LTD</option>
    <option>ARAMEX KENYA LTD</option><option>DHL WORLD WIDE EXPRESS</option>
    </select></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Status: </label></td><td><select name='status' id='status' class='form-select' style='margin-top:10px;height:30px;'>
    <option hidden><?php status(); ?></option><option>IN TRANSIT</option><option>CLOSED</option>
    </select></td></tr>
    <!-- <tr><td style='width:100px;'><label style='margin-top:10px;'>AWB/BL Date: </label></td><td><input name='awb' id='awb' class='form-control' type='date' style='margin-top:10px;'/></td></tr> -->
</table>
</div>
<!---------right div----->
<div class="col">
<!--<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="div-right">-->
<table  style="margin-left:60px;">
    <tr><td>
    <tr><td style='width:100px;'><label>COC No.: </label></td><td><input name='coc' id='coc' value='<?php cocno(); ?>' class='form-control'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>ETD Origin (Date): </label></td><td><input name='etddate' id='etddate' class='form-control' value=<?php etdorigin(); ?> type='date' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Payment Status: </label></td><td><select name='paystatus' id='paystatus' class='form-select form-select-lg' style='margin-top:10px;height:30px;'>
    <option hidden><?php paymentstatus(); ?></option><option>UNPAID</option><option>PAID</option>
    <option>Advance payment</option><option>150Days LC</option><option>90Days LC</option>
    <option>60Days LC</option><option>60Days LC</option><option>45Days LC</option>
    </select></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>USD Exchange Rate: </label></td><td><input name='usdrate' id='usdrate' class='form-control' type='number' value=<?php usdrate(); ?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>EUR Exchange Rate: </label></td><td><input name='eurrate' id='eurrate' class='form-control' type='number' value=<?php eurrate(); ?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>GBP Exchange Rate : </label></td><td>
    <input name='gbprate' value=<?php echo gbprate() ?> id='gbprate' class='form-control' type='number'  style='margin-top:10px;'/></td></tr>
    <!-- <tr><td style='width:100px;'><label style='margin-top:10px;'>Freight Charges USD: </label></td><td><input name='freightusd' id='freightusd' class='form-control' type='number' value=<?php freight(); ?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Freight Charges EUR: </label></td><td><input name='freigheur' id='freigheur' class='form-control' type='number' value=<?php freighteur(); ?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Other Charges KES: </label></td><td><input name='othchgs' id='othchgs' class='form-control' type='number' value=<?php othercharges(); ?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Insurance Charges KES: </label></td><td><input name='inschgs' id='inschgs' class='form-control' type='number' value=<?php inscharges(); ?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Port Charges KES: </label></td><td><input name='portchgs' id='portchgs' class='form-control' type='number' value=<?php portcharges(); ?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Agency Fees KES: </label></td><td><input name='agfees' id='agfees' class='form-control' type='number' value=<?php agencyfees(); ?> style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>KEBS Fees KES: </label></td><td><input name='kebsfees' id='kebsfees' class='form-control' type='number' value=<?php kebsfees(); ?>  style='margin-top:10px;'/></td></tr> -->
    <tr><td style='width:100px;'><label style='margin-top:10px;'>AWB/BL No.: </label></td><td><input name='awbno' id='awbno' class='form-control' style='margin-top:10px;' value=<?php awbno(); ?>/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>MI PO No.: </label></td><td><input name='mino' id='mino' class='form-control' style='margin-top:10px;'/></td></tr>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier PI No.: </label></td><td><input name='pino' id='pino' class='form-control' style='margin-top:10px;'/></td></tr>
    <!-- <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier PI Date: </label></td><td><input name='pidate' id='pidate' class='form-control' type='date' style='margin-top:10px;'/></td></tr> -->
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier CI No.: </label></td><td><input name='cino' id='cino' class='form-control' style='margin-top:10px;'/></td></tr>
    <!-- <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier CI Date: </label></td><td><input name='cidate' id='cidate' class='form-control' type='date' style='margin-top:10px;'/></td></tr> -->
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Main Supplier Pickup No.: </label></td><td><input name='pickupno' id='pickupno' class='form-control' style='margin-top:10px;'/></td></tr>
    </tr></td>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>LC Number: </label></td><td>
    <input name='lcno' value=<?php lcno(); ?> id='lcno' class='form-control' style='margin-top:10px;'/></td></tr>
    </tr></td>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>LC Date: </label></td><td>
    <input name='lcdate' value= <?php lcdate(); ?> type='date' id='lcdate' class='form-control' style='margin-top:10px;'/></td></tr>
    </tr></td>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>LC Bank: </label></td><td>
    <input name='lcbank' value=<?php lcbank(); ?>  placeholder='Enter the name of the bank' type='text' id='lcbank' class='form-control' style='margin-top:10px;'/></td></tr>
    </tr></td>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Duty to pay: </label></td><td>
    <input name='payduty' value= <?php duty(); ?>  id='payduty' class='form-control' style='margin-top:10px;'/></td></tr>
    </tr></td>
    <tr><td style='width:100px;'><label style='margin-top:10px;'>Date to pay duty: </label></td><td>
    <input name='dateduty' value=<?php duty_date(); ?>  type='date' id='dateduty' class='form-control' style='margin-top:10px;'/></td></tr>
    </tr></td>
    </table>
    <!--</div>--->
    </div>
    <div class="col">
    <div class="form-check form-check-inline" style="margin-right:60px;margin-left:50px;">
        <table><tr><td><label>GRV Numbers linked to this shipment: </label>   
        </td></tr>
        <?php
          include("config.php");
		  if (isset($_GET['s'])){
		  $query = $_GET['shipno'];
		  $results = array('error' => false, 'data' => '');
			 $sql = "select InvNumber from InvNum im join _etblUserHistLink ek
             on im.AutoIndex=ek.TableID
             join _rtblUserDict rt on ek.UserDictID=rt.idUserDict 
             where rt.cfieldname='ucIDPOrdShipmentNo'
             and UserValue='$query' and docstate=4 and docflag=1
             ";
			 $params = array();
			 $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql,$params,$options);		
			 if( $stmt === false) {
					die( print_r( sqlsrv_errors(), true) );
				}
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					?>
					<tr><td><input class='form-control' value='<?php echo $row["InvNumber"] ;?>' disabled/></td></tr>				
				<?php }
		//sqlsrv_close($conn);
                }
	    ?>
    </table>
</div> 
</div>
</div>
</div>
</body>
</html>