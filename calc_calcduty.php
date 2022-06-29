<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
          $value=$_POST['calcduty'] ?? 0;
          $update="IF OBJECT_ID('tempdb..#tmpcode') IS NOT NULL DROP TABLE #tmpcode
          select code,shipment_no as ship_no,po_no 
          into #tmpcode
          from _cplshipmentlines where id=$query
          --update _cplshipmentlines
          update _cplshipmentlines set Calc_Duty=$value from _cplshipmentlines
          where shipment_no=(select ship_no from #tmpcode) and code=(select code from #tmpcode)
          and po_no=(select po_no from #tmpcode)
          update _cplshipmentlines set cost= (case when $value=0 then 0 end) from _cplshipmentlines
          where shipment_no=(select ship_no from #tmpcode) and code=(select code from #tmpcode)
          and costcode=3 and po_no=(select po_no from #tmpcode)
          
          IF OBJECT_ID('tempdb..#duty') IS NOT NULL DROP TABLE #duty
         select distinct invoicelineid, cs.code, (max(amount*rate)+ 
         sum(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0) else 0 end)+
         sum(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0)else 0 end))*
         max(DutyPercent) as duty,
         (max(amount*rate)+ 
         sum(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0) else 0 end)+
         sum(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end)) as customs
         into #duty
         from stkitem st
         left join _CplHScode ce on st.cComponent=ce.HSCode join _cplshipmentlines cs
         on st.stocklink=cs.stkcode join _cplshipmentmaster tm
         on cs.shipment_no=tm.shipment_no join _cplcostmaster cr on cs.costcode=cr.id
         where cs.shipment_no=(select ship_no from #tmpcode)
         group by invoicelineid,cs.code 
         
         --update duty
         update _cplshipmentlines set cost=(case when $value=1 then duty end) from _cplshipmentlines cs join _cplshipmentmaster tm
         on cs.shipment_no=tm.shipment_no join #duty dt on cs.code=dt.code
         join _cplcostmaster cr on cs.costcode=cr.id
         where cs.shipment_no=(select ship_no from #tmpcode) and cr.cost='duty'
         and cs.code=(select code from #tmpcode) and po_no=(select po_no from #tmpcode)
         
         --update customs value
         update _cplshipmentlines set customs_value=customs from _cplshipmentlines cs join _cplshipmentmaster tm
         on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
         where cs.shipment_no=(select ship_no from #tmpcode)
         
         --Calculate totals
         IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
        select code,stkcode,round(sum(Cost)+(max(amount)*max(qty)*max(rate)),2) as totcost 
        into #totals
        from _cplshipmentlines ts
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        where ts.shipment_no=(select ship_no from #tmpcode)
        group by code,stkcode
        
        update _cplshipmentlines set totals=tts.totcost from _cplshipmentlines ts  
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        join #totals tts on ts.stkcode=tts.stkcode
        where ts.shipment_no=(select ship_no from #tmpcode)";
        sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));
    sqlsrv_close($conn)

?>