<!DOCTYPE html>
<?php
  require("header.php");  
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Order Result</title>
    
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.css">

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
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
    
    <div class="container" >
   	<?php
	if (isset($_SESSION["email"]) && !empty($_SESSION["email"])) {           
		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  print "Error!: " . $e->getMessage() . "<br/>";
		  die();
		}
		
		$sql = 'select o.order_id, o.line_screen_price_1, o.line_screen_price_2, o.qty_1, o.qty_2, o.amt, ';
		$sql .= 's1.gender as gender_1, s1.shirt_type as shirt_type_1, s1.color_hex as color_hex_1, s1.shirt_price as shirt_price_1, ';
		$sql .= 's2.gender as gender_2, s2.shirt_type as shirt_type_2, s2.color_hex as color_hex_2, s2.shirt_price as shirt_price_2, ';
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
		}
	}   	
   	?>

   		
  	<div class="panel panel-info" id="print-area" >			
	    <div class="panel-heading">
		    <h4 class="panel-title"><?php echo 'รายการสั่งซื้อ ' . '#' . $_GET['order_id'] ?></h4>
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
		    		<p class="form-control-static" name="txtAddress" id="txt-address"><?php echo $result['address']; ?></p>
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
				      		<label class="form-control-static col-xs-3" for="total-1">รวม</label>
				      		<div class="col-xs-3">
				      			<p class="form-control-static" id="total-1">0</p>
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
					      			<p class="form-control-static" id="screen-price-2"><?php echo $result['line_screen_price_2'] ?></p>
					      		</div>
					      	</div>
					      	<div class="row">
					      		<label class="form-control-static col-xs-3">จำนวน</label>
					      		<div class="col-xs-6">
					      			<p class="form-control-static"><?php echo $result['qty_2'] ?></p>
					      		</div>
					      	</div>
					      	<div class="row">
					      		<label class="form-control-static col-xs-3" for="total-2">รวม</label>
					      		<div class="col-xs-3">
					      			<p class="form-control-static" id="total-2">0</p>
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
	
	<div class="row"></div>
		<div class="col-xs-6">
			<button type="button" class="btn btn-success" id="btn-print">พิมพ์รายการสั่งซื้อ</button>
		</div>
    </div> <!-- container -->
    <iframe id="ifmcontentstoprint" style="height: 0px; width: 0px; position: absolute"></iframe>    
    <script type="text/javascript">
    var btnPrint = document.getElementById('btn-print');
    btnPrint.onclick = function() {
    	var content = document.getElementById('print-area');
		var pri = document.getElementById('ifmcontentstoprint').contentWindow;
		var myStyle = '<link rel="stylesheet" href="css/bootstrap.css" /><link rel="stylesheet" href="css/bootstrap-theme.css">';
		pri.document.open();
		pri.document.write(myStyle + content.innerHTML);
		pri.document.close();
		pri.focus();
		pri.print();
    }
    </script>
  </body>
</html> 