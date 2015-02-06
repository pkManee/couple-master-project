<?php
require_once("../service/message_service.php");
require_once("../service/db_connect.php");

if(!isset($_POST["method"]) || empty($_POST["method"])) {

  header("Content-Type: application/json");
  echo json_encode(array("result"=>"fail"));
  die();
}

$method = $_POST["method"];

switch ($method) {
  case 'insert':
        insertSizePrice();
        break;
  case 'delete':
        deleteSizePrice();
        break;
  case 'getPrice':
        getPrice();
        break;
  default:
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"no_method"));
    break;
}

function getPrice() {
   try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = 'SELECT  min(price) as price from size_price where size_area >= :area ';
  
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":area", $_POST['area']);

  header("Content-Type: application/json");
  if ($stmt->execute()){
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($results);
  } else {    
    echo json_encode($stmt->errorInfo());
  }

}

function insertSizePrice() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  $sql = "insert into size_price (size_price_id, size_area, price, description) ";
  $sql .= "values (:size_price_id, :size_area, :price, :description) ";
  $sql .= "on duplicate key update ";
  $sql .= "size_area = :size_area, price = :price, description = :description ";

  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":size_price_id", (!empty($_POST["size_price_id"])) ? $_POST["size_price_id"] : NULL);
  $stmt->bindValue(":size_area", $_POST["txtSizeArea"]);
  $stmt->bindValue(":price", $_POST["txtPrice"]);
  
  $stmt->bindValue(":description", $_POST["txtDescription"]);

  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function deleteSizePrice() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  $sql = "delete from size_price where size_price_id = :size_price_id ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":size_price_id", $_POST["size_price_id"]);

  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

?>