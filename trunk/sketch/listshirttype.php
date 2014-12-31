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
    <title>List Shirt Tye</title>   
    <!--CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">   
    <link href="css/jasny-bootstrap.css" rel="stylesheet"> 

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
  <form class="form-inline" id="shirttype-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" data-toggle="validator">
  <div class="container">
    <div class="col-xs-6 col-md-4">    
      <div class="input-group">
        <span class="input-group-btn">
          <button class="btn btn-default" type="submit">Go!</button>
        </span>
        <input type="text" class="form-control" id="txt-shirttype" placeholder="ประเภทเสื้อ" 
        name="txtShirtType">
        
      </div>
      <input class="btn btn-default" type="button" value="New"/>
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

$sql = "select shirt_type, shirt_type_description from shirt_type where 1 = 1 ";

if (!empty($_GET["txtShirtType"])){
  $sql .= "and shirt_type like :shirt_type order by shirt_type asc ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":shirt_type", "%" .$_GET["txtShirtType"]. "%");
}else{
  $sql .= "order by shirt_type asc ";
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
        <th>แบบเสื้อ</th>
        <th>คำอธิบาย</th>       
      </tr>
    </thead>
    <tbody data-link="row" class="rowlink">
      <?php
      $i = 1;
      foreach($result as $row) {
        $shirt_type = $row["shirt_type"];
        $shirt_type_description = $row["shirt_type_description"];
        echo "<tr>";

        echo "<th scope=\"row\">" .$i. "</th>";
        echo "<td><a href=\"manageshirttype.php?shirttype=" .urlencode($shirt_type). "\">" .$shirt_type. "</a></td>";
        echo "<td>" .$shirt_type_description. "</td>";

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
