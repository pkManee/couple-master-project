<!DOCTYPE html>
<?php
  require("header.php");
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/bootstrapValidator.css" rel="stylesheet">  
    <link href="css/jasny-bootstrap.css" rel="stylesheet">  

    <script src="js/jquery-2.1.1.min.js"></script>    
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script>
    <script src="js/jasny-bootstrap.min.js"></script>
    
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

      // class Member
      // {
      //   public $email;
      //   public $member_name = "";
      //   public $address;
      //   public $province_id;
      //   public $amphur_id;
      //   public $district_id;
      //   public $password;
      //   public $postcode;
      //   public $photo;
      //   public $height_1 = 0;
      //   public $height_2 = 0;
      // }
      include("data/Member.class.php");
      //select user profile
      if (isset($_SESSION["email"]) && !empty($_SESSION["email"])){           
          try {
              $dbh = dbConnect::getInstance()->dbh;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
          }

          $sql = "select email, member_name, address, province_id, amphur_id, district_id, password, postcode ";
          $sql .= ",photo, height_1, height_2 ";
          $sql .= "from member where email = :email ";
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
          
          <div class="col-xs-6 col-md-4">    
            <div class="form-group">        
              <label class="control-label" for="txt-form-email">อีเมล์</label>
              <input type="email" class="form-control" id="txt-form-email" 
                  value="<?php echo $member->email; ?>" name="txtEmail" disabled > 

            </div>
            <div class="form-group">
              <label class="control-label" for="txt-member-name">ชื่อ นามสกุล</label>
              <input type="text" class="form-control" id="txt-member-name" placeholder="ชื่อ นามสกุล" name="txtName" value="<?php echo $member->member_name ?>">
            </div>      
            <div class="form-group">
              <label class="control-label" for="txt-address">ที่อยู่</label>
              <textarea class="form-control" id="txt-address" placeholder="ที่อยู่" rows="4" name="txtAddress"><?php echo htmlentities($member->address); ?></textarea> 
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="cbo-province">จังหวัด</label>
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
                <label class="control-label" for="cbo-amphur">อำเภอ/เขต</label>
                <select id="cbo-amphur" class="form-control" name="cboAmphur" disabled >
                  <option>อำเภอ</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="control-label" for="cbo-district">ตำบล/แขวง</label>
                <select id="cbo-district" class="form-control" name="cboDistrict" disabled>
                  <option>ตำบล</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 form-group">
                <label class="control-label" for="txt-post-code">รหัสไปรษณีย์</label>
                <input type="text" class="form-control" id="txt-post-code" placeholder="รหัสไปรษณีย์" name="txtPostCode"
                      value="<?php echo $member->postcode; ?>">          
              </div>
            </div>
            <div class="row">
              <div class="col-xs-6 form-group">
             
                <label class="control-label" for="txt-password">รหัสผ่าน</label>
                <input type="password" class="form-control" id="txt-password-signup" placeholder="รหัสผ่าน" 
                      name="txtPassword" value="<?php echo $member->password; ?>" >        
              </div>
              <div class="col-xs-6 form-group">
            
                <label class="control-label" for="txt-confirm-password">ยืนยันรหัสผ่าน</label>       
                <input type="password" class="form-control" id="txt-confirm-password" placeholder="ยืนยันรหัสผ่าน" name="txtConfirm"
                      value="<?php echo $member->password; ?>">
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-default" onclick="window.location='index.php'">Cancel</button>
          </div>          
        </div>
        <!--end of tab 1 -->
        <!--tab 2-->
        <div role="tabpanel" class="tab-pane" id="wearer-profile">            
          <div class="col-md-4">   
                  
               
                <?php 
                  echo "<img class=\"img-thumbnail\" id=\"img-show\" style=\"max-width: 6000px;max-height: 450px;\" src=\"" . $member->photo . "\" alt=\"...\" value=\"" .$member->photo. "\">";
                ?>
                
                <div class="form-group form-inline">
                  <div class="fileinput fileinput-new" data-provides="fileinput">
                    <span class="btn btn-default btn-file">
                    <span class="fileinput-new">อัพโหลดรูปคู่</span>
                    <span class="fileinput-exists">Change</span>
                    <input type="file" name="..." id="file-upload"></span>
                    <span class="fileinput-filename" ></span>
                    
                  </div>
                  <button type="button" class="btn btn-warning" id="btn-delete-image">ลบรูป</button>
                </div>                 
             
            <div class="form-group">
              <label class="control-label" for="txt-height-boy">ส่วนสูง (คนที่ 1)</label>
              <input type="text" class="form-control" id="txt-height-boy" placeholder="ซม." 
                    name="txtHeight_1" value="<?php echo $member->height_1; ?>" >
            </div>      
            <div class="form-group">
              <label class="control-label" for="txt-height-girl">ส่วนสูง (คนที่ 2)</label>
              <input type="text" class="form-control" id="txt-height-girl" placeholder="ซม." 
                    name="txtHeight_2" value="<?php echo $member->height_2; ?>">
            </div>            
          </div>
        </div>
      <!--end of tab 2 -->
      </div>
    </div>    

    </div>
    </form>  
    <script src="js/province.combo.js"></script>
    <script type="text/javascript">
    var imageLoader = document.getElementById('file-upload');    
    var fileUpload = undefined;
    var imgShow = document.getElementById('img-show');
    var btnDeleteImage = document.getElementById('btn-delete-image');
    btnDeleteImage.onclick = function(){
      imageLoader.files = "";
      imgShow.src = "";
      imgShow.setAttribute('value', '');
      $('.fileinput').fileinput('clear');      
    }

    //document ready
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
              txtHeight_1: {
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
              txtHeight_2: {
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
            }//fields
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
 
    imageLoader.addEventListener('change', handleImage, false);
    function handleImage(e){

        var files = e.target.files;
        for (var i = 0; i < files.length; i++) {
            if (files[i].type.match(/image.svg*/)) {
                var reader = new FileReader();
                reader.onload = function (event) {
                  //
                   fileUpload = event.target.result;
                   imgShow.src = fileUpload;
                }
                reader.readAsDataURL(files[i]);
               
            }else if (files[i].type.match(/image.*/)){
                var reader = new FileReader();
                reader.onload = function(event){
                    //
                    fileUpload = event.target.result;
                    imgShow.src = fileUpload;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        } 
    }

    function goSave($form){
      var email = document.getElementById('txt-form-email');
      var oldUpload = imgShow.getAttribute('value');

      $.ajax({
          type: 'POST',
          dataType: 'json',
          url: './service/', 
          data: $form.serialize() + "&email=" + email.value + "&method=updateMember&fileToUpload=" + fileUpload +"&oldUpload=" + oldUpload
      })//ajax
      .done(function(data){
        if (data.result === "success"){
          Toast.init({
              "selector": ".alert-success"
          });
          Toast.show("<strong>แก้ไขสำเร็จ!!!</strong><br/>");             
        }else{
          Toast.init({
            "selector": ".alert-danger"
          });
          Toast.show("<strong>Error on saving!!!<strong>" + data);
        }
      })//done
      .fail(function(data) {
        bootbox.dialog({
                    title: 'Fatal Error',
                    message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });
      });//fail
    }      
    </script>
  </body>
</html>