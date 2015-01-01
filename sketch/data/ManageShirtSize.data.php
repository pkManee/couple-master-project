<?php
require("../service/message_service.php");
require("../service/db_connect.php");

if(isset($_POST["txtSizecode"]) && !empty($_POST["txtSizecode"])) {
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
    $sql = "insert into shirt_size (size_code, chest_size, shirt_length) values ";
    $sql .= "(:size_code, :chest_size, :shirt_length) ";
    $sql .= "on duplicate key update  chest_size = :chest_size, shirt_length = :shirt_length ";

    $stmt = $dbh->prepare($sql);

    $stmt->bindValue(":size_code", $_POST["txtSizecode"]);
    $stmt->bindValue(":chest_size", $_POST["txtChestSize"]);
    $stmt->bindValue(":shirt_length", $_POST["txtShirtLength"]);

  }else{
    $sql = "delete from shirt_size where size_code = :size_code ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":size_code", $_POST["isDelete"]);
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