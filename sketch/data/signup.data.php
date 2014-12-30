<?php
require("../service/message_service.php");
require("../service/db_connect.php");

if(isset($_POST) && !empty($_POST)) {

  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $sql = "insert into member ";
  $sql .= "(email, member_name, address, password, province_id, province_name, amphur_id, amphur_name, district_id, district_name, postcode) ";
  $sql .= "values";
  $sql .= "(:email, :member_name, :address, :password, :province_id, :province_name, :amphur_id, :amphur_name, :district_id, :district_name, :postcode)";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":email", $_POST["txtEmail"]);
  $stmt->bindValue(":member_name", $_POST["txtName"]);
  $stmt->bindValue(":address", $_POST["txtAddress"]);
  $stmt->bindValue(":password", $_POST["txtPassword"]);
  $stmt->bindValue(":province_id", doExplode($_POST["cboProvince"])[0]);
  $stmt->bindValue(":province_name", doExplode($_POST["cboProvince"])[1]);
  $stmt->bindValue(":amphur_id", doExplode($_POST["cboAmphur"])[0]);
  $stmt->bindValue(":amphur_name", doExplode($_POST["cboAmphur"])[1]);
  $stmt->bindValue(":district_id", doExplode($_POST["cboDistric"])[0]);
  $stmt->bindValue(":district_name", doExplode($_POST["cboDistric"])[1]);
  $stmt->bindValue(":postcode", $_POST["txtPostCode"]);

  if ($stmt->execute()){
    header("Content-Type: application/json");    
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}
?> 