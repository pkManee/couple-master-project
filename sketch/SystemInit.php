<!DOCTYPE html>
<?php
  session_start();  
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>System Initializing</title>
    
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/bootstrap-dialog.css" rel="stylesheet" >
    <link href="css/bootstrap-select.css" rel="stylesheet">
    <link href="css/bootstrapValidator.css" rel="stylesheet">

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>   
    <script src="js/bootstrapValidator.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/bootstrap-dialog.js"></script>
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

      try {
        $dbh = dbConnect::getInstance()->dbh;
      } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
      }

      $sql = "select print_format, width, height, vat_rate from printer ";
      $stmt = $dbh->prepare($sql);
      if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
      }

      echo '<input type="hidden" id="old-print-format" value="'.$result['print_format'].'">';
    ?>
    <form id="system-init-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >    
    <div class="container">
    <ol class="breadcrumb">
      <li><a href="index.php">Home</a>
      <li class="active">ค่าตั้งต้น</li>
    </ol>
    <div class="col-md-4">    
      <div class="form-group">        
        <label class="control-label" for="cbo-printer-size">ขนาด Printer</label>
        <select class="selectpicker form-control" id="cbo-printer-size" name="cboPrinterSize">
        <?php
          echo '<option value="A4|21|29.7" ' .(($result['print_format']=='A4')?'selected':''). '>A4 ขนาด 21x29.7 ซม.</option>';
          echo '<option value="A3|29.7|42" ' .(($result['print_format']=='A3')?'selected':''). '>A3 ขนาด 29.7x42 ซม.</option>';
        ?>
        </select>      
      </div>

      <div class="form-group">
        <label class="control-label" for="txt-vat-rate">VAT (%)</label>
        <input type="text" class="form-control" placeholder="0.00" name="txtVatRate" id="txt-vat-rate" value="<?php echo $result['vat_rate']; ?>">
      </div>      
     
      <button type="submit" class="btn btn-primary" id="btn-signup-submit">Save</button>
    </div>
    </div>
    </form>
    <script type="text/javascript">
      $(document).ready(function() {


      $('#system-init-form')
          .bootstrapValidator({
              //... options ...
              feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  txtVatRate: {
                      validators: {
                          notEmpty:{
                            message: 'กรุณาระบุ VAT'
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
              goSave();
          });//on success.form.bv
      });//document.ready

      function goSave(){
        var cboPrinterSize = document.getElementById('cbo-printer-size').value;
        var print_format = cboPrinterSize.split('|')[0];
        var width = cboPrinterSize.split('|')[1];
        var height = cboPrinterSize.split('|')[2];
        var vat_rate = document.getElementById('txt-vat-rate').value;
        var old_print_format = document.getElementById('old-print-format').value;
        $.ajax({
            type: 'POST',
            url: 'service/',
            data: {method: "updateSystemInit", print_format: print_format, width: width, height: height, vat_rate: vat_rate, old_print_format: old_print_format}
        })
        .done(function(data){
          if (data.result === "success"){
            Toast.init({
                "selector": ".alert-success"
            });
            Toast.show("<strong>Save completed!!!</strong>");
            setTimeout(function() {
              window.location = 'SystemInit.php';
            }, 3000);
          }else{
            Toast.init({
              "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on saving!!!<strong> " + data);
          }
        })//done
        .fail(function(data) {
          BootstrapDialog.show({
                      type: BootstrapDialog.TYPE_WARNING,
                      title: 'Fatal Error',
                      message : '<strong>Error in connection !!!</strong>'
          });
        });//fail
      }//goSave
    </script>
  </body>
</html>



