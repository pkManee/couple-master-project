<?php
require_once("../service/message_service.php");
require_once("../service/db_connect.php");

if(!isset($_POST["method"]) || empty($_POST["method"])) {

  header("Content-Type: application/json");
  echo json_encode(array("result"=>"fail"));
  die();
}

$method = $_POST["method"];

switch ($method) {
  case 'confirmPaid':
        confirmPaid();
        break;
  case 'confirmDeliver':
        confirmDeliver();
        break;
  case 'cancelOrder':
        cancelOrder();
        break;
  case 'confirmCancel':
        confirmCancel();
        break;
  case 'confirmDelete':
        confirmDelete();
        break;
  case 'checkExistingOrder':
        checkExistingOrder();
        break;
  case 'customerPaid':
        customerPaid();
        break;
  default:
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"no_method"));
    break;
}

function customerPaid() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $order_id = $_POST['txtOrderId'];
  $paid_date = $_POST['txtPaidDate'];
  $paid_time = $_POST['txtPaidTime'];
  $confirm_paid_amount = $_POST['txtAmt'];

  //upload slip
  if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"] == UPLOAD_ERR_OK)
  {
      ############ Edit settings ##############
      $UploadDirectory    = '../uploads/'; //specify upload directory ends with / (slash)
      ##########################################
      
      /*
      Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
      Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
      and set them adequately, also check "post_max_size".
      */
      
      //check if this is an ajax request
      if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
          die();
      }      
      
      //Is file size is less than allowed size.
      if ($_FILES["FileInput"]["size"] > 5242880) {
          die("File size is too big!");
      }
      
      //allowed file type Server side check
      switch(strtolower($_FILES['FileInput']['type']))
          {
              //allowed file types
              // case 'image/png': 
              // case 'image/gif': 
              case 'image/jpeg': 
              case 'image/pjpeg':
              // case 'text/plain':
              // case 'text/html': //html file
              // case 'application/x-zip-compressed':
              case 'application/pdf':
              // case 'application/msword':
              // case 'application/vnd.ms-excel':
              // case 'video/mp4':
                  break;
              default:
                  die('Unsupported File!'); //output error
      }
      
      $File_Name          = strtolower($_FILES['FileInput']['name']);
      $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention      
      $NewFileName        = 'slip' .$order_id.$File_Ext; //new file name
      
      if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
         {
          // do other stuff 
            header("Content-Type: application/json");
            echo json_encode(array("result"=>"success"));
      } else {
          die('error uploading File!');
      }
      
  }
  else
  {    
    die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
  }

  header("Content-Type: application/json");
  echo json_encode(array("result"=>"success"));
  die();
  //----  

  $sql = 'update shirt_order ';
  $sql .= 'set paid_date = :paid_date ';
  $sql .= 'where order_id = :order_id ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);
  $stmt->bindValue(':paid_date', $paid_date);

  if ($stmt->execute()) {
    
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  } 
}

function confirmPaid() {
  $order_id = $_POST['order_id'];
  $paid_date = $_POST['paid_date']; 

  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = 'update shirt_order ';
  $sql .= 'set confirm_paid_date = :paid_date ';
  $sql .= 'where order_id = :order_id and confirm_paid_date is null ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);
  $stmt->bindValue(':paid_date', $paid_date);

  if ($stmt->execute()) {
    
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  } 
}

function confirmDeliver() {
  $order_id = $_POST['order_id'];
  $deliver_date = $_POST['deliver_date'];
  $tracking_id = (!empty($_POST['tracking_id'])) ? $_POST['tracking_id'] : null;

  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $sql = 'update shirt_order ';
  $sql .= 'set deliver_date = :deliver_date, ';
  $sql .= 'tracking_id = :tracking_id ';
  $sql .= 'where order_id = :order_id ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);
  $stmt->bindValue(':deliver_date', $deliver_date);  
  $stmt->bindValue(':tracking_id', $tracking_id);  

  if ($stmt->execute()) {
    
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  } 
}

function confirmCancel() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $order_id = $_POST['order_id'];
  $cancel_remark = (empty($_POST['cancel_remark'])) ? null : $_POST['cancel_remark'];

  $sql = 'update shirt_order ';
  $sql .= 'set cancel_date = current_date, ';
  $sql .= 'cancel_remark = :cancel_remark ';
  $sql .= 'where order_id = :order_id ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);
  $stmt->bindValue(':cancel_remark', $cancel_remark);

  if ($stmt->execute()) {    
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));
  } else {
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  }
}

function confirmDelete() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
  
  $order_id = $_POST['order_id'];
  $sql = 'delete from shirt_order ';
  $sql .= 'where order_id = :order_id and cancel_date is not null ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);

  if ($stmt->execute()) {
    
    header("Content-Type: application/json");
    echo json_encode(array("result"=>"success"));

  }else{
    header("Content-Type: application/json");
    echo json_encode($stmt->errorInfo());
  } 
}

function checkExistingOrder() {
  try {
      $dbh = dbConnect::getInstance()->dbh;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  $order_id = $_POST['order_id'];
  $email = $_POST['email'];

  $sql = 'select order_id from shirt_order ';
  $sql .= 'where order_id = :order_id ';
  $sql .= 'and email = :email ';
  $sql .= 'and paid_date is null ';

  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':order_id', $order_id);
  $stmt->bindValue(':email', $email);

  if ($stmt->execute()) {
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($results["order_id"])) {
      header("Content-Type: application/json");
      echo json_encode(array('valid' => true));
    } else {
      header("Content-Type: application/json");
      echo json_encode(array('valid' => false));
    }
  }
}
?>