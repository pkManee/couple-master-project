<!DOCTYPE html>
<?php
require("header.php");
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Size Price</title>   
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
  <form class="form-inline" id="list-shirts-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
  <div class="container">
    <div class="col-xs-6 col-md-4">    
      <div class="input-group">
        <span class="input-group-btn">
          <button class="btn btn-default icon-search" type="submit"></button>
        </span>
        <input type="text" class="form-control" id="txt-search" placeholder="คำอธิบาย" 
                name="txtSearch">
        
      </div>
      <a role="button" class="btn btn-default" href="ManageSizePrice.php?size_price_id=">New</a>
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

$sql = "select size_price_id, size_area, price, description ";
$sql .= "from size_price where 1 = 1 ";

if (!empty($_GET["txtSearch"])) {
  $sql .= "and description like :description order by shirt_name asc ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":description", "%" .$_GET["txtSearch"]. "%");
} else {
  $sql .= "order by size_area asc ";
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
        <th>คำอธิบาย</th>
        <th>ขนาด (ตร.ซม.)</th>
        <th style="text-align: right">ราคา</th>
      </tr>
    </thead>
    <tbody data-link="row" class="rowlink">
      <?php
      $i = 1;
      foreach($result as $row) {
        $size_price_id = $row["size_price_id"];
        $size_area = $row["size_area"];
        $price = $row["price"];        
        $description = $row["description"];   

        echo "<tr>";

        echo "<th scope=\"row\">" .$i. "</th>";
        
        echo "<td>" .$description. "</td>";
        echo "<td><a href=\"ManageSizePrice.php?size_price_id=" .$size_price_id. "\">" .$size_area. "</a></td>";
        echo "<td style='text-align: right;'>" .number_format($price, 2). "</td>";        
        
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