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
  case 'confirmOrder':
        confirmOrder();
        break;
  case 'confirmDeliver':
        confirmDeliver();
        break;
  case 'cancelOrder':
        cancelOrder();
        break;
  case 'delete':
        deleteOrder();
        break;
  default:
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"no_method"));
    break;
}

function confirmOrder() {
  $order_id = $_POST['order_id'];

  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = 'update shirt_order ';
  $sql .= 'set paid_date = current_date ';
  $sql .= 'where order_id = :order_id ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);

  if ($stmt->execute()) {
    
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  } 
}
?>