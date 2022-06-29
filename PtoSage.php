<?php
include("config.php");
$query=$_POST['ID'];
$conn = sqlsrv_connect( $servername, $connectioninfo);
    $update="update _cplshipmentlines set active='False' from _cplshipmentlines ts join _cplshipmentmaster ttr 
    on ttr.shipment_no=ts.shipment_no where ttr.id=$query"; 
    $upload1="--remove from import(postst)
    insert into postst (txdate,id,Accountlink,Trcodeid,debit,Credit,iCurrencyID,fExchangeRate,fForeignDebit,
    fForeignCredit,Description,TaxTypeID,Reference,cauditnumber,Tax_Amount,fForeignTax, Quantity,cost,WarehouseID,
    DTStamp,username,creference2,bchargecom,iGLAccountID,QuantityR) 
    (select format(getdate(),'yyyy-MM-dd'), 'WTrf',(stkcode),32,0,case when AveUCst*max(qty)=0 then 0.00000000123 else AveUCst*max(qty) end,0,1,0,0, 
    'Transfer', 0,'Transfer','cpl_'+ (select 'WTrf'+ format(inextno,'00000') from _rtblrefbase where idRefBase=2),0,0,max(qty),
    AveUCst,(select whselink from whsemst where code='IMPORT'), getdate(),'Admin',(select 'WTrf'+ format(inextno,'00000') from _rtblrefbase where idRefBase=2),
    1,87,max(qty) from _cplshipmentlines join stkitem on _cplshipmentlines.stkcode=stkitem.StockLink join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no where _cplshipmentmaster.id=$query
    group by stkcode,AveUCst)
    --insert into mstr(postst)
    insert into postst (txdate,id,Accountlink,Trcodeid,debit,Credit,iCurrencyID,fExchangeRate,fForeignDebit,
    fForeignCredit,Description,TaxTypeID,Reference,cauditnumber,Tax_Amount,fForeignTax, Quantity,cost,WarehouseID,
    DTStamp,username,creference2,bchargecom,iGLAccountID,QuantityR) 
    (select format(getdate(),'yyyy-MM-dd'), 'WTrf',(stkcode),32, case when ((max(amount)*max(rate))*max(qty))+sum(cost)=0 then 0.00000000123 when max(costcode)<>3 
    then ((max(amount)*max(rate))*max(qty))+sum(cost) else sum(cost) end,0,0,1,0,0, 
    'Transfer', 0,'Transfer','cpl_'+ (select 'WTrf'+ format(inextno,'00000') from _rtblrefbase where idRefBase=2),0,0,max(qty),
    ((max(amount)*max(rate))+(sum(cost))/max(qty)),(select whselink from whsemst where code='Mstr'), getdate(),'Admin',
    (select 'WTrf'+ format(inextno,'00000') from _rtblrefbase where idRefBase=2),1,87,max(qty)
    from _cplshipmentlines join StkItem on _cplshipmentlines.stkcode=StkItem.StockLink join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no 
    where _cplshipmentmaster.id=$query
    group by stkcode,AveUCst)

    --compare items in stock qtys in import warehouse
    IF OBJECT_ID('tempdb..#stockqtys') IS NOT NULL DROP TABLE #stockqtys;
    (select count(*)as nos,stkcode,whQtyOnHand
    into #stockqtys
    from WhseStk join _cplshipmentlines on WhseStk.WHStockLink=_cplshipmentlines.stkcode join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no join whsemst on WhseStk.WHWhseID=
    WhseMst.whselink where _cplshipmentmaster.id=$query and whsemst.code='IMPORT'
    group by stkcode,WHQtyOnHand)
    IF OBJECT_ID('tempdb..#stockq') IS NOT NULL DROP TABLE #stockq;
    select max(qty) as qty,(stkcode) 
    into #stockq
    from WhseStk eq 
    join _cplshipmentlines ts on eq.WHStockLink=ts.stkcode join _cplshipmentmaster tr on ts.shipment_no=tr.Shipment_no
    where tr.id=$query and  ts.stkcode in (select stkcode from #stockqtys) and eq.WhseID=(select whselink from whsemst where code='IMPORT')
    group by stkcode
	update WhseStk set WHQtyOnHand=eq.WHQtyOnHand-(qty) from WhseStk eq 
    join #stockq sq on eq.WHStockLink =sq.stkcode and eq.WHWhseID=(select whselink from whsemst where code='IMPORT')
    
    --compare items in stock qtys in Mstr warehouse
    IF OBJECT_ID('tempdb..#stockqty') IS NOT NULL DROP TABLE #stockqty;
    (select count(*)as nos,stkcode,WHQtyOnHand
    into #stockqty
    from WhseStk join _cplshipmentlines on WhseStk.WHStockLink=_cplshipmentlines.stkcode join _cplshipmentmaster
    on _cplshipmentlines.shipment_no=_cplshipmentmaster.shipment_no join whsemst on WhseStk.WHWhseID=
    WhseMst.whselink where _cplshipmentmaster.id=$query and whsemst.code='Mstr'
    group by stkcode,WHQtyOnHand)
    
    --update existing qty in whse stock
    IF OBJECT_ID('tempdb..#stockq2') IS NOT NULL DROP TABLE #stockq2;
    select max(qty) as qty,(stkcode) 
    into #stockq2
    from WhseStk eq 
    join _cplshipmentlines ts on eq.WHStockLink=ts.stkcode join _cplshipmentmaster tr on ts.shipment_no=tr.Shipment_no
    where tr.id=$query and  ts.stkcode in (select stkcode from #stockqty) and eq.WHWhseID=(select whselink from whsemst where code='Mstr')
    group by stkcode
    update whsestk set WHQtyOnHand=eq.whQtyOnHand+(qty) from WhseStk eq 
    join #stockq2 sq on eq.WHStockLink =sq.stkcode and eq.WHWhseID=(select whselink from whsemst where code='Mstr')

    --update costs
    --IF OBJECT_ID('tempdb..#tmpcosts') IS NOT NULL DROP TABLE #tmpcosts
	--select eqs.StockID as stkid, max(tms.totals/tms.qty)  as average_cost 
	--into #tmpcosts
	--from _etblStockCosts ecs join _cplshipmentlines tms 
	--on ecs.stockid=tms.stkcode	join _etblstockqtys eqs
    --on tms.stkcode=eqs.StockID join _cplshipmentmaster tr on tms.shipment_no=tr.shipment_no 
    --where tr.id=$query
	--group by eqs.stockid
	--update _etblStockCosts set averagecost=tc.average_cost,latestcost=tc.average_cost,manualcost=tc.average_cost from _etblstockcosts es
	--join #tmpcosts tc on es.StockID=tc.stkid
    --update next automatic number
    --update _rtblRefBase set iNextNo=iNextNo+1 from _rtblrefbase where creftype in ('NextWHTrfRefNo')";
    sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));
    sqlsrv_query($conn, $upload1) or die(print_r( sqlsrv_errors(), true));
sqlsrv_close($conn);
?>