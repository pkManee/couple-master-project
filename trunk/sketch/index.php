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
    <title>Bootstrap 101 Template</title>

   
    <link href="css/bootstrap.css" rel="stylesheet">    
  </head>
  <body>  
 
  <?php 
    require('navbar.php');
  ?>  

  </body>
</html>

