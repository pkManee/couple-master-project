<!DOCTYPE html>
<?php
  require("header.php");
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Warer Profile</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/bootstrapValidator.css" rel="stylesheet">

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script>   
  </head>
  <body>
  <?php include("navbar.php"); ?>
    <div class="alert alert-success" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>
    <div class="alert alert-danger" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>    
    <form id="editprofile-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
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
        public $postcode;
      }

      //select user profile
      if (isset($_SESSION["email"]) && !empty($_SESSION["email"])){           
          try {
              $dbh = dbConnect::getInstance()->dbh;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
          }

          $sql = "select email, member_name, address, province_id, amphur_id, district_id, password, postcode ";
          $sql .= ",shirt_id_1, shirt_id_2, photo, height_1, height_2 ";
          $sql .= "from member where email = :email ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(":email", $_SESSION["email"]);
          if ($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Member");
            $member = $stmt->fetch();
          }else{
            echo "error -> " .$stmt->errorInfo()[2];
          }
      }
    ?>
    <div class="container">
      <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-pills" >
          <li role="presentation" class="active"><a href="#member-profile" aria-controls="member-profile" role="tab" data-toggle="tab">ข้อมูสมาชิก</a></li>
          <li role="presentation"><a href="#wearer-profile" aria-controls="wearer-profile" role="tab" data-toggle="tab">ข้อมูลผู้สวมใส่</a></li>          
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <!-- tab 1 -->
          <div role="tabpanel" class="tab-pane active" id="member-profile">
            
          </div>
          <!--end of tab 1 -->
          <div role="tabpanel" class="tab-pane" id="wearer-profile">
            <div class="panel panel-default">
            <div class="panel-body">
              <div class="col-md-4">    
                <div class="form-group">        
                  <label class="control-label" for="upload-photo">รูปถ่าย</label>
                  <input type="text" class="form-control" id="txt-form-email" 
                      name="uploadPhoto" value="<?php echo $member->photo; ?>" >        
                </div>
                <div class="form-group">
                  <label class="control-label" for="txt-height-boy">ส่วนสูง (ชาย)</label>
                  <input type="text" class="form-control" id="txt-height-boy" placeholder="ซม." 
                        name="txtHeightBoy" value="<?php echo $member->height_1; ?>" >
                </div>      
                <div class="form-group">
                  <label class="control-label" for="txt-height-girl">ส่วนสูง (หญิง)</label>
                  <input type="text" class="form-control" id="txt-height-girl" placeholder="ซม." 
                        name="txtHeightGirl" value="<?php echo $member->height_2; ?>">
                </div>
              <button type="submit" class="btn btn-primary">Save</button>              
              <a role="button" class="btn btn-default" href="index.php">Cancel</a>
              </div>
          </div>
          <!--end of tab 2 -->
        </div>

      </div>
    </div>
    
    </form>  
    <script src="js/province.combo.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {

      $('#editprofile-form')
        .bootstrapValidator( {
          feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
          },
          fields: {             
              txtPostCode: {
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
                  validators: {
                    identical: {
                        field: 'txtPassword',
                        message: 'กรุณายืนยันรหัสผ่านให้ถูกต้อง'
                  },
                    notEmpty: {
                        message: 'กรุณายืนยันรหัสผ่าน'
                    }
                }
              },
              txtHeightBoy: {
                  validators: {
                      numeric: {
                      message: 'กรุณาระบุเป็นตัวเลข'
                    },
                    greaterThan: {
                      value: 0,
                      inclusive: false,
                      message: 'กรุณาระบุตัวเลขที่มากกว่าศูนย์'
                    }
                }
              },
              txtHeightGirl:{
                  validators: {
                      numeric: {
                      message: 'กรุณาระบุเป็นตัวเลข'
                    },
                    greaterThan: {
                      value: 0,
                      inclusive: false,
                      message: 'กรุณาระบุตัวเลขที่มากกว่าศูนย์'
                    }
                  }
              }
          }
        })//bootstrapValidator
        .on('success.form.bv', function(e){
           // Prevent form submission
            e.preventDefault();

            // Get the form instance
            var $form = $(e.target);
            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');
            goSave($form);
        });//on success.form.bv

    });//document.ready
    function goSave($form){
      var email = document.getElementById('txt-form-email');
      $.ajax({
          type: 'POST',
          url: 'data/editprofile.data.php', 
          data: $form.serialize() + "&email=" + email.value
      })//ajax
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
      })//done
      .fail(function() {
        bootbox.dialog({
                    title: 'Fatal Error',
                    message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });
      });//fail
    }      
    </script>
  </body>
</html>