<?php
        //require_once("insert.php");
        include("config.php");
          $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
          $value=$_POST['customs'] ?? 0;
          $rate=$_POST['rate'] ?? 0;

        if ($value=='yes'){
        $duty="IF OBJECT_ID('tempdb..#duty') IS NOT NULL DROP TABLE #duty;
         select distinct invoicelineid, cs.code, max(cme.scheme) as scheme,(max(amount*cs.rate*qty)+
         max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
         max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end)) as customs,
         0 as duty
         into #duty
         from stkitem st
         join _CplHScode ce on st.cComponent=ce.HSCode join _cplshipmentlines cs
         on st.stocklink=cs.stkcode join _cplshipmentmaster tm on cs.shipment_no=tm.shipment_no 
         join _cplcostmaster cr on cs.costcode=cr.id
         join _cplScheme cme on cs.scheme=cme.Scheme
         where tm.id=cast($query as int) and st.ServiceItem<>1
         group by invoicelineid,cs.code
         
         update #duty set duty =ce.rate from #duty join _cplscheme ce on #duty.scheme=ce.Scheme
         join _cplcostmaster cr 
         on ce.Cost_Code=cr.id where cost='duty'
 
         
        --update customs value
        update _cplshipmentlines set customs_value=customs from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join stkitem st on cs.stkcode=st.StockLink
        where tm.id=cast($query as int) and customs_modified_date is null and st.ServiceItem<>1";

        sqlsrv_query($conn, $duty) or die(print_r( sqlsrv_errors(), true));
    }
    Else{
        $duty="IF OBJECT_ID('tempdb..#duty') IS NOT NULL DROP TABLE #duty;
         select distinct invoicelineid, cs.code, max(cme.scheme) as scheme,(max(amount*cs.rate*qty)+
         max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
         max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end)) as customs,
         0 as duty
         into #duty
         from stkitem st
         join _CplHScode ce on st.cComponent=ce.HSCode join _cplshipmentlines cs
         on st.stocklink=cs.stkcode join _cplshipmentmaster tm on cs.shipment_no=tm.shipment_no 
         join _cplcostmaster cr on cs.costcode=cr.id
         join _cplScheme cme on cs.scheme=cme.Scheme
         where tm.id=cast($query as int) and st.ServiceItem<>1
         group by invoicelineid,cs.code
         
         update #duty set duty =ce.rate from #duty join _cplscheme ce on #duty.scheme=ce.Scheme
         join _cplcostmaster cr 
         on ce.Cost_Code=cr.id where cost='duty'
 
         
        --update customs value
        update _cplshipmentlines set customs_value=0 from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join stkitem st on cs.stkcode=st.StockLink
        where tm.id=cast($query as int) and customs_modified_date is null and st.ServiceItem<>1";

        sqlsrv_query($conn, $duty) or die(print_r( sqlsrv_errors(), true));
    }
    sqlsrv_close($conn);
?>
