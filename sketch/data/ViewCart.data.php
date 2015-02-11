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
        insertOrder();
        break;
  case 'delete':
        deleteOrder();
        break;
  default:
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"no_method"));
    break;
}

function deleteOrder() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "delete from shirt_color where color = :color ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":color", $_POST["isDelete"]);

  
  if ($stmt->execute()){      
    header("Content-Type: application/json");  
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function insertOrder(){
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = 'insert into shirt_order ';
  $sql .= '(email, line_screen_1, line_screen_2, line_screen_price_1, line_screen_price_2, shirt_id_1, ';
  $sql .= 'shirt_id_2, qty_1, qty_2, order_status, order_date) ';
  $sql .= 'values';
  $sql .= '(:email, :line_screen_1, :line_screen_2, :line_screen_price_1, :line_screen_price_2, :shirt_id_1, ';
  $sql .= ':shirt_id_2, :qty_1, :qty_2, :order_status, curdate())';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(":email", $_POST["email"]);
  $stmt->bindValue(":line_screen_1", $_POST["line_screen_1"]);
  $stmt->bindValue(":line_screen_2", $_POST["line_screen_2"]);
  $stmt->bindValue(":line_screen_price_1", $_POST["line_screen_price_1"]);
  $stmt->bindValue(":line_screen_price_2", $_POST["line_screen_price_2"]);
  $stmt->bindValue(":shirt_id_1", $_POST["shirt_id_1"]);
  $stmt->bindValue(":shirt_id_2", $_POST["shirt_id_2"]);
  $stmt->bindValue(":qty_1", $_POST["qty_1"]);
  $stmt->bindValue(":qty_2", $_POST["qty_2"]);
  $stmt->bindValue(":order_status", 'order');  

  if ($stmt->execute()){
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

?>