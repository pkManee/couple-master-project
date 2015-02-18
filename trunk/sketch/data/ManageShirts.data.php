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
        insertShirts();
        break;
  case 'delete':
        deleteShirts();
        break;
  case 'getAllShirtsByGender':
        getAllShirtsByGender();
        break;
  case 'getShirtColor':
        getShirtColor();
        break;
  case 'getShirtSize':
        getShirtSize();
        break;
  case 'getShirtTypeByGender' :
        getShirtTypeByGender();
        break;
  case 'getMaterialType' :
        getMaterialType();
        break;
  default:
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"no_method"));
    break;
}

function getMaterialType() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  $sql = "select s.shirt_id, s.material_type, s.shirt_price, s.size_code, ";
  $sql .= "sh.chest_size, sh.shirt_length ";
  $sql .= "from shirts s inner join shirt_size sh on s.size_code = sh.size_code and s.gender = sh.gender ";
  $sql .=" where s.color_hex = :color_hex and s.shirt_type = :shirt_type ";
  $sql .= "and s.gender = :gender ";

  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":color_hex", $_POST["color_hex"]);
  $stmt->bindValue(":shirt_type", $_POST["shirt_type"]);
  $stmt->bindValue(":gender", $_POST["gender"]);
  if ($stmt->execute()){    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($results);

  } else {
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function getShirtTypeByGender() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  $sql = "select distinct s.shirt_type from shirts s ";
  $sql .= "where s.gender = :gender ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":gender", $_POST["gender"]);
  if ($stmt->execute()){    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($results);

  } else {
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function getShirtSize() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "select distinct s.size_code from shirts s ";
  $sql .= "where s.gender = :gender and s.shirt_type = :shirt_type";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":gender", $_POST["gender"]);
  $stmt->bindValue(":shirt_type", $_POST["shirt_type"]);

  if ($stmt->execute()) {    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($results);

  } else {
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function getShirtColor() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "select distinct s.color_hex, c.color from ";
  $sql .= "shirts s inner join shirt_color c on s.color_hex = c.color_hex ";
  $sql .= "where s.gender = :gender and s.shirt_type = :shirt_type ";
  $sql .= "order by c.color asc ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":gender", $_POST["gender"]);
  $stmt->bindValue(":shirt_type", $_POST["shirt_type"]);

  if ($stmt->execute()){    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($results);

  } else {
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function getAllShirtsByGender() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "select * from shirts where gender = :gender ";
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(":gender", $_POST["gender"]);
  if ($stmt->execute()){    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($results);

  } else {
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function insertShirts(){
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "insert into shirts ";
  $sql .= "(shirt_id, shirt_name, shirt_type, material_type, size_code, shirt_price, gender, color_hex) ";
  $sql .= "values";
  $sql .= "(:shirt_id, :shirt_name, :shirt_type, :material_type, :size_code, :shirt_price, :gender, :color_hex) ";
  $sql .= "on duplicate key update shirt_name = :shirt_name, shirt_type = :shirt_type, material_type = :material_type ";
  $sql .= ",size_code = :size_code, shirt_price = :shirt_price, gender = :gender, color_hex = :color_hex ";

  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":shirt_id", (!empty($_POST["shirt_id"])) ? $_POST["shirt_id"] : NULL);
  $stmt->bindValue(":shirt_name", $_POST["txtShirtName"]);
  $stmt->bindValue(":color_hex", $_POST["cboShirtColor"]);
  $stmt->bindValue(":shirt_type", $_POST["cboShirtType"]);
  $stmt->bindValue(":material_type", $_POST["cboMaterialType"]);
  $stmt->bindValue(":size_code", $_POST["cboShirtSize"]);
  $stmt->bindValue(":shirt_price", $_POST["txtShirtPrice"]);
  $stmt->bindValue(":gender", $_POST["rdoGender"]);  

  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function deleteShirts() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      echo "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = "delete from shirts where shirt_id = :shirt_id ";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":shirt_id", $_POST["shirtId"]);

  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

?>