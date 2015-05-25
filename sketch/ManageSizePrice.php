<!DOCTYPE html>
<?php
 require("header.php");
 if (!isset($_GET["size_price_id"])){
 	$_GET["size_price_id"] = "";
 }
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manage Size Price</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">       
    <link href="css/bootstrap-select.css" rel="stylesheet">
    <link href="css/bootstrapValidator.css" rel="stylesheet">    

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script>
    <script src="js/bootstrap-select.js"></script>
  </head>
  <body>  
    <div class="alert alert-success" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>
    <div class="alert alert-danger" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>
    <input type="hidden" id="hidden-size-price-id" name="isDelete" value="<?php echo $_GET["size_price_id"]; ?>">

    <?php
      require("navbar.php");
      require("service/message_service.php");
      require("service/db_connect.php");

      //get data
      class SizePrice
      {
        public $size_price_id = 0;
        public $size_area = 0;
        public $price = 0;
        public $width = 0;
        public $height = 0;
        public $description = "";
      }

      try {
          $dbh = dbConnect::getInstance()->dbh;
      } catch (PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();
      }

      //select shirts
      if (isset($_GET["size_price_id"]) && !empty($_GET["size_price_id"])){
          $sql = "select size_price_id, size_area, price, width, height, description ";
          $sql .= "from size_price ";
          $sql .= "where size_price_id = :size_price_id ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(":size_price_id", $_GET["size_price_id"]);
          if ($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, "SizePrice");
            $size_price = $stmt->fetch();           
          }else{
            echo "error -> " .$stmt->errorInfo()[2];
          }         

      }else{
      	$size_price = new SizePrice();
      }      

    ?>
    <form id="manage-size-price-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="container">
    <ol class="breadcrumb">
      <li><a href="index.php">Home</a></li>    
      <li><a href="ListSizePrice.php">ราคาลายสกรีน</a></li>    
      <li class="active"><?php echo $size_price->description; ?></li>
    </ol>
    <div class="col-xs-6 col-md-4">
      <div class="form-group">        
        <label class="control-label" for="txt-description">คำอธิบาย</label>
        <input type="text" class="form-control" id="txt-description" name="txtDescription" 
               value="<?php echo $size_price->description; ?>" >        
      </div>
     
      <div class="form-group">        
        <label class="control-label" for="txt-size-area">ขนาดพื้นที่ (ตร.ซม.)</label>
        <input type="text" class="form-control" id="txt-size-area" name="txtSizeArea" 
               value="<?php echo $size_price->size_area; ?>">        
      </div>      
      <div class="form-group">        
        <label class="control-label" for="txt-price">ราคา</label>
        <input type="text" class="form-control" id="txt-price" name="txtPrice" 
               value="<?php echo $size_price->price; ?>" >        
      </div>

      <button type="submit" class="btn btn-primary">Save</button>
      <button type="button" class="btn btn-default" onclick="window.location = 'ListSizePrice.php'">Cancel</button>
      <button type="button" id="btn-delete" class="btn btn-warning">Delete</button>
    </div>
    </div>
    </form>    
    <script type="text/javascript">
    var btnDelete = document.getElementById('btn-delete');
    var isDelete = document.getElementById('hidden-size-price-id');

    $(document).ready(function() {

      $('#manage-size-price-form')
          .bootstrapValidator({
              //... options ...
              feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  txtSizeArea: {
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุขนาดพื้นที่'
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
                  txtPrice: {
                      validators: {
                          notEmpty:{
                            message: 'กรุณาระบุราคา'
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
        url: 'data/ManageSizePrice.data.php', 
        data: $form.serialize() + "&method=insert&size_price_id=" + isDelete.value
    })
    .done(function(data) {
      if (data.result === "success"){
        Toast.init({
            "selector": ".alert-success"
        });
        Toast.show("<strong>Save completed!!!</strong><br/>redirecting ...");
        setTimeout(function(){ window.location = "ListSizePrice.php" }, 1000);
      }else{
        Toast.init({
          "selector": ".alert-danger"
        });
        Toast.show("<strong>Error on saving!!!<strong> " + data);
      }
    })
    .fail(function(data) {
      bootbox.dialog({
                  title: 'Fatal Error',
                  message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
      });
    });
  }
  
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
          url: 'data/ManageSizePrice.data.php', 
          data: {method: 'delete', size_price_id: isDelete.value}
      })
      .done(function(data){
        if (data.result === "success"){
          Toast.init({
              "selector": ".alert-success"
          });
          Toast.show("<strong>Delete completed!!!</strong><br/>redirecting ...");
          setTimeout(function(){ window.location = "ListSizePrice.php" }, 1000);
        }else{
          Toast.init({
            "selector": ".alert-danger"
          });
          Toast.show("<strong>Error on deleting!!!<strong> " + data);
        }
      })//done
      .fail(function() {
        bootbox.dialog({
                    title: 'Fatal Error',
                    message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });//fail
      });//ajax
  } 
  </script>
  </body>
</html>