<?php
session_start();
include("config.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
<link rel="stylesheet" href="css/style.css">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
<script src="js/bootstrap1.js"></script>
<script src="js/bootstrap2.js"></script>
<script src="js/bootstrap3.js"></script>
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<body>
<?php include 'navbar2.php' ?>
<div style='margin-top:70px;'>
<a>Select Customer Batch To Post To:</a>
<?php
            include("config.php");
            $conn = sqlsrv_connect( $servername, $connectioninfo);
            
                $sql = "select idARAPBatches, cbatchdesc from _etblarapbatches where iDCModule=1";	
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
<table id="table" class="table table-bordered table-striped table-hover" style='font-size:10px;margin-top:10px;'>
    <thead >
        <tr>
            <th>Cost Type</th>
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
    $id=$_SESSION['ship'];
    $results = array('error' => false, 'data' => '');
    $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
    select distinct  case when costcode=1 then 'Swift Charge' when costcode=2 then 'Insurance Fee' when costcode=3 then 'Duty Fee' 
        when costcode=4 then 'Railway Fee' when costcode=5 then 'Gok Fee' when costcode=7 then 'Freight Fee'  
        when costcode=8 then 'Entry Fee' when costcode=9 then 'Penalty Fee' when costcode=1002 then 'Handling Fee' 
        when costcode=1003 then 'Kebs Fee' when costcode=1004 then 'Ism Fee' when costcode=1005 then 'Warehouse Storage Fee'
        when costcode=1006 then 'Customs Processing Fee' when costcode=1007 then 'Customs Verification Fee'
        when costcode=1008 then 'Agency Fee' when costcode=1009 then 'Documentation Fee'
        when costcode=1010 then 'Breakbulk Fee' when costcode=1011 then 'Offloading Fee'
        when costcode=1012 then 'Transport Fee' when costcode=1013 then 'Coc Fee'
        when costcode=1014 then 'Concession Fee' when costcode=1015 then 'Surcharges Fee'
        when costcode=1020 then 'Vat Fee' when costcode=1021 then 'Other Charges Fee'
        when costcode=1022 then 'Excise Duty Fee' when costcode=1023 then 'Stamps Fee'
        when costcode=1024 then 'Additional Fee' when costcode=1025 then 'Disbursements Fee'
        end as Cost_type,sum(cost) as cost
    from _cplshipmentlines st join _cplshipmentmaster tm
    on st.shipment_no=tm.shipment_no
    where tm.id=$id
    group by costcode
    having sum(cost)>0";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query($conn3,$sql,$params,$options);	
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}	
   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        $i=0;?>
       <tr><td> <?php echo $row["Cost_type"] ;?></td>
            <td> <input id='cost' name='cost[]' value=<?php echo $row["cost"] ;?> type='number' style='form-control'/></td>
            <td> <input id='rate' name='rate[]' value=0 type='number' style='form-control'/></td>
            <td> <input id='ref' name='ref[]' placeholder='Enter Reference' type='text' style='form-control'/></td>
            <td> <input id='description' name='description[]' placeholder='Enter Description' type='text' style='form-control'/></td>
            <td>
            <?php  
                include("config.php");
                $conn2 = sqlsrv_connect( $servername, $connectioninfo);
                $sql2 = "Select distinct dclink,name from vendor";	
                $stmt2 = sqlsrv_query($conn2,$sql2);
                if ($stmt2) {			 
                echo "<select id='supplier' name='supplier[]' class='form-select' style='width:250px;height:27px;'>";
                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {				
                        echo "<option  value=" .$row2["dclink"]. "> " .$row2["name"]. "</option>";
                    }   
                echo"</select>";
                }
            ?>
            <script>
                $('.select2').select2();
            </script>
            </td>
            
</tr>
   <?php $i++;}
   sqlsrv_close($conn2);
?>
</tbody>
</table>
<button id="create" name="create" class='btn btn-success' style='margin-top:10px;'> <span class="glyphicon glyphicon-floppy-save"></span>CREATE SUPPLIER BATCH</button>
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
                $.ajax({
                    url: 'create_batch.php',
                    type: 'post',
                    data: {cost:cost,supplier:supplier,rate:rate,ref:ref,description:description,
                    batch:$("#batch").val()},
                    success: function(result){
                            alert('SUPPLIER BATCH SUCCESSFULLY POSTED IN SAGE');
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