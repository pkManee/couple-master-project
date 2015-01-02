<?php
require("../service/message_service.php");
require("../service/db_connect.php");

if(isset($_POST["txtShirtId"]) && !empty($_POST["txtShirtId"])) {
  doWork();  
}else{
  header("Content-Type: application/json");
    echo json_encode(array("result"=>"fail"));
}

function doWork(){
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  if (empty($_POST["isDelete"]))
  {
    $sql = "insert into shirts ";
    $sql .= "(shirt_name, color, shirt_type, material_type, size_code, shirt_price) ";
    $sql .= "values";
    $sql .= "(:shirt_name, :color, :shirt_type, :material_type, :size_code, :shirt_price) ";
    $sql .= "on duplicate key update color = :color, shirt_type = :shirt_type, material_type = :material_type ";
    $sql .= ",size_code = :size_code, shirt_price = :shirt_price";

    $stmt = $dbh->prepare($sql);

    $stmt->bindValue(":shirt_name", $_POST["txtShirtName"]);
    $stmt->bindValue(":color", $_POST["cboShirtColor"]);
    $stmt->bindValue(":shirt_type", $_POST["cboShirtType"]);
    $stmt->bindValue(":material_type", $_POST["cboMaterialType"]);
    $stmt->bindValue(":size_code", $_POST["cboShirtSize"]);
    $stmt->bindValue(":shirt_price", $_POST["txtShirtPrice"]);

  }else{
    $sql = "delete from shirts where shirt_id = :shirt_id ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":shirt_id", $_POST["isDelete"]);
  }


  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

?>