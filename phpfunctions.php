<?php
include("config.php");
$conn = sqlsrv_connect( $servername, $connectioninfo);
$query = $_GET['SH'];
$results = array('error' => false, 'data' => '');
         $sql2="--get the sumation
         IF OBJECT_ID('tempdb..#tmpship') IS NOT NULL DROP TABLE #tmpship
         select costcode, (sum(volume)) as tot, sum(weight) as totweight,sum(amount) as amt 
         into #tmpship
         from _cplshipmentlines join _cplshipmentmaster on _cplshipmentlines.shipment_no=
         _cplshipmentmaster.shipment_no
         where 
         _cplshipmentmaster.id=$query
         group by costcode";
        //  $marinecost="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
        //  select distinct invoicelineid, max(code)as code,max(qty) as qty,max(amount) as amt,
        //  sum(st.Cost) as freight, (((((max(amount)+(sum(st.Cost)/max(qty)))*111.915)*max(qty))*1.1)*0.00249)
        //  as marine
        //  into #tmpcost 
        //  from _cplshipmentlines st join _cplshipmentmaster tm
        //  on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
        //  where tm.id=cast($query as int) and cr.Cost='FreightKsh'
        //  group by invoicelineid
         
        //  update _cplshipmentlines set cost= marine
        //  from _cplshipmentlines ts join #tmpcost ty on ts.invoicelineid=ty.invoicelineid
        //  join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        //  join _cplcostmaster cr on ts.costcode=cr.id
        //  where tr.id=cast($query as int)  and ts.active='True' and cr.cost='MarineCost'
        //  and isnull(marine_modified_date,0)=0";               
         //duty
         $duty="IF OBJECT_ID('tempdb..#duty') IS NOT NULL DROP TABLE #duty;
         select distinct invoicelineid, cs.code, max(cme.scheme) as scheme,(max(amount*cs.rate*qty)+
         max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
         max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end)) as customs,
         0 as duty
         into #duty
         from stkitem st
         join _cplshipmentlines cs
         on st.stocklink=cs.stkcode join _cplshipmentmaster tm on cs.shipment_no=tm.shipment_no 
         join _cplcostmaster cr on cs.costcode=cr.id
         join _cplScheme cme on cs.scheme=cme.Scheme
         where tm.id=cast($query as int) and st.ServiceItem<>1
         group by invoicelineid,cs.code
         
         update #duty set duty =ce.rate from #duty join _cplscheme ce on #duty.scheme=ce.Scheme
         join _cplcostmaster cr 
         on ce.Cost_Code=cr.id where cost='duty'"; 
        $exciseduty="IF OBJECT_ID('tempdb..#exciseduty') IS NOT NULL DROP TABLE #exciseduty;
         select distinct invoicelineid, cs.code, max(cme.scheme) as scheme,(max(amount*cs.rate*qty)+
         max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
         max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end)) as customs,
         0 as exciseduty
         into #exciseduty
         from stkitem st
        join _cplshipmentlines cs
         on st.stocklink=cs.stkcode join _cplshipmentmaster tm on cs.shipment_no=tm.shipment_no 
         join _cplcostmaster cr on cs.costcode=cr.id
         join _cplScheme cme on cs.scheme=cme.Scheme
         where tm.id=cast($query as int) and st.ServiceItem<>1
         group by invoicelineid,cs.code";
         
         $upeduty="update #exciseduty set exciseduty =isnull(ce.vat,0) from #exciseduty join _cplscheme ce on #exciseduty.scheme=ce.Scheme
         join _cplcostmaster cr 
         on ce.Cost_Code=cr.id where cost='EXCISE_DUTY'";
         
         $updateduty="--update duty
        update _cplshipmentlines set cost=((cast(duty as float)/100)*cast(customs as float)) from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id join stkitem st on cs.stkcode=st.StockLink
        where tm.id=cast($query as int) and cr.cost='Duty' and calc_duty=1 and cs.duty_modified_date is null and st.ServiceItem<>1"; 

         $updateexcise=" --update excise duty
         update _cplshipmentlines set cost=((cast(exciseduty as float)/100)*cast(customs as float)) from _cplshipmentlines cs join _cplshipmentmaster tm
         on cs.shipment_no=tm.shipment_no join #exciseduty dt on cs.invoicelineid=dt.invoicelineid
         join _cplcostmaster cr on cs.costcode=cr.id
         where tm.id=cast($query as int) and cr.cost='EXCISE_DUTY' and excise_duty_modified_date is null";

        $sql3="update _cplshipmentlines set cost=  (case when cb.calcbase='volume' then volume/nullif(tot,0)*ce.rate when cb.calcbase='weight' then weight/nullif(totweight,0)*ce.rate when cb.calcbase='FOB' then 
         ((amount*qty)/nullif(amt,0) * ce.rate) when cb.calcbase='N/A' then ts.cost else 0 end ) from	_cplshipmentlines ts join _cplScheme ce on ts.costcode=ce.Cost_Code and ce.Scheme=ts.scheme 
         join _cplshipmentmaster ttr on ts.shipment_no=ttr.shipment_no
         join #tmpship on ts.costcode=#tmpship.costcode
         join _cplcalcbase cb on ce.calcbase=cb.id
         join _cplcostmaster cr on ts.costcode=cr.id
         where ttr.id=cast($query as int) and active='True' and cr.cost not in ('GOK','RailwayLevy','Duty','EXCISE_DUTY') and isnull(updated,0)=0";
         $updatetotals="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
         select code,stkcode,round(sum(Cost)+(max(amount)*max(qty)*max(rate)),2) as totcost 
         into #totals
         from _cplshipmentlines ts
         join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
         where tr.id=cast($query as int)
         group by code,stkcode
         
         update _cplshipmentlines set totals=tts.totcost from _cplshipmentlines ts  
         join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
         join #totals tts on ts.stkcode=tts.stkcode
         where tr.id=cast($query as int)";
         $actualfactor="IF OBJECT_ID('tempdb..#actualfactor') IS NOT NULL DROP TABLE #actualfactor
         select distinct invoicelineid,code,stkcode,round(((sum(ts.Cost)+(max(amount)*max(qty)*max(rate)))/(max(amount)*max(qty)*max(rate))),2) as factor
         into #actualfactor
         from _cplshipmentlines ts
         join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
         join _cplcostmaster cr on ts.costcode=cr.id
         where tr.id=cast($query as int)
         group by invoicelineid,code,stkcode
         
         update _cplshipmentlines set correctfactor=b.factor from _cplshipmentlines a join
         #actualfactor b on a.invoicelineid=b.invoicelineid";
         sqlsrv_query($conn,$sql2) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$duty) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$exciseduty) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$upeduty) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$updateduty) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$updateexcise) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$updatetotals) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$sql3) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$actualfactor) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_close($conn);
?>
