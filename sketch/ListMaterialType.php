<!DOCTYPE html>
<?php

session_start();
if (!isset($_SESSION["email"]) || empty($_SESSION["email"])){
  header("location: index.php");
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Material Type</title>   
    <!--CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">       
    <link href="css/bootstrapValidator.css" rel="stylesheet">
    <link href="css/iconfont.css" rel="stylesheet">
    <!--List -->
    <link href="css/jasny-bootstrap.css" rel="stylesheet">

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script> 
  </head>
  <body>  
  <div class="alert alert-success" role="alert" style="display:none; z-index: 1000; position: absolute; left:0px; top: 50px;">
      <span>The examples populate this alert with dummy content</span>
  </div>
  <?php
    require("navbar.php");
    require("service/message_service.php");
    require("service/db_connect.php");

  ?>  
  <form class="form-inline" id="listmaterialtype-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
  <div class="container">
    <div class="col-xs-6 col-md-4">    
      <div class="input-group">
        <span class="input-group-btn">
          <button class="btn btn-default icon-search" type="submit"></button>
        </span>
        <input type="text" class="form-control" id="txt-materialtype" placeholder="ประเภทผ้า" 
        name="txtMaterialType">
        
      </div>
      <a role="button" class="btn btn-default" href="managematerialtype.php?materialtype=">New</a>
    </div>    
  </div>
  </form>
  <br/>

<?php 
try {
    $conn = dbConnect::getInstance();
    $dbh = $conn->dbh;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$sql = "select material_type, description from material where 1 = 1 ";

if (!empty($_GET["txtMaterialType"])){
  $sql .= "and material_type like :material_type order by material_type asc ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":material_type", "%" .$_GET["txtMaterialType"]. "%");
}else{
  $sql .= "order by material_type asc ";
  $stmt = $dbh->prepare($sql);
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
  <div class="container">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>ประเภทผ้า</th>
        <th>คำอธิบาย</th>       
      </tr>
    </thead>
    <tbody data-link="row" class="rowlink">
      <?php
      $i = 1;
      foreach($result as $row) {
        $material_type = $row["material_type"];
        $description = $row["description"];
        echo "<tr>";

        echo "<th scope=\"row\">" .$i. "</th>";
        echo "<td><a href=\"managematerialtype.php?materialtype=" .urlencode($material_type). "\">" .$material_type. "</a></td>";
        echo "<td>" .$description. "</td>";

        echo "</tr>";
        $i ++;
      }
      ?>       
    </tbody>
  </table>
  </div>
  <script type="text/javascript" src="js/jasny-bootstrap.min.js"></script>
  </body>
</html>