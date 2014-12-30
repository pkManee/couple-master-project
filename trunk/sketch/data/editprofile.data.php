<?php
require("../service/message_service.php");
require("../service/db_connect.php");

if(isset($_POST) && !empty($_POST)) {
  doWork();
}

function doWork(){
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "update member set member_name = :member_name, address = :address, password = :password, ";
  $sql .= "province_id = :province_id, province_name = :province_name, ";
  $sql .= "amphur_id = :amphur_id, amphur_name = :amphur_name, ";
  $sql .= "district_id = :district_id, district_name = :district_name, ";
  $sql .= "postcode = :postcode ";
  $sql .= "where email = :email";

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(":member_name", $_POST["txtName"]);
  $stmt->bindValue(":address", $_POST["txtAddress"]);
  $stmt->bindValue(":password", $_POST["txtPassword"]);
  $stmt->bindValue(":province_id", doExplode($_POST["cboProvince"])[0]);
  $stmt->bindValue(":province_name", doExplode($_POST["cboProvince"])[1]);
  $stmt->bindValue(":amphur_id", doExplode($_POST["cboAmphur"])[0]);
  $stmt->bindValue(":amphur_name", doExplode($_POST["cboAmphur"])[1]);
  $stmt->bindValue(":district_id", doExplode($_POST["cboDistrict"])[0]);
  $stmt->bindValue(":district_name", doExplode($_POST["cboDistrict"])[1]);
  $stmt->bindValue(":postcode", $_POST["txtPostCode"]);
  $stmt->bindValue(":email", $_POST["email"]);


  if ($stmt->execute()){        
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

?>