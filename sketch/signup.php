<!DOCTYPE html>
<?php
  session_start();  
?>
<html lang="en">
  <head>
    <meta name="Content-Type" content="text/html; charset=utf-8" />
    <title>Sign up</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">    
  </head>
  <body>  
 
    <?php
      require("navbar.php");
      require("service/message_service.php");
      require("service/db_connect.php");
    ?>
    <form id="sign-up-form" action="signup.php" method="post" data-toggle="validator">
      <div class="col-xs-4">
      </div>
    <div class="col-xs-4">    
      <div class="form-group">        
        <label class="control-label" for="txt-email">Email address</label>
        <input type="email" class="form-control" id="txt-email" placeholder="Enter email" 
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
            <label for="cbo-province">Province</label>
            <select id="cbo-province" class="form-control">

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
                  echo "<option value=\"" .$province_id. "\">" .$province_name. "</option>";
                }
              }
              ?> 
            </select>         
          </div>
        </div>
        <div class="col-md-4">
          <label id="cbo-amphur">Amphur</label>
          <select id="cbo-amphur" class="form-control" disabled>
            <option>อำเภอ</option>
          </select>
        </div>
        <div class="col-md-4">
          <label id="cbo-tambol">Tambol</label>
          <select id="cbo-tambol" class="form-control" disabled>
            <option>ตำบล</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="txt-password">Password</label>
        <input type="password" class="form-control" id="txt-password" placeholder="Password" required 
        data-minlength="6" value="111111" name="txtPassword" >       
        <span class="help-block">Minimum of 6 characters</span>
      </div>     
      <div class="form-group">
        <label for="txt-password">Confirm password</label>       
        <input type="password" class="form-control" id="txt-confirm-password" placeholder="Confirm" 
        data-match="#txt-password" data-match-error="Password not match" required value="111111">
        <div class="help-block with-errors"></div>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
    </form>     
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

$stmt = $dbh->prepare("insert into member(email, member_name, address, password) values (:email, :member_name, :address, :password)");
$stmt->bindParam(":email", $_POST["txtEmail"]);
$stmt->bindParam(":member_name", $_POST["txtName"]);
$stmt->bindParam(":address", $_POST["txtAddress"]);
$stmt->bindParam(":password", $_POST["txtPassword"]);

if ($stmt->execute()){
   
  $script = messageSuccess("<strong>Save Complete!!!</strong>", 1000);     
  echo $script;

}else{
  $script = messageFail("<strong>Error on saving !!!<strong>", 0);    
  echo $script;
}

?>