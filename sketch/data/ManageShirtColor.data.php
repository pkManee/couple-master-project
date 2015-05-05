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
        insertColor();
        break;
  case 'delete':
        deleteColor();
        break;
  case 'checkColor':
        checkColor();
        break;
  case 'checkColorHex':
        checkColorHex();
        break;
  case 'getColor':
        getColor();
        break;
  default:
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"no_method"));
    break;
}
function getColor() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  $sql = "select color_hex, color from shirt_color where color_hex = :color_hex ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":color_hex", $_POST["color_hex"]);
  if ($stmt->execute()) {
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $results["color"];
  }
}
function checkColorHex() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "select color_hex from shirt_color where color_hex = :color_hex ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":color_hex", $_POST["txtColorHex"]);

  if ($stmt->execute()) {
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($results["color_hex"])) {
      header("Content-Type: application/json");
      echo json_encode(array('valid' => false));
    } else {
      header("Content-Type: application/json");
      echo json_encode(array('valid' => true));
    }
  }
}

function checkColor() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "select color from shirt_color where color = :color ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":color", $_POST["txtColor"]);

  if ($stmt->execute()) {
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($results["color"])) {
      header("Content-Type: application/json");
      echo json_encode(array('valid' => false));
    } else {
      header("Content-Type: application/json");
      echo json_encode(array('valid' => true));
    }
  }
}

function deleteColor() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "delete from shirt_color where color_hex = :color_hex ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":color_hex", $_POST["isDelete"]);

  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function insertColor(){
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = "insert into shirt_color (color, color_hex) values ";
  $sql .= "(:color, :color_hex) ";
  $sql .= "on duplicate key update color = :color ";

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(":color", $_POST["txtColor"]);
  $stmt->bindValue(":color_hex", $_POST["txtColorHex"]);

  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

?>