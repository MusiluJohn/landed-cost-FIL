<?php
session_start();
include("config.php");
if(isset($_POST["submit"])) {
    print_r($_POST["users"]);
    foreach ($_POST["users"] as $key => $value){
        $id=$_POST["users"][$key];
        $update="insert into _cplshipmentlines(shipment_no,code, description,qty,amount,
        volume,unit_weight,scheme,Costcode,stkcode,active,invoicelineid,po_no,tot_amount,
        weight,clientid,grv_no,grv_qty,calc_duty)
        select '" .$_SESSION['shipment_no']. "', st.Code,st.Description_1 ,
        isnull(bl.fQtyProcessed,0), isnull(fUnitPriceInclForeign,0),isnull(st.ufIIVolume,0)
        ,isnull(st.ufIIWeight,0),st.ucIIScheme,ce.Cost_Code,bl.iStockCodeID,'True',$id,Ordernum
        ,(isnull(bl.fQtyProcessed,0)*isnull(fUnitPriceInclForeign,0)),(isnull(bl.fQtyProcessed,0)*isnull(st.ufIIWeight,0))
        ,im.AccountID,invnumber,fquantity,1 from _btblinvoicelines bl join StkItem st on 
        bl.istockcodeid=st.stocklink join invnum im on bl.iInvoiceID=im.AutoIndex 
        join _cplScheme ce on st.ucIIScheme=ce.Scheme
        join _cplcostmaster cr on ce.Cost_Code=cr.id  where bl.idinvoicelines=$id";
        sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));
    }
        $insship="insert into _cplshipmentmaster (shipment_no, create_date,userid)
        values ('" .$_SESSION['shipment_no']. "', getdate()," .$_SESSION['userid']. ")";
        sqlsrv_query($conn, $insship) or die(print_r( sqlsrv_errors(), true));
    }
        echo("<script>alert('Shipment successfully updated');</script>");
    header("Location:CostEstHome.php");
?>


