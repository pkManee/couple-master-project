<!DOCTYPE html>
<?php
  session_start();  
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sign up</title>
    
    <link href="css/bootstrap.css" rel="stylesheet">    
    <link href="css/bootstrapValidator.css" rel="stylesheet">

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script>
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
    <form id="sign-up-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
    <div class="container">
    <div class="col-xs-6 col-md-4">    
      <div class="form-group">        
        <label class="control-label" for="txt-email">อีเมล์</label>
        <input type="text" class="form-control" id="txt-form-email" placeholder="อีเมล์" name="txtEmail">
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group">
        <label class="control-label" for="txt-member-name">ชื่อ นามสกุล</label>
        <input type="text" class="form-control" id="txt-member-name" placeholder="ชื่อ นามสกุล" name="txtName" >
      </div>      
      <div class="form-group">
        <label class="control-label" for="txt-address">ที่อยู่</label>
        <textarea class="form-control" id="txt-address" placeholder="ที่อยู่" rows="4" name="txtAddress"></textarea> 
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label" for="cbo-province">จังหวัด</label>
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
          <label class="control-label" for="cbo-amphur">อำเภอ/เขต</label>
          <select id="cbo-amphur" class="form-control" name="cboAmphur" disabled>
            <option>อำเภอ</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="control-label" for="cbo-district">ตำบล/แขวง</label>
          <select id="cbo-district" class="form-control" name="cboDistric" disabled>
            <option>ตำบล</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 form-group">
          <label class="control-label" for="txt-post-code">รหัสไปรษณีย์</label>
          <input type="text" class="form-control" id="txt-post-code" placeholder="รหัสไปรษณีย์" name="txtPostCode" >          
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 form-group">
       
          <label class="control-label" for="txt-password">รหัสผ่าน</label>
          <input type="password" class="form-control" id="txt-password-signup" placeholder="รหัสผ่าน" name="txtPassword" >        
        </div>
        <div class="col-xs-6 form-group">
      
          <label class="control-label" for="txt-confirm-password">ยืนยันรหัสผ่าน</label>       
          <input type="password" class="form-control" id="txt-confirm-password" placeholder="ยืนยันรหัสผ่าน" name="txtConfirm">
        </div>
      </div>
      <button type="submit" class="btn btn-primary" id="btn-signup-submit" >Sign up</button>
    </div>
    </div>
    </form>     
    <script src="js/province.combo.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
      $('#sign-up-form')
          .bootstrapValidator({
              //... options ...
              feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  txtEmail: {
                      message: 'อีเมล์ไม่ถูกต้อง',
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุอีเมล์'
                          },
                          emailAddress: {
                              message: 'กรุณาระบุอีเมล์ให้ถูกต้อง'
                          }
                      }
                  },
                  txtPostCode: {
                      message: 'รหัสไปรษณ๊ย์ไม่ถูกต้อง',
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุรหัสไปรษณีย์'
                          }
                      }
                  },
                  txtPassword: {
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุรหัสผ่าน'
                          },
                          identical: {
                              field: 'txtConfirm',
                              message: 'กรุณายืนยันรหัสผ่านให้ถูกต้อง'
                          },
                          stringLength: {
                              min: 6,
                              message: 'รหัสผ่านต้องไม่ต่ำกว่า 6 ตัวอักษร'
                          }
                      }
                  },
                  txtConfirm: {
                      message: 'กรุณายืนยันรหัสผ่าน',
                      validators: {
                        identical: {
                            field: 'txtPassword',
                            message: 'กรุณายืนยันรหัสผ่านให้ถูกต้อง'
                      },
                        notEmpty: {
                            message: 'กรุณายืนยันรหัสผ่าน'
                        }
                    }
                  }
              }
            })//bootstrapValidator
          .on('success.form.bv', function(e) {
              // Prevent form submission
              e.preventDefault();

              // Get the form instance
              var $form = $(e.target);
              // Get the BootstrapValidator instance
              var bv = $form.data('bootstrapValidator');

              // Use Ajax to submit form data
              goSave($form);
          });//on success.form.bv
      });//document.ready
      function goSave($form){
        $.ajax({
            type: 'POST',
            url: 'data/signup.data.php', 
            data: $form.serialize()
        })
        .done(function(data){
          if (data.result === "success"){
            Toast.init({
                "selector": ".alert-success"
            });
            Toast.show("<strong>Save completed!!!</strong> redirecting ...<br/>Please <strong>sign in</strong> with your email");
            setTimeout(function(){ window.location = "index.php" }, 3000);
          }else{
            Toast.init({
              "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on saving!!!<strong> " + data);
          }
        })//done
        .fail(function() {
          bootbox.dialog({
                      title: 'Fatal Error',
                      message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
          });
        });//fail
      }//goSave
      

    </script>
  </body>
</html>



