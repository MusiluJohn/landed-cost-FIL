<?php
    include("config.php");
    $conn = sqlsrv_connect( $servername, $connectioninfo); 
    $shipid=$_POST['shipid'] ?? 0;
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
    $lcl= $_POST['lcl'] ?? 0;
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
    $gbprate= $_POST['gbprate'] ?? 0;
    $dutypaid= $_POST['dutypaid'] ?? '';
    $dateduty= $_POST['dateduty'] ?? '';  
    $lcno=$_POST['lcnum'] ?? '';
    $lcdate=$_POST['lcdate'] ?? '';
           $sql = "update _cplshipment set cshipmentno='$shipno',cmode='$mode',detaport='$portdate',detaoffice='$officedate', fgrosswtkg=$weight,
           fVolumeCbm=$volume, iPackages=$packages, cCustomEntryNo='$customno',dCustomEntryDate='$customdate',dCustomPassDate='$passdate',cIDFNo='$idfno',
           i20ft=$twentyft,i40ft=$fortyft,ilcl=$lcl,ccagent='$clagent',cstatus='$status',dactualport='$arrdate',dshipmentdate='$etddate',ccocno='$coc',
           detdorigin='$etddate',cpaymentstatus='$paystatus',
           fotherchgsHOME=$othchgs,fexchrateUSD=$usdrate,fexchrateEUR=$eurrate,
           finsurancechgsHOME=$inschgs,fportchgsHOME=$portchgs,fagencyfeesHOME=$agfees,
           fKEBSfeesHOME=$kebsfees,ffreightchgsUSD=$freightusd,ffreightchgsEUR=$freigheur,cawbblno='$awbno',gbprate=$gbprate,
           duty_to_pay=$dutypaid,date_to_pay_duty='$dateduty',lcno='$lcno',lcdate='$lcdate' 
           from _cplshipment where idshipment=$shipid";
           sqlsrv_query($conn,$sql) or die(print_r( sqlsrv_errors(), true));		
?>