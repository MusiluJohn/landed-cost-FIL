<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['ID'];
            $freight=$_POST['freight'] ?? 0;
            $dry=$_POST['dry'] ?? 0;
            $dgf=$_POST['dgf'] ?? 0;
            $import=$_POST['importduty'] ?? 0;
            $ppb=$_POST['ppb'] ?? 0;
            $idf=$_POST['idf'] ?? 0;
            $krd1=$_POST['krd1'] ?? 0;
            $duty=$_POST['exciseduty'] ?? 0;
            $hsc=$_POST['hsc'] ?? 0;
            $rate=$_POST['rate'] ?? 0;
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
        select ts.costcode,ts.shipment_no,ts.qty,ts.code, (case when max(cb.calcbase) in ('Volume') then (max(volume)/nullif(sum(#tmpship.tot),0)) when max(cb.calcbase)='weight' then (max(weight)/nullif(sum(totweight),0))  else 0 end) as vol_weigh_amts,
        case when max(cb.calcbase) =('FOB') then ((max(amount*qty))/nullif(sum(amt),0))  else 0 end as fobamt, case when max(cb.calcbase) in ('#NA') then max(cost) end as import
        into #tmpamts
        from _cplshipmentlines ts join _cplScheme ce on ts.costcode=ce.Cost_Code and ce.Scheme=ts.scheme  join _cplshipmentmaster ttr on ts.shipment_no=ttr.shipment_no
        join #tmpship on ts.costcode=#tmpship.costcode join _cplcalcbase cb on ce.calcbase=cb.id
        where ttr.id=$query
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
    sqlsrv_close($conn);

?>