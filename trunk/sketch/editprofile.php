<!DOCTYPE html>
<?php
  session_start();  
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Member Profile</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">    
  </head>
  <body>  
    <?php
      include("navbar.php");
      include("./service/message_service.php");
      include("./service/db_connect.php");

      $email = $_SESSION["email"];

      class Member
      {
        public $email;
        public $member_name;
        public $address;
        public $province_id;
        public $amphur_id;
        public $district_id;
        public $password;        
      }

      //select user profile
      if (!empty($email)){          
          try {
              $dbh = dbConnect::getInstance()->dbh;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
          }

          $sql = "select email, member_name, address, province_id, amphur_id, district_id, password from member where email = :email ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindParam(":email", $email);
          $stmt->execute();
          $stmt->setFetchMode(PDO::FETCH_CLASS, "Member");
          $member = $stmt->fetch();

          echo "<input type=\"hidden\" id=\"hidden-amphur\" value=\"" .$member->amphur_id. "\" >";
          echo "<input type=\"hidden\" id=\"hidden-district\" value=\"" .$member->district_id. "\" >";
      }

    ?>
    <form id="sign-up-form" action="signup.php" method="post" data-toggle="validator">
     
    <div class="col-xs-6 col-md-4">    
      <div class="form-group">        
        <label class="control-label" for="txt-email">Email address</label>
        <input type="email" class="form-control" id="txt-form-email" placeholder="Enter email" disabled
        name="txtEmail" required data-error="Please provide your email" value="<?php echo $member->email; ?>">
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group">
        <label class="control-label" for="txt-member-name">Your name</label>
        <input type="text" class="form-control" id="txt-member-name" placeholder="Your name" name="txtName" value="<?php echo $member->member_name ?>">
      </div>      
      <div class="form-group">
        <label class="control-label" for="txt-address">Address</label>
        <textarea class="form-control" id="txt-address" placeholder="Address" rows="4" name="txtAddress"><?php echo htmlentities($member->address); ?></textarea> 
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="cbo-province">Province</label>
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
                  $province_name = $row["province_name"];
                  $province_id = $row["province_id"];                  
                  if ($province_id == $member->province_id){
                    print_r("this is --> " .$member->province_id);
                    echo "<option value=\"" .$province_id. "|" .$province_name. "\" selected>" .$province_name. "</option>";
                  }else{
                    echo "<option value=\"" .$province_id. "|" .$province_name. "\">" .$province_name. "</option>";
                  }
                }
              }
              ?> 
            </select>         
          </div>
        </div>
        <div class="col-md-4">
          <label class="control-label" for="cbo-amphur">Amphur</label>
          <select id="cbo-amphur" class="form-control" name="cboAmphur" disabled >
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
          data-error="Please provide your post code">
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
    <a role="button" class="btn btn-default" href="index.php">Cancel</a>
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

$sql = "update member set member_name = :member_name, address = :address, password = :password, ";
$sql .= "province_id = :province_id, province_name = :province_name, ";
$sql .= "amphur_id = :amphur_id, amphur_name = :amphur_name, ";
$sql .= "district_id = :disctrict_id, district_name = :district_name, ";
$sql .= "postcode = :postcode ";
$sql .= "where email = :email";
$stmt = $dbh->prepare();
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
   
  $script = messageSuccess("<strong>Save Complete!!!</strong>", 1000);     
  echo $script;

}else{
  $script = messageFail("<strong>Error on saving !!!<strong>", 0);    
  echo $script;
}

?>