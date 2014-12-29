<!DOCTYPE html>
<?php
  session_start();  
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sign up</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">    
  </head>
  <body>  
    <div class="alert alert-success" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>
    <div class="alert alert-danger" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>

    <?php
      require("navbar.php");
      require("service/message_service.php");
      require("service/db_connect.php");
    ?>
    <form id="sign-up-form" action="" method="post" data-toggle="validator">
    <div class="container">
    <div class="col-xs-6 col-md-4">    
      <div class="form-group">        
        <label class="control-label" for="txt-email">Email address</label>
        <input type="email" class="form-control" id="txt-form-email" placeholder="Enter email" 
        name="txtEmail" required data-error="Please provide your email" >
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group">
        <label class="control-label" for="txt-member-name">Your name</label>
        <input type="text" class="form-control" id="txt-member-name" placeholder="Your name" name="txtName" >
      </div>      
      <div class="form-group">
        <label class="control-label" for="txt-address">Address</label>
        <textarea class="form-control" id="txt-address" placeholder="Address" rows="4" name="txtAddress"></textarea> 
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label" for="cbo-province">Province</label>
            <select id="cbo-province" class="form-control" name="cboProvince">

              <?php 
              try {
                  $conn = dbConnect::getInstance();
                  $dbh = $conn->dbh;
              } catch (PDOException $e) {
                  print "Error!: " . $e->getMessage() . "<br/>";
                  die();
              }

              $sql = "select province_name, province_id from provinces order by province_name asc";
              $stmt = $dbh->prepare($sql);
              if ($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($result as $row) {
                  $province_name = trim($row["province_name"]);
                  $province_id = $row["province_id"];
                  echo "<option value=\"" .$province_id. "|" .$province_name. "\">" .$province_name. "</option>";
                }
              }
              ?> 
            </select>         
          </div>
        </div>
        <div class="col-md-4">
          <label class="control-label" for="cbo-amphur">Amphur</label>
          <select id="cbo-amphur" class="form-control" name="cboAmphur" disabled>
            <option>อำเภอ</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="control-label" for="cbo-district">Tambol</label>
          <select id="cbo-district" class="form-control" name="cboDistric" disabled>
            <option>ตำบล</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 form-group">
          <label class="control-label" for="txt-post-code">Post Code</label>
          <input type="text" class="form-control" id="txt-post-code" placeholder="Post Code" name="txtPostCode" required
          data-error="Please provide your post code" >
          <div class="help-block with-errors"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 form-group">
       
          <label class="control-label" for="txt-password">Password</label>
          <input type="password" class="form-control" id="txt-password-signup" placeholder="Password" 
          data-minlength="6" name="txtPassword" >       
          <span class="help-block">Minimum of 6 characters</span>
       
        </div>
        <div class="col-xs-6 form-group">
      
          <label class="control-label" for="txt-confirm-password">Confirm password</label>       
          <input type="password" class="form-control" id="txt-confirm-password" placeholder="Confirm" 
          data-match="#txt-password-signup" data-match-error="Password not match" required>
          <div class="help-block with-errors"></div>
        
        </div>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
    </div>
    </form>     
    <script src="js/province.combo.js"></script>
  </body>
</html>

<?php
if (empty($_POST)) return;

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
   
  //$script = messageSuccess("<strong>Save Complete!!!</strong><br/>Please <strong>sign in</strong> with your email", 0, 'index.php');     
  $script = toastSuccess("<strong>Save completed!!!</strong><br/>Please <strong>sign in</strong> with your email");
  echo $script;

}else{
  //$script = messageFail("<strong>Error on saving !!!<strong>", 0);    
  $script = toastFail("<strong>Error on saving!!!<strong>");
  echo $script;
}

?> 

