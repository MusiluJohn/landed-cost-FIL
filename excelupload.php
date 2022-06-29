<?php include './connect.php'; include './functions.php'; ?>

<!DOCTYPE html>
<html lang="en">
<title>
</title>
<link rel="stylesheet" type="text/css" href="bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="bootsrap2.css"/>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form method="post" enctype="multipart/form-data" >
        <label class='form-control' for="fileToUpload">Select Payroll Filename:</label>
        <input class='form-control' type="file" id="fileToUpload" name="fileToUpload" accept=".xlsx" required>
        <input class="btn btn-success" type="submit" name ="submit" style='margin-top:10px;margin-left:13px;'>
    </form>
    <?php
    include './SimpleXLSX.php';
    if (isset($_POST['submit']) and isset($_FILES['fileToUpload'])) {
        $file_name = $_FILES['fileToUpload']['name'];
        $file_tmp = $_FILES['fileToUpload']['tmp_name'];
        if (file_exists("uploads/".$file_name)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        else{
            move_uploaded_file($file_tmp,"uploads/".$file_name);
            echo "Success";
            if ( $xlsx = SimpleXLSX::parse("uploads/".$file_name) ) {
                foreach ( $xlsx->rows() as $row ){
                    insertArray($row);
                }
              
            }
        }
        
    }

    ?>
</body>
<script>
    if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
    }
</script>
</html>