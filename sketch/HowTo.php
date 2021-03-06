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
    <title>วิธีใช้งาน</title>

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
  <div class="container marketing">
  <ol class="breadcrumb">
    <li><a href="index.php">Home</a></li>
    <li class="active">วิธีใช้งาน</li>
  </ol>
    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading">อยากได้เสื้อเชิ้ตลายเก๋ๆ ไม่ยากด้วย <span class="text-muted">3 ขั้นตอน</span></h2>
        <p class="lead">1. สมัครสมาชิก กรอกรายละเอียดต่างๆ เช่น อีเมล์ รหัสผ่าน ชื่อ และที่อยู่ เพื่อจัดส่งสินค้าในอนาคต</p>
        <p class="lead">2. ระบุข้อมูลผู้สวมใส่โดยอัพโหลดรูปคู่และระบุส่วนสูงของทั้งคู่</p>
      </div>
      <div class="col-md-5">
        <img class="featurette-image img-responsive center-block" alt="500x500" src="img/howto01.png">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7 col-md-push-5">
        <h2 class="featurette-heading">เรามีเครื่องมือให้ใช้งานง่าย <span class="text-muted">ทำความเข้าใจ และทดลองใช้</span></h2>
        <p class="lead">3. ใช้เครื่อมือสร้างลายสกรีนนำเข้ารูปภาพ หรือสร้างสรรค์ใหม่หมดด้วยตัวเอง</p>
        <p class="lead">4. กดที่ปุ่ม <span class="glyphicon glyphicon-shopping-cart"></span> (สั่งซื้อ) เพื่อสั่งซื้อเสื้อพร้อมลายสกรีน</p>
        <p class="lead">5. ระบบจะนำท่านเข้าสู่หน้ายืนยันการสั่งซื้อ ท่านสามารถระบุจำนวนเสื้อที่ต้องการ และขนาดของเสื้อที่ต้องการได้</p>
      </div>
      <div class="col-md-5 col-md-pull-7">
        <img class="featurette-image img-responsive center-block" alt="500x500" src="img/02.jpg">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading">ขั้นตอนสุดท้ายที่สำคัญ<span class="text-muted"> แจ้งชำระเงินในระบบ</span></h2>
        <p class="lead">6. เมื่อยืนยันการสั่งซื้อแล้ว ท่านจะสามารถพิมพ์คำสั่งซื้อจากระบบ หรือจากอีเมล์ของท่านที่ได้ลงทะเบียนไว้ โดยจะระบุเลขที่คำสั่งซื้ออยู่</p>
        <p class="lead">ซึ่งเลขที่คำสั่งซื้อนี้มีความสำคัญ เพราะจะต้องใช้ในการแจ้งชำระเงินอีกทีหนึ่ง</p>
        <p class="lead">7. ไปที่เมนู ข้อมูลสมาชิก->แจ้งชำระเงิน จากนั้นกรอกข้อมูลให้ครบ โดยใช้เลขที่คำสั่งซื้อจากคำสั่งซื้อที่ท่านมีอยู่ พร้อมทั้งแนบหลักฐานการโอน</p>
        <p class="lead">8. กด ยืนยันแจ้งชำระเงิน ทางร้านจะตรวจสอบหลักฐานและจะส่งสินค้าให้ท่าานภายใน 3-5 วันทำการ</p>
      </div>
      <div class="col-md-5">
        <img class="featurette-image img-responsive center-block" src="img/howto02.png">
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

