<?php
require("../service/message_service.php");
require("../service/db_connect.php");

if(isset($_POST["txtColor"]) && !empty($_POST["txtColor"])) {
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
    $sql = "insert into shirt_color (color, color_hex) values ";
    $sql .= "(:color, :color_hex) ";
    $sql .= "on duplicate key update color_hex = :color_hex ";

    $stmt = $dbh->prepare($sql);

    $stmt->bindValue(":color", $_POST["txtColor"]);
    $stmt->bindValue(":color_hex", $_POST["txtColorHex"]);
  }else{
    $sql = "delete from shirt_color where color = :color ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":color", $_POST["isDelete"]);
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