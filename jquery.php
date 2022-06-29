<script type="text/javascript">
    $(function() {
       var TotalValueqty = 0;var TotalValueuw=0;var TotalValueunw =0;
       var TotalValue = 0;var TotalValuefob = 0;var TotalValuekes= 0;
       var TotalValuew = 0;var TotalValuei=0;var TotalValuer=0;
       var TotalValueg=0;var TotalValueh=0;var TotalValuef=0;
       var TotalValuee=0;var TotalValuep=0;var TotalValuea=0;
       var TotalValuek=0;var TotalValueis=0;var TotalValuest=0;
       var TotalValuecp=0;var TotalValuev=0;var TotalValueag=0;
       var TotalValueds=0;var TotalValuebs=0;var TotalValueos=0;
       var TotalValueks=0;var TotalValuets=0;var TotalValuecs=0;
       var TotalValuesk=0;var TotalValuecc=0; var TotalValueos=0;
       var TotalValueek=0;var TotalValuesek=0;var TotalValueaek=0;
       var TotalValuest=0;var TotalValuedc=0;var TotalValueac=0;
       var TotalValuevk=0;var TotalValuelk=0;var TotalValuespk=0;
       var TotalValuetkes=0;var TotalValueus=0;var TotalValueins=0;

       $("tr #qtys").each(function(index,value){
         currentRowqty = parseFloat($(this).text());
         TotalValueqty += currentRowqty
       });

       document.getElementById('totalqty').innerHTML = TotalValueqty.toFixed(2);

       $("tr #fob").each(function(index,value){
         currentRow = parseFloat($(this).text());
         TotalValue += currentRow
       });

       document.getElementById('totalamt').innerHTML = TotalValue.toFixed(2);

       $("tr #totamt").each(function(index,value){
         currentRowfob = parseFloat($(this).text());
         TotalValuefob += currentRowfob
       });

       document.getElementById('totamount').innerHTML = TotalValuefob.toFixed(2);

       $("tr #totamtkes").each(function(index,value){
         currentRowkes = parseFloat($(this).text());
         TotalValuekes += currentRowkes
       });

       document.getElementById('totamountkes').innerHTML = TotalValuekes.toFixed(2);

       $("tr #unweight").each(function(index,value){
         currentRowuw = parseFloat($(this).text());
         TotalValueuw += currentRowuw
       });

       document.getElementById('unitw').innerHTML = TotalValuew.toFixed(2);
       
       $("tr #totweight").each(function(index,value){
         currentRowunw = parseFloat($(this).text());
         TotalValueunw += currentRowunw
       });

       document.getElementById('tweight').innerHTML = TotalValueunw.toFixed(2);

       $("tr #swifts").each(function(index,value){
         currentRoww = parseFloat($(this).text());
         TotalValuew += currentRoww
       });

       document.getElementById('swiftc').innerHTML = TotalValuew.toFixed(2);

       $("tr #insu").each(function(index,value){
         currentRowins = parseFloat($(this).text());
         TotalValueins += currentRowins
       });

       document.getElementById('insuto').innerHTML = TotalValueins.toFixed(2);

       $("tr #no").each(function(index,value){
         currentRowi = parseFloat($(this).text());
         TotalValuei += currentRowi
       });

       document.getElementById('dutyto').innerHTML = TotalValuei.toFixed(2);

       $("tr #raillevy").each(function(index,value){
         currentRowr = parseFloat($(this).text());
         TotalValuer += currentRowr
       });

       document.getElementById('rail').innerHTML = TotalValuer.toFixed(2);

       $("tr #goktot").each(function(index,value){
         currentRowg = parseFloat($(this).text());
         TotalValueg += currentRowg
       });

       document.getElementById('goktots').innerHTML = TotalValueg.toFixed(2);

       $("tr #custs").each(function(index,value){
         currentRowh = parseFloat($(this).text());
         TotalValueh += currentRowh
       });

       document.getElementById('custots').innerHTML = TotalValueh.toFixed(2);

       $("tr #frekes").each(function(index,value){
         currentRowf = parseFloat($(this).text());
         TotalValuef += currentRowf
       });

       document.getElementById('frekestot').innerHTML = TotalValuef.toFixed(2);

       $("tr #entrykes").each(function(index,value){
         currentRowe = parseFloat($(this).text());
         TotalValuee += currentRowe
       });

       document.getElementById('entrykestot').innerHTML = TotalValuee.toFixed(2);

       $("tr #penkes").each(function(index,value){
         currentRowp = parseFloat($(this).text());
         TotalValuep += currentRowp
       });

       document.getElementById('penkestot').innerHTML = TotalValuep.toFixed(2);

       $("tr #handlingkes").each(function(index,value){
         currentRowa = parseFloat($(this).text());
         TotalValuea += currentRowa
       });

       document.getElementById('handlingkestot').innerHTML = TotalValuea.toFixed(2);

       $("tr #kebskes").each(function(index,value){
         currentRowk = parseFloat($(this).text());
         TotalValuek += currentRowk
       });

       document.getElementById('kebskestot').innerHTML = TotalValuek.toFixed(2);

       $("tr #ismkes").each(function(index,value){
         currentRowis = parseFloat($(this).text());
         TotalValueis += currentRowis
       });

       document.getElementById('ismkestot').innerHTML = TotalValueis.toFixed(2);

       $("tr #storagekes").each(function(index,value){
         currentRowst = parseFloat($(this).text());
         TotalValuest += currentRowst
       });

       document.getElementById('storagekestot').innerHTML = TotalValuest.toFixed(2);

       $("tr #custprockes").each(function(index,value){
         currentRowcp = parseFloat($(this).text());
         TotalValuecp += currentRowcp
       });

       document.getElementById('custprockestot').innerHTML = TotalValuecp.toFixed(2);
       
       $("tr #custverkes").each(function(index,value){
         currentRowv = parseFloat($(this).text());
         TotalValuev += currentRowv
       });

       document.getElementById('custverkestot').innerHTML = TotalValuev.toFixed(2);

       $("tr #agencykes").each(function(index,value){
         currentRowag = parseFloat($(this).text());
         TotalValueag += currentRowag
       });

       document.getElementById('agencykestot').innerHTML = TotalValueag.toFixed(2);

       $("tr #docchkes").each(function(index,value){
         currentRowds = parseFloat($(this).text());
         TotalValueds += currentRowds
       });

       document.getElementById('docchkestot').innerHTML = TotalValueds.toFixed(2);

       $("tr #bbkes").each(function(index,value){
         currentRowbs = parseFloat($(this).text());
         TotalValuebs += currentRowbs
       });

       document.getElementById('bbkestot').innerHTML = TotalValuebs.toFixed(2);

       $("tr #offkes").each(function(index,value){
         currentRowos = parseFloat($(this).text());
         TotalValueos += currentRowos
       });

       document.getElementById('offkestot').innerHTML = TotalValueos.toFixed(2);
       
       $("tr #transkes").each(function(index,value){
         currentRowts = parseFloat($(this).text());
         TotalValuets += currentRowts
       });

       document.getElementById('transkestot').innerHTML = TotalValuets.toFixed(2);

       $("tr #cockes").each(function(index,value){
         currentRowcs = parseFloat($(this).text());
         TotalValuecs += currentRowcs
       });

       document.getElementById('cockestot').innerHTML = TotalValuecs.toFixed(2);

       $("tr #conckes").each(function(index,value){
         currentRowcc = parseFloat($(this).text());
         TotalValuecc += currentRowcc
       });

       document.getElementById('conckestot').innerHTML = TotalValuecc.toFixed(2);

       $("tr #surkes").each(function(index,value){
         currentRowsk = parseFloat($(this).text());
         TotalValuesk += currentRowsk
       });

       document.getElementById('surkestot').innerHTML = TotalValuesk.toFixed(2);

       $("tr #otherkes").each(function(index,value){
         currentRowos = parseFloat($(this).text());
         TotalValueos += currentRowos
       });

       document.getElementById('otherkestot').innerHTML = TotalValueos.toFixed(2);

       $("tr #excisekes").each(function(index,value){
         currentRowek = parseFloat($(this).text());
         TotalValueek += currentRowek
       });

       document.getElementById('excisekestot').innerHTML = TotalValueek.toFixed(2);

       $("tr #stampskes").each(function(index,value){
         currentRowspk = parseFloat($(this).text());
         TotalValuespk += currentRowspk
       });

       document.getElementById('stampskestot').innerHTML = TotalValuespk.toFixed(2);

       $("tr #disbkes").each(function(index,value){
         currentRowdc = parseFloat($(this).text());
         TotalValuedc += currentRowdc
       });

       document.getElementById('disbkestot').innerHTML = TotalValuedc.toFixed(2);
       
       $("tr #addkes").each(function(index,value){
         currentRowac = parseFloat($(this).text());
         TotalValueac += currentRowac
       });

       document.getElementById('addkestot').innerHTML = TotalValueac.toFixed(2);
       
       $("tr #vatkes").each(function(index,value){
         currentRowvk = parseFloat($(this).text());
         TotalValuevk += currentRowvk
       });

       document.getElementById('vatkestot').innerHTML = TotalValuevk.toFixed(2);
       
       $("tr #landkes").each(function(index,value){
         currentRowlk = parseFloat($(this).text());
         TotalValuelk += currentRowlk
       });

       document.getElementById('landkestot').innerHTML = TotalValuelk.toFixed(2);

       $("tr #totkes").each(function(index,value){
         currentRowtkes = parseFloat($(this).text());
         TotalValuetkes += currentRowtkes
       });

       document.getElementById('totkestot').innerHTML = TotalValuetkes.toFixed(2);
       
       $("tr #unitkes").each(function(index,value){
         currentRowus = parseFloat($(this).text());
         TotalValueus += currentRowus
       });

       document.getElementById('unitkestot').innerHTML = TotalValueus.toFixed(2);
});
</script>