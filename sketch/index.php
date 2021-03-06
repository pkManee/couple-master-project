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
            <img class="center-block" src="img/carousel/c3.jpg" alt="">
            <div class="container">
              <div class="carousel-caption">
                <h3>ออกแบบ</h3>
                <p><a class="btn btn-primary hidden" href="design.php" role="button">เริ่มสร้างลายสกรีน</a></p>
              </div>
            </div>
         
        </div>
        <div class="item">          
            <img class="center-block" src="img/carousel/c2.jpg" alt="">
            <div class="carousel-caption">
              <h3>สร้างสรรค์</h3>
              <p><a class="btn btn-primary hidden" href="design.php" role="button">เริ่มสร้างลายสกรีน</a></p>
            </div>         
        </div>
        <div class="item">          
            <img class="center-block" src="img/carousel/c4.jpg" alt="">
            <div class="carousel-caption">
              <h3>พิมพ์ลายสกรีน</h3>
              <p><a class="btn btn-primary hidden" href="design.php" role="button">เริ่มสร้างลายสกรีน</a></p>
            </div>          
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

  <div class="container marketing">
    <hr class="featurette-divider">
    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading">อยากได้เสื้อเชิ้ตลายเก๋ๆ ไม่ยากด้วย <span class="text-muted">3 ขั้นตอน</span></h2>
        <p class="lead">1. มีความคิดริเริ่มสร้างสรรค์</p>
      </div>
      <div class="col-md-5">
        <img class="featurette-image img-responsive center-block" alt="500x500" src="img/01.jpg">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7 col-md-push-5">
        <h2 class="featurette-heading">เรามีเครื่องมือให้ใช้งานง่าย <span class="text-muted">ทำความเข้าใจ และทดลองใช้</span></h2>
        <p class="lead">2. ใช้เครื่อมือสร้างลายสกรีนนำเข้ารูปภาพ หรือสร้างสรรค์ใหม่หมดด้วยตัวเอง</p>
      </div>
      <div class="col-md-5 col-md-pull-7">
        <img class="featurette-image img-responsive center-block" alt="500x500" src="img/02.jpg">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading">เลือกสีเสื้อให้เหมาะกับลายที่ออกแบบ<span class="text-muted"> จะเด่นเป็นคู่ หรือกลมกลืน ก็มีให้เลือก</span></h2>
        <p class="lead">3. แสดงภาพลายสกรีนกับสีเสื้อที่เลือกไว้ ถึงผู้สวมใส่จะมีความสูงที่แตกต่างกัน ก็สามารถปรับให้ลายมีการต่อกันได้เป็นคู่</p>
      </div>
      <div class="col-md-5">
        <img class="featurette-image img-responsive center-block" src="img/03.png">
      </div>
    </div>

    <hr class="featurette-divider">

    <!-- /END THE FEATURETTES -->


    <!-- FOOTER -->
    <footer>
      <p class="pull-right"><a href="#">Back to top</a></p>
      <p>© 2014 Company, Inc. · <a href="#">Privacy</a> · <a href="#">Terms</a></p>
    </footer>
  </div>

  </body>
</html>

