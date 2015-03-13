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
  case 'confirmCancel':
        confirmCancel();
        break;
  case 'confirmDelete':
        confirmDelete();
        break;
  default:
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"no_method"));
    break;
}

function confirmOrder() {
  $order_id = $_POST['order_id'];
  $paid_date = $_POST['paid_date']; 

  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = 'update shirt_order ';
  $sql .= 'set paid_date = :paid_date ';
  $sql .= 'where order_id = :order_id ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);
  $stmt->bindValue(':paid_date', $paid_date);

  if ($stmt->execute()) {
    
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  } 
}

function confirmDeliver() {
  $order_id = $_POST['order_id'];
  $deliver_date = $_POST['deliver_date'];
  $tracking_id = (!empty($_POST['tracking_id'])) ? $_POST['tracking_id'] : null;

  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = 'update shirt_order ';
  $sql .= 'set deliver_date = :deliver_date, ';
  $sql .= 'tracking_id = :tracking_id ';
  $sql .= 'where order_id = :order_id ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);
  $stmt->bindValue(':deliver_date', $deliver_date);  
  $stmt->bindValue(':tracking_id', $tracking_id);  

  if ($stmt->execute()) {
    
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  } 
}

function confirmCancel() {
  $order_id = $_POST['order_id'];
  $cancel_remark = (empty($_POST['cancel_remark'])) ? null : $_POST['cancel_remark'];

  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = 'update shirt_order ';
  $sql .= 'set cancel_date = current_date, ';
  $sql .= 'cancel_remark = :cancel_remark ';
  $sql .= 'where order_id = :order_id ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);
  $stmt->bindValue(':cancel_remark', $cancel_remark);

  if ($stmt->execute()) {    
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));
  } else {
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function confirmDelete() {
  $order_id = $_POST['order_id'];

  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = 'delete from shirt_order ';
  $sql .= 'where order_id = :order_id and cancel_date is not null ';

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