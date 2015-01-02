<!DOCTYPE html>
<?php

session_start();
if (!isset($_SESSION['email'])){  
  $_SESSION['email'] = '';
  $_SESSION['member_name'] = '';
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>C Shirt</title>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">    
    <link href="css/bootstrapValidator.css" rel="stylesheet">

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script> 
  </head>
  <body>  
  <div class="alert alert-success" role="alert" style="display:none; z-index: 1000; position: absolute; left:0px; top: 50px;">
      <span>populate alert</span>
  </div>
  <?php 
    require('navbar.php');
  ?>  

  </body>
</html>

