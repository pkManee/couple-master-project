<?php
require("../service/message_service.php");
require("../service/db_connect.php");

if(!isset($_POST["method"]) || empty($_POST["method"])) {

  header("Content-Type: application/json");
  echo json_encode(array("result"=>"fail"));
  die();
}

$method = $_POST["method"];
switch ($method) {
  case 'insert':
    insertShirtSize();
    break;
  case 'delete':
    deleteShirtSize();
    break;
  case 'get_shirt_size':
    getShirtSize();
    break;
  default:
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"no_method"));
    break;
}

function getShirtSize() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $gender = $_POST["gender"];

  $sql = "select size_code, chest_size, shirt_length, gender from shirt_size ";  
  $sql .= "where 1 = 1 ";
  $sql .= "and gender = :gender ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":gender", $gender);

  if ($stmt->execute()){    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($results);

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function insertShirtSize(){
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "insert into shirt_size (size_code, chest_size, shirt_length, gender) values ";
  $sql .= "(:size_code, :chest_size, :shirt_length, :gender) ";
  $sql .= "on duplicate key update  chest_size = :chest_size, shirt_length = :shirt_length, gender = :gender ";

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(":size_code", $_POST["txtSizecode"]);
  $stmt->bindValue(":chest_size", $_POST["txtChestSize"]);
  $stmt->bindValue(":shirt_length", $_POST["txtShirtLength"]);
  $stmt->bindValue(":gender", $_POST["rdoGender"]);

  header("Content-Type: application/json");
  if ($stmt->execute()){    
    echo json_encode(array("result"=>"success"));

  }else{    
    echo json_encode($stmt->errorInfo());
  }
}

function deleteShirtSize() {
  $sql = "delete from shirt_size where size_code = :size_code ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":size_code", $_POST["size_code"]);

  header("Content-Type: application/json");
  if ($stmt->execute()){    
    echo json_encode(array("result"=>"success"));

  }else{    
    echo json_encode($stmt->errorInfo());
  }
}

?>