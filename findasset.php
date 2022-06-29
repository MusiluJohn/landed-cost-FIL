<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Scan assets</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-lg fixed-top bg-white clean-navbar">
        <div class="container"><a class="navbar-brand logo" href="#">MTN</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <!-- <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="addasset.php">Add asset</a></li>
                </ul> -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link active" href="findasset.php">Asset details</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="sessionmanagment.php">Session Managment</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link " href="completesession.php">Complete Session</a></li>
                </ul>
            </div>
        </div>
    </nav>


    <main class="page registration-page">
        <section class="clean-block clean-form light ">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text-info">Asset Details</h2>
                    <div>
                        <?php
                            session_start();
                            if(isset($_SESSION['sessionName'])){
                               echo " <div class='alert alert-success' role='alert'>
                                    The selected Session is :- ".$_SESSION['sessionName']."
                                </div>";

                            }else{
                                echo " <div class='alert alert-danger' role='alert'>
                                    No session has been selected
                                </div>";
                            }
                        ?>
                    </div>
                </div>
                <form method="post" id = 'myform'>
                    <div class="form-group">
                        <label for="barcode">Barcode</label>
                        <input class="form-control item" type="text" class="barcode" id="barcode" name="barcode" onchange="submitForm();" >
                    </div>
                    <?php
                        // session_start();
                        if(isset($_SESSION['sessionName']) and isset($_SESSION['username'])){
                            echo "<input type='submit' name='submit' value='Fetch Asset' class='btn btn-primary btn-block btn-lg'>";}
                            else{
                                echo "<input type='submit' disabled name='submit' value='No session' class='btn btn-warning btn-block btn-lg'>";
                            }

                    ?>
                </form>
                <?php
                    require "connect.php";
                    if (isset($_POST['submit']) || isset($_POST['barcode']))
                    {
                        
                        $barcode =$_POST['barcode'];
                        // echo $barcode;
                        $sql = " EXEC insertScanned ?,?,?";
                        $params = array($barcode, $_SESSION['sessionName'],$_SESSION['username'] );
                        $stmt = sqlsrv_query( $conn, $sql,$params);
                        sqlsrv_close($conn);

                    }
                ?>

                <!-- Table thatfetches the scanned items based on location -->
                </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Quantity</th><th>Item Code</th><th>Item Name</th><th>Location</th><th>Scanned Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    // session_start();
                                    if (isset($_SESSION['sessionName'])){
                                    $namedSession = $_SESSION['sessionName'];
                                    require './connect.php';
                                    $sql = "select count(itemcode) as quantity,itemcode, itemname, itemLocation, scannedLocation from scannedAssetHistory 
                                            where session = ?
                                            group by itemcode,itemname,itemLocation,scannedLocation";
                                    $params = array($namedSession);
                                    $stmt = sqlsrv_query( $conn,$sql, $params);
                                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                                        echo "<tr><td>".$row['quantity']."</td><td>".$row['itemcode']."</td>
                                        <td>".$row['itemname']."</td><td>".$row['itemLocation']."</td><td>".$row['scannedLocation']."</td>
                                        
                                        </tr>";
                                    }
                                    sqlsrv_close($conn);
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </section>
    </main>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script type='text/javascript'> 
        function submitForm(){ 
        // Call submit() method on <form id='myform'>
        document.getElementById('myform').submit(); 
        } 
    </script>
    <script>
        if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>

</html>