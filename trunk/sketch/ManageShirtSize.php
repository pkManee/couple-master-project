<!DOCTYPE html>
<?php
 require("header.php");
 if (!isset($_GET["sizecode"])){
 	$_GET["sizecode"] = "";
 }
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Shirt Size</title>
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
    <div class="alert alert-success" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>
    <div class="alert alert-danger" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>
    <input type="hidden" id="hidden-sizecode" name="isDelete" value="<?php echo $_GET["sizecode"]; ?>">

    <?php
      require("navbar.php");
      require("service/message_service.php");
      require("service/db_connect.php");

      //get data
      class Sizecode
      {
        public $size_code = "";
        public $chest_size = 0;
        public $shirt_length = 0;
        public $gender = "M";
      }

      //select user profile
      if (isset($_GET["sizecode"]) && !empty($_GET["sizecode"])){           
          try {
              $dbh = dbConnect::getInstance()->dbh;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
          }

          $sql = "select size_code, chest_size, shirt_length, gender from shirt_size ";
          $sql .= "where size_code = :size_code and gender = :gender ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(":size_code", $_GET["sizecode"]);
          $stmt->bindValue(":gender", $_GET["gender"]);
          if ($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Sizecode");
            $sizecode = $stmt->fetch();           
          }else{
            echo "error -> " .$stmt->errorInfo()[2];
          }
      }else{
      	$sizecode = new Sizecode();
      }
    ?>
    <form id="manage-shirt-size-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="container">
    <div class="col-xs-6 col-md-4">   
    
      <div class="form-group">        
        <label class="control-label" for="txt-size-code">รหัสขนาดเสื้อ</label>
        <input type="text" class="form-control" id="txt-size-code" name="txtSizecode" placeholder="รหัสขนาดเสื้อ"
        	     value="<?php echo $sizecode->size_code; ?>" >        
      </div>
      <div class="form-group">        
        <label class="control-label" for="txt-chest-size">ความยาวรอบอก (ซม.)</label>
        <input type="text" class="form-control" id="txt-chest-size" name="txtChestSize" placeholder="ความยาวรอบอก"
              value="<?php echo $sizecode->chest_size; ?>" >      
      </div>
      <div class="form-group">        
        <label class="control-label" for="txt-shirt-length">ความยาวเสื้อ (ซม.)</label>
        <input type="text" class="form-control" id="txt-shirt-length" name="txtShirtLength" placeholder="ความยาวเสื้อ"
              value="<?php echo $sizecode->shirt_length; ?>" >      
      </div>
      <div class="form-group">
        
      <?php        
        $active = $sizecode->gender=="M"?"active":"";
        $checked = $sizecode->gender=="M"?"checked":"";
       
        echo "<div class=\"btn-group\" data-toggle=\"buttons\">";
        echo    "<label class=\"btn btn-primary " .$active. " \">";
        echo      "<input type=\"radio\" name=\"rdoGender\" value=\"M\" " .$checked. ">สำหรับชาย";
        echo    "</label>";
        $active = $sizecode->gender=="F"?"active":"";
        $checked = $sizecode->gender=="F"?"checked":"";
        echo    "<label class=\"btn btn-primary ".$active." \">";
        echo      "<input type=\"radio\" name=\"rdoGender\" value=\"F\" " .$checked. " >สำหรับหญิง";
        echo    "</label>";
        echo "</div>";
      ?>
      </div>
      <button type="submit" class="btn btn-primary">Save</button>
      <a role="button" class="btn btn-default" href="listshirtsize.php">Cancel</a>
      <button id="btn-delete" class="btn btn-warning">Delete</button>
    </div>
    </div>
    </form>    
    <script type="text/javascript">
    
     $(document).ready(function() {
      $('.btn-group').button();

      $('#manage-shirt-size-form')
          .bootstrapValidator({
              //... options ...
              feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  txtSizecode: {
                      validators: {
                        notEmpty: {
                          message: 'กรุณาระบุรหัสขนาดเสื้อ'
                        }
                      }
                  },
                  txtChestSize: {
                      validators: {
                        notEmpty:{
                          message: 'กรุณาระบุความยาวรอบอก'
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
                  txtShirtLength: {
                      validators: {
                        notEmpty:{
                          message: 'กรุณาระบุความยาวเสื้อ'
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
              goSave($form);
          });//on success.form.bv
      });//document.ready

  function goSave($form){
    $.ajax({
        type: 'POST',
        url: 'data/manageshirtsize.data.php', 
        data: $form.serialize() + "&method=insert"
    })
    .done(function(data){
      if (data.result === "success"){
        Toast.init({
            "selector": ".alert-success"
        });
        Toast.show("<strong>Save completed!!!</strong><br/>redirecting ...");
        setTimeout(function(){ window.location = "listshirtsize.php" }, 1000);
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
   var isDelete = document.getElementById('hidden-sizecode');
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
          url: 'data/manageshirtsize.data.php', 
          data: {method: 'delete', size_code: isDelete.value}
      })
      .done(function(data){
        if (data.result === "success"){
          Toast.init({
              "selector": ".alert-success"
          });
          Toast.show("<strong>Delete completed!!!</strong><br/>redirecting ...");
          setTimeout(function(){ window.location = "listshirtsize.php" }, 1000);
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