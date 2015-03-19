<!DOCTYPE html>
<?php
  session_start();  
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Confirm Payment</title>
    
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
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
    <?php echo '<input type="hidden" id="hidden-email" value="' .$_SESSION['email']. '">'; ?>
    <div class="container">
    <div class="col-xs-6 col-md-4">    
      <div class="form-group">        
        <label class="control-label" for="txt-order-id">เลขที่คำสั่งซื้อ</label>
        <input type="text" class="form-control" id="txt-order-id" placeholder="ID" name="txtOrderId">       
      </div>

      <div class="form-group">
        <label class="control-label" for="txt-paid-date">วันที่โอน</label>
        <input type="date" class="form-control" id="txt-member-name" placeholder="วันที่โอน" name="txtPaidDate" >
      </div>      
      <div class="form-group">
        <label class="control-label" for="txt-paid-time">เวลาโอน</label>
        <input type="time" class="form-control" id="txt-paid-time" placeholder="เวลาโอน" name="txtPaidTime" >
      </div>
      <div class="form-group">
        <label class="control-label" for="txt-amt">จำนวนเงิน</label>
        <input type="text" class="form-control" id="txt-amt" placeholder="ยอดเงิน" name="txtAmt" >
      </div>
      <div class="form-group">
        <label class="control-label" for="input-file">แนบหลักฐาน</label>
        <input type="file" class="form-control" id="input-file" placeholder="แนบหลักฐาน" name="fileToUpload" >
      </div>  
      <button type="submit" class="btn btn-primary" id="btn-signup-submit">ยืนยันการโอน</button>
    </div>
    </div>
    </form>
    <script type="text/javascript">
    var file;
    var fileUpload = document.getElementById('input-file');
    fileUpload.onchange = function(e) {
      file = e.target.files;      
    }

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
                  txtOrderId: {
                      verbose: false,
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุเลขที่สั่งซื้อ'
                          },
                          remote: {
                              url: 'data/ShirtOrder.data.php',
                              data: function(validator) {
                                return {
                                        method: 'checkExistingOrder', 
                                        email: document.getElementById('hidden-email').value,
                                        order_id: validator.getFieldElements('txtOrderId').val()
                                      };
                              },
                              message: 'ไม่พบเลขที่สั่งซื้อในระบบ กรุณาตรวจสอบ',
                              type: 'POST'
                          }
                      }
                  },
                  txtPaidDate: {
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุวันที่โอน'
                          }
                      }
                  },
                  txtPaidTime: {
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุเวลาโอน'
                          }
                      }
                  },
                  txtAmt: {
                      validators: {
                          notEmpty:{
                            message: 'กรุณาระบุจำนวนเงิน'
                          },
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
                  fileToUpload: {
                    validators: {
                      notEmpty: {
                        message: 'กรุณาแนบหลักฐานการโอน (JPG, PDF)'
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
            url: 'data/ShirtOrder.data.php', 
            data: $form.serialize() + '&method=customerPaid&FileInput=' + file
        })
        .done(function(data){
          if (data.result === "success"){
            Toast.init({
                "selector": ".alert-success"
            });
            Toast.show("<strong>Save completed!!!</strong> redirecting ...<br/>Please <strong>Conform Payment</strong> with your email");
            //setTimeout(function(){ window.location = "index.php" }, 3000);
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



