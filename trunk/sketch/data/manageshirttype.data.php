<?php 
require("../service/message_service.php");
require("../service/db_connect.php");

if(isset($_POST["txtShirtType"]) && !empty($_POST["txtShirtType"])) {
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
    $sql = "insert into shirt_type (shirt_type, shirt_type_description) values ";
    $sql .= "(:shirt_type, :shirt_type_description) ";
    $sql .= "on duplicate key update shirt_type_description = :shirt_type_description ";

    $stmt = $dbh->prepare($sql);

    $stmt->bindValue(":shirt_type", $_POST["txtShirtType"]);
    $stmt->bindValue(":shirt_type_description", $_POST["txtShirtTypeDescription"]);
  }else{
    $sql = "delete from shirt_type where shirt_type = :shirt_type ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":shirt_type", $_POST["isDelete"]);
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