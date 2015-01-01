<?php
require("../service/message_service.php");
require("../service/db_connect.php");

if(isset($_POST["txtMaterialType"]) && !empty($_POST["txtMaterialType"])) {
  doWork();
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
    $sql = "insert into material (material_type, description) values ";
    $sql .= "(:material_type, :description) ";
    $sql .= "on duplicate key update description = :description ";

    $stmt = $dbh->prepare($sql);

    $stmt->bindValue(":material_type", $_POST["txtMaterialType"]);
    $stmt->bindValue(":description", $_POST["txtDescription"]);
  }else{
    $sql = "delete from material where material_type = :material_type ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":material_type", $_POST["isDelete"]);
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