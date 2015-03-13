<!DOCTYPE html>
<?php
  require("header.php");  
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Job</title>
    
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.css">
    <link rel="stylesheet" href="css/bootstrap-dialog.css" >

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap-dialog.js"></script>
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

      if (isset($_SESSION["email"]) && !empty($_SESSION["email"])) {           
		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  print "Error!: " . $e->getMessage() . "<br/>";
		  die();
		}
		
		$sql = 'select o.order_id, o.line_screen_price_1, o.line_screen_price_2, o.qty_1, o.qty_2, o.amt, o.order_date, ';
		$sql .= 'o.paid_date, o.deliver_date, o.cancel_date, o.cancel_remark, o.tracking_id, ';
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
		$sql .= 'where o.email = :email and o.order_id = :order_id ';

		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(':email', $_SESSION['email']);
		$stmt->bindValue(':order_id', $_GET['order_id']);

		if ($stmt->execute()){			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);	    
		} else {
			echo "error -> " .$stmt->errorInfo()[2];
			die();
		}

		echo '<input type="hidden" value="' .$_GET['order_id']. '" id="order-id"></input>';
		echo '<input type="hidden" value="' .$_GET['rtn']. '" id="return-url"></input>';

		$orderDate = (empty($result['order_date']))?'':date('d-m-Y', strtotime($result['order_date']));
        $paidDate = (empty($result['paid_date']))?'':date('d-m-Y', strtotime($result['paid_date']));
        $deliverDate = (empty($result['deliver_date']))?'':date('d-m-Y', strtotime($result['deliver_date']));
        $cancelDate = (empty($result['cancel_date']))?'':date('d-m-Y', strtotime($result['cancel_date']));
        $cancelRemark = $result['cancel_remark'];
        $trackingId = $result['tracking_id'];

		$shirt1 = '<b>เสื้อ: </b>'. $result['shirt_name_1'] . '<b> เพศ: </b>' . (($result['gender_1'] == 'M') ? 'ชาย' : 'หญิง') . '<b> size: </b>' . $result['size_code_1'] . '<b> สี: </b>' . $result['color_1'];
		$shirt2 = '<b>เสื้อ: </b>'. $result['shirt_name_2'] . '<b> เพศ: </b>' . (($result['gender_2'] == 'M') ? 'ชาย' : 'หญิง') . '<b> size: </b>' . $result['size_code_2'] . '<b> สี: </b>' . $result['color_2'];
	}
    ?>
    
    <div class="container" >
   	<div id="print-area">
   		<div>
	    	<label>ชื่อ-นามสกุล</label>        	
	    	<p style="display: inline-block; margin-left: 20px;"><?php echo $result['member_name']; ?></p>
	    </div>
	    <div>
	    	<label>อีเมล์</label>        	
	    	<p style="display: inline-block; margin-left: 20px;"><?php echo $_SESSION['email']; ?></p>
	    </div>
	    <div>
	    	<label>ที่อยู่</label>	    	
	    	<p style="display: inline-table; margin-left: 20px;"><?php echo nl2br($result['address']); ?></p>
	    </div>
	   
		<div style="font: normal 12px/150% Arial, Helvetica, sans-serif;background: #fff;overflow: hidden;border: 1px solid #069;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px">
	        <table style="border-collapse: collapse;text-align: left;width: 100%">
	            <thead>
	                <tr>
	                    <th style="text-align: center;width: 20px;padding: 3px 10px;background: -moz-linear-gradient(center top, #069 5%, #00557F 100%);background-color: #069;color: #FFF;font-size: 15px;font-weight: bold;border-left: 1px solid #0070A8">ลำดับ</th>
	                    <th style="padding: 3px 10px;background: -moz-linear-gradient(center top, #069 5%, #00557F 100%);background-color: #069;color: #FFF;font-size: 15px;font-weight: bold;border-left: 1px solid #0070A8">รายการสั่งซื้อ <?php echo '<b>#' . $_GET['order_id'] . '</b> (' .$orderDate.')' ?></th>
	                    <th style="text-align: center;padding: 3px 10px;background: -moz-linear-gradient(center top, #069 5%, #00557F 100%);background-color: #069;color: #FFF;font-size: 15px;font-weight: bold;border-left: 1px solid #0070A8">ราคา (บาท)</th>
	                    <th style="text-align: center;padding: 3px 10px;background: -moz-linear-gradient(center top, #069 5%, #00557F 100%);background-color: #069;color: #FFF;font-size: 15px;font-weight: bold;border-left: 1px solid #0070A8">จำนวน</th>
	                    <th style="text-align: center;padding: 3px 10px;background: -moz-linear-gradient(center top, #069 5%, #00557F 100%);background-color: #069;color: #FFF;font-size: 15px;font-weight: bold;border-left: 1px solid #0070A8">รวม (บาท)</th>
	                </tr>
	            </thead>
	            <tbody>
	                <tr>
	                    <td style="padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">1</td>
	                    <td style="padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">
	                        <?php echo $shirt1; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">
	                        <?php echo $result[ 'shirt_price_1']; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">
	                        <?php echo $result[ 'qty_1']; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">
	                        <?php echo number_format($result['shirt_price_1'] * $result[ 'qty_1'], 2) ?>
	                    </td>
	                </tr>
	                <tr class="alt">
	                    <td style="padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">2</td>
	                    <td style="padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">ลายสกรีน ขนาด
	                        <?php echo $result[ 'screen_width_1'] . 'x' .$result[ 'screen_height_1'] . ' ซม.'; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">
	                        <?php echo $result[ 'line_screen_price_1']; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">
	                        <?php echo $result[ 'qty_1']; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">
	                        <?php echo number_format($result[ 'line_screen_price_1'] * $result[ 'qty_1'], 2) ?>
	                    </td>
	                </tr>
	                <tr>
	                    <td style="padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">3</td>
	                    <td style="padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">
	                        <?php echo $shirt2; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">
	                        <?php echo $result[ 'shirt_price_2']; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">
	                        <?php echo $result[ 'qty_2']; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal">
	                        <?php echo number_format($result[ 'shirt_price_2'] * $result[ 'qty_2'], 2) ?>
	                    </td>
	                </tr>
	                <tr class="alt">
	                    <td style="padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">4</td>
	                    <td style="padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">ลายสกรีน ขนาด
	                        <?php echo $result[ 'screen_width_2'] . 'x' .$result[ 'screen_height_2'] . ' ซม.'; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">
	                        <?php echo $result[ 'line_screen_price_2']; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">
	                        <?php echo $result[ 'qty_2']; ?>
	                    </td>
	                    <td style="text-align: right;padding: 3px 10px;color: #00496B;border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal;background: #E1EEF4">
	                        <?php echo number_format($result[ 'line_screen_price_2'] * $result[ 'qty_2'], 2) ?>
	                    </td>
	                </tr>
	            </tbody>
	            <tfoot style="min-height: 25px !important;">
	                <tr>
	                    <td colspan="4" style="padding: 0;font-size: 12px">
	                        <div style="text-align: center;font-size: 13px;min-height: 25px !important;border-top: 1px solid #069;background: #E1EEF4;padding: 2px">
	                             <?php	        
	                            //echo !!empty($paidDate);//(!empty($paidDate) && empty($deliverDate) && empty($cancelDate));                    
	                            if (!empty($paidDate)) echo 'ชำระเงินแล้ว: ' .$paidDate;
	                            if (!empty($deliverDate)) echo ' ส่งสินค้าแล้ว: ' .$deliverDate;
	                            if (!empty($trackingId)) echo ' หมายเลขสิ่งของ: ' .$trackingId;
	                            if (!empty($cancelDate)) echo ' ยกเลิกแล้ว: ' .$cancelDate. ' สาเหตุ: ' .nl2br($cancelRemark);
	                            ?>
	                            <b style="padding-left: 30px;">รวม</b>	                           
	                        </div>
	                    </td>
	                    <td style="text-align: right;padding: 0;font-size: 12px">
	                        <div style="min-height: 25px;border-top: 1px solid #069;background: #E1EEF4;padding: 2px">
	                            <b style="padding-right: 8px;"><?php echo number_format($result['amt'], 2); ?></b>
	                        </div>
	                    </td>
	                </tr>
	            </tfoot>
	        </table>
	    </div>
	    <br>
	    <p style="text-align: left;width: 100%;padding: 3px 10px;background: -moz-linear-gradient(center top, #069 5%, #00557F 100%);background-color: #069;color: #FFF;font-size: 15px;font-weight: bold;border-left: 1px solid #0070A8">ลายสกรีน</p>
	    <div style="text-align: center;">
		    <img style="width: 300px;" src="<?php echo 'uploads/' .$result['order_id']. '/01.png' ?>">
		    <img style="width: 300px;" src="<?php echo 'uploads/' .$result['order_id']. '/02.png' ?>">
		</div>	    
	   
	    <br>
	    <p style="page-break-before: always; text-align: left;width: 100%;padding: 3px 10px;background: -moz-linear-gradient(center top, #069 5%, #00557F 100%);background-color: #069;color: #FFF;font-size: 15px;font-weight: bold;border-left: 1px solid #0070A8">ภาพตัวอย่าง</p>
	    <div style="text-align: center;">
	    	<img src="<?php echo 'uploads/' .$result['order_id']. '/03.png' ?>">
	    </div>
    </div> 
    <!-- print area -->

   	<p class="form-control-static bg-success">รายการสั่งซื้อเสื้อพร้อมลายสกรีน</p>
   	<br/>
  	<div class="panel panel-info" >			
	    <div class="panel-heading">
		    <h4 class="panel-title"><?php echo 'รายการสั่งซื้อ ' . '<b>#' . $_GET['order_id'] . '</b> (' . $orderDate .')' ?></h4>
	    </div>
	    		    	
    	<div class="panel-body">
			<div class="form-group">
	        	<label class="col-xs-3 form-control-static">ชื่อ-นามสกุล</label>
	        	<div class="col-xs-9">
	        		<p class="form-control-static"><?php echo $result['member_name']; ?></p>
	        	</div>		        	
		    </div>
		    <div class="form-group">
		    	<label class="col-xs-3 form-control-static">อีเมล์</label>
	        	<div class="col-xs-9">
	        		<p class="form-control-static"><?php echo $_SESSION['email']; ?></p>
	        	</div>
		    </div>
		    <div class="form-group">
		    	<label class="col-xs-3 form-control-static">ที่อยู่</label>
		    	<div class="col-xs-6">
		    		<p class="form-control-static" name="txtAddress" id="txt-address"><?php echo nl2br($result['address']); ?></p>
		    	</div>
		    </div>
      		<div class="form-group">
      			<div class="col-xs-6">
	      			<div class="panel panel-default">
		      			<div class="panel-heading">เสื้อตัวที่ 1</div>	
		      			<div class="panel-body">	
	      				<div class="row">
				      		<label class="form-control-static col-xs-3">เพศ</label>
				      		<div class="col-xs-3">
				      			<p class="form-control-static"><?php echo ($result['gender_1'] == 'M') ? 'ชาย' : 'หญิง' ; ?></p>
				      		</div>
				      	</div>				      	
				      	<div class="row">
		      				<div class="form-group">
				      			<label class="form-control-static col-xs-3">ประเภท</label>
					      		<div class="col-xs-3">
					      			<p class="form-control-static"><?php echo $result['shirt_type_1']; ?></p>
					      		</div>
					      	</div>
				      	</div>
				      	<div class="row">					      
				      		<label class="form-control-static col-xs-3">สี</label>
		      				<div class="col-xs-4">
				      			<p class="form-control-static"><?php echo $result['color_1']; ?></p>								      			
				      		</div>
				      		<div class="col-xs-2">
				      			<span class="form-control label-sm" style="background: <?php echo $result['color_hex_1']; ?>"></span>
				      		</div>					      	
				      	</div>
				      	<div class="row">					      	
				      		<label class="form-control-static col-xs-3">ชนิดผ้า - ขนาด</label>
				      		<div class="col-xs-3">
				      			<p class="form-control-static"><?php echo $result['material_type_1'] . ' - ' . $result['size_code_1']; ?></p>
				      		</div>					      	
				      	</div>
				      	<div class="row">				      	
				      		<label class="form-control-static col-xs-3">ราคาเสื้อเปล่า</label>
				      		<div class="col-xs-3">
				      			<p class="form-control-static" id="price-1"><?php echo $result['shirt_price_1']; ?></p>
				      		</div>				      	
				      	</div>
				      	<div class="row">
				      		<label class="form-control-static col-xs-3" for="screen-price-1">ราคาลายสกรีน</label>
				      		<div class="col-xs-3">
				      			<p class="form-control-static" id="screen-price-1"><?php echo $result['line_screen_price_1'] ?></p>
				      		</div>
				      	</div>
				      	<div class="row">
				      		<label class="form-control-static col-xs-3" for="qty-1">จำนวน</label>
				      		<div class="col-xs-6">
				      			<p class="form-control-static"><?php echo $result['qty_1'] ?></p>
				      		</div>
				      	</div>
				      	<div class="row">
				      		<label class="form-control-static col-xs-3">รวม</label>
				      		<div class="col-xs-3">
				      			<?php
				      			$total_1 = ($result['shirt_price_1'] + $result['line_screen_price_1']) * $result['qty_1'];
				      			?>
				      			<p class="form-control-static"><?php echo number_format($total_1, 2); ?></p>
				      		</div>
				      	</div>
				      	</div>
		      		</div>
		      	</div>

		      	<div class="col-xs-6">
	      			<div class="panel panel-default">
		      			<div class="panel-heading">เสื้อตัวที่ 2</div>
		      			<div class="panel-body">
		      				<div class="row">
					      		<label class="form-control-static col-xs-3">เพศ</label>
					      		<div class="col-xs-3">
					      			<p class="form-control-static"><?php echo ($result['gender_2'] == 'M') ? 'ชาย' : 'หญิง' ; ?></p>
					      		</div>
					      	</div>
		      				<div class="row">
				      			<label class="form-control-static col-xs-3">ประเภท</label>
					      		<div class="col-xs-3">
					      			<p class="form-control-static"><?php echo $result['shirt_type_2']; ?></p>
					      		</div>
					      	</div>
					      	<div class="row">
				      			<label class="form-control-static col-xs-3">สี</label>							      								      			
			      				<div class="col-xs-3">
					      			<p class="form-control-static"><?php echo $result['color_2']; ?></p>								      			
					      		</div>
					      		<div class="col-xs-2">
					      			<span class="form-control" style="background: <?php echo $result['color_hex_2']; ?>"></span>
					      		</div>						      			
					      	</div>
					      	<div class="row">
					      		<label class="form-control-static col-xs-3">ชนิดผ้า - ขนาด</label>						      								      		
					      		<div class="col-xs-3">
					      			<p class="form-control-static"><?php echo $result['material_type_2'] . ' - ' . $result['size_code_2']; ?></p>						      			
					      		</div>
					      	</div>
					      	<div class="row">
					      		<label class="form-control-static col-xs-3">ราคาเสื้อเปล่า</label>
					      		<div class="col-xs-3">
					      			<p class="form-control-static"><?php echo $result['shirt_price_2']; ?></p>
					      		</div>
					      	</div>
					      	<div class="row">
					      		<label class="form-control-static col-xs-3" for="screen-price-2">ราคาลายสกรีน</label>
					      		<div class="col-xs-3">
					      			<p class="form-control-static" id="screen-price-2"><?php echo $result['line_screen_price_2']; ?></p>
					      		</div>
					      	</div>
					      	<div class="row">
					      		<label class="form-control-static col-xs-3">จำนวน</label>
					      		<div class="col-xs-6">
					      			<p class="form-control-static"><?php echo $result['qty_2'] ?></p>
					      		</div>
					      	</div>
					      	<div class="row">
					      		<label class="form-control-static col-xs-3">รวม</label>
					      		<div class="col-xs-3">
					      			<?php
					      			$total_2 = ($result['shirt_price_2'] + $result['line_screen_price_2']) * $result['qty_2'];
					      			?>
					      			<p class="form-control-static"><?php echo number_format($total_2, 2); ?></p>
					      		</div>
					      	</div>
				      	</div>
		      		</div>				      		
		      	</div>
      		</div>

      		<div class="col-xs-6">
	      		<div class="form-group">
	      			<label class="form-control-static col-xs-3">รวมทั้งสิ้น (บาท)</label>
	      			<div >
	      				<b><p class="form-control-static text-success" id="total-price"><?php echo number_format($result['amt'], 2); ?></p></b>
	      			</div>				      			
				</div>
			</div>
			
		</div>
	   
  	</div>
	<img src="">
	<div class="row"></div>
		<div class="col-xs-6">
			<button type="button" class="btn btn-primary" id="btn-print">พิมพ์ใบงาน</button>
			<button type="button" class="btn btn-success <?php echo (empty($paidDate) && empty($deliverDate) && empty($cancelDate)) ?'':'hidden'; ?>" id="btn-paid">ยืนยันการรับชำระเงิน</button>
			<button type="button" class="btn btn-success <?php echo (!empty($paidDate) && empty($deliverDate) && empty($cancelDate)) ?'':'hidden'; ?>" id="btn-deliver">ยืนยันการส่งสินค้า</button>
			<button type="button" class="btn btn-warning <?php echo (empty($paidDate) && empty($deliverDate) && empty($cancelDate)) ?'':'hidden'; ?>" id="btn-cancel">ยกเลิกรายการสั่งซื้อ</button>
			<button type="button" class="btn btn-danger <?php echo (!empty($cancelDate)) ?'':'hidden'; ?>" id="btn-delete">ลบรายการสั่งซื้อ</button>
			<a class="btn btn-default" href="<?php echo $_GET['rtn'] ?>">กลับ</a>			
		</div>

    </div> <!-- container -->
    <iframe id="ifmcontentstoprint" style="height: 0px; width: 0px; position: absolute"></iframe>    
    <script type="text/javascript">
    var btnPrint = document.getElementById('btn-print');
    btnPrint.onclick = function() {    	
    	var content = document.getElementById('print-area');
		var pri = document.getElementById('ifmcontentstoprint').contentWindow;
		var myStyle = '<html><div style="font-size: 16px; font-family: tahoma ">';
		var body = myStyle + content.innerHTML + '</div></html>';
		pri.document.open();
		pri.document.write(body);
		pri.document.close();
		pri.focus();
		pri.print();		
    }

    var totalPrice = document.getElementById('total-price').innerHTML;
    var btnPaid = document.getElementById('btn-paid');    
    btnPaid.onclick = function() {
    	var msg = '<div class="container">';
    	msg += '<div class="row">';
    	msg += '<label class="form-control-static col-xs-3">รับชำระเงินแล้วจำนวน:</label>';
    	msg += '<p class="form-control-static col-xs-2"><b>' + totalPrice + ' บาท</b></p>';
    	msg += '</div>';
    	msg += '<div class="row">';    	
    	msg += '<label class="form-control-static col-xs-3">วันที่</label>';
    	msg += '<div class="col-xs-2">';   	
    	msg += '<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>">';
    	msg += '</div>';
    	msg += '</div>';
    	msg += '</div>';

    	var dialog = new BootstrapDialog({
    		type    :   BootstrapDialog.TYPE_SUCCESS,
			title	:	'ยืนยันการรับชำระเงิน',
			message	:	$(msg),
			buttons	:	[{
				label	:	'ไม่ยืนยัน',
				action	:	function(dialog){
					dialog.close();
				}
			}, {
				label	:	'ยืนยัน',
				cssClass:	'btn-success',
				action	:	function(dialog){
					var paidDate = dialog.getModalBody().find('input').val();
					dialog.close();
                    confirmOrder(paidDate);					
				}
			}]
		});
		dialog.open();
    }

    function confirmOrder(paidDate) {
    	if (paidDate === '') {
    		BootstrapDialog.show({
	        		type: BootstrapDialog.TYPE_WARNING,
	                title: 'Error',
	                message : '<div class="alert alert-warning" role="alert"><strong>กรุราระบุวันที่ !!!</strong></div>'
	        });//bootbox
	        return false;
    	}

    	var orderId = document.getElementById('order-id').value;
    	$.ajax({
	        type: "POST",
	        dataType: "json",
	        url: "data/ShirtOrder.data.php",
	        data: {method: "confirmOrder", order_id: orderId, paid_date: paidDate}
	    })
	    .done(function(data) {
	        if (data.result === "success") {

	        	var rtnUrl = document.getElementById('return-url').value;
	        	window.scrollTo(0, 0);
	            Toast.init({
	                "selector": ".alert-success"
	            });
	            Toast.show('<strong>Success !!!<strong><br>redirecting ...');

	            setTimeout(function() {
	            	window.location = rtnUrl;
	            }, 2000);
	        }

	    })//done
	    .fail(function(data) { 
	        BootstrapDialog.show({
	        		type: BootstrapDialog.TYPE_WARNING,
	                title: 'Fatal Error',
	                message : '<div class="alert alert-danger" role="alert"><strong>Error in confirmOrder !!!</strong></div>'
	        });//bootbox
	    });//fail
    }

    var btnDeliver = document.getElementById('btn-deliver');
    btnDeliver.onclick = function() {    	
        var msg = '<div class="container">';

    	msg += '<div class="row">';
    	msg += 		'<div class="col-xs-2">';
    	msg += 			'<p class="form-control-static">ส่งสินค้ารายการนี้</p>';    
    	msg += 		'</div>';	
    	msg += '</div>';
    	msg += '<div class="row">';
    	msg += 		'<div class="col-xs-2">';
    	msg += 			'<p class="form-control-static">วันที่</p>';
    	msg += 		'</div>';
    	msg += 		'<div class="col-xs-2">';   	
    	msg += 			'<input type="date" class="form-control" id="delivery-date" value="<?php echo date("Y-m-d");?>">';
    	msg += 		'</div>';
    	msg += '</div>';
    	msg += '<div class="row">';
    	msg += 		'<div class="col-xs-2">';
    	msg +=			'<p class="form-control-static">หมายเลขสิ่งของ</p>';
		msg += 		'</div>';
    	msg +=		'<div class="col-xs-2">';
    	msg +=			'<input type="text" class="form-control" id="tracking-id">';
    	msg +=		'</div>';
    	msg += '</div>';

    	msg += '</div>';

        BootstrapDialog.show({
            title: 'ยืนยันการส่งสินค้า',
            message: msg,
            buttons: [{
                label: 'ไม่ยืนยัน',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: 'ยืนยัน',
                cssClass: 'btn-success',
                action: function(dialog) {
                	var deliverDate = dialog.getModalBody().find('#delivery-date').val();
                	var trackingId = dialog.getModalBody().find('#tracking-id').val();
                	dialog.close();
                	confirmDeliver(deliverDate, trackingId);
                }
            }]
        });
    }

    function confirmDeliver(deliverDate, trackingId) {
    	if (deliverDate === '') {
    		BootstrapDialog.show({
	        		type: BootstrapDialog.TYPE_WARNING,
	                title: 'Error',
	                message : '<div class="alert alert-warning" role="alert"><strong>กรุราระบุวันที่ !!!</strong></div>'
	        });//bootbox
	        return false;
    	}

    	var orderId = document.getElementById('order-id').value;
    	$.ajax({
	        type: "POST",
	        dataType: "json",
	        url: "data/ShirtOrder.data.php",
	        data: {method: "confirmDeliver", order_id: orderId, deliver_date: deliverDate, tracking_id: trackingId}
	    })
	    .done(function(data) {
	        if (data.result === "success") {

	        	var rtnUrl = document.getElementById('return-url').value;
	        	window.scrollTo(0, 0);
	            Toast.init({
	                "selector": ".alert-success"
	            });
	            Toast.show('<strong>Success !!!<strong><br>redirecting ...');

	            setTimeout(function() {
	            	window.location = rtnUrl;
	            }, 2000);
	        }

	    })//done
	    .fail(function(data) { 
	        BootstrapDialog.show({
	        		type: BootstrapDialog.TYPE_WARNING,
	                title: 'Fatal Error',
	                message : '<div class="alert alert-danger" role="alert"><strong>Error in confirmDeliver !!!</strong></div>'
	        });//bootbox
	    });//fail
    }

    var btnCancel = document.getElementById('btn-cancel');
    btnCancel.onclick = function() {

        var msg = '<div class="container">';
    	msg += 		'<div class="row">';
    	msg += 			'<label class="control-lable col-xs-3">ยกเลิกรายการสั่งซื้อ</label>';
    	msg += 		'</div>';
    	msg += 		'<div class="row col-xs-6">'; 		
    	msg += 			'<label class="control-lable">เหตผล</label>';    	 	
    	msg += 			'<input type="text" class="form-control">';
    	msg += 		'</div>';
    	msg += '</div>';

        BootstrapDialog.show({
        	type: BootstrapDialog.TYPE_WARNING,
            title: 'ยกเลิกรายการสั่งซื้อสินค้า',
            message: msg,
            buttons: [{
                label: 'ไม่ยืนยัน',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: 'ยืนยัน',
                cssClass: 'btn-success',
                action: function(dialog) {
                	var cancelRemark = dialog.getModalBody().find('input').val();
                	dialog.close();
                	confirmCancel(cancelRemark);
                }
            }]
        });
    }

    function confirmCancel(cancelRemark) {
    	var orderId = document.getElementById('order-id').value;
    	$.ajax({
	        type: "POST",
	        dataType: "json",
	        url: "data/ShirtOrder.data.php",
	        data: {method: "confirmCancel", order_id: orderId, cancel_remark: cancelRemark}
	    })
	    .done(function(data) {
	        if (data.result === "success") {

	        	var rtnUrl = document.getElementById('return-url').value;
	        	window.scrollTo(0, 0);
	            Toast.init({
	                "selector": ".alert-success"
	            });
	            Toast.show('<strong>Success !!!<strong><br>redirecting ...');

	            setTimeout(function() {
	            	window.location = rtnUrl;
	            }, 2000);
	        }

	    })//done
	    .fail(function(data) { 
	        BootstrapDialog.show({
	        		type: BootstrapDialog.TYPE_WARNING,
	                title: 'Fatal Error',
	                message : '<div class="alert alert-danger" role="alert"><strong>Error in confirmCancel !!!</strong></div>'
	        });//bootbox
	    });//fail
    }

    var btnDelete = document.getElementById('btn-delete');
    btnDelete.onclick = function() {
    	BootstrapDialog.confirm({
    		title: 'ลบรายการสั่งซื้อ',
            message: 'รายการสั่งซื้อนี้จะถูกลบออกอย่างถาวร<br><b>ท่านต้องการลบรายการสั่งซื้อนี้หรือไม่</b>',
            type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
            closable: true, // <-- Default value is false
            draggable: true, // <-- Default value is false
            btnCancelLabel: 'ไม่ยืนยัน', // <-- Default value is 'Cancel',
            btnOKLabel: 'ยืนยัน', // <-- Default value is 'OK',
            btnOKClass: 'btn-success', // <-- If you didn't specify it, dialog type will be used,
            callback: function(result) {
                // result will be true if button was click, while it will be false if users close the dialog directly.
                if(result) {
                    confirmDelete();
                }
            }
        });
    }

    function confirmDelete() {
    	var orderId = document.getElementById('order-id').value;
    	$.ajax({
	        type: "POST",
	        dataType: "json",
	        url: "data/ShirtOrder.data.php",
	        data: {method: "confirmDelete", order_id: orderId}
	    })
	    .done(function(data) {
	        if (data.result === "success") {

	        	var rtnUrl = document.getElementById('return-url').value;
	        	window.scrollTo(0, 0);
	            Toast.init({
	                "selector": ".alert-success"
	            });
	            Toast.show('<strong>Success !!!<strong><br>redirecting ...');

	            setTimeout(function() {
	            	window.location = rtnUrl;
	            }, 2000);
	        }

	    })//done
	    .fail(function(data) { 
	        BootstrapDialog.show({
	        		type: BootstrapDialog.TYPE_WARNING,
	                title: 'Fatal Error',
	                message : '<div class="alert alert-danger" role="alert"><strong>Error in confirmDelete !!!</strong></div>'
	        });//bootbox
	    });//fail
    }
    </script>
  </body>
</html>