<!DOCTYPE html>
<?php
 require("header.php");
 if (!isset($_GET["materialtype"])){
 	$_GET["materialtype"] = "";
 }
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Material Type</title>
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
    <input type="hidden" id="hidden-materialtype" name="isDelete" value="<?php echo $_GET["materialtype"]; ?>">

    <?php
      require("navbar.php");
      require("service/message_service.php");
      require("service/db_connect.php");

      //get data
      class materialtype
      {
        public $material_type = "";
        public $description = "";
      }

      //select user profile
      if (isset($_GET["materialtype"]) && !empty($_GET["materialtype"])){           
          try {
              $dbh = dbConnect::getInstance()->dbh;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
          }

          $sql = "select material_type, description from material ";
          $sql .= "where material_type = :material_type ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(":material_type", $_GET["materialtype"]);
          if ($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, "materialtype");
            $materialtype = $stmt->fetch();           
          }else{
            echo "error -> " .$stmt->errorInfo()[2];
          }
      }else{
      	$materialtype = new materialtype();
      }
    ?>
    <form id="manage-material-type-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="container">
    <div class="col-xs-6 col-md-4">   
    
      <div class="form-group">        
        <label class="control-label" for="txt-material-type">ประเภทผ้า</label>
        <input type="text" class="form-control" id="txt-material-type" name="txtMaterialType" 
        	     value="<?php echo $materialtype->material_type; ?>" >        
      </div>

      <div class="form-group">        
        <label class="control-label" for="txt-description">คำอธิบาย</label>
        <input type="text" class="form-control" id="txt-description" name="txtDescription" 
              value="<?php echo $materialtype->description; ?>" >      
      </div>     
        <button type="submit" class="btn btn-primary">Save</button>
        <a role="button" class="btn btn-default" href="listmaterialtype.php">Cancel</a>
        <button id="btn-delete" class="btn btn-warning">Delete</button>
    </div>
    </div>
    </form>    
    <script type="text/javascript">
    
     $(document).ready(function() {
      $('#manage-material-type-form')
          .bootstrapValidator({
              //... options ...
              feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  txtMaterialType: {
                      message: 'กรุณาระบุประเภทผ้า',
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุประเภทผ้า'
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
        url: 'data/managematerialtype.data.php', 
        data: $form.serialize()
    })
    .done(function(data){
      if (data.result === "success"){
        Toast.init({
            "selector": ".alert-success"
        });
        Toast.show("<strong>Save completed!!!</strong><br/>redirecting ...");
        setTimeout(function(){ window.location = "listmaterialtype.php" }, 1000);
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
   var isDelete = document.getElementById('hidden-materialtype');
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
          url: 'data/managematerialtype.data.php', 
          data: {isDelete: isDelete.value, txtMaterialType: isDelete.value}
      })
      .done(function(data){
        if (data.result === "success"){
          Toast.init({
              "selector": ".alert-success"
          });
          Toast.show("<strong>Delete completed!!!</strong><br/>redirecting ...");
          setTimeout(function(){ window.location = "listmaterialtype.php" }, 1000);
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