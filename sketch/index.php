<?php

session_start();
if (!isset($_SESSION['email'])){  
  $_SESSION['email'] = '';
  $_SESSION['member_name'] = '';
}
?>
<!DOCTYPE html>
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
  
  <div class="container">
  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
      <li data-target="#carousel-example-generic" data-slide-to="1"></li>
      <li data-target="#carousel-example-generic" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <a href="design.php">
          <img class="center-block" src="img/carousel/c3.jpg" alt="">
          <div class="carousel-caption">
            <h3>คลิ๊กเพื่อใช้เครื่องมือสร้างลายสกรีน</h3>
          </div>
        </a>
      </div>
      <div class="item">
        <a href="design.php">
          <img class="center-block" src="img/carousel/c2.jpg" alt="">
          <div class="carousel-caption">
            <h3>คลิ๊กเพื่อใช้เครื่องมือสร้างลายสกรีน</h3>
          </div>
        </a>
      </div>
      <div class="item">
        <a href="design.php">
          <img class="center-block" src="img/carousel/c4.jpg" alt="">
          <div class="carousel-caption">
            <h3>คลิ๊กเพื่อใช้เครื่องมือสร้างลายสกรีน</h3>
          </div>
        </a>
      </div>      
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  </div>
  </body>
</html>

