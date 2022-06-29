
<!DOCTYPE html>
<html>
<!-- <?php session_start(); ?> -->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
* {box-sizing: border-box;}



.topnav {
  overflow: hidden;
  background-color: #e9e9e9;
 
  position: fixed;
  top: 0;
  width: 100%;
  
 
  
  height: 60px; /* Used in this example to enable scrolling */
}

.topnav a {
  float: left;
  display: block;
  color: black;
  text-align: center;
  padding: 9px 5px;
  text-decoration: none;
  font-size: 12px;
  border-radius:4px;
}

.topnav a:hover {
  background-color: #87CEFA;
  color: white;
}

.topnav .nav-item:hover + .sub-menu {
  background-color: #87CEFA;
  color: red;
  display: block;
}

.topnav .sub-menu {
  display: none;
}

.topnav a.active {
  background-color: #2196F3;
  color: white;
  border-radius: 4px;
}

.topnav .search-container {
  float: right;
}

.topnav input[type=text] {
  padding: 6px;
  margin-top: 8px;
  font-size: 17px;
 
  border-radius: 4px;
}
.topnav input[type=text]:focus {
  border: 3px Solid #1bcb0a;
}

.topnav .search-container button {
  float: right;
  padding: 6px;
  margin-top: 8px;
  margin-right: 12px;
  margin-left: 12px;
  background: #ddd;
  font-size: 17px;
  border: none;
  cursor: pointer;
}

.topnav .search-container button:hover {
  background-color: #1bcb0a;
}

@media screen and (max-width: 600px) {
  .topnav .search-container {
    float: none;
  }
  .topnav a, .topnav input[type=text], .topnav .search-container button {
    float: none;
    display: block;
    text-align: left;
    width: 100%;
    margin: 0;
    padding: 14px;
  }
  .topnav input[type=text] {
    border: 3px Solid #1bcb0a;  

}

</style>
</head>
<body>



<div class="topnav">
<a  class="navbar-brand" href="CostEstHome.php">
      <img src="clogo.png" alt="" width="40" height="40" class="d-inline-block align-text-top" style='margin-down:10px;'>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="nav-link" aria-current="page" href="createscheme.php">CREATE SCHEME</a>
    <a class="nav-link" href="schemes.php">VIEW SCHEME</a>
    <a class="nav-link" href="createibt.php">CREATE IBT</a>
    <a class="nav-link" href="supplier_costing2.php">CREATE SUPPLIER BATCH</a>
    <a class="nav-link" href="CreateShipment.php">CREATE SHIPMENTS</a>
    <a class="nav-link" href="EditShipment.php">EDIT SHIPMENTS</a>
    <ul>
        <a class="sub-menu" href="#">Item History Report</a>
    </ul>
    <a class="nav-link" href="" style='color:red'><?php echo 'Logged in as: '.$_SESSION['users']. ''; ?></a>
    <a class="nav-link" href="" style='color:red'><?php echo 'Company: '.$_SESSION['db']. ''; ?></a>
    <a class="nav-link" href="index.php" style='color:blue'>sign out</a>
   
</div>

