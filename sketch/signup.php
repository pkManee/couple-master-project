<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">    
  </head>
  <body>

    <form id="sign-up-form" action="signup.php" method="post" data-toggle="validator">
      <div class="col-xs-4">
      </div>
    <div class="col-xs-4">    
      <div class="form-group">        
        <label class="control-label" for="txt-email">Email address</label>
        <input type="email" class="form-control" id="txt-email" placeholder="Enter email" name="txtEmail" required data-error="Please provide your email">
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group">
        <label class="control-label" for="txt-member-name">Your name</label>
        <input type="text" class="form-control" id="txt-member-name" placeholder="Your name" name="txtName">
      </div>      
      <div class="form-group">
        <label class="control-label" for="txt-address">Address</label>
        <textarea class="form-control" id="txt-address" placeholder="Address" rows="4" name="txtAddress"></textarea> 
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
    </div>

    </form>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/validator.js"></script>   
  </body>
</html>

<?php
session_start();

if (empty($_POST)) return;

$user = "root";
$pass = "";
try {
    $dbh = new PDO('mysql:host=localhost;dbname=c_shirt', $user, $pass);   
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
   
    $script = "<script type='text/JavaScript'>";
    $script .= "$( document ).ready(function() { ";
    $script .= "bootbox.dialog({";
    $script .= "title: '',";
    $script .= "message : '<div class=\"alert alert-success\" role=\"alert\">Save Successfully !!!</div>' ";
    $script .= "});";  
    $script .= "setTimeout(function(){bootbox.hideAll()}, 1000);";
    $script .= "}); ";
    $script .= "</script>";
 
    
  echo $script;

}else{
    $script = "<script type='text/JavaScript'>";
    $script .= "$( document ).ready(function() { ";
    $script .= "bootbox.dialog({";
    $script .= "title: '',";
    $script .= "message : '<div class=\"alert alert-warning\" role=\"alert\">Error on Saving !!!</div>' ";
    $script .= "});";  
    // $script .= "setTimeout(function(){bootbox.hideAll()}, 1000);";
    $script .= "}); ";
    $script .= "</script>";
    
  echo $script;
}

?>