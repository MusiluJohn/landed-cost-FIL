
<html>
<title>
</title>
<link rel="stylesheet" href="css/style.css">
<link rel='stylesheet' type='text/css' href='css/bootstrap1.css'/>
<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/bootstrap1.js"></script>
<script src="js/bootstrap2.js"></script>
<script src="js/bootstrap3.js"></script>
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
<script src="js/script.js"></script>
<head>

<table><tr><td>
</td></tr></table>
<button id="btnprint"  type="submit">Print this page</button> 
  
<script>
    $(document).ready(function(){
        $("#btnprint").click(function(){
            $(".hidecol").hide(); 
            $("#text").hide();
            $(".text").hide();
            $("#btnprint").hide();
            window.print();
        });
    });
</script> 
</head>
<body onload='costTable2()'>
<div>
        <strong id="text">Check to hide column</strong>
        <br>
        <input type="checkbox" class="hidecol" value="qty" id="col_3" />&nbsp;<a class="text">Qty</a>&nbsp;
        <input type="checkbox" class="hidecol" value="Volume" id="col_6" />&nbsp;<a class="text">Volume</a>
        <input type="checkbox" class="hidecol" value="Weight" id="col_7" />&nbsp;<a class="text">Weight</a>
        <input type="checkbox" class="hidecol" value="Dry_Ice" id="col_11" />&nbsp;<a class="text">Dry Ice</a>
        <input type="checkbox" class="hidecol" value="Import_duty" id="col_12" />&nbsp;<a class="text">Import Duty</a>
        <input type="checkbox" class="hidecol" value="D_goods" id="col_13" />&nbsp;<a class="text">Dangerous Goods</a>
        <input type="checkbox" class="hidecol" value="PPB" id="col_14" />&nbsp;<a class="text">PPB</a>
        <input type="checkbox" class="hidecol" value="IDF" id="col_15" />&nbsp;<a class="text">IDF</a>
        <input type="checkbox" class="hidecol" value="KRDL" id="col_16" />&nbsp;<a class="text">KRDL</a>
        <input type="checkbox" class="hidecol" value="EDuty" id="col_17" />&nbsp;<a class="text">Excise Duty</a>
        <input type="checkbox" class="hidecol" value="Hcharge" id="col_18" />&nbsp;<a class="text">Harzadous_charge</a>
    </div>
<!------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------Grid-Lines--------------------------------------------------->
<div class="row" >

<table id="cost_table2" class="table table-bordered table-striped table-hover">
<thead>
            <tr>
				<th>Description</th>
                <th>PO Number</th>
				<th>Qty</th>
				<th>U_Amt Foreign</th>
                <th>T_Amt KES</th>
                <th>Unit Vol</th>
                <th>Unit Weight</th>
                <th>Total Weight</th>	
                <th>Rate </th>
                <th>Freight KES</th>
                <th>DIce KES</th>
                <th>Iduty KES</th>
                <th>DGoods KES</th>
                <th>PPB KES</th>
                <th>IDF KES</th>
                <th>KRDL KES</th>
                <th>EDuty KES</th>
                <th>Hcharge KES</th>
                <th>Totals KES</th>
                <th>Unit_Cost KES</th>
            </tr>
</thead>
<tbody>
<?php
    include("config.php");
    if(!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
        $quoteValue = $_GET['invoice_id'];		
        $sql="IF OBJECT_ID('tempdb..#shipment11') IS NOT NULL DROP TABLE #shipment11
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
        into #shipment1
        from _cplshipmentlines
        group by code,shipment_no,costcode,active  order by shipment_no";
    $sql1="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
           select code,stkcode,round(sum(Cost)+(max(amount)*max(qty)*max(rate)),2) as totcost 
           into #totals
           from _cplshipmentlines ts
           join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
           where tr.shipment_no='$quoteValue'
           group by code,stkcode";
    $sql2="select max(st.id) as id,isnull(code,'TOTAL') as code,(case when code is null then '' else  max(invoicelineid)  end) as invoicelineid,(case when code is null then '' else max(po_no) end) as po_no,(case when code is null then '' else max(st.shipment_no)  end) as shipment_no, (case when code is null then '' else max(description) end) as description, (case when code is null then '' when max(qty)=0 then '' else max(qty) end) as qty, (case when code is null then '' else max(amount) end) as amount,(case when code is null then '' else max(tot_amount_kes) end) as tot_amount_kes,(case when code is null then '' else sum([volume]) end) as volume,
        (case when code is null then ''  when max([unit_weight])=0 then '' else max([unit_weight]) end) as unit_weight,(case when code is null then ''  when max([weight])=0 then '' else max([weight]) end) as [weight], case when code is null then '' else round(max(rate),2) end as rate, round(sum(freight),2)  as freight, round(sum(dry),2)  as dry, round(sum(import),2)  as import, round(sum(dng),2)  as dng, round(sum(ppb),2)  as ppb, round(sum(idf),2)  as idf, round(sum(krdl),2) as krdl, round(sum(excise),2)
        as excise,round(sum(harzad),2)  as harzad, (case when code is null then (select sum(totcost) from #totals) else max(totals) end) as Totals,max(unit_amount_kes) as unit_amount_kes,(case when code is null then '' else max(scheme) end) as scheme,  case when code is null then '' else max(active) end as active,max(tot_amount) as tot_amount from #shipment1 st join _cplshipmentmaster tm
        on st.shipment_no=tm.shipment_no
        where tm.shipment_no='$quoteValue'
        group by(code)";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );	
        sqlsrv_query($conn,$sql,$params,$options);
        sqlsrv_query($conn,$sql1,$params,$options);
        $stmt = sqlsrv_query($conn,$sql2,$params,$options) or die(print_r( sqlsrv_errors(), true));		
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }
        $row_count = sqlsrv_num_rows($stmt);
        if ($row_count > 0) {
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
?>
<tr>
<td><?php echo $row['description']; ?>
</td>
<td><?php echo $row['po_no']; ?>
</td>
</td>
<td><?php echo $row['qty']; ?> 
</td>
<td><?php echo $row['amount']; ?>
</td>
<td><?php echo $row['tot_amount_kes']; ?>
</td>
<td><?php echo $row['volume']; ?></td>
<td><?php echo $row['unit_weight']; ?></td>
<td><?php echo $row['weight']; ?></td>
<td><?php echo $row['rate']; ?></td>
<td><?php echo $row['freight']; ?></td>
<td><?php echo $row['dry']; ?></td>
<td><?php echo $row['import']; ?></td>
<td><?php echo $row['dng']; ?></td>
<td><?php echo $row['ppb']; ?></td>
<td><?php echo $row['idf']; ?></td>
<td><?php echo $row['krdl']; ?></td>
<td><?php echo $row['excise']; ?></td>
<td><?php echo $row['harzad']; ?></td>
<td><?php echo $row['Totals']; ?></td>
<td><?php echo $row['unit_amount_kes']; ?></td>
</tr>
<?php }}} ?>
<tfoot>
                    <tr><th>TOTALS</th>
                    <th></th>
                    <th></th>
					<th> <label id="qty"></label></th>
                    <th> <label id="amount"></label></th>		
                    <th> <label id="totamountkes">0</label></th>
                    <th> <label id="volume"></label></th>
                    <th> <label id="weight"></label></th>
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
                    </tr>
        </tfoot>
</tbody>
</table>
</div>
</div>
</center>

</body>
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
            $('#cost_table2 td:nth-child('+colno+')').hide();
            $('#cost_table2 th:nth-child('+colno+')').hide();
        } else{
            $('#cost_table2 td:nth-child('+colno+')').show();
            $('#cost_table2 th:nth-child('+colno+')').show();
        }

    }, 1500);

});
});
</script>
</html>