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
    <title>List Shirt Color</title>   
    <!--CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">       
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
  <?php
    require("navbar.php");
    require("service/message_service.php");
    require("service/db_connect.php");

  ?>  
  <form class="form-inline" id="list-shirt-color-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
  <div class="container">
    <div class="col-xs-6 col-md-4">    
      <div class="input-group">
        <span class="input-group-btn">
          <button class="btn btn-default icon-search" type="submit"></button>
        </span>
        <input type="text" class="form-control" id="txt-search" placeholder="สีเสื้อ" 
                name="txtSearch">
        
      </div>
      <a role="button" class="btn btn-default" href="manageshirtcolor.php?color=">New</a>
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

$sql = "select color, color_hex from shirt_color where 1 = 1 ";

if (!empty($_GET["txtSearch"])){
  $sql .= "and color like :color order by color asc ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":color", "%" .$_GET["txtSearch"]. "%");
}else{
  $sql .= "order by color asc ";
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
        <th>สี</th>
        <th>รหัส Hex</th>       
        <th></th>
      </tr>
    </thead>
    <tbody data-link="row" class="rowlink">
      <?php
      $i = 1;
      foreach($result as $row) {
        $color = $row["color"];
        $color_hex = $row["color_hex"];

        echo "<tr>";

        echo "<th scope=\"row\">" .$i. "</th>";
        echo "<td><a href=\"ManageShirtColor.php?color=" .urlencode($color). "\">" .$color. "</a></td>";
        echo "<td>" .$color_hex. "</td>";
        echo "<td><span class=\"form-control\" style=\"background-color:" .$color_hex. "; height: 20px;\"</span></td>";
        //echo "<td><div class=\"well\" style=\"background:".$color_hex. ";\"></div></td>";
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