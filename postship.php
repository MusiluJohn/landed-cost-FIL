<?php
    include("config.php");
    $conn = sqlsrv_connect( $servername, $connectioninfo); 
    $shipno=$_POST['shipno'] ?? '';
    $mode= $_POST['mode'] ?? '';
    $weight= $_POST['weight'] ?? 0;
    $volume= $_POST['volume'] ?? 0; 
    $packages= $_POST['packages'] ?? 0;
    $portdate= $_POST['portdate'] ?? '';
    $officedate= $_POST['officedate'] ?? '';
    $arrdate= $_POST['arrdate'] ?? '';
    $customno= $_POST['customno'] ?? '';
    $customdate= $_POST['customdate'] ?? '';
    $passdate= $_POST['passdate'] ?? '';
    $idfno= $_POST['idfno'] ?? '';
    $twentyft= $_POST['twentyft'] ?? 0;
    $fortyft= $_POST['fortyft'] ?? 0;
    $lcl= $_POST['lcl'] ?? 1;
    $clagent= $_POST['clagent'] ?? '';
    $status= $_POST['status'] ?? '';
    $awb= $_POST['awb'] ?? '';
    $coc= $_POST['coc'] ?? '';
    $etddate= $_POST['etddate'] ?? '';
    $paystatus= $_POST['paystatus'] ?? '';
    $usdrate= $_POST['usdrate'] ?? 0;
    $eurrate= $_POST['eurrate'] ?? 0;
    $freightusd= $_POST['freightusd'] ?? 0;
    $freigheur= $_POST['freigheur'] ?? 0;
    $othchgs= $_POST['othchgs'] ?? 0;
    $inschgs= $_POST['inschgs'] ?? 0;
    $portchgs= $_POST['portchgs'] ?? 0;
    $agfees= $_POST['agfees'] ?? 0;
    $kebsfees= $_POST['kebsfees'] ?? 0;
    $awbno= $_POST['awbno'] ?? '';
    $mino= $_POST['mino'] ?? '';
    $pino= $_POST['pino'] ?? '';
    $pidate= $_POST['pidate'] ?? '';
    $cino= $_POST['cino'] ?? '';
    $cidate= $_POST['cidate'] ?? '';
    $pickupno= $_POST['pickupno'] ?? '';
    $lcno= $_POST['lcno'] ?? 0;
    $lcdate= $_POST['lcdate'] ?? '';
    $lcbank= $_POST['lcbank'] ?? '';  
    $gbprate= $_POST['gbprate'] ?? '';
    $dutypaid= $_POST['dutypaid'] ?? '';
    $dateduty= $_POST['dateduty'] ?? '';    
           $sql = "insert into _cplshipment (cshipmentno,cmode,detaport,detaoffice, fgrosswtkg,
           fVolumeCbm, iPackages, cCustomEntryNo,dCustomEntryDate,dCustomPassDate,cIDFNo,
           i20ft,i40ft,ilcl,ccagent,cstatus,dactualport,dshipmentdate,ccocno,detdorigin,cpaymentstatus,
           fotherchgsHOME,fexchrateUSD,fexchrateEUR,cawbblno,lcNo,lcDate,lcBank,gbprate,duty_to_pay,date_to_pay_duty)
           values ('$shipno','$mode',isnull('$portdate',getdate()),'$officedate',isnull($weight,0),isnull($volume,0),isnull($packages,0),
           isnull('$customno',getdate()),isnull('$customdate',getdate()),isnull('$passdate',getdate()),'$idfno',isnull($twentyft,0),isnull($fortyft,0),isnull($lcl,1),'$clagent',
           '$status',isnull('$arrdate',getdate()),isnull('$etddate',getdate()),'$coc',isnull('$etddate',getdate()),'$paystatus',isnull($othchgs,0),isnull($usdrate,0),
           isnull($eurrate,0),'$awbno',
           isnull($lcno,0),isnull('$lcdate',getdate()),'$lcbank',$gbprate,'$dutypaid','$dateduty')";
           sqlsrv_query($conn,$sql) or die(print_r( sqlsrv_errors(), true));
?>