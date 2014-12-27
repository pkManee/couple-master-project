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

          $sql = "select email, member_name, address, password from member where email = :email ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindParam(":email", $email);
          $stmt->execute();
          $stmt->setFetchMode(PDO::FETCH_CLASS, "Member");
          $result = $stmt->fetch();
      }

    ?>
    <form id="sign-up-form" action="signup.php" method="post" data-toggle="validator">
      <div class="col-xs-4">
      </div>
    <div class="col-xs-4">    
      <div class="form-group">        
        <label class="control-label" for="txt-email">Email address</label>
        <input type="email" class="form-control" id="txt-email" placeholder="Enter email" 
        name="txtEmail" required data-error="Please provide your email" value=<?php echo htmlentities($result->email); ?>>
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group">
        <label class="control-label" for="txt-member-name">Your name</label>
        <input type="text" class="form-control" id="txt-member-name" placeholder="Your name" name="txtName" value=<?php echo $result->member_name ?>>
      </div>      
      <div class="form-group">
        <label class="control-label" for="txt-address">Address</label>
        <textarea class="form-control" id="txt-address" placeholder="Address" rows="4" name="txtAddress"><?php echo htmlentities($result->address); ?></textarea> 
      </div>
      <div class="form-group">
        <label for="txt-address">Province</label>
        <div class="dropdown">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" 
            data-toggle="dropdown" aria-expanded="true">
            Province
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Province1</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Province2</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Province3</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Province4</a></li>
          </ul>
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
    <a role="button" class="btn btn-default" href="index.php">Cancel</a>
    </div>

    </form>  


  </body>
</html>

<?php
if (empty($_POST)) return;

$stmt = $dbh->prepare("update member set member_name = :member_name, address = :address, password = :password where email = :email");
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