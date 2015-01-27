<!DOCTYPE html>
<?php
  require("header.php");  
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Cart</title>
    
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
    <form id="view-cart-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
    <div class="container">
   	<?php
   	$gender_1 = $_POST["gender_1"];
    $shirt_type_1 = $_POST["shirt_type_1"];
 
   	?>
   	<img src="<?php echo $_POST['screen1']; ?>" style="width:512px; height:512px;">
    </div>
    </form>   
  </body>
</html>



