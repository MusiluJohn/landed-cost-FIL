<?php 
session_start();
?> 
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap2.css"/>
<link rel="stylesheet" href="css/style.css">
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
</head>
<script type="text/javascript" src="js/script.js">
</script>
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
<script src="js/bootstrap1.js"></script>
<script src="js/bootstrap2.js"></script>
<script src="js/bootstrap3.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
<style>
/* Style for positioning toast */
.toast{
    position: absolute; 
    top: 100px; 
    right: 500px;
}
</style>
<body >
<?php include 'navbar2.php' ?>
<div id='body'style='margin-top:10px;'>
<a>Below are the lines of the selected shipment with the costs based on the scheme allocated to the item:</a>
<hr style='margin-top:40px;'></hr>
<!--BUTTONS-->
<table><tr>

<form id="Form" method="POST" action="createibt.php">
<td><button type="SUBMIT" class="btn btn-success" name='close' id='close'>
<span class="glyphicon glyphicon-forward" style="margin-right:2px;"></span>PROCEED</button>
</td>
<?php
          include("config.php");
            $_SESSION['ship']=$_GET['SH'];
?>
</form>
<td><button class="btn btn-success" style="margin-left:5px;" type="submit" name="submit" onclick="del()">
<span class="glyphicon glyphicon-trash" style="margin-right:2px;"></span>DELETE</button></td></tr></table>
<hr></hr>
<a> Input all the costs applicable and exchange rate.</a>
<a> Once done click "PROCEED" to create ibt.</a>
<table style='margin-left:5px;'>
<?php include("phpfunctions.php"); ?>
    <form id="myForm" method="POST" action="">
        
        <tr>
        <td>
        <?php
        include("config.php");
        $query = $_GET['SH'];
          $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="declare @ship as varchar(100)
             set @ship=(select shipment_no from _cplshipmentmaster where id=$query) 
             select distinct isnull(rate,0) as rate from _cplShipment where cShipmentNo=@ship";    
             $stmt = sqlsrv_query($conn,$sql);     
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    echo "<a style='font-size:10px;margin-left:10px;font-weight:bold;'>Exch Rate KES: </a> <input id='rate' name='rate' class='form-control' style='width:100px;' value=" .$row["rate"]. " ></td>";
                };
        sqlsrv_close($conn);
    ?>
    </td>
        <script>
            $(document).ready(function(){
            $('#rate').change(function(){
                $.ajax({
                    url: 'calc_rate.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    
                    })
                            }); 
                        });
        </script>
        </tr>
        <tr><td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='TT_SWIFT_Charges'"; 
             $sql1="insert into #tmpcost (code,cost) values('',0)";    
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);	
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Swift Cost KES: </a><input id=swift value=" .$row["cost"]. " name='swift' class='form-control' style='width:100px;length:50px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
<!--calc swift--->
<script>
    $(document).ready(function(){
    $('#swift').change(function(){
        $.ajax({
            url: 'calc_swift.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,swift: $('#swift').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 70000
                        });
                $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script>
        <td>       
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid,code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='InsuranceAmount'";
             $sql1="insert into #tmpcost (code,cost) values('',0)";      
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='margin-left:9px;font-size:10px;'>Insurance KES: </a><input id=ins value=" .$row["cost"]. " name='ins' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <td>
<!--calc insurance--->
<script>
    $(document).ready(function(){
    $('#ins').change(function(){
        $.ajax({
            url: 'calc_ins.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,ins: $('#ins').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                location.reload();
            }
            })
                    }); 
                });
</script>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, isnull(code,'null') as code, isnull(st.cost,0) as cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='Duty'";     
             $sql1="insert into #tmpcost (code,cost) values('',0)";
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Duty KES: </a><input id=duty value=" .$row["cost"]. " name='duty' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
<!--calc duty--->
<script>
    $(document).ready(function(){
    $('#duty').change(function(){
        $.ajax({
            url: 'calc_duty.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,duty: $('#duty').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                location.reload();
            }
            })
                    }); 
                });
</script>      
      <td>       
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='COC_in_Ksh'";
             $sql1="insert into #tmpcost (code,cost) values('',0)";      
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='margin-left:9px;font-size:10px;'>Coc KES: </a><input id='coc' value=" .$row["cost"]. " name='coc' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
<!--calc coc--->
<script>
    $(document).ready(function(){
    $('#coc').change(function(){
        $.ajax({
            url: 'calc_coc.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,coc: $('#coc').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script>
<td>       
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid,code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='ConcessionFee'";
             $sql1="insert into #tmpcost (code,cost) values('',0)";      
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='margin-left:9px;font-size:10px;'>Concession KES: </a><input id='concession' value=" .$row["cost"]. " name='concession' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
<!--calc concession--->
<script>
    $(document).ready(function(){
    $('#concession').change(function(){
        $.ajax({
            url: 'calc_concession.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,concession: $('#concession').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                location.reload();
            }
            })
                    }); 
                });
</script>
<td>
<?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid,code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='ISM'";
             $sql1="insert into #tmpcost (code,cost) values('',0)";      
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='margin-left:9px;font-size:10px;'>Ism KES: </a><input id='Ism' value=" .$row["cost"]. " name='Ism' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
<!--ism--->
<script>
    $(document).ready(function(){
    $('#Ism').change(function(){
        $.ajax({
            url: 'calc_ism.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,ism: $('#Ism').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script>
<td>
<?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid,code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='WHouseStorageCharge'";
             $sql1="insert into #tmpcost (code,cost) values('',0)";      
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='margin-left:9px;font-size:10px;'>Ship Line KES: </a><input id='storage' value=" .$row["cost"]. " name='storage' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
<!--storage--->
<script>
    $(document).ready(function(){
    $('#storage').change(function(){
        $.ajax({
            url: 'calc_storage.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,storage: $('#storage').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script> 
<td>
<?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='CustomsVerification'";
             $sql1="insert into #tmpcost (code,cost) values('',0)";      
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='margin-left:9px;font-size:10px;'>Customs Ver KES: </a><input id='custver' value=" .$row["cost"]. " name='custver' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
<!--customs verification--->
<script>
    $(document).ready(function(){
    $('#custver').change(function(){
        $.ajax({
            url: 'calc_custver.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,custver: $('#custver').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script> 
<td>
<?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid,code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='BreakBulkCharges'";
             $sql1="insert into #tmpcost (code,cost) values('',0)";      
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='margin-left:9px;font-size:10px;'>Breakbulk KES: </a><input id='breakbulk' value=" .$row["cost"]. " name='breakbulk' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
<!--breakbulk--->
<script>
    $(document).ready(function(){
    $('#breakbulk').change(function(){
        $.ajax({
            url: 'calc_breakbulk.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,breakbulk: $('#breakbulk').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script>
<td>
<?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='OffloadingCharge'";
             $sql1="insert into #tmpcost (code,cost) values('',0)";      
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='margin-left:9px;font-size:10px;'>Kpa KES: </a><input id='offloading' value=" .$row["cost"]. " name='offloading' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
<!--breakbulk--->
<script>
    $(document).ready(function(){
    $('#offloading').change(function(){
        $.ajax({
            url: 'calc_offloading.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,offloading: $('#offloading').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script>  
<td style='width:100px;'></td>
<td style='width:100px;'></td>
<td style='width:100px;'>
<?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);     
			 $sql = "select distinct isnull(cmode,'') as mode from _cplShipment a 
             join _cplshipmentmaster b on a.cShipmentNo=b.shipment_no 
             where b.id=cast($query as int)";	
			 $stmt = sqlsrv_query($conn,$sql);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='margin-left:9px;font-size:10px;'>Transport Mode: </a><input id='mode' value='" .$row["mode"]. "' name='mode' class='form-control' style='width:100px;' disabled/></td>";
				};
		sqlsrv_close($conn);
	    ?>
</td>      
</tr>
        <tr><td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='FreightKsh'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo " <a style='font-size:10px;margin-left:10px;'>Freight KES: </a><input id='freight' value=" .$row["cost"]. " name='freight' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <!--calc freight--->
<script>
    $(document).ready(function(){
    $('#freight').change(function(){
        $.ajax({
            url: 'calc_fre.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,fre: $('#freight').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                window.location.reload(true);
                //document.location.reload(true);
            }
            })
                    }); 
                });
</script>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='Entry_Amendment_Charges'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Idf KES: </a><input id=entry value=" .$row["cost"]. " name='entry' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <script>
    $(document).ready(function(){
    $('#entry').change(function(){
        $.ajax({
            url: 'calc_entry.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,entry: $('#entry').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='Penalty'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Penalty KES: </a><input id='penalty' value=" .$row["cost"]. " name='penalty' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <script>
            $(document).ready(function(){
            $('#penalty').change(function(){
                $.ajax({
                    url: 'calc_pena.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,penalty: $('#penalty').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='Handling'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Handling KES: </a> <input id='handling' value=" .$row["cost"]. " name='handling' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        </td>
        <script>
            $(document).ready(function(){
            $('#handling').change(function(){
                $.ajax({
                    url: 'calc_handling.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,handling: $('#handling').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
                <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid,code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='KEBS_QualityInspection'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Kebs KES: </a> <input id='Kebs' value=" .$row["cost"]. " name='Kebs' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        </td>
        <script>
            $(document).ready(function(){
            $('#Kebs').change(function(){
                $.ajax({
                    url: 'calc_kebs.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,Kebs: $('#Kebs').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
                        
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='CustomsProcessing'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Customs Pro KES: </a> <input id='custproc' value=" .$row["cost"]. " name='custproc' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        </td>
        <script>
            $(document).ready(function(){
            $('#custproc').change(function(){
                $.ajax({
                    url: 'calc_custproc.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,custproc: $('#custproc').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
            <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='AgencyFee'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Agency KES: </a> <input id='agency' value=" .$row["cost"]. " name='agency' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        </td>
        <script>
            $(document).ready(function(){
            $('#agency').change(function(){
                $.ajax({
                    url: 'calc_agency.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,agency: $('#agency').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='Documentation_Charges'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Doc Charges KES: </a> <input id='doccharges' value=" .$row["cost"]. " name='doccharges' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        </td>
        <script>
            $(document).ready(function(){
            $('#doccharges').change(function(){
                $.ajax({
                    url: 'calc_doccharges.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,doccharges: $('#doccharges').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
<td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='Transport_Charges'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Transport KES: </a> <input id='transport' value=" .$row["cost"]. " name='transport' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        </td>
        <script>
            $(document).ready(function(){
            $('#transport').change(function(){
                $.ajax({
                    url: 'calc_transport.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,transport: $('#transport').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
        <td style='width:100px;'></td>
        <td style='width:100px;'></td>
        <td>
        </td>
        <td>
        
        <?php
        include("config.php");
        $query = $_GET['SH'];
          $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='RailwayLevy'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
             $sql2 = "Select  round(sum(cost),2)  as cost
             from #tmpcost";    
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1); 
             $stmt = sqlsrv_query($conn,$sql2);     
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    echo "<a style='font-size:10px;margin-left:10px;'>RailwayLevy KES: </a> <input id='railwaylevy' value=" .$row["cost"]. " name='railwaylevy' class='form-control' style='width:100px;'/></td>";
                };
        sqlsrv_close($conn);
        ?>
        

            <script>
            $(document).ready(function(){
            $('#railwaylevy').change(function(){
                $.ajax({
                    url: 'calc_railwaylevy.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,railwaylevy: $('#railwaylevy').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
        <td>
        <?php
        include("config.php");
        $query = $_GET['SH'];
          $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='GOK'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
             $sql2 = "Select  round(sum(cost),2)  as cost
             from #tmpcost";    
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1); 
             $stmt = sqlsrv_query($conn,$sql2);     
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    echo "<a style='font-size:10px;margin-left:10px;'>GOK KES: </a> <input id='gok' value=" .$row["cost"]. " name='gok' class='form-control' style='width:100px;'/></td>";
                };
        sqlsrv_close($conn);
        ?>
        </td>
            <script>
            $(document).ready(function(){
            $('#gok').change(function(){
                $.ajax({
                    url: 'calc_gok.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,gok: $('#gok').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
        </tr>
        <tr><td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='Surcharges'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo " <a style='font-size:10px;margin-left:10px;'>Surcharges KES: </a><input id='Surcharges' value=" .$row["cost"]. " name='Surcharges' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <!--calc surcharges--->
<script>
    $(document).ready(function(){
    $('#Surcharges').change(function(){
        $.ajax({
            url: 'calc_Surcharges.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,surcharges: $('#Surcharges').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='OtherChargesOnAWBOrSea'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Other AWB KES: </a><input id='otherawb' value= " .$row["cost"]. " name='otherawb' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <script>
    $(document).ready(function(){
    $('#otherawb').change(function(){
        $.ajax({
            url: 'calc_otherawb.php',
            type: 'post',
            data: {id: <?php echo $_GET['SH']; ?>,otherawb: $('#otherawb').val(),rate: $('#rate').val()},
            success: function(result){
                $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                window.location.reload();
            }
            })
                    }); 
                });
</script>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='EXCISE_DUTY'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Excise Duty KES: </a><input id='excise' value=" .$row["cost"]. " name='excise' class='form-control' style='width:100px;' /></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <script>
            $(document).ready(function(){
            $('#excise').change(function(){
                $.ajax({
                    url: 'calc_excise_duty.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,exciseduty: $('#excise').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='Stamps'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Stamps KES: </a> <input id='stamps' value=" .$row["cost"]. " name='stamps' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        </td>
        <script>
            $(document).ready(function(){
            $('#stamps').change(function(){
                $.ajax({
                    url: 'calc_stamps.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,stamps: $('#stamps').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
                <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='Additional_Cost'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>Adds Cost KES: </a> <input id='additionalcost' value=" .$row["cost"]. " name='additionalcost' class='form-control' style='width:100px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <script>
            $(document).ready(function(){
            $('#additionalcost').change(function(){
                $.ajax({
                    url: 'calc_additionalcost.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,additional: $('#additionalcost').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
                <td>
        <?php
        include("config.php");
        $query = $_GET['SH'];
          $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, st.cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
             where tm.id=cast($query as int) and cr.cost='VATonDuty'";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
             $sql2 = "Select  round(sum(cost),2)  as cost
             from #tmpcost";    
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1); 
             $stmt = sqlsrv_query($conn,$sql2);     
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    echo "<a style='font-size:10px;margin-left:10px;'>Vat On Duty KES: </a> <input id='vatonduty' value=" .$row["cost"]. " name='vatonduty' class='form-control' style='width:100px;'/></td>";
                };
        sqlsrv_close($conn);
        ?>
        <script>
            $(document).ready(function(){
            $('#vatonduty').change(function(){
                $.ajax({
                    url: 'calc_vatonduty.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,vatonduty: $('#vatonduty').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
        <td style='width:100px;'>
        <a style='font-size:10px;margin-left:10px;'>Calc Customs Val: </a>
        <select id='calc_vh' name='calc_vh' class="form-select" style="height:35px;">
            <option value='' selected disabled hidden>select</option>
            <option value='yes'>yes</option>
            <option value='no'>no</option>
        </select>
        </td>
        <script>
            $(document).ready(function(){
            $('#calc_vh').change(function(){
                $.ajax({
                    url: 'calc_customsval.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,customs: $('#calc_vh').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
        <hr></hr>
                    </form>
    <td>
    <?php
        include("config.php");
        $query = $_GET['SH'];
          $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="declare @ship as varchar(100)
             set @ship=(select shipment_no from _cplshipmentmaster where id=$query) 
             select isnull(Vat,0) as Vat from _cplShipment where cShipmentNo=@ship";    
             $stmt = sqlsrv_query($conn,$sql);     
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    echo "<a style='font-size:10px;margin-left:10px;'>Vat KES: </a> <input id='Vat' name='Vat' class='form-control' style='width:100px;' value=" .$row["Vat"]. " ></td>";
                };
        sqlsrv_close($conn);
    ?>
    <script>
            $(document).ready(function(){
            $('#Vat').change(function(){
                $.ajax({
                    url: 'calc_vat.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,vat: $('#Vat').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>
        <a style='font-size:10px;margin-left:10px;'>Calc VOH: </a>
        <select id='calc_voh' name='calc_voh' class="form-select" style="height:35px;">
            <option value='' selected disabled hidden>select</option>
            <option value='yes'>yes</option>
            <option value='no'>no</option>
        </select>
    </td>
        <script>
            $(document).ready(function(){
            $('#calc_voh').change(function(){
                $.ajax({
                    url: 'calc_vatonhandling.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,vatonhandling: $('#calc_voh').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
    <td>
    <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct invoicelineid, code, vatonhandling into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int)";    
			 $sql2 = "Select  isnull(round(sum(vatonhandling),2),0)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a style='font-size:10px;margin-left:10px;'>VatOnHandlingKES: </a> <input id='vatonhandling' value=" .$row["cost"]. " name='vatonhandling' class='form-control' style='width:100px;' disabled /></td>";
				};
		sqlsrv_close($conn);
	    ?>    
    </td>
            <script>
            $(document).ready(function(){
            $('#vatonhandling').change(function(){
                $.ajax({
                    url: 'calc_vatonhandling.php',
                    type: 'post',
                    data: {id: <?php echo $_GET['SH']; ?>,vatonhandling: $('#vatonhandling').val(),rate: $('#rate').val()},
                    success: function(result){
                        $('.toast').toast({
                            animation: false,
                            delay: 50000
                        });
                        $('.toast').toast('show');
                        window.location.reload();
                    }
                    })
                            }); 
                        });
        </script>
    </table>
    <div class="toast" id="myToast" >
    <div class="toast-body" style='background-color:green;'>
          <a style='color:white; font-weight:bold;font-size:17px;'>The Cost Has Been Successfully Calculated.</a> 
    </div>
    </div>
<!--GRID LINES--->
<div class="row" style="overflow:scroll;margin-top:10px;">
<div id="table" style="margin-left:20px;" >
<table id="cost_table" class="table table-sm table-bordered table-striped table-hover" style='font-size:10px;'>
        <thead >
            <tr>
                <th hidden>id</th>
                <th>id</th>
                <th>Code</th>
				<th>Description</th>
                <th hidden>idlines</th>
                <th>Grv Number</th>
                <th>Duty %</th>
				<th>Qty</th>
				<th>Unit Amt </th>
                <th>Total Amt Foreign</th>
                <th>Total Amt Kes</th>
                <th>Unit Weight</th>
                <th>Total Weight</th>	
                <th>Rate KES</th>
                <th>Swift Charges KES</th>
                <th>Insurance KES</th>
                <th>Duty KES</th>
                <th>Railway Levy KES</th>
                <th>Gok KES</th>
                <th>Customs Value KES</th>
                <th>Freight For</th>
                <th>Freight KES</th>
                <th>Entry Ammendment KES</th>
                <th>Penalty KES</th>
                <th>Handling KES</th>
                <th>Kebs KES</th>
                <th>Ism KES</th>
                <th>Shipping Line KES</th>
                <th>Customs Processing KES</th>
                <th>Customs Verification KES</th>
                <th>Agency Fee KES</th>
                <th>Documentation Charges KES</th>
                <th>Breakbulk KES</th>
                <th>KPA KES</th>
                <th>Transport KES</th>
                <th>Coc KES</th>
                <th>Concession KES</th>
                <th>Surcharges KES</th>
                <th>Factor KES</th>
                <th>Actual Factor KES</th>
                <th>Other Charges KES</th>
                <th>Excise Duty KES</th>
                <th>Stamps KES</th>
                <th>Marine Cost KES</th>
                <th>Additional Cost KES</th>
                <th>Vat On Duty KES</th>
                <th>Landed Cost KES</th>
                <th>Totals + Fob KES</th>
                <th>Unit Cost + Fob KES</th>
                <th>Scheme</th>
                <th>Status</th>	
                <th><input type='checkbox' id="selectall" onclick='selectallship()'/></th>		
            </tr>
        </thead>
        <tbody>
		<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		  $query = $_GET['SH'];
         $results = array('error' => false, 'data' => '');
         $rate="--get rate
         IF OBJECT_ID('tempdb..#tmprate') IS NOT NULL DROP TABLE #tmprate
         select max(dratedate) as date, max(fSellRate) as rate 
         into #tmprate
         from currencyhist ct join vendor vr on ct.icurrencyid=vr.iCurrencyID 
         join _cplshipmentlines on vr.dclink=_cplshipmentlines.clientid 
         join _cplshipmentmaster on _cplshipmentlines.shipment_no=
         _cplshipmentmaster.shipment_no  
         where _cplshipmentmaster.id=$query and _cplshipmentlines.clientid is not null";
         $updaterate="update _cplshipmentlines set rate=(select rate from #tmprate) from _cplshipmentlines
         join _cplshipmentmaster on _cplshipmentlines.shipment_no=
         _cplshipmentmaster.shipment_no where _cplshipmentmaster.id=$query and _cplshipmentlines.updated is NULL";
         $updateunitkes="update _cplshipmentlines set unit_amount_kes=round(totals/qty,2) from _cplshipmentlines
         join _cplshipmentmaster on _cplshipmentlines.shipment_no=
         _cplshipmentmaster.shipment_no where _cplshipmentmaster.id=$query ";
         $updatetotkes="update _cplshipmentlines set tot_amount_kes=round(tot_amount*rate,2) from _cplshipmentlines
         join _cplshipmentmaster on _cplshipmentlines.shipment_no=
         _cplshipmentmaster.shipment_no where _cplshipmentmaster.id=$query";

         $sql = "IF OBJECT_ID('tempdb..#shipment') IS NOT NULL DROP TABLE #shipment
         select invoicelineid as invoicelineid,max(po_no) as Po_No,max(cs.id) as id, (shipment_no), code, max(description) as description, max(qty) as qty, max(amount) as amount,max(tot_amount_kes) as tot_amount_kes,max([volume]) as [volume],
                      max([weight]) as [weight],max(cs.rate) as rate,case when cr.cost='TT_SWIFT_Charges' then max(cs.cost) else 0 end as swift, case when cr.cost='InsuranceAmount' then max(cs.cost) else 0 end as insurance,
                      max(factor) as factor,
                      case when cr.cost='Duty' then max(cs.cost) else 0 end as duty,
                      case when cr.cost='RailwayLevy' then max(cs.cost) else 0 end as railway,
                      case when cr.cost='GOK' then max(cs.cost) else 0 end as gok,
                      max(freight_for) as freight_for,
                      max(customs_value) as customs_value,
                      max(correctfactor) as actualfactor,
                      case when cr.cost='VATonDuty' then max(cs.cost) else 0 end as vat,
                      max(grv_no) as grv_no,
                      case when cr.cost='FreightKsh' then max(cs.cost) else 0 end as freight_ksh,
                      case when cr.cost='Entry_Amendment_Charges' then max(cs.cost) else 0 end as entry,
                      case when cr.cost='Penalty' then max(cs.cost) else 0 end as penalty,
                      case when cr.cost='Handling' then max(cs.cost) else 0 end as handling,
                      case when cr.cost='KEBS_QualityInspection' then max(cs.cost) else 0 end as kebs,
                      case when cr.cost='ISM' then max(cs.cost) else 0 end as ism,
                      case when cr.cost='WHouseStorageCharge' then max(cs.cost) else 0 end as storage,
                      case when cr.cost='CustomsProcessing' then max(cs.cost) else 0 end as customsproc,
                      case when cr.cost='CustomsVerification' then max(cs.cost) else 0 end as customsver,
                      case when cr.cost='AgencyFee' then max(cs.cost) else 0 end as agencyfee,
                      case when cr.cost='Documentation_Charges' then max(cs.cost) else 0 end as doccharges,
                      case when cr.cost='BreakBulkCharges' then max(cs.cost) else 0 end as breakbulk,
                      case when cr.cost='OffloadingCharge' then max(cs.cost) else 0 end as offloading,
                      case when cr.cost='Transport_Charges' then max(cs.cost) else 0 end as transport,
                      case when cr.cost='COC_in_Ksh' then max(cs.cost) else 0 end as coc_ksh,
                      case when cr.cost='ConcessionFee' then max(cs.cost) else 0 end as concession,
                      case when cr.cost='Surcharges' then max(cs.cost) else 0 end as surcharges,
                      case when cr.cost='OtherChargesOnAWBOrSea' then max(cs.cost) else 0 end as othercharges,
                      case when cr.cost='EXCISE_DUTY' then max(cs.cost) else 0 end as exciseduty,
                      case when cr.cost='Stamps' then max(cs.cost) else 0 end as stamps,
                      case when cr.cost='Additional_Cost' then max(cs.cost) else 0 end as addcost,
                      case when cr.cost='MarineCost' then max(cs.cost) else 0 end as disbcost,
                      case when cr.cost='Duty' then max(ce.rate) else 0 end as dutyperc,
                      max(totals) as Totals, max(cs.scheme) as scheme,
                      max(cast(Calc_Duty as int)) as calcduty,max(tot_amount) as tot_amount,max(unit_amount_kes) as unit_amount_kes, max(unit_weight) as unit_weight,
                      case when active='True' then 'Open' else 'Closed' end as active
         into #shipment
         from _cplshipmentlines cs join _cplcostmaster cr on cs.costcode=cr.id
         join _cplscheme ce on cs.scheme=ce.Scheme and cs.costcode=ce.Cost_Code
         group by invoicelineid,code,shipment_no,cr.cost,active  order by shipment_no";
         $sql4="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
                select invoicelineid,code,stkcode,round(sum(Cost)+(max(amount)*max(qty)*max(rate)),2) as totcost 
                into #totals
                from _cplshipmentlines ts
                join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
                where tr.id=$query
                group by invoicelineid,code,stkcode";
            $sql1="select ROW_NUMBER() OVER( ORDER BY invoicelineid ASC) AS Row#,invoicelineid as invoicelineid,max(dutyperc) as dutyperc,(case when max(calcduty)=1 then 'True' else 'False' end) as calcduty,max(factor) as factor,max(st.id) as id,isnull(max(code),'TOTAL') as code,(case when code is null then '' else max(po_no) end) as po_no,(case when code is null then '' else max(st.shipment_no)  end) as shipment_no, (case when code is null then '' else max(description) end) as description, (case when code is null then '' when max(qty)=0 then '' else max(qty) end) as qty, (case when code is null then '' else max(amount) end) as amount,(case when code is null then '' else max(tot_amount_kes) end) as tot_amount_kes,(case when code is null then '' else sum([volume]) end) as [volume],
            (case when code is null then ''  when max([unit_weight])=0 then '' else max([unit_weight]) end) as [unit_weight],(case when code is null then ''  when max([weight])=0 then '' else max([weight]) end) as [weight], case when code is null then '' else round(max(rate),2) end as rate, round(sum(swift),2) as Swift,round(sum(insurance),2) as Insurance,round(sum(duty),2)  as Duty, round(sum(railway),2)  as Railway_Levy, round(sum(gok),2)  as GOK, round(sum(freight_for),2)  as Freight_For, round(sum(freight_ksh),2)  as Freight_Kshs, round(sum(entry),2)  as Entry, round(sum(penalty),2) as penalty, round(sum(handling),2)
            as handling,(max(grv_no)) as grv_no,round(max(vat),2) as vat, round(max(actualfactor),2) as actualfactor,round(max(customs_value),2)  as customs_value,round(sum(kebs),2)  as kebs,round(sum(ism),2)  as ism,round(sum(storage),2)  as storage,round(sum(customsproc),2)  as customsproc, round(sum(customsver),2)  as customsver, round(sum(agencyfee),2)  as agencyfee,
            round(sum(doccharges),2)  as doccharges,round(sum(breakbulk),2)  as breakbulk,round(sum(offloading),2)  as offloading,round(sum(transport),2)  as transport,round(sum(coc_ksh),2)  as coc_ksh,round(sum(concession),2)  as concession,
            round(sum(surcharges),2)  as surcharges,round(sum(disbcost),2)  as disbcost,round(sum(factor),2)  as factor,round(sum(othercharges),2)  as othercharges,round(sum(exciseduty),2)  as exciseduty,round(sum(stamps),2)  as stamps,round(sum(addcost),2)  as addcost,(case when code is null then (select sum(totcost) from #totals) else max(totals) end) as Totals,max(unit_amount_kes) as unit_amount_kes,(case when code is null then '' else max(scheme) end) as scheme,  case when code is null then '' else max(active) end as active,max(tot_amount) as tot_amount from #shipment st join _cplshipmentmaster tm
            on st.shipment_no=tm.shipment_no
            where tm.id=cast($query as int)
            group by invoicelineid,code";
       
			 $params = array();
             $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            //  sqlsrv_query($conn,$marinecost,$params,$options) or die(print_r( sqlsrv_errors(), true));		
             sqlsrv_query($conn,$rate,$params,$options) or die(print_r( sqlsrv_errors(), true));
             //sqlsrv_query($conn,$updaterate,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$updateunitkes,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$updatetotkes,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$sql,$params,$options) or die(print_r( sqlsrv_errors(), true));
             //sqlsrv_query($conn,$railway,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$sql4,$params,$options) or die(print_r( sqlsrv_errors(), true));	
             $stmt = sqlsrv_query($conn,$sql1,$params,$options) or die(print_r( sqlsrv_errors(), true));		
			 if( $stmt === false) {
					die( print_r( sqlsrv_errors(), true) );
				}
                $row_count = sqlsrv_num_rows($stmt);
				if ($row_count > 0) {
                $rows=0;
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<tr><form method='post' action='delshipline.php' >"; ?>
                    <td hidden><input class='form-control' style='width:100px;' type='number' id='shipid<?php echo $rows;?>' name='shipid<?php echo $rows;?>' value="<?php echo $row["id"] ;?>" disabled></td>
                    <td> <?php echo $row["Row#"] ;?></td>
                    <td> <input disabled style='width:90px;height:12.5px;' class='form-control' id='code<?php echo $rows;?>' name='code<?php echo $rows;?>' value="<?php echo $row["code"] ;?>" /></td>
					<td> <?php echo $row["description"] ;?></td>
                    <td hidden> <input class='form-control' style='width:100px;' type='number' id='id<?php echo $rows;?>' name='id<?php echo $rows;?>' value="<?php echo $row["invoicelineid"] ;?>" disabled></td>
                    <td><?php echo $row["grv_no"]; ?></td>
                    <td><?php echo $row["dutyperc"]; ?></td>
                    <!-- <script>
                    $(document).on('change','#calcduty<?php echo $rows;?>', function(){           
                                        $.ajax({
                                                url: 'calc_calcduty.php',
                                                type: 'POST',
                                                data: {id: $('#shipid<?php echo $rows;?>').val(),
                                                calcduty: $('#calcduty<?php echo $rows;?>').val()
                                                },
                                                success: function(){
                                                    $('.toast').toast({
                                                        animation: false,
                                                        delay: 50000
                                                    });
                                                    $('.toast').toast('show');
                                                    window.location.reload();
                                                }
                                                })
                                        });
                    </script> -->
					<td id='qtys'> <?php echo $row["qty"] ;?></td>
                    <td id='fob'> <?php echo $row["amount"] ;?></td>		
                    <td id='totamt'> <?php echo $row["tot_amount"] ;?></td>	
                    <td id='totamtkes'> <?php echo $row["tot_amount_kes"] ;?></td>	
                    <td id='unweight'> <?php echo $row["unit_weight"] ;?></td>
                    <td id='totweight'> <?php echo $row["weight"] ;?></td>
                    <td> <?php echo $row["rate"] ;?></td>
                    <td id='swifts'> <?php echo $row["Swift"] ;?></td>
                    <td id='insu'> <?php echo $row["Insurance"] ;?></td>
                    <td><a hidden id='no'><?php echo $row["Duty"] ;?></a> <input type="number" style='width:90px;height:15px;border:none;border-bottom:2px solid grey;opacity:1;'  id='duty<?php echo $rows;?>' name='duty<?php echo $rows;?>' value="<?php echo $row["Duty"] ;?>" /></td>
                    <script>
						$(document).ready(function(){
						$('#duty<?php echo $rows;?>').change(function(){
							$.ajax({
								url: 'chduty.php',
								type: 'post',
								data: {code: $('#code<?php echo $rows;?>').val(),duty: $('#duty<?php echo $rows;?>').val(),
                                id:<?php echo $_GET['SH']; ?>},
								success: function(result){
								alert('updated');
								window.location.reload();
								}
								
								})
										}); 
									});
        			</script>
                    <td id='raillevy'> <?php echo $row["Railway_Levy"] ;?></td>
                    <td id='goktot'> <?php echo $row["GOK"] ;?></td>
                    <td id='custs'> <?php echo $row["customs_value"] ;?></td>	
                    <td> <?php echo $row["Freight_For"] ;?></td>	
                    <td id='frekes'> <?php echo $row["Freight_Kshs"] ;?></td>
                    <td id='entrykes'> <?php echo $row["Entry"] ;?></td>	
                    <td id='penkes'> <?php echo $row["penalty"] ;?></td>	
                    <td id='handlingkes'> <?php echo $row["handling"] ;?></td>
                    <td id='kebskes'> <?php echo $row["kebs"] ;?></td>
                    <td id='ismkes'> <?php echo $row["ism"] ;?></td>
                    <td id='storagekes'> <?php echo $row["storage"] ;?></td>
                    <td id='custprockes'> <?php echo $row["customsproc"] ;?></td>
                    <td id='custverkes'> <?php echo $row["customsver"] ;?></td>
                    <td id='agencykes'> <?php echo $row["agencyfee"] ;?></td>
                    <td id='docchkes'> <?php echo $row["doccharges"] ;?></td>
                    <td id='bbkes'> <?php echo $row["breakbulk"] ;?></td>
                    <td id='offkes'> <?php echo $row["offloading"] ;?></td>
                    <td id='transkes'> <?php echo $row["transport"] ;?></td>
                    <td id='cockes'> <?php echo $row["coc_ksh"] ;?></td>
                    <td id='conckes'> <?php echo $row["concession"] ;?></td>
                    <td id='surkes'> <?php echo $row["surcharges"] ;?></td>
                    <td> <?php echo $row["factor"] ;?></td>
                    <td> <?php echo $row["actualfactor"] ;?></td>
                    <td id='otherkes'> <?php echo $row["othercharges"] ;?></td>
                    <td id='excisekes'> <?php echo $row["exciseduty"] ;?></td>
                    <td id='stampskes'> <?php echo $row["stamps"] ;?></td>
                    <td id='disbkes'> <?php echo $row["disbcost"];?></td>
                    <td id='addkes'> <?php echo $row["addcost"] ;?></td>
                    <td id='vatkes'> <?php echo $row["vat"] ;?></td>
                    <td id='landkes'> <?php echo $row["Insurance"] + $row["Duty"]+$row["Railway_Levy"]+$row["GOK"]+$row["Freight_Kshs"]+$row["coc_ksh"]+$row["concession"]+$row["surcharges"]+
                    $row["stamps"]+$row["exciseduty"] +$row["addcost"]+$row["agencyfee"]+$row["Entry"]+$row["penalty"]+$row["handling"]+ $row["kebs"]+$row["ism"]
                    +$row["storage"]+$row["customsproc"]+$row["customsver"]+$row["doccharges"]+$row["breakbulk"]+$row["offloading"]+$row["transport"]+$row["othercharges"]+$row["disbcost"]  ;?></td>
                    <td id='totkes'> <?php echo $row["Totals"] ;?></td>
                    <td id='unitkes'> <?php echo $row["unit_amount_kes"] ;?></td>
                    <td> <?php echo $row["scheme"] ;?></td>	
                    <td> <?php echo $row["active"] ;?></td>	
                    <td><input type="checkbox" name="lines[]" value="<?php echo $row["id"] ;?>" /></td>	
					</tr>
					
				<?php $rows++;}}
		sqlsrv_close($conn);
	    ?>
        <?php
        
        ?>
		<tfoot>
        <?php include("jquery.php"); ?>
        <tr>
                    <th hidden> </th>
                    <th> </th>
                    <th>TOTALS</th>
					<th> </th>
                    <th hidden></th>
                    <th> </th>
                    <th> </th>
					<th id='totalqty'> <label id="qty"></a></th>
                    <th id='totalamt'> <label id="amount"></a></th>		
                    <th id='totamount'> <label id="totamount"></a></th>
                    <th id='totamountkes'> <label id="totamountkes"></a></th>
                    <th id='unitw'> <label id="weight"></a></th>
                    <th id='tweight'> <label id="tot_weight"></a></th>
                    <th> <label id="tot_tot_weight"></a> </th>
                    <th id='swiftc'> <label id="swifttot"></a> </th>
                    <th id='insuto'> <label id="insurance"></a></th>
                    <th id='dutyto'> <label id="dutytot"></a></th>
                    <th id='rail'> <label id="railwaytot"></a></th>
                    <th id='goktots'> <label id="goktotsi"></a></th>
                    <th id='custots'> <label id="customstot"></a></th>		
                    <th> <label id="freight_for"></a></th>	
                    <th id='frekestot'> <label id="freight_ksh"></a></th>
                    <th id='entrykestot'> <label id="entrytot"></a></th>	
                    <th id='penkestot'> <label id="penaltytot"></a></th>	
                    <th id='handlingkestot'> <label id="handlingtot"></a></th>
                    <th id='kebskestot'> <label id="kebstot"></a></th>
                    <th id='ismkestot'> <label id="ismtot"></a></th>
                    <th id='storagekestot'> <label id="storagetot"></a></th>
                    <th id='custprockestot'> <label id="customsproc"></a></th>
                    <th id='custverkestot'> <label id="customsver"></a></th>
                    <th id='agencykestot'> <label id="agencyfeetot"></a></th>
                    <th id='docchkestot'> <label id="docchargestot"></a></th>
                    <th id='bbkestot'> <label id="breakbulktot"></a></th>
                    <th id='offkestot'> <label id="offloadingtot"></a></th>
                    <th id='transkestot'> <label id="transporttot"></a></th>
                    <th id='cockestot'> <label id="coc_ksh"></a></th>
                    <th id='conckestot'> <label id="concessiontot"></a></th>
                    <th id='surkestot'> <label id="surchargestot"></a></th>
                    <th> <label id="factor"></a></th>
                    <th> <label id="actualfactortot"></a></th>
                    <th id='otherkestot'> <label id="otherchargestot"></a></th>
                    <th id='excisekestot'> <label id="excisedutytot"></a></th>
                    <th id='stampskestot'> <label id="stampstot"></a></th>
                    <th id='disbkestot'> <label id="disbcosttot"></a></th>    
                    <th id='addkestot'> <label id="addcosttot"></a></th>
                    <th id='vatkestot'> <label id="vattotals"></a></th>
                    <th id='landkestot'> <label id="totalstot"></a></th>
                    <th id='totkestot'> <label id="totalcosttot"></a></th>
                    <th id='unitkestot'> <label id="unittotals"></a></th>
                    <th> </th>	
                    <th> </th>	
                    <th> </th>
                    </tr>
        </tfoot>
        </tbody>
    </table>
    <script>
    $(document).ready( function () {
        $('#cost_table').DataTable({
            paging: true
        });
    } );
    </script>
    <hr></hr>
    <hr></hr>
    </form>
    </div>
	</div>
            <div id="cst">
</div>
	<a id="link" href="CostEstHome.php"><<<<<< Go back to home page</a>
</div>
</body>

</html>