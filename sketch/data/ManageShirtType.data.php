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
  case 'insertShirtType':
    insertShirtType();
    break;
  case 'deleteShirtType':
    deleteShirtType();
    break;
  case 'getAll':
    getAll();
    break;
  default:
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"no_method"));
    break;
}

function getAll() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  $sql = "select shirt_type, shirt_type_description from shirt_type ";
  $stmt = $dbh->prepare($sql);
  if ($stmt->execute()){        
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($results);

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function insertShirtType(){
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = "insert into shirt_type (shirt_type, shirt_type_description) values ";
  $sql .= "(:shirt_type, :shirt_type_description) ";
  $sql .= "on duplicate key update shirt_type_description = :shirt_type_description ";

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(":shirt_type", $_POST["txtShirtType"]);
  $stmt->bindValue(":shirt_type_description", $_POST["txtShirtTypeDescription"]);


  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function deleteShirtType() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "delete from shirt_type where shirt_type = :shirt_type ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":shirt_type", $_POST["isDelete"]);

  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

?>