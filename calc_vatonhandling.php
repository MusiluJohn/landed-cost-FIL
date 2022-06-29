<?php
        //require_once("insert.php");
        include("config.php");
          $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
          $value=$_POST['vatonhandling'] ?? 0;
          $rate=$_POST['rate'] ?? 0;

        if ($value=='yes'){
        $vatonhandling="IF OBJECT_ID('tempdb..#vatonhandling') IS NOT NULL DROP TABLE #vatonhandling
        select distinct invoicelineid, cs.code,(sum(case when cr.cost='Handling' then 0.16*cs.cost else 0 end)+ 
        sum(case when cr.cost='CustomsVerification' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='AgencyFee' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='Documentation_Charges' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='Transport_Charges' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='Disbursement' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='BreakBulkCharges' then 0.16*cs.cost else 0 end)) as vatonhandling
        into #vatonhandling
        from stkitem st
        join _cplshipmentlines cs
        on st.stocklink=cs.stkcode join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join _cplcostmaster cr
        on cs.costcode=cr.id
        where tm.id=cast($query as int)
        group by invoicelineid,cs.code

        --update customs value
        update _cplshipmentlines set vatonhandling=dt.vatonhandling from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #vatonhandling dt on cs.invoicelineid=dt.invoicelineid
        where tm.id=cast($query as int)";

        sqlsrv_query($conn, $vatonhandling) or die(print_r( sqlsrv_errors(), true));
    }
    Else{

    	$vatonhandling="IF OBJECT_ID('tempdb..#vatonhandling') IS NOT NULL DROP TABLE #vatonhandling
        select distinct invoicelineid, cs.code,(sum(case when cr.cost='Handling' then 0.16*cs.cost else 0 end)+ 
        sum(case when cr.cost='CustomsVerification' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='AgencyFee' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='Documentation_Charges' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='Transport_Charges' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='Disbursement' then 0.16*cs.cost else 0 end)+
        sum(case when cr.cost='BreakBulkCharges' then 0.16*cs.cost else 0 end)) as vatonhandling
        into #vatonhandling
        from stkitem st
        join _cplshipmentlines cs
        on st.stocklink=cs.stkcode join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join _cplcostmaster cr
        on cs.costcode=cr.id
        where tm.id=cast($query as int)
        group by invoicelineid,cs.code

        --update customs value
        update _cplshipmentlines set vatonhandling=0 from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #vatonhandling dt on cs.invoicelineid=dt.invoicelineid
        where tm.id=cast($query as int)";

        sqlsrv_query($conn, $vatonhandling) or die(print_r( sqlsrv_errors(), true));
    }
    sqlsrv_close($conn);
?>
