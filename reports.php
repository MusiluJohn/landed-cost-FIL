<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap2.css"/>
<link rel="stylesheet" href="css/style.css">
<script src="js/script.js"></script>
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
<!-- <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
</head>

<link href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<body>
<h1>Item History Report</h1>
<table>
<tr>
    <form method="post" action="">
    <td><a>Select Inventory Item:</a></td>
    <td>

    <?php
            
            include("config.php");
              $conn = sqlsrv_connect( $servername, $connectioninfo);
                 $sql = "select distinct code from _cplshipmentlines cs join _cplshipmentmaster cr on
                 cs.shipment_no=cr.shipment_no";	
                // $params = array();
                // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                 $stmt = sqlsrv_query($conn,$sql);
                if ($stmt) {
                 echo"<select id='item' name='item' class='form-control select2' style='width:150px;height:33px;margin-bottom:10px;'>";
                 //echo "<option  value=" .$row["id"]. "> " .$row["shipment_no"]. "</option>";
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                        echo "<option  value=" .$row["code"]. "> " .$row["code"]. "</option>";
                    }
                echo"</select>";
                }
            sqlsrv_close($conn);
            ?>
    <script>
        $('.select').select2();
    </script>
    </td>
    <td><a style="margin-left:15px;margin-bottom:10px;">From Date:</a></td><td>
        <input class='form-control' type='date' style='margin-bottom:10px;'/>
    </td>
    <td><a style="margin-left:15px;margin-bottom:10px;">To Date:</a></td><td>
        <input class='form-control' type='date' style='margin-bottom:10px;'/>
    </td>
    <td>
    <button style="margin-left:15px;margin-bottom:10px;" class="btn btn-success" type="submit" ><span class="glyphicon glyphicon-search" style="margin-right:2px;"></span>SEARCH</button> 
    </td>   
</tr>
</table>

  </div>
<div id="container">
<table id="table" name="table" class="table table-bordered table-striped table-hover" style='font-size:10px;margin-top:50px;'>
    <thead >
        <tr>
            <th>Cost Type</th>
            <th>shipment No</th>
            <th>Amount</th>						
        </tr>
    </thead>
    <tbody>
<?php
//require_once("insert.php");
include("config.php");
$conn = sqlsrv_connect( $servername, $connectioninfo);
$results = array('error' => false, 'data' => '');
$item=$_POST['item'] ?? '';
$qresults="select distinct  
case when cr.cost='TT_SWIFT_Charges ' then 'Swift Charge' when cr.cost='InsuranceAmount' then 'Insurance Fee' when cr.cost='Duty' then 'Duty Fee' 
when cr.cost='RailwayLevy' then 'Railway Fee' when cr.cost='GOK' then 'Gok Fee' when cr.cost='FreightKsh' then 'Freight Fee'  
when cr.cost='Entry_Amendment_Charges' then 'Entry Fee' when cr.cost='Penalty' then 'Penalty Fee' when cr.cost='Handling' then 'Handling Fee' 
when cr.cost='KEBS_QualityInspection' then 'Kebs Fee' when cr.cost='ISM' then 'Ism Fee' when cr.cost='WHouseStorageCharge' then 'Shipping Line Fee'
when cr.cost='CustomsProcessing' then 'Customs Processing Fee' when cr.cost='CustomsVerification' then 'Customs Verification Fee'
when cr.cost='AgencyFee' then 'Agency Fee' when cr.cost='Documentation_Charges' then 'Documentation Fee'
when cr.cost='BreakBulkCharges' then 'Breakbulk Fee' when cr.cost='OffloadingCharge' then 'Kpa Fee'
when cr.cost='Transport_Charges' then 'Transport Fee' when cr.cost='COC_in_Ksh' then 'Coc Fee'
when cr.cost='ConcessionFee'  then 'Concession Fee' when cr.cost='Surcharges' then 'Surcharges Fee'
when cr.cost='VATonduty' then 'Vat on duty Fee' when cr.cost='OtherChargesOnAWBOrSea' then 'Other Charges Fee'
when cr.cost='EXCISE_DUTY' then 'Excise Duty Fee' when cr.cost='Stamps' then 'Stamps Fee'
when cr.cost='Additional_Cost' then 'Additional Fee' when cr.cost='MarineCost' then 'Marine Fee'
end as Cost_type,round(sum(st.cost),2) as cost, ((tm.shipment_no)) as descr
from _cplshipmentlines st join _cplshipmentmaster tm
on st.shipment_no=tm.shipment_no
join _cplcostmaster cr on st.costcode=cr.id
where st.code='$item'
group by cr.cost,tm.shipment_no
having sum(st.Cost)>0";
sqlsrv_query($conn,$qresults);
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query($conn,$qresults,$params,$options);	
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}	
   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        $i=0;?>
       <tr><td> <?php echo $row["Cost_type"] ;?></td>
            <td><?php echo $row["descr"] ;?></td>
            <td><?php echo $row["cost"] ;?></td>            
</tr>
   <?php $i++;}
   sqlsrv_close($conn);
?>
</tbody>
</table>
</form>        
</form>
<script>
$(document).ready( function () {
    $('#table').DataTable({
        paging: true,
        "dom": 'lBfrtip',
        buttons: [
                    'copy',
                    'csv',
                    'print'
          ]
    });
} );
</script>
</body>
</html>