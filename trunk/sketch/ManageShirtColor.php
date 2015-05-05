<!DOCTYPE html>
<?php
 require("header.php");
 if (!isset($_GET["color"])){
 	$_GET["color"] = "";
 }
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Shirt Color</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">       
    <link href="css/bootstrapValidator.css" rel="stylesheet">
    <link href="css/bootstrap-colorpicker.css" rel="stylesheet" >

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
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

      //get data
      class Color
      {
        public $color = "";
        public $color_hex = "";
      }

      //select user profile
      if (isset($_GET["color"]) && !empty($_GET["color"])){           
          try {
              $dbh = dbConnect::getInstance()->dbh;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
          }

          $sql = "select color, color_hex from shirt_color ";
          $sql .= "where color = :color ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(":color", $_GET["color"]);
          if ($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Color");
            $color = $stmt->fetch();           
          }else{
            echo "error -> " .$stmt->errorInfo()[2];
          }
          echo '<input type="hidden" value="update" id="form-method">';
          echo '<input type="hidden" value="'.$color->color_hex.'" id="hidden-color-hex">';
      }else{
      	$color = new Color();
        echo '<input type="hidden" value="insert" id="form-method">';
        echo '<input type="hidden" value="" id="hidden-color-hex">';
      }
    ?>
    <form id="manage-shirt-color-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="container">
    <div class="col-xs-6 col-md-4">   
    
      <div class="form-group">        
        <label class="control-label" for="txt-color">สี</label>
        <input type="text" class="form-control" id="txt-color" name="txtColor" placeholder="สี" 
               value="<?php echo $color->color; ?>" aria-describedby="basic-addon2" >
      </div>
      <div class="form-group">
        <div class="input-group" id="color-pick">
          <input type="text" class="form-control" id="txt-color-hex" name="txtColorHex" placeholder="#000000" 
        	     value="<?php echo $color->color_hex; ?>" >
          <span class="input-group-addon"><i></i></span>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Save</button>
      <a role="button" class="btn btn-default" href="ListShirtColor.php">Cancel</a>
      <button id="btn-delete" class="btn btn-warning">Delete</button>
    
    </div>
    </div>
    </form>    
    <script type="text/javascript">
     $(document).ready(function() {
      //initial color picker for bootstrap
      $('#color-pick').colorpicker();

      $('#manage-shirt-color-form')
          .bootstrapValidator({
              //... options ...
              feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  txtColor: {
                      verbose: false,
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุสี'
                          }
                          // ,remote: {
                          //   url: 'data/ManageShirtColor.data.php',
                          //   data: { method: 'checkColor', color: document.getElementById('txt-color').value, formMethod: document.getElementById('form-method').value },
                          //   message: 'สีนี้มีในระบบแล้ว',
                          //   type: 'POST'
                          // }
                      }
                  },
                  txtColorHex: {
                      validators: {
                          notEmpty: {
                              message: 'กรุณาเลือกสี'
                          }
                          // ,remote: {
                          //   url: 'data/ManageShirtColor.data.php',
                          //   data: { method: 'checkColorHex', colorHex: document.getElementById('txt-color-hex').value, formMethod: document.getElementById('form-method').value },
                          //   message: 'รหัสนี้มีในระบบแล้ว',
                          //   type: 'POST'
                          // }
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
        url: 'data/ManageShirtColor.data.php', 
        data: $form.serialize() + '&method=insert'
    })
    .done(function(data){
      if (data.result === "success"){
        Toast.init({
            "selector": ".alert-success"
        });
        Toast.show("<strong>Save completed!!!</strong><br/>redirecting ...");
        setTimeout(function(){ window.location = "ListShirtColor.php" }, 1000);
      }else{
        Toast.init({
          "selector": ".alert-danger"
        });
        Toast.show("<strong>Error on saving!!!<strong> " + data);
      }
    })
    .fail(function() {
      bootbox.dialog({
                  title: 'Fatal Error',
                  message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
      });
    });
  }

   var btnDelete = document.getElementById('btn-delete');
   var isDelete = document.getElementById('hidden-color-hex');
   btnDelete.onclick = function(){     	
   	event.preventDefault();
    if (!isDelete.value) return false;

   	bootbox.confirm({
			title: '', 
			message: '<div class="alert alert-info" role="alert">Are you sure to <strong>delete?</strong></div>',
			callback: function(result) {
				if (result) goDelete();     
		}
  	}); 
   }

   function goDelete(){ 

   	$.ajax({
          type: 'POST',
          url: 'data/ManageShirtColor.data.php', 
          data: {isDelete: isDelete.value, method: 'delete'}
      })
      .done(function(data){
        if (data.result === "success"){
          Toast.init({
              "selector": ".alert-success"
          });
          Toast.show("<strong>Delete completed!!!</strong><br/>redirecting ...");
          setTimeout(function(){ window.location = "ListShirtColor.php" }, 1000);
        }else{
          Toast.init({
            "selector": ".alert-danger"
          });
          Toast.show("<strong>Error on deleting!!!<strong> " + data);
        }
      })
      .fail(function() {
        bootbox.dialog({
                    title: 'Fatal Error',
                    message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });
      });
   }
    </script>
  </body>
</html>