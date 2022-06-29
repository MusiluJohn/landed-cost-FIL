<?php
//get shipment no
    function shipmentid() {
        //if (isset($_GET['submit'])){
            include "config.php";
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select idShipment from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["idShipment"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//get shipment no
    function shipmentno() {
        //if (isset($_GET['submit'])){
            include "config.php";
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select cShipmentNo from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["cShipmentNo"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//get gross weight
    function grossweight() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select fGrossWtKg from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["fGrossWtKg"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//get volume
    function volume() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select fVolumeCbm from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["fVolumeCbm"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//get packages qty
    function packages() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select iPackages from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["iPackages"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//get eta date
    function etadate() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select format(dETAPort,'yyyy-MM-dd') as d from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["d"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//get eta office
    function etaoffice() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select format(dETAOffice,'yyyy-MM-dd') as d from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["d"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//get actualarrival date
    function arrivaldate() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select format(dactualport,'yyyy-MM-dd') as d from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["d"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//get custom entry number
    function customentrynumber() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select cCustomEntryNo from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["cCustomEntryNo"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//get custom entry date
    function customentrydate() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select format(dCustomEntryDate,'yyyy-MM-dd') as d from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["d"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//get custom pass date
    function custompassdate() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select format(dCustompassDate,'yyyy-MM-dd') as d from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["d"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//idf number
    function idfno() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select cIDFNo from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["cIDFNo"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//20ft containers
    function twentyft() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select i20ft from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["i20ft"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//40ft containers
    function fortyft() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select i40ft from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["i40ft"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//lcl 
    function lcl() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select ilcl from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["ilcl"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
?>
<?php
//cocno
    function cocno() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select ccocno from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["ccocno"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//etd origin date
    function etdorigin() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select format(detdorigin,'yyyy-MM-dd') as d from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["d"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//usd rate
    function usdrate() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select fexchrateUSD from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["fexchrateUSD"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//Euro rate
    function eurrate() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select fexchrateEUR from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["fexchrateEUR"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//freight usd
    function freight() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select ffreightchgsUSD from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["ffreightchgsUSD"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//freight eur
    function freighteur() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select ffreightchgseur from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["ffreightchgseur"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//other charges
    function othercharges() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select fotherchgsHOME from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["fotherchgsHOME"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//insurance charges
    function inscharges() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select finsurancechgsHOME from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["finsurancechgsHOME"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//port charges
    function portcharges() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select fportchgsHOME from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["fportchgsHOME"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//agency fees
    function agencyfees() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select fagencyfeesHOME from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["fagencyfeesHOME"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//kebs fees
    function kebsfees() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select fKEBSfeesHOME from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["fKEBSfeesHOME"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//awb no
    function awbno() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select cawbblno from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["cawbblno"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//transport
    function transport() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select cmode from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["cmode"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//clearing agent
    function agent() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select ccagent from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["ccagent"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//status
    function status() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select cstatus from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["cstatus"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//payment status
    function paymentstatus() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select cpaymentstatus from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["cpaymentstatus"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo '';    
        }
    }//}
?>
<?php
//awb no
    function lcdate() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select format(lcdate,'yyyy-MM-dd') as lcdate from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["lcdate"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//awb no
    function lcno() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select lcno from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["lcno"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//awb no
    function lcbank() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select lcbank from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["lcbank"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//awb no
    function gbprate() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select gbprate from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["gbprate"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//awb no
    function duty() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select duty_to_pay from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["duty_to_pay"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>
<?php
//awb no
    function duty_date() {
    include "config.php";
        //if (isset($_GET['submit'])){
        $query = $_SESSION['shipno'] ?? '';
        $sql = "select format(date_to_pay_duty,'yyyy-MM-dd') as date_to_pay_duty from _cplshipment where cShipmentNo='$query'";	
        $stmt = sqlsrv_query($conn,$sql);
        if ($stmt) {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $select=$row["date_to_pay_duty"];
            }
        }
        if (isset($select)){
        echo $select;
        } else{
        echo 0;    
        }
    }//}
?>