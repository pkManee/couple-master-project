<!DOCTYPE html>
<?php
session_start();  

if (!isset($_SESSION["email"]) || empty($_SESSION["email"])){
  header("location: index.php");
}
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">    
  </head>
  <body>
  <?php include("navbar.php"); ?>
    <div class="alert alert-success" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>
    <div class="alert alert-danger" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>    
    <form id="editprofile-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" data-toggle="validator">
    <?php      
      include("service/message_service.php");
      include("service/db_connect.php");

      class Member
      {
        public $email;
        public $member_name;
        public $address;
        public $province_id;
        public $amphur_id;
        public $district_id;
        public $password;
        public $zipcode;
      }

      //select user profile
      if (isset($_SESSION["email"]) && !empty($_SESSION["email"])){           
          try {
              $dbh = dbConnect::getInstance()->dbh;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
          }

          $sql = "select email, member_name, address, province_id, amphur_id, district_id, password, postcode from member where email = :email ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(":email", $_SESSION["email"]);
          if ($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Member");
            $member = $stmt->fetch();

            echo "<input type=\"hidden\" id=\"hidden-amphur\" value=\"" .$member->amphur_id. "\" >";
            echo "<input type=\"hidden\" id=\"hidden-district\" value=\"" .$member->district_id. "\" >";
          }else{
            echo "error -> " .$stmt->errorInfo()[2];
          }
      }
    ?>
    <div class="container">
    <div class="col-xs-6 col-md-4">    
      <div class="form-group">        
        <label class="control-label" for="txt-form-email">Email address</label>
        <input type="email" class="form-control" id="txt-form-email" placeholder="Enter email"
          required data-error="Please provide your email" value="<?php echo $member->email; ?>" name="txtEmail" disabled >
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
          <select id="cbo-district" class="form-control" name="cboDistrict" disabled>
            <option>ตำบล</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 form-group">
          <label class="control-label" for="txt-post-code">Post Code</label>
          <input type="text" class="form-control" id="txt-post-code" placeholder="Post Code" name="txtPostCode" required
          data-error="Please provide your post code" value="<?php echo $member->zipcode; ?>">
          <div class="help-block with-errors"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 form-group">
       
          <label class="control-label" for="txt-password">Password</label>
          <input type="password" class="form-control" id="txt-password-signup" placeholder="Password" 
          data-minlength="6" name="txtPassword" value="<?php echo $member->password; ?>" >       
          <span class="help-block">Minimum of 6 characters</span>
       
        </div>
        <div class="col-xs-6 form-group">
      
          <label class="control-label" for="txt-confirm-password">Confirm password</label>       
          <input type="password" class="form-control" id="txt-confirm-password" placeholder="Confirm" 
          data-match="#txt-password-signup" data-match-error="Password not match" required value="<?php echo $member->password; ?>">
          <div class="help-block with-errors"></div>
        
        </div>
      </div>
    <button type="submit" class="btn btn-default" name="submit">Submit</button>
    <a role="button" class="btn btn-default" href="index.php">Cancel</a>
    </div>
    </div>
    </form>  
    <script src="js/province.combo.js"></script>
    <script type="text/javascript">
      var theForm = $('#editprofile-form')[0];     
      theForm.onsubmit = function(){
        event.preventDefault();
        var email = document.getElementById('txt-form-email');

        $.ajax({
            type: 'POST',
            url: 'data/editprofile.data.php', 
            data: $(this).serialize() + "&email=" + email.value
        })
        .done(function(data){
          if (data.result === "success"){
            Toast.init({
                "selector": ".alert-success"
            });
            Toast.show("<strong>Profile update completed!!!</strong><br/>");             
          }else{
            Toast.init({
              "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on saving!!!<strong>" + data);
          }
        })
        .fail(function() {
          bootbox.dialog({
                      title: 'Fetal Error',
                      message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
          });
        });
 
        // to prevent refreshing the whole page page
        return false;
      }
    </script>
  </body>
</html>