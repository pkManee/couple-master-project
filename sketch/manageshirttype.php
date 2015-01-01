<!DOCTYPE html>
<?php
 require("header.php");
 if (!isset($_GET["shirttype"])){
 	$_GET["shirttype"] = "";
 }
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sign up</title>
    <!-- Bootstrap -->
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
    <input type="hidden" id="hidden-shirttype" name="isDelete" value="<?php echo $_GET["shirttype"]; ?>">

    <?php
      require("navbar.php");
      require("service/message_service.php");
      require("service/db_connect.php");

      //get data
      class ShirtType
      {
        public $shirt_type = "";
        public $shirt_type_description = "";
      }

      //select user profile
      if (isset($_GET["shirttype"]) && !empty($_GET["shirttype"])){           
          try {
              $dbh = dbConnect::getInstance()->dbh;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
          }

          $sql = "select shirt_type, shirt_type_description from shirt_type ";
          $sql .= "where shirt_type = :shirt_type ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(":shirt_type", $_GET["shirttype"]);
          if ($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, "ShirtType");
            $shirttype = $stmt->fetch();           
          }else{
            echo "error -> " .$stmt->errorInfo()[2];
          }
      }else{
      	$shirttype = new ShirtType();
      }
    ?>
    <form id="manage-shirt-type-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="container">
    <div class="col-xs-6 col-md-4">   
    
      <div class="form-group">        
        <label class="control-label" for="txt-shirt-type">แบบเสื้อ</label>
        <input type="text" class="form-control" id="txt-shirt-type" name="txtShirtType" 
        	     value="<?php echo $shirttype->shirt_type; ?>" >        
      </div>

      <div class="form-group">        
        <label class="control-label" for="txt-shirt-type-description">คำอธิบาย</label>
        <input type="text" class="form-control" id="txt-shirt-type-description" name="txtShirtTypeDescription" 
              value="<?php echo $shirttype->shirt_type_description; ?>" >      
      </div>     
        <button type="submit" class="btn btn-primary">Save</button>
        <a role="button" class="btn btn-default" href="listshirttype.php">Cancel</a>
        <button id="btn-delete" class="btn btn-warning">Delete</button>
    </div>
    </div>
    </form>    
    <script type="text/javascript">
     $(document).ready(function() {
      $('#manage-shirt-type-form')
          .bootstrapValidator({
              //... options ...
              feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  txtShirtType: {
                      message: 'กรุณาระบุแบบเสื้อ',
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุแบบเสื้อ'
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
        url: 'data/manageshirttype.data.php', 
        data: $form.serialize()
    })
    .done(function(data){
      if (data.result === "success"){
        Toast.init({
            "selector": ".alert-success"
        });
        Toast.show("<strong>Save completed!!!</strong><br/>redirecting ...");
        setTimeout(function(){ window.location = "listshirttype.php" }, 1000);
      }else{
        Toast.init({
          "selector": ".alert-danger"
        });
        Toast.show("<strong>Error on saving!!!<strong> " + data);
      }
    })
    .fail(function() {
      bootbox.dialog({
                  title: 'Fetal Error',
                  message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
      });
    });
  }

   var btnDelete = document.getElementById('btn-delete');
   btnDelete.onclick = function(){     	
   	event.preventDefault();
   	bootbox.confirm({
			title: '', 
			message: '<div class="alert alert-info" role="alert">Are you sure to <strong>delete?</strong></div>',
			callback: function(result) {
				if (result) deleteShirtType();     
		}
  	}); 
   }

   function deleteShirtType(){     	
   	var isDelete = document.getElementById('hidden-shirttype');
   	$.ajax({
          type: 'POST',
          url: 'data/manageshirttype.data.php', 
          data: {isDelete: isDelete.value, txtShirtType: isDelete.value}
      })
      .done(function(data){
        if (data.result === "success"){
          Toast.init({
              "selector": ".alert-success"
          });
          Toast.show("<strong>Delete completed!!!</strong><br/>redirecting ...");
          setTimeout(function(){ window.location = "listshirttype.php" }, 1000);
        }else{
          Toast.init({
            "selector": ".alert-danger"
          });
          Toast.show("<strong>Error on saving!!!<strong> " + data);
        }
      })
      .fail(function() {
        bootbox.dialog({
                    title: 'Fetal Error',
                    message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });
      });
   }
    </script>
  </body>
</html>