<html>
<?php include ("config.php") ?>
<title>
</title>
<link rel="stylesheet" type="text/css" href="bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="bootstrap2.css"/>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="bootstrap1.js"></script>
<script src="bootstrap2.js"></script>
<script src="bootstrap3.js"></script>
<script src="jquery1.js"></script>
<script src="jquery2.js"></script>
<form method="GET" action=''>

</form>
</br>
<body onload='costTable()'>
<ul><a>Below are the lines of the selected shipment with the costs based on the scheme allocated to the item:</a></ul>
<hr></hr>
<div id="table" >
<div>
        <strong id="text">Check to hide column</strong>
        <br>
        <input type="checkbox" class="hidecol" value="qty" id="col_5" />&nbsp;<a class="text">Qty</a>&nbsp;
        <input type="checkbox" class="hidecol" value="Volume" id="col_10" />&nbsp;<a class="text">Volume</a>
        <input type="checkbox" class="hidecol" value="Weight" id="col_11" />&nbsp;<a class="text">Weight</a>
        <input type="checkbox" class="hidecol" value="Dry_Ice" id="col_15" />&nbsp;<a class="text">Dry Ice</a>
        <input type="checkbox" class="hidecol" value="Import_duty" id="col_16" />&nbsp;<a class="text">Import Duty</a>
        <input type="checkbox" class="hidecol" value="D_goods" id="col_17" />&nbsp;<a class="text">Dangerous Goods</a>
        <input type="checkbox" class="hidecol" value="PPB" id="col_18" />&nbsp;<a class="text">PPB</a>
        <input type="checkbox" class="hidecol" value="IDF" id="col_19" />&nbsp;<a class="text">IDF</a>
        <input type="checkbox" class="hidecol" value="KRDL" id="col_20" />&nbsp;<a class="text">KRDL</a>
        <input type="checkbox" class="hidecol" value="EDuty" id="col_21" />&nbsp;<a class="text">Excise Duty</a>
        <input type="checkbox" class="hidecol" value="Hcharge" id="col_22" />&nbsp;<a class="text">Harzadous_charge</a>
    </div>
    <script>
$(document).ready(function(){

// Checkbox click
$(".hidecol").click(function(){

    var id = this.id;
    var splitid = id.split("_");
    var colno = splitid[1];
    var checked = true;
     
    // Checking Checkbox state
    if($(this).is(":checked")){
        checked = true;
    }else{
        checked = false;
    }
    setTimeout(function(){
        if(checked){
            $('#cost_table td:nth-child('+colno+')').hide();
            $('#cost_table th:nth-child('+colno+')').hide();
        } else{
            $('#cost_table td:nth-child('+colno+')').show();
            $('#cost_table th:nth-child('+colno+')').show();
        }

    }, 1500);

});
});
</script>
<table id="cost_table" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Code</th>
				<th>Description</th>
                <th>idlines</th>
                <th>PO Number</th>
				<th>Qty</th>
				<th>Unit_Amt Foreign</th>
                <th>New Unit_Cost</th>
                <th>Total_Amt Foreign</th>
                <th>Total_Amt KES</th>
                <th>Unit Volume</th>
                <th>Unit Weight</th>
                <th>Total Weight</th>	
                <th>Rate </th>
                <th>Freight KES</th>
                <th>Dry_Ice KES</th>
                <th>Import_duty KES</th>
                <th>Dangerous_Goods KES</th>
                <th>PPB KES</th>
                <th>IDF KES</th>
                <th>KRDL KES</th>
                <th>Excise_Duty KES</th>
                <th>Harzadous_charge KES</th>
                <th>Totals KES</th>
                <th>Unit_Cost KES</th>
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
          if (isset($_GET['submit'])){
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
         $sql2="--get the sumation
         IF OBJECT_ID('tempdb..#tmpship') IS NOT NULL DROP TABLE #tmpship
         select costcode, (sum(volume)) as tot, sum(weight) as totweight,sum(amount) as amt 
         into #tmpship
         from _cplshipmentlines join _cplshipmentmaster on _cplshipmentlines.shipment_no=
         _cplshipmentmaster.shipment_no
         where 
         _cplshipmentmaster.id=$query
         group by costcode";
         $sql3="update _cplshipmentlines set cost=  (case when ce.calcbase=1 then volume/nullif(tot,0)*ce.rate when ce.calcbase=2 then weight/nullif(totweight,0)*ce.rate when ce.calcbase=3 then 
         ((amount*qty)/nullif(amt,0) * ce.rate) when ce.calcbase=4 then cost else 0 end ) from	_cplshipmentlines ts join _cplScheme ce on ts.costcode=ce.Cost_Code and ce.Scheme=ts.scheme join _cplshipmentmaster ttr on ts.shipment_no=ttr.shipment_no
         join #tmpship on ts.costcode=#tmpship.costcode
         where ttr.id=cast($query as int) and active='True' and isnull(updated,'False') in ('False')";
         $sql = "IF OBJECT_ID('tempdb..#shipment') IS NOT NULL DROP TABLE #shipment
             select max(invoicelineid) as invoicelineid,max(po_no) as Po_No,max(id) as id, (shipment_no), code, max(description) as description, max(qty) as qty, max(amount) as amount,max(tot_amount_kes) as tot_amount_kes,max([volume]) as [volume],
                          max([weight]) as [weight],max([rate]) as rate,case when (costcode)=1 then max(cost) else 0 end as freight, case when (costcode)=2 then max(cost) else 0 end as dry,
                          case when (costcode)=3 then max(cost) else 0 end as import,
                          case when (costcode)=4 then max(cost) else 0 end as dng,
                          case when (costcode)=5 then max(cost) else 0 end as ppb,
                          case when (costcode)=6 then max(cost) else 0 end as idf,
                          case when (costcode)=7 then max(cost) else 0 end as krdl,
                          case when (costcode)=8 then max(cost) else 0 end as excise,
                          case when (costcode)=9 then max(cost) else 0 end as harzad,
                          max(totals) as Totals, max(scheme) as scheme,
                          max(tot_amount) as tot_amount,max(unit_amount_kes) as unit_amount_kes, max(unit_weight) as unit_weight,
                          case when active='True' then 'Open' else 'Closed' end as active
             into #shipment
             from _cplshipmentlines
             group by code,shipment_no,costcode,active  order by shipment_no";
         $sql4="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
                select code,stkcode,round(sum(Cost)+(max(amount)*max(qty)*max(rate)),2) as totcost 
                into #totals
                from _cplshipmentlines ts
                join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
                where tr.id=$query
                group by code,stkcode";
            $sql1="select max(st.id) as id,isnull(code,'TOTAL') as code,(case when code is null then '' else  max(invoicelineid)  end) as invoicelineid,(case when code is null then '' else max(po_no) end) as po_no,(case when code is null then '' else max(st.shipment_no)  end) as shipment_no, (case when code is null then '' else max(description) end) as description, (case when code is null then '' when max(qty)=0 then '' else max(qty) end) as qty, (case when code is null then '' else max(amount) end) as amount,(case when code is null then '' else max(tot_amount_kes) end) as tot_amount_kes,(case when code is null then '' else sum([volume]) end) as [volume],
            (case when code is null then ''  when max([unit_weight])=0 then '' else max([unit_weight]) end) as [unit_weight],(case when code is null then ''  when max([weight])=0 then '' else max([weight]) end) as [weight], case when code is null then '' else round(max(rate),2) end as rate, round(sum(freight),2)  as freight, round(sum(dry),2)  as dry, round(sum(import),2)  as import, round(sum(dng),2)  as dng, round(sum(ppb),2)  as ppb, round(sum(idf),2)  as idf, round(sum(krdl),2) as krdl, round(sum(excise),2)
            as excise,round(sum(harzad),2)  as harzad, (case when code is null then (select sum(totcost) from #totals) else max(totals) end) as Totals,max(unit_amount_kes) as unit_amount_kes,(case when code is null then '' else max(scheme) end) as scheme,  case when code is null then '' else max(active) end as active,max(tot_amount) as tot_amount from #shipment st join _cplshipmentmaster tm
            on st.shipment_no=tm.shipment_no
            where tm.id=cast($query as int)
            group by(code)";
       
			 $params = array();
             $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );		
             sqlsrv_query($conn,$rate,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$updaterate,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$updateunitkes,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$updatetotkes,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$sql2,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$sql3,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$sql,$params,$options) or die(print_r( sqlsrv_errors(), true));
             sqlsrv_query($conn,$sql4,$params,$options) or die(print_r( sqlsrv_errors(), true));	
			 $stmt = sqlsrv_query($conn,$sql1,$params,$options) or die(print_r( sqlsrv_errors(), true));		
			 if( $stmt === false) {
					die( print_r( sqlsrv_errors(), true) );
				}
                $row_count = sqlsrv_num_rows($stmt);
				if ($row_count > 0) {
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<tr><form method='post' action='delshipline.php' >"; ?>
                    <td> <?php echo $row["code"] ;?></td>
					<td> <?php echo $row["description"] ;?></td>
                    <td> <?php echo $row["invoicelineid"] ;?></td>
                    <td> <?php echo $row["po_no"] ;?></td>
					<td> <?php echo $row["qty"] ;?></td>
                    <td> <?php echo $row["amount"] ;?></td>		
                    <td> <a id="link" href='inscst.php?edit=<?php echo $row["id"] ;?>'>insert cost</a></td>
                    <td> <?php echo $row["tot_amount"] ;?></td>	
                    <td> <?php echo $row["tot_amount_kes"] ;?></td>	
                    <td> <?php echo $row["volume"] ;?></td>
                    <td> <?php echo $row["unit_weight"] ;?></td>
                    <td> <?php echo $row["weight"] ;?></td>
                    <td> <?php echo $row["rate"] ;?></td>
                    <td> <?php echo $row["freight"] ;?></td>
                    <td> <?php echo $row["dry"] ;?></td>
                    <td> <?php echo $row["import"] ;?></td>	
                    <td> <?php echo $row["dng"] ;?></td>	
                    <td> <?php echo $row["ppb"] ;?></td>
                    <td> <?php echo $row["idf"] ;?></td>	
                    <td> <?php echo $row["krdl"] ;?></td>	
                    <td> <?php echo $row["excise"] ;?></td>
                    <td> <?php echo $row["harzad"] ;?></td>
                    <td> <?php echo $row["Totals"] ;?></td>
                    <td> <?php echo $row["unit_amount_kes"] ;?></td>
                    <td> <?php echo $row["scheme"] ;?></td>	
                    <td> <?php echo $row["active"] ;?></td>	
                    <td><input type="checkbox" name="lines[]" value="<?php echo $row["id"] ;?>" /></td>	
					</tr>
					
				<?php }}}
		sqlsrv_close($conn);
	    ?>
        <?php
        
        ?>
		<tfoot>
                    <tr><th>TOTALS</th>
					<th> </th>
                    <th></th>
                    <th></th>
					<th> <label id="qty"></label></th>
                    <th> <label id="amount"></label></th>		
                    <th> </th>
                    <th> <label id="totamount"></label></th>
                    <th> <label id="totamountkes">0</label></th>
                    <th> <label id="volume"></label></th>
                    <th> <label id="weight"></label></th>
                    <th> <label id="tot_weight"></label></th>
                    <th> </th>
                    <th> <label id="freight"></label></th>
                    <th> <label id="dry"></label></th>
                    <th> <label id="import"></label></th>	
                    <th> <label id="dng"></label></th>	
                    <th> <label id="ppb"></label></th>
                    <th> <label id="idf"></label></th>	
                    <th> <label id="krdl"></label></th>	
                    <th> <label id="excise"></label></th>
                    <th> <label id="hazard"></label></th>
                    <th> <label id="totals"></label></th>
                    <th> <label id="unittotals"></label></th>
                    <th> </th>	
                    <th> </th>	
                    <th> </th>
                    </tr>
        </tfoot>
        </tbody>
    </table>
    <hr></hr>
    <ul><a>To remove items from shipment, select the items and click "DELETE"</a></ul>
    <button type="submit" name="submit" onclick="del()">DELETE</button>
    <hr></hr>
    </form>
			</div>
            <a> Input all the costs applicable and exchange rate and click "submit" for the system to calculate the costs.</a>
            <a> Kindly note the shipment will be open for further update when you click "submit".</a>
            <div id="cst">
            
    <table>
    <form id="myForm" method="POST" action="">
        <tr><td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct code, cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int) and st.costcode=1"; 
             $sql1="insert into #tmpcost (code,cost) values('',0)";    
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);	
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a>Total Freight Cost: </a><input id=freight value=" .$row["cost"]. " name='freight' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <td>       
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct code, cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int) and st.costcode=2";
             $sql1="insert into #tmpcost (code,cost) values('',0)";      
			 $sql2 = "Select round(sum(cost),2) as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a>Total Dry Ice Cost: </a><input id=dry value=" .$row["cost"]. " name='dry' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct isnull(code,'null') as code, isnull(cost,0) as cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int) and st.costcode=4";     
             $sql1="insert into #tmpcost (code,cost) values('',0)";
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a>Total Dangerous Goods Fee: </a><input id=dgf value=" .$row["cost"]. " name='dgf' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct code, cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int) and st.costcode=3";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a>Total Import Duty Cost: </a><input id=importduty value=" .$row["cost"]. " name='importduty' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct code, cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int) and st.costcode=5";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a>Total Pharmacy & Poisons Board cost: </a><input id=ppb value=" .$row["cost"]. " name='ppb' class='form-control' style='width:150px;'/></tr></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <tr><td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct code, cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int) and st.costcode=6";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo " <a>Total IDF Cost: </a><input id=idf value=" .$row["cost"]. " name='idf' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct code, cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int) and st.costcode=7";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a>Total KRDL Cost: </a><input id=krdl value=" .$row["cost"]. " name='krd1' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct code, cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int) and st.costcode=8";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a>Total Excise Duty Cost: </label><input id=exciseduty value=" .$row["cost"]. " name='exciseduty' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct code, cost into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int) and st.costcode=9";   
             $sql1="insert into #tmpcost (code,cost) values('',0)";  
			 $sql2 = "Select  round(sum(cost),2)  as cost
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<a>Total Harzadous surcharge cost: </label> <input id=hsc value=" .$row["cost"]. " name='hsc' class='form-control' style='width:150px;'/></td>";
				};
		sqlsrv_close($conn);
	    ?>
        </td>
        <td>
        <?php
	    include("config.php");
        $query = $_GET['SH'];
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
             $sql="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
             select distinct code, rate into #tmpcost from _cplshipmentlines st join _cplshipmentmaster tm
             on st.shipment_no=tm.shipment_no
             where tm.id=cast($query as int)";   
             $sql1="insert into #tmpcost (code,rate) values('',0)";  
			 $sql2 = "Select top(1) round((rate),2)  as rate
			 from #tmpcost";	
			// $params = array();
            // $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
             sqlsrv_query($conn,$sql);
             sqlsrv_query($conn,$sql1);	
			 $stmt = sqlsrv_query($conn,$sql2);		
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        echo "<a>Enter Exchange rate: </a><input id=rate value=" .$row["rate"]. " name='rate' class='form-control' style='width:150px;'/></td></tr>";
    };
    sqlsrv_close($conn);
    
    ?>
        <tr><button id="btn1" type="submit"  name='submit'>SUBMIT
        
        </button></tr>
        <hr></hr>
    </form>
    </table>
    

 <script>
        $(document).ready(function(){
        $('#btn1').keyup(function(){
            <?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query1 = $_GET['SH'];
          if(isset($_POST['submit'])){
            $freight=$_POST['freight'] ?? "nothing";
            $dry=$_POST['dry'] ?? "nothing";
            $dgf=$_POST['dgf'] ?? "nothing";
            $import=$_POST['importduty'] ?? "nothing";
            $ppb=$_POST['ppb'] ?? "nothing";
            $idf=$_POST['idf'] ?? "nothing";
            $krd1=$_POST['krd1'] ?? "nothing";
            $duty=$_POST['exciseduty'] ?? "nothing";
            $hsc=$_POST['hsc'] ?? "nothing";
            $rate=$_POST['rate'] ?? "nothing";
        $qtys="--get the sumation
        IF OBJECT_ID('tempdb..#tmpship') IS NOT NULL DROP TABLE #tmpship
        select costcode, (code), max(amount) as fob,max(qty) as qt, (sum(volume)) as tot, sum(weight) as totweight,(sum(amount*qty))as amt 
        into #tmpship
        from _cplshipmentlines join _cplshipmentmaster on _cplshipmentlines.shipment_no=
        _cplshipmentmaster.shipment_no
        where 
        _cplshipmentmaster.id=$query
        group by costcode,code,qty,amount";
        $amts="--get amts
        IF OBJECT_ID('tempdb..#tmpamts') IS NOT NULL DROP TABLE #tmpamts
        select ts.costcode,ts.shipment_no,ts.qty,ts.code, (case when max(ce.calcbase) in (1) then (max(volume)/nullif(sum(#tmpship.tot),0)) when max(ce.calcbase)=2 then (max(weight)/nullif(sum(totweight),0))  else 0 end) as vol_weigh_amts,
        case when max(ce.calcbase) in (3) then ((max(amount*qty))/nullif(sum(amt),0))  else 0 end as fobamt, case when max(ce.calcbase) in (4) then max(cost) end as import
        into #tmpamts
        from _cplshipmentlines ts join _cplScheme ce on ts.costcode=ce.Cost_Code and ce.Scheme=ts.scheme  join _cplshipmentmaster ttr on ts.shipment_no=ttr.shipment_no
        join #tmpship on ts.costcode=#tmpship.costcode 
        where ttr.id=cast($query as int)
        group by ts.costcode,ts.shipment_no,ts.qty,ts.code";
        $rates="--get rates
        IF OBJECT_ID('tempdb..#rates') IS NOT NULL DROP TABLE #rates
        select code,costcode,$freight*case when max(costcode)=1 then nullif(sum(vol_weigh_amts+fobamt),0) else nullif(0,0) end as frrate, $dry*case when max(costcode)=2 then nullif(sum(vol_weigh_amts+fobamt),0) else nullif(0,0) end as dryrate,
        $dgf*case when max(costcode)=4 then nullif(sum(vol_weigh_amts+fobamt),0) else nullif(0,0) end as dangrate,max(import) as imprate,
        $ppb*case when max(costcode)=5 then nullif(sum(fobamt+vol_weigh_amts),0) else nullif(0,0) end as ppbrate,$idf*case when max(costcode)=6 then nullif(sum(fobamt+vol_weigh_amts),0) else nullif(0,0) end as idfrate,
        $krd1*case when max(costcode)=7 then nullif(sum(fobamt+vol_weigh_amts),0) else nullif(0,0) end as krdlrate,$duty*case when max(costcode)=8 then nullif(sum(fobamt+vol_weigh_amts),0) else nullif(0,0) end as exciserate,
        $hsc*case when max(costcode)=9 then nullif(sum(fobamt+vol_weigh_amts),0) else nullif(0,0) end as harzard
        into #rates
        from #tmpamts
        group by code,costcode";
        $updates="--update _cplshipmentlines
        update _cplshipmentlines set cost= case when ts.costcode=1 then frrate 
        when ts.costcode=2 then dryrate
        when ts.costcode=3 then imprate 
        when ts.costcode=4 then dangrate 
        when ts.costcode=5 then ppbrate 
        when ts.costcode=6 then idfrate 
        when ts.costcode=7 then krdlrate 
        when ts.costcode=8 then exciserate
        when ts.costcode=9 then harzard 
        end 
        from _cplshipmentlines ts join #rates ty on ts.code=ty.code
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        where tr.id=cast($query as int)  and ts.active='True'";
        $close="update _cplshipmentlines set updated='True' from _cplshipmentlines ts join _cplshipmentmaster ttr 
        on ttr.shipment_no=ts.shipment_no where ttr.id=$query"; 
        $calcrate="update _cplshipmentlines set cost= (case when ce.calcbase=3 then cost*1 else cost*1 end) from _cplshipmentlines ts join _cplshipmentmaster tr on
        ts.shipment_no=tr.shipment_no join _cplScheme ce on ts.scheme=ce.Scheme and ts.costcode=ce.Cost_Code
        where tr.id=$query and ts.rate=1";
        $updaterate="update _cplshipmentlines set rate=$rate from _cplshipmentlines ts join _cplshipmentmaster tr on
        ts.shipment_no=tr.shipment_no 
        where tr.id=$query ";
        $updatetotals="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
        select code,stkcode,round(sum(Cost)+(max(amount)*max(qty)*max(rate)),2) as totcost 
        into #totals
        from _cplshipmentlines ts
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        where tr.id=$query
        group by code,stkcode
        
        update _cplshipmentlines set totals=tts.totcost from _cplshipmentlines ts  
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        join #totals tts on ts.stkcode=tts.stkcode
        where tr.id=$query";
        sqlsrv_query($conn, $qtys)  or die(print_r( sqlsrv_errors(), true)) ;
        sqlsrv_query($conn, $amts) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $rates) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $updates) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $close) or die(print_r( sqlsrv_errors(), true));
        //sqlsrv_query($conn, $calcrate) or die(print_r( sqlsrv_errors(), true)); 
        sqlsrv_query($conn, $updaterate) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $updatetotals) or die(print_r( sqlsrv_errors(), true));
      
    }
    "<script> document.getElementById('myForm').submit();</script>";
    sqlsrv_close($conn);

?>
            alert("Record successfully submitted");
            }); 
        });
</script>

<form method="POST" action="">
    <button name='close' >POST INTO SAGE EVOLUTION</button>
</form>
<?php
include("config.php");
$conn = sqlsrv_connect( $servername, $connectioninfo);
if(isset($_POST['close'])){
    $update="update _cplshipmentlines set active='False' from _cplshipmentlines ts join _cplshipmentmaster ttr 
    on ttr.shipment_no=ts.shipment_no where ttr.id=$query"; 
    /*$upload1="---Create IBT
    insert into _etblWhseIBT (cIBTNumber, cIBTDescription, iWhseidfrom, iWhseIDTo,iWhseIDIntransit,iWhseIDVariance,iWhseIDDamaged,iIBTStatus,
    cDelNoteNumber,iprojectid, ddateissued, ddatereceived, cAuditNumberIssued, bUseAddCostPerLine,iAgentIDIssue,iIBTAction,cIBTReference1,cIBTReference2,
    dActionIssueStock,dActionShipStock) 
    Values ((select 'IBT'+ format(inextno,'00000') from _rtblrefbase where idRefBase=5), 
    'trf with additional costs',(select whselink from whsemst where code='IMPORT'),(select whselink from whsemst where code='Mstr'),(select whselink from whsemst where code='GIT'),
    (select whselink from whsemst where code='VG'),(select whselink from whsemst where code='DG'),1,'IDEL'+ (select format(inextno,'00000') from _rtblrefbase where idRefBase=7) ,
    0,getdate(), getdate(),'cpl_'+ (select 'IBT'+ format(inextno,'00000') from _rtblrefbase where idRefBase=5)
    ,1,1,4,'Transfer','Transfer',getdate(),getdate())
    ---Insert IBT lines
    insert into _etblWhseIBTLines (iwhseibtid,istockid,cReference,cDescription,iProjectID,bisserialitem,bislotitem,ilotid,fQtyIssued,
    fQtyReceived,fQtyDamaged,fQtyVariance,fQtyOverDelivered,fnewreceivecost,fadditionalcost,fissuedcost) 
    (select (select top (1)idwhseibt from _etblWhseIBT where cauditnumberissued like '%cpl%' order by IDWhseIBT desc), (stkcode),'Transfer', 
    'Transfer', 0,0,0,0,max(qty),max(qty), 0,0,0,(sum(cost)+(max(amount*qty)*max(rate))),(sum(cost)+(max(amount*qty)*max(rate))),averagecost
    from _cplshipmentlines join _etblstockcosts on _cplshipmentlines.stkcode=_etblstockcosts.stockid join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no where _cplshipmentmaster.id=$query
    group by stkcode,AverageCost)*/
    $upload1="--remove from import(postst)
    insert into postst (txdate,id,Accountlink,Trcodeid,debit,Credit,iCurrencyID,fExchangeRate,fForeignDebit,
    fForeignCredit,Description,TaxTypeID,Reference,cauditnumber,Tax_Amount,fForeignTax, Quantity,cost,WarehouseID,
    DTStamp,username,creference2,bchargecom,iGLAccountID,QuantityR) 
    (select format(getdate(),'yyyy-MM-dd'), 'WTrf',(stkcode),32,0,case when AverageCost*max(qty)=0 then 0.00000000123 else AverageCost*max(qty) end,0,1,0,0, 
    'Transfer', 0,'Transfer','cpl_'+ (select 'WTrf'+ format(inextno,'00000') from _rtblrefbase where idRefBase=2),0,0,max(qty),
    Averagecost,(select whselink from whsemst where code='IMPORT'), getdate(),'Admin',(select 'WTrf'+ format(inextno,'00000') from _rtblrefbase where idRefBase=2),1,87,max(qty)
    from _cplshipmentlines join _etblstockcosts on _cplshipmentlines.stkcode=_etblstockcosts.stockid join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no where _cplshipmentmaster.id=$query
    group by stkcode,AverageCost)

    --insert into mstr(postst)
    insert into postst (txdate,id,Accountlink,Trcodeid,debit,Credit,iCurrencyID,fExchangeRate,fForeignDebit,
    fForeignCredit,Description,TaxTypeID,Reference,cauditnumber,Tax_Amount,fForeignTax, Quantity,cost,WarehouseID,
    DTStamp,username,creference2,bchargecom,iGLAccountID,QuantityR) 
    (select format(getdate(),'yyyy-MM-dd'), 'WTrf',(stkcode),32, case when ((max(amount)*max(rate))*max(qty))+sum(cost)=0 then 0.00000000123 when max(costcode)<>3 then ((max(amount)*max(rate))*max(qty))+sum(cost) else sum(cost) end,0,0,1,0,0, 
    'Transfer', 0,'Transfer','cpl_'+ (select 'WTrf'+ format(inextno,'00000') from _rtblrefbase where idRefBase=2),0,0,max(qty),
    ((max(amount)*max(rate))+(sum(cost))/max(qty)),(select whselink from whsemst where code='Mstr'), getdate(),'Admin',(select 'WTrf'+ format(inextno,'00000') from _rtblrefbase where idRefBase=2),1,87,max(qty)
    from _cplshipmentlines join _etblstockcosts on _cplshipmentlines.stkcode=_etblstockcosts.stockid join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no join _etblstockqtys on _cplshipmentlines.stkcode=_etblstockqtys.StockID
    where _cplshipmentmaster.id=$query and _etblstockqtys.WhseID=2
    group by stkcode,AverageCost)

    --compare items in stock qtys in import warehouse
    IF OBJECT_ID('tempdb..#stockqtys') IS NOT NULL DROP TABLE #stockqtys;
    (select count(*)as nos,stkcode,QtyOnHand
    into #stockqtys
    from _etblstockQtys join _cplshipmentlines on _etblstockqtys.StockID=_cplshipmentlines.stkcode join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no join whsemst on _etblstockqtys.WhseID=
    WhseMst.whselink where _cplshipmentmaster.id=$query and whsemst.code='IMPORT'
    group by stkcode,QtyOnHand)
    IF OBJECT_ID('tempdb..#stockq') IS NOT NULL DROP TABLE #stockq;
	select max(qty) as qty,(stkcode) 
	into #stockq
	from _etblStockQtys eq 
    join _cplshipmentlines ts on eq.StockID=ts.stkcode join _cplshipmentmaster tr on ts.shipment_no=tr.Shipment_no
    where tr.id=$query and  ts.stkcode in (select stkcode from #stockqtys) and eq.WhseID=(select whselink from whsemst where code='IMPORT')
	group by stkcode
	update _etblstockqtys set QtyOnHand=eq.QtyOnHand-(qty) from _etblStockQtys eq 
    join #stockq sq on eq.stockid =sq.stkcode and eq.WhseID=(select whselink from whsemst where code='IMPORT')
    --compare items in stock qtys in Mstr warehouse
    IF OBJECT_ID('tempdb..#stockqty') IS NOT NULL DROP TABLE #stockqty;
    (select count(*)as nos,stkcode,QtyOnHand
    into #stockqty
    from _etblstockQtys join _cplshipmentlines on _etblstockqtys.StockID=_cplshipmentlines.stkcode join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no join whsemst on _etblstockqtys.WhseID=
    WhseMst.whselink where _cplshipmentmaster.id=$query and whsemst.code='Mstr'
    group by stkcode,QtyOnHand)
    --insert lines that are not in stock qtys
    insert into _etblstockqtys (StockID, WhseID,LotID,BinLocationID,QtyOnHand,qtyonso,qtyonpo,qtyreserved,QtyToDeliver,QtyJCWIP,
    QtyIBTToIssue,QtyIBTToReceive,QtyLastGrvCount)
    select (_cplshipmentlines.stkcode),(select whselink from whsemst where code='Mstr'),0,0,max(qty),0,0,0,0,0,0,0,0
    from _cplshipmentlines join _etblstockcosts on _cplshipmentlines.stkcode=_etblstockcosts.stockid join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no 
    where _cplshipmentmaster.id=$query and _cplshipmentlines.stkcode not in (select stkcode from #stockqty)
    group by _cplshipmentlines.stkcode
    --update existing qty
    IF OBJECT_ID('tempdb..#stockq2') IS NOT NULL DROP TABLE #stockq2;
	select max(qty) as qty,(stkcode) 
	into #stockq2
	from _etblStockQtys eq 
    join _cplshipmentlines ts on eq.StockID=ts.stkcode join _cplshipmentmaster tr on ts.shipment_no=tr.Shipment_no
    where tr.id=$query and  ts.stkcode in (select stkcode from #stockqty) and eq.WhseID=(select whselink from whsemst where code='Mstr')
	group by stkcode
    update _etblstockqtys set QtyOnHand=eq.QtyOnHand+(qty) from _etblStockQtys eq 
    join #stockq2 sq on eq.stockid =sq.stkcode and eq.WhseID=(select whselink from whsemst where code='Mstr')
    --update costs
    IF OBJECT_ID('tempdb..#tmpcosts') IS NOT NULL DROP TABLE #tmpcosts
	select eqs.StockID as stkid, max(tms.totals/tms.qty)  as average_cost 
	into #tmpcosts
	from _etblStockCosts ecs join _cplshipmentlines tms 
	on ecs.stockid=tms.stkcode	join _etblstockqtys eqs
    on tms.stkcode=eqs.StockID join _cplshipmentmaster tr on tms.shipment_no=tr.shipment_no 
    where tr.id=$query
	group by eqs.stockid
	update _etblStockCosts set averagecost=tc.average_cost,latestcost=tc.average_cost,manualcost=tc.average_cost from _etblstockcosts es
	join #tmpcosts tc on es.StockID=tc.stkid
    --update next automatic number
    update _rtblRefBase set iNextNo=iNextNo+1 from _rtblrefbase where creftype in ('NextWHTrfRefNo')";
    sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));
    sqlsrv_query($conn, $upload1) or die(print_r( sqlsrv_errors(), true));
   // header("refresh:0.05; url=Index.php");
   echo("<script> alert('Costs of the items have been updated on sage');</script>");
}
sqlsrv_close($conn);

?>

</div>
	<a id="link" href="Index.php"><<<<<< Go back</a>
	</body>
    </html>
</div>
</body>
</html>