<?php
session_start();
include("config.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap2.css"/>
<link rel="stylesheet" href="css/style.css">
<script src="js/script.js"></script>
<!-- <script src="js/bootstrap1.js"></script> -->
<!-- <script src="js/bootstrap2.js"></script> -->
<!-- <script src="js/bootstrap3.js"></script> -->
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<body>
<?php include 'navbar2.php' ?>
<div style='margin-top:70px;'>
<table>
<tr style='width:200px;'>
<form method="post" action="">
<td><a>Select shipment to create ibt:</a>
<?php
		
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		
			 $sql = "select distinct _cplshipmentmaster.id as id, _cplshipmentmaster.shipment_no
			 from _cplshipmentmaster join _cplshipmentlines on _cplshipmentmaster.shipment_no=_cplshipmentlines.shipment_no
			 where isnull(active,'True')='True' and isnull(updated,'False') in ('False','True') ";	
			// $params = array();
			// $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql);
			if ($stmt) {
			 echo"<select id='SH' name='SH' class='form-control select2' style='width:150px;'>";
			 //echo "<option  value=" .$row["id"]. "> " .$row["shipment_no"]. "</option>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<option  value=" .$row["id"]. "> " .$row["shipment_no"]. "</option>";
				}
			echo"</select>";
			}
		sqlsrv_close($conn);
	    ?>
<script>
    $('.select2').select2();
</script>
<button class="btn btn-success" type="submit" ><span class="glyphicon glyphicon-search" style="margin-right:2px;"></span>SEARCH</button>
</td>
</tr>
</table>
<hr>
<br>
<a>Select Customer Batch To Post To:</a>
<?php
            include("config.php");
            $conn = sqlsrv_connect( $servername, $connectioninfo);
            
                $sql = "select idARAPBatches, cbatchdesc from _etblarapbatches where cBatchDesc='Accounts Payable Batch'";	
                $stmt = sqlsrv_query($conn,$sql);
                if ($stmt) {			 
                echo "<select id='batch' name='batch' class='form-control select2' style='width:200px;height:30px;'>";
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {				
                        echo "<option  value=" .$row["idARAPBatches"]. "> " .$row["cbatchdesc"]. "</option>";
                    }   
                echo"</select>";
                }
            sqlsrv_close($conn);
            ?>
<script>
    $('.select2').select2();
</script>
<div id="container">
<table id="table" class="table table-bordered table-striped table-hover" style='font-size:10px;margin-top:10px;'>
    <thead >
        <tr>
            <th>Cost Type</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Rate</th>
            <th>Reference</th>
            <th>Description</th>
            <th>Supplier</th>						
        </tr>
    </thead>
    <tbody>
    <?php
    include("config.php");
    $conn3 = sqlsrv_connect( $servername, $connectioninfo);
    $id=$_POST['SH'] ?? 0;
    $results = array('error' => false, 'data' => '');
    $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
    select distinct  
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
    end as Cost_type,round(sum(st.cost),2) as cost, (max(tm.shipment_no) +'-'+ format(getdate(),'yyyy-MM-dd')) as descr,
    max(st.rate) as rate
    into #tmpcost
    from _cplshipmentlines st join _cplshipmentmaster tm
    on st.shipment_no=tm.shipment_no
    join _cplcostmaster cr on st.costcode=cr.id
    where tm.id=$id
    group by cr.cost
    having sum(st.Cost)>0
    
    declare @ship as varchar(100)
    set @ship=(select shipment_no from _cplshipmentmaster where id=$id) 
    
    insert into #tmpcost (Cost_type, cost, descr,rate) select 'VAT', Vat, ((cShipmentNo) +'-'+ format(getdate(),'yyyy-MM-dd')),1 
    from _cplShipment where cShipmentNo=@ship and isnull(vat,0)<>0";
    
$sql2="select Cost_type, cost,descr,rate from #tmpcost";
sqlsrv_query($conn3,$sql);
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query($conn3,$sql2,$params,$options);	
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}	
   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        $i=0;?>
       <tr><td> <?php echo $row["Cost_type"] ;?></td>
            <td> <input id="date" name='date[]' type='date' style="border:none;width:105px;border-bottom:1px solid rgb(0, 0, 0);" /></td>
            <td> <input id="cost_supp" name='cost[]' value=<?php echo $row["cost"] ;?> type='number' style="border:none;width:80px;border-bottom:1px solid rgb(0, 0, 0);" disabled/></td>
            <td> <input id="rate_supp" name='rate[]' value=<?php echo $row["rate"] ;?> type='number' style="border:none;width:80px;border-bottom:1px solid rgb(0, 0, 0);" /></td>
            <td> <input id='ref' name='ref[]' placeholder='Enter Reference' type='text' style="border:none;width:180px;border-bottom:1px solid rgb(0, 0, 0);" /></td>
            <td> <input id='description' name='description[]' value='<?php echo $row["descr"] ?>' type='text' style="border:none;width:220px;border-bottom:1px solid rgb(0, 0, 0);" /></td>
            <td>
            <?php  
                include("config.php");
                $conn2 = sqlsrv_connect( $servername, $connectioninfo);
                $sql2 = "Select distinct dclink,name from vendor";	
                $stmt2 = sqlsrv_query($conn,$sql2);
                if ($stmt2) {		 
                ?> <select id='supplier' name='supplier[]' class='form-select' style='width:250px;height:27px;'><?php
                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {				
                        echo "<option  value=" .$row2["dclink"]. "> " .$row2["name"]. "</option>";
                    }   
                echo"</select><script>$('.select2').select2();</script>";
                }
                
            ?>
            
            </td>
            
</tr>
   <?php $i++;}
   sqlsrv_close($conn);
?>
</tbody>
</table>
</form>
<button id="create" name="create" class='btn btn-success' style='margin-top:10px;'> <span class="glyphicon glyphicon-floppy-save"></span>CREATE SUPPLIER BATCH</button>
</div>
<script>
            $(document).ready(function(){
            $("#create").click(function(){
                var cost=[];
                $('input[name^="cost"]').each(function() {
                    cost.push(this.value);
                });
                var supplier=[];
                $('select[name^="supplier"]').each(function() {
                    supplier.push(this.value);
                });
                var rate=[];
                $('input[name^="rate"]').each(function() {
                    rate.push(this.value);
                });
                var ref=[];
                $('input[name^="ref"]').each(function() {
                    ref.push(this.value);
                });
                var description=[];
                $('input[name^="description"]').each(function() {
                    description.push(this.value);
                });
                var dates=[];
                $('input[name^="date"]').each(function() {
                    dates.push(this.value);
                });
                $.ajax({
                    url: 'create_batch.php',
                    type: 'post',
                    data: {cost:cost,supplier:supplier,rate:rate,ref:ref,description:description,dates:dates,
                    batch:$("#batch").val(),id:$("#SH").val()},
                    success: function(result){
                            alert(result);
                        },
                    error: function(result) {
                            alert('ERROR');
                    }
                            }); 
                        });
             });
        </script>
</body>
</html>