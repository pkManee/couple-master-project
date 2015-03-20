<!DOCTYPE html>
<?php

session_start();
if (!isset($_SESSION["email"]) || empty($_SESSION["email"])){
  header("location: index.php");
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Shirt Order</title>   
    <!--CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">       
    <link href="css/bootstrapValidator.css" rel="stylesheet">
    <link href="css/iconfont.css" rel="stylesheet">
    <!--List -->
    <link href="css/jasny-bootstrap.css" rel="stylesheet">

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script> 
  </head>
  <body>   
  <?php
    require("navbar.php");
    require("service/message_service.php");
    require("service/db_connect.php");

  ?>  
  <form id="list-shirt-color-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
  <div class="container">
    <div class="col-md-3">
      <div class="form-group"> 
        <div class="input-group">
          <span class="input-group-btn">
            <button class="btn btn-default icon-search" type="submit"></button>
          </span>
          <input type="text" class="form-control" id="txt-search" placeholder="ID" name="txtSearch"
          value="<?php 
            if (!empty($_GET['txtSearch'])) {
              echo $_GET['txtSearch'];
            } else {
              echo '';
            }
          ?>"
          >        
        </div>
      </div>
      <div class="form-group">
        <label for="order-status">สถานะคำสั่งซื้อ</label>
        <select class="form-control" name="OrderStatus" id="order-status">
        <?php

          $selectedItem = '';
          if (isset($_GET['OrderStatus']) && !empty($_GET['OrderStatus'])) {
            $selectedItem = $_GET['OrderStatus'];
          }
          echo '<option value="order" ' .(($selectedItem == 'order') ? 'selected' : ''). '>รับคำสั่งซื้อ</option>';
          echo '<option value="paid" ' .(($selectedItem == 'paid') ? 'selected':''). '>ลูกค้าแจ้งจำระเงินแล้ว</option>';
          echo '<option value="receive" ' .(($selectedItem == 'receive') ? 'selected':''). '>ยืนยันชำระเงินแล้ว</option>';
          echo '<option value="delivered" ' .(($selectedItem == 'delivered') ? 'selected':''). '>จัดส่งแล้ว</option>';        
          echo '<option value="canceled" ' .(($selectedItem == 'canceled') ? 'selected':''). '>ยกเลิก</option>';        
          echo '<option value="all" ' .(($selectedItem == 'all') ? 'selected':''). '>ทั้งหมด</option>';        
        ?>       
        </select>
      </div>
    </div>    
  </div>
  </form>
  <br/>

<?php 
try {
    $conn = dbConnect::getInstance();
    $dbh = $conn->dbh;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$sql = 'select o.order_id, o.email as email, o.line_screen_price_1, o.line_screen_price_2, o.qty_1, o.qty_2, o.amt, o.order_date, ';
$sql .= 'o.paid_date, o.deliver_date, o.cancel_date, o.tracking_id, ';
$sql .= 'o.screen_width_1, o.screen_height_1, o.screen_width_2, o.screen_height_2, o.color_area_1, o.color_area_2, ';
$sql .= 's1.shirt_name as shirt_name_1, s1.gender as gender_1, s1.shirt_type as shirt_type_1, s1.color_hex as color_hex_1, s1.shirt_price as shirt_price_1, ';
$sql .= 's2.shirt_name as shirt_name_2, s2.gender as gender_2, s2.shirt_type as shirt_type_2, s2.color_hex as color_hex_2, s2.shirt_price as shirt_price_2, ';
$sql .= 's1.material_type as material_type_1, s1.size_code as size_code_1, ';
$sql .= 's2.material_type as material_type_2, s2.size_code as size_code_2, ';
$sql .= 'm.member_name, m.address, ';
$sql .= 'c1.color as color_1, ';
$sql .= 'c2.color as color_2 ';
$sql .= 'from shirt_order o inner join shirts s1 on o.shirt_id_1 = s1.shirt_id ';
$sql .= 'inner join shirts s2 on o.shirt_id_2 = s2.shirt_id ';
$sql .= 'inner join member m on o.email = m.email ';
$sql .= 'inner join shirt_color c1 on s1.color_hex = c1.color_hex ';
$sql .= 'inner join shirt_color c2 on s2.color_hex = c2.color_hex ';
$sql .= 'where 1 = 1 ';

if (!isset($_GET['OrderStatus'])) $_GET['OrderStatus'] = 'order';

switch ($_GET['OrderStatus']) {
  case 'order':   
    $sql .= 'and order_date is not null and paid_date is null and deliver_date is null and cancel_date is null ';
    break;
  case 'paid':
    $sql .= 'and order_date is not null and paid_date is not null and confirm_paid_date is null and deliver_date is null and cancel_date is null ';
    break;
  case 'receive':
    $sql .= 'and order_date is not null and paid_date is not null and confirm_paid_date is not null and deliver_date is null and cancel_date is null ';
    break;
  case 'delivered':
    $sql .= 'and order_date is not null and paid_date is not null and deliver_date is not null and cancel_date is null ';
    break;
  case 'canceled':
    $sql .= 'and cancel_date is not null ';
    break;
  default:
    break;
}

if (!empty($_GET["txtSearch"])){
  $sql .= 'and o.order_id = :order_id ';
  $sql .= "order by order_date asc, order_id asc ";

  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(":order_id",$_GET["txtSearch"]); 
} else {
  $sql .= "order by order_date asc, order_id asc ";

  $stmt = $dbh->prepare($sql);
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
  <div class="container">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>#ID</th>
        <th>สมาชิก</th>
        <th>อีเมล์สมาชิก</th>
        <th>วันที่สั่งซื้อ</th>       
        <th>วันที่รับชำระเงิน</th>       
        <th>วันที่ส่งของ</th>
        <th>หมายเลขสิ่งของ</th>
        <th style="width: 150px; text-align: right;">ยอดสั่งซื้อ (บาท)</th>
        <th>ยกเลิก</th>
      </tr>
    </thead>
    <tbody data-link="row" class="rowlink">
      <?php
      $actual_link = urlencode(getUrl());
      $i = 1;
      foreach($result as $row) {       
        $order_id = $row['order_id'];
        $orderDate = (empty($row['order_date']))?'':date('d-m-Y', strtotime($row['order_date']));
        $paidDate = (empty($row['paid_date']))?'':date('d-m-Y', strtotime($row['paid_date']));
        $deliverDate = (empty($row['deliver_date']))?'':date('d-m-Y', strtotime($row['deliver_date']));
        $cancelDate = (empty($row['cancel_date']))?'':date('d-m-Y', strtotime($row['cancel_date']));
        echo '<tr>';

        echo '<th scope="row">' .$order_id. '</th>';
        echo '<td>' .$row['member_name']. '</td>';
        echo '<td>' .$row['email']. '</td>';        
        echo '<td><a href="JobToDo.php?order_id=' .$order_id. '&rtn=' .$actual_link. '">' .$orderDate. '</a></td>';
        echo '<td>' .$paidDate. '</td>';
        echo '<td>' .$deliverDate. '</td>';
        echo '<td>' .$row['tracking_id']. '</td>';
        echo '<td style="text-align: right;">' .number_format($row['amt']). '</td>';
        echo '<td>' .$cancelDate. '</td>';
       
        echo '</tr>';

        $i ++;
      }

      function getUrl() {
        $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
        $url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
        $url .= $_SERVER["REQUEST_URI"];
        return $url;
      }
      ?>       
    </tbody>
  </table>
  </div>
  <script type="text/javascript" src="js/jasny-bootstrap.min.js"></script>
  </body>
</html>