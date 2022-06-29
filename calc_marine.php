<?php
include("config.php");
$conn = sqlsrv_connect( $servername, $connectioninfo);
$query = $_POST['id'];
$marine=$_POST['marine'] ?? 0;
$rate=$_POST['rate'] ?? 0;

$marinecalc="IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
select distinct invoicelineid, max(code)as code,max(qty) as qty,max(amount) as amt,
sum(st.Cost) as freight, (((((max(amount)+(sum(st.Cost)/max(qty)))*$marine)*max(qty))*1.1)*0.00249)
as marine
into #tmpcost 
from _cplshipmentlines st join _cplshipmentmaster tm
on st.shipment_no=tm.shipment_no join _cplcostmaster cr on st.costcode=cr.id
where tm.id=cast($query as int) and cr.Cost='FreightKsh'
group by invoicelineid

update _cplshipmentlines set cost= marine,marine_modified_date=format(getdate(),'yyyy-MM-dd')
from _cplshipmentlines ts join #tmpcost ty on ts.invoicelineid=ty.invoicelineid
join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
join _cplcostmaster cr on ts.costcode=cr.id
where tr.id=cast($query as int)  and ts.active='True' and cr.cost='MarineCost'";

sqlsrv_query($conn, $marinecalc)  or die(print_r( sqlsrv_errors(), true)) ;
?>