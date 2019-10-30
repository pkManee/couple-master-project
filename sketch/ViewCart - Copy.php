<!DOCTYPE html>
<?php
  require("header.php");  
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Cart</title>
    
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.css">
    <link rel="stylesheet" href="css/bootstrapValidator.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/jquery.bootstrap-touchspin.css">

    <!-- object detect -->
    <script src="js/objectdetect.js"></script>	
	<script src="js/objectdetect.frontalface.js"></script>	
	
	<script src="js/jquery-2.1.1.min.js"></script>	
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script>
    <!-- object detect -->
	<script src="js/jquery.objectdetect.js"></script>
	<script src="js/fabric.js"></script>
  </head>
  <body>  
    <div class="alert alert-success testclass" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
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
    
    <div class="container">
   	<?php
	include("data/Member.class.php");
	//select user profile
	if (isset($_SESSION["email"]) && !empty($_SESSION["email"])){           
		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  print "Error!: " . $e->getMessage() . "<br/>";
		  die();
		}

		if (!isset($_POST['shirt_color_1']) || empty($_POST['shirt_color_1'])) {
			die();
		}

		if (!isset($GLOBALS['print_format']) || empty($GLOBALS['print_format'])) {
			$sql = "select * from printer ";
			$stmt = $dbh->prepare($sql);
			if ($stmt->execute()) {
				$results = $stmt->fetch(PDO::FETCH_ASSOC);
				$GLOBALS['print_format'] = $results['print_format'] . '|' . $results['width'] . '|' . $results['height'];
			} else {
				print "Error!: " . $stmt->errorInfo() . "<br/>";
				die();
			}
		}		

		$sql = "select email, member_name, address, province_id, amphur_id, district_id, password, postcode, ";
		$sql .= "photo, height_1, height_2 ";
		$sql .= "from member where email = :email ";
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(":email", $_SESSION["email"]);
		if ($stmt->execute()){
			$stmt->setFetchMode(PDO::FETCH_CLASS, "Member");
			$member = $stmt->fetch();	    
		}else{
			echo "error -> " .$stmt->errorInfo()[2];
		}		

		$color_1 = $_POST['shirt_color_1'];
		echo '<input type="hidden" id="color_1" value="' .$color_1. '">';
		$shirt_type_1 = $_POST['shirt_type_1'];
		echo '<input type="hidden" id="shirt_type_1" value="' .$shirt_type_1. '">';
		$shirt_size_1 = $_POST['shirt_size_1'];
		echo '<input type="hidden" id="shirt_size_1" value="' .$shirt_size_1. '">';
		$gender_1 = $_POST['gender_1'];
		echo '<input type="hidden" id="gender_1" value="' .$gender_1. '">';
		echo '<input type="hidden" id="scale-x-1" value="' .$_POST['scaleX_1']. '">';
		echo '<input type="hidden" id="scale-y-1" value="' .$_POST['scaleY_1']. '">';
		echo '<input type="hidden" id="top-1" value="' .$_POST['top_1']. '">';
		echo '<input type="hidden" id="gap-left-1" value="' .$_POST['gapLeft_1']. '">';

		$color_2 = $_POST['shirt_color_2'];
		echo '<input type="hidden" id="color_2" value="' .$color_2. '">';
		$shirt_type_2 = $_POST['shirt_type_2'];
		echo '<input type="hidden" id="shirt_type_2" value="' .$shirt_type_2. '">';
		$shirt_size_2 = $_POST['shirt_size_2'];
		echo '<input type="hidden" id="shirt_size_2" value="' .$shirt_size_2. '">';
		$gender_2 = $_POST['gender_2'];
		echo '<input type="hidden" id="gender_2" value="' .$gender_2. '">';
		echo '<input type="hidden" id="scale-x-2" value="' .$_POST['scaleX_2']. '">';
		echo '<input type="hidden" id="scale-y-2" value="' .$_POST['scaleY_2']. '">';
		echo '<input type="hidden" id="top-2" value="' .$_POST['top_2']. '">';
		echo '<input type="hidden" id="gap-left-2" value="' .$_POST['gapLeft_2']. '">';

		echo '<input type="hidden" id="print-format" value="' .$print_format. '">';
		echo '<input type="hidden" id="member-email" value="' .$_SESSION['email']. '">';
		echo '<input type="hidden" id="height_1" value="' .$member->height_1. '">';
		echo '<input type="hidden" id="height_2" value="' .$member->height_2. '">';

		echo '<input type="hidden" id="member-photo" value="' .$member->photo. '">';
	}
 	
	function getColor($color_hex) {
		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  print "Error!: " . $e->getMessage() . "<br/>";
		  die();
		}
		$sql = "select color_hex, color from shirt_color where color_hex = :color_hex ";
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(":color_hex", $color_hex);
		if ($stmt->execute()) {
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			return $results["color"];
		}
	}
	function getShirtSize($size_code, $gender) {
		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  print "Error!: " . $e->getMessage() . "<br/>";
		  die();
		}
		$sql = "select chest_size, shirt_length from shirt_size ";
		$sql .= "where size_code = :size_code and gender = :gender ";
		$stmt->bindValue(":size_code", $size_code);
		$stmt->bindValue(":gender", $gender);
		if ($stmt->execute()) {
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			return $results;
		}
	}
   	
   	?>
	   	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  <div class="panel panel-info">			
			    <div class="panel-heading" role="tab" id="headingOne">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			          รายการสั่งซื้อ
			        </a>
			      </h4>
			    </div>
			    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
			    	<form class="form-horizontal" id="form-view-cart" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
			    	<div class="panel-body">
						<div class="form-group">
				        	<label class="col-xs-3 control-label">ชื่อ-นามสกุล</label>
				        	<div class="col-xs-9">
				        		<p class="form-control-static"><?php echo $member->member_name; ?></p>
				        	</div>		        	
					    </div>
					    <div class="form-group">
					    	<label class="col-xs-3 control-label">อีเมล์</label>
				        	<div class="col-xs-9">
				        		<p class="form-control-static"><?php echo $member->email; ?></p>
				        	</div>
					    </div>
					    <div class="form-group">
					    	<label class="col-xs-3 control-label">ที่อยู่</label>
					    	<div class="col-xs-6">
					    		<textarea class="form-control" name="txtAddress" id="txt-address"
					    			style="min-height: 100px;"><?php echo $member->address; ?></textarea>
					    	</div>
					    </div>
			      		<div class="form-group">
			      			<div class="col-xs-6">
				      			<div class="panel panel-default">
					      			<div class="panel-heading">เสื้อตัวที่ 1</div>
				      				<div class="form-group">
							      		<label class="control-label col-xs-3">เพศ</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static"><?php echo ($gender_1 == 'M') ? 'ชาย' : 'หญิง' ; ?></p>
							      		</div>
							      	</div>
				      				<div class="form-group">
						      			<label class="control-label col-xs-3">ประเภท</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static"><?php echo $shirt_type_1; ?></p>
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3">สี</label>
					      				<div class="col-xs-3">
							      			<p class="form-control-static"><?php echo getColor($color_1); ?></p>								      			
							      		</div>
							      		<div class="col-xs-2">
							      			<span class="form-control" style="background: <?php echo $color_1; ?>"></span>
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3">ชนิดผ้า - ขนาด</label>
							      		<div class="col-xs-3">
							      			<select class="selectpicker" id="cbo-material-1"></select>
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3">ราคาเสื้อเปล่า</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static" id="price-1"></p>
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3" for="screen-price-1">ราคาลายสกรีน</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static" id="screen-price-1">0</p>
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3" for="qty-1">จำนวน</label>
							      		<div class="col-xs-6">
							      			<input type="text" id="qty-1" name="qty1" value="1">
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3" for="total-1">รวม</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static" id="total-1">0</p>
							      		</div>
							      	</div>
					      		</div>
					      	</div>

					      	<div class="col-xs-6">
				      			<div class="panel panel-default">
					      			<div class="panel-heading">เสื้อตัวที่ 2</div>
				      				<div class="form-group">
							      		<label class="control-label col-xs-3">เพศ</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static"><?php echo ($gender_2 == 'M') ? 'ชาย' : 'หญิง' ; ?></p>
							      		</div>
							      	</div>
				      				<div class="form-group">
						      			<label class="control-label col-xs-3">ประเภท</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static"><?php echo $shirt_type_2; ?></p>
							      		</div>
							      	</div>
							      	<div class="form-group">
						      			<label class="control-label col-xs-3">สี</label>							      								      			
					      				<div class="col-xs-3">
							      			<p class="form-control-static"><?php echo getColor($color_2); ?></p>								      			
							      		</div>
							      		<div class="col-xs-2">
							      			<span class="form-control" style="background: <?php echo $color_2; ?>"></span>
							      		</div>						      			
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3">ชนิดผ้า - ขนาด</label>						      								      		
							      		<div class="col-xs-3">
							      			<select class="selectpicker" id="cbo-material-2"></select>						      			
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3">ราคาเสื้อเปล่า</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static" id="price-2"></p>
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3" for="screen-price-2">ราคาลายสกรีน</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static" id="screen-price-2">0</p>
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3" for="qty-2">จำนวน</label>
							      		<div class="col-xs-6">
							      			<input type="text" id="qty-2" name="qty2" value="1">
							      		</div>
							      	</div>
							      	<div class="form-group">
							      		<label class="control-label col-xs-3" for="total-2">รวม</label>
							      		<div class="col-xs-3">
							      			<p class="form-control-static" id="total-2">0</p>
							      		</div>
							      	</div>
					      		</div>				      		
					      	</div>
			      		</div>

			      		<div class="col-xs-6">
				      		<div class="form-group">
				      			<label class="control-label col-xs-3">รวมทั้งสิ้น (บาท)</label>
				      			<div >
				      				<b><p class="form-control-static text-success" id="total-price">0</p></b>
				      			</div>				      			
							</div>
						</div>
						<div class="row"></div>
						<div class="col-xs-6">
							<button type="button" class="btn btn-success" id="btn-confirm" data-loading-text="กำลังบันทึกข้อมูลการสั่งซื้อ...">ยืนยันการสั่งซื้อ</button>
						</div>
					</div>
					</form>
			    </div>
		  </div>
		  <div class="panel panel-info">
		    <div class="panel-heading" role="tab" id="headingTwo">
		      <h4 class="panel-title">
		        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
		          ลายสกรีน
		        </a>
		      </h4>
		    </div>
		    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		      <div class="panel-body">
		      	<div style="text-align: center; !important">
		      		<div style="display: inline-block;">
				  		<img src="<?php echo $_POST['screen1']; ?>" id="img-screen-1" class="img-thumbnail">
				  		<div style="text-align: left;">
				  			<p id="screen-size-1">size 1</p>
				  		</div>
				  	</div>
				  	<div style="display: inline-block;">
			   			<img src="<?php echo $_POST['screen2']; ?>" id="img-screen-2" class="img-thumbnail">
			   			<div style="text-align: left;">
				  			<p id="screen-size-2">size 2</p>
				  		</div>
			   		</div>
		   		</div>
		   		   		
		      </div>
		    </div>
		  </div>
		  <!-- panel example -->
		  <div class="panel panel-info">
		    <div class="panel-heading" role="tab" id="headingThree">
		      <h4 class="panel-title">
		        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
		          รูปภาพตัวอย่าง
		        </a>
		      </h4>
		    </div>
		    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		      <div class="panel-body">
		        <img id="product-image" src="<?php echo $_POST['product']; ?>" class="img-thumbnail center-block" style="width: 595px; height: 420px;">
		      </div>
		    </div>
		  </div>
		  <!-- panel example -->
		  <!-- panel fitting room -->
		  <div class="panel panel-info">
		    <div class="panel-heading" role="tab" id="headingFour">
		      <h4 class="panel-title">
		        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
		          จำลองกับภาพถ่าย
		        </a>
		      </h4>
		    </div>
		    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
		      <div class="panel-body" id="try-it">
		        <img id="try-it-photo" src="<?php echo $member->photo; ?>" style="z-index: 1; position: relative;" >
		        <canvas id="c" style="z-index: 2;"></canvas>
		        <img src="<?php echo $_POST['shirt_photo_1'] ?>" class="img-thumnail" style="position: absolute; left: 100px; top: 500px; width: 200px;">
		        <img src="<?php echo $_POST['shirt_photo_2'] ?>" class="img-thumnail" style="position: absolute; left: 100px; top: 500px; width: 200px;">
		      </div>
		    </div>
		  </div>
		  <!-- panel fitting room -->
		</div>
    </div>
   
    <script type="text/javascript" src="js/bootstrap-select.js"></script>
    <script type="text/javascript" src="js/jquery.bootstrap-touchspin.js"></script>
    <script type="text/javascript" src="js/utils.js"></script>
    <script type="text/javascript">
    var canvas = new fabric.Canvas('c');  //normal event
	fabric.Object.prototype.selectable = false;	
	canvas.selection = false;

    var scaleX_1 = document.getElementById('scale-x-1');
	var scaleY_1 = document.getElementById('scale-y-1');
	var scaleX_2 = document.getElementById('scale-x-2');
	var scaleY_2 = document.getElementById('scale-y-2');
	var top_1 = document.getElementById('top-1').value;
	var top_2 = document.getElementById('top-2').value;

    var A3 = {
            	cmWidth: 29.7, 
            	cmHeight: 42
       		}
	var A4 = {
            	cmWidth: 21, 
            	cmHeight: 29.7
        	}

    var txtPrice1 = document.getElementById('price-1');
    var txtPrice2 = document.getElementById('price-2');
	function getMaterialType(color, shirt_type, gender, cbo, txtPrice) {
	    $.ajax({
	        type: "POST",
	        dataType: "json",
	        url: "data/ManageShirts.data.php",
	        data: {method: "getMaterialType", color_hex: color, shirt_type: shirt_type, gender: gender}       
	    })
	    .done(function(data) {
	        if (data) {
	            var text = '';
	            data.forEach(function(item) {
	            	if (txtPrice.innerHTML === '') txtPrice.innerHTML = item.shirt_price;
	                text += '<option value="' + item.shirt_id + '|' + item.material_type + '|' + item.shirt_price + '|' + item.chest_size + '|' + item.shirt_length + '">' + item.material_type + ' - ' + item.size_code + ' (' + item.chest_size + 'x' + item.shirt_length + ' ซม.)</option>';	            
	            });

	            $(cbo).html(text).selectpicker('refresh');		                              
	        } else {
	            Toast.init({
	                "selector": ".alert-danger"
	            });
	            Toast.show("<strong>Error on getMaterialType !!!<strong> " + data);
	        }

	    })//done
	    .fail(function(data) { 
	        bootbox.dialog({
	                title: 'Fatal Error',
	                message : '<div class="alert alert-danger" role="alert"><strong>Error in getMaterialType !!!</strong></div>'
	        });//bootbox
	    });//fail
	}

	var cboMaterial_1 = document.getElementById('cbo-material-1');
	cboMaterial_1.onchange =  function() {
		txtPrice1.innerHTML = this.value.split('|')[2];
		calTotal();
	}

	var cboMaterial_2 = document.getElementById('cbo-material-2');
	cboMaterial_2.onchange = function() {
		txtPrice2.innerHTML = this.value.split('|')[2];
		calTotal();
	}


	var txtQty1 = document.getElementById('qty-1');
	var txtQty2 = document.getElementById('qty-2');
	txtQty1.onchange = function () {
		calTotal()
	}
	txtQty2.onchange = function() {
		calTotal();
	}

	var txtTotal1 = document.getElementById('total-1');
	var txtTotal2 = document.getElementById('total-2');
	var txtScreenPrice1 = document.getElementById('screen-price-1');
	var txtScreenPrice2 = document.getElementById('screen-price-2');
	var txtTotalPrice = document.getElementById('total-price');	
	var printSize = document.getElementById('print-format').value;

	function calTotal() {
		txtTotal1.innerHTML = Number((parseFloat(txtPrice1.innerHTML) + parseFloat(txtScreenPrice1.innerHTML)) * parseInt(txtQty1.value)).formatMoney(2);
		txtTotal2.innerHTML = Number((parseFloat(txtPrice2.innerHTML) + parseFloat(txtScreenPrice2.innerHTML)) * parseInt(txtQty2.value)).formatMoney(2);
		var total1 = txtTotal1.innerHTML.replace(',', '');
		var total2 = txtTotal2.innerHTML.replace(',', '');
		txtTotalPrice.innerHTML = Number(parseFloat(total1) + parseFloat(total2)).formatMoney(2);
	}

	$(document).ready(function() {

		$('#qty-1').TouchSpin({
                min: 1,
                max: 100,
                step: 1,
                decimals: 0,
                boostat: 5,
                maxboostedstep: 10,
                postfix: 'ตัว'
        });
        $('#qty-2').TouchSpin({
                min: 1,
                max: 100,
                step: 1,
                decimals: 0,
                boostat: 5,
                maxboostedstep: 10,
                postfix: 'ตัว'
        });

		getMaterialType($('#color_1').val(), $('#shirt_type_1').val(), $('#gender_1').val(), cboMaterial_1, txtPrice1);
		getMaterialType($('#color_2').val(), $('#shirt_type_2').val(), $('#gender_2').val(), cboMaterial_2, txtPrice2);

		var rectArray = displayCalculatedArea();
		setTimeout(function() {
			calculateAreaPrice(rectArray);
			setTimeout(function() { 
				calTotal();
			}, 500);
		}, 500);
		
	}); //document ready function

	var alreadyTryIt = false;	

	///begin of "try it"
	$.fn.highlight = function(rect, color) {
		$("<div />", {
			"css": {
				"border":   "2px solid " + color,
				"position":	"absolute",
				"left":		($(this).offset().left + rect[0]) + "px",
				"top":		($(this).offset().top  + rect[1]) + "px",
				"width": 	rect[2] + "px",
				"height": 	rect[3] + "px"
			}
		}).appendTo("#try-it");
	}
	
	function displayLineScreen(person, who) {
		//ratio of image/canvas
		var picHeight = document.getElementById('try-it-photo').height;
		var picWidth = document.getElementById('try-it-photo').width;
		var rect = person.face;

		var img = new Image();
		
		img.onload = function() {
			var factor = rect[2] / img.width;
			img.style.width = parseInt(img.width * factor * person.scaleX) + 'px';
			img.style.left = parseInt($(who).offset().left + rect[0] + ((person.gapLeft * picWidth) / 850)) + 'px';
			var screenTop = parseInt($(who).offset().top + rect[1] + rect[3] + (rect[3] * 0.5));
			
			//150 means standard frame in shirt mode
			img.style.top = parseInt(person.line_screen_top - 150) + screenTop + 'px';
			img.style.position = 'absolute';
			img.style.zIndex = '3';
		}
		img.src = person.line_screen.src;
		img.id = person.id;

		$(img).appendTo('#try-it');
	}

	var alreadyTryIt = false;
	$('#collapseFour').on('shown.bs.collapse', function () {
		if (alreadyTryIt) return;

  		$("#try-it-photo").objectdetect("all", {classifier: objectdetect.frontalface}, function(faces) {
			if (faces.length != 2) return;

			var faceTop_1 = 0,
				faceTop_2 = 0,
				faceLeft_1 = 0,
				faceLeft_2 = 0;
			
			// $(this).highlight(faces[0], "red");
			// $(this).highlight(faces[1], "red");

			$(this).highlight(faces[0], "transparent");
			$(this).highlight(faces[1], "transparent");	

			var height_1 = parseFloat(document.getElementById('height_1').value);
			var height_2 = parseFloat(document.getElementById('height_2').value);
			var gapLeft_1 = document.getElementById('gap-left-1').value;
			var gapLeft_2 = document.getElementById('gap-left-2').value;

			var personLeft, 
				personRight,
				faceLeft,
				faceRight;
			if (faces[0][0] < faces[1][0]) {
				//faces[0] is on the left side
				faceLeft = faces[0];
				faceRight = faces[1];
			} else {
				faceLeft = faces[1];
				faceRight = faces[0];
			}

			personLeft = {face: faceLeft, line_screen: line_screen_1, line_screen_top: top_1, scaleX: scaleX_1.value, scaleY: scaleY_1.value, id: 'img-01', gapLeft: gapLeft_1};
			personRight = {face: faceRight, line_screen: line_screen_2, line_screen_top: top_2, scaleX: scaleX_2.value, scaleY: scaleY_2.value, id: 'img-02', gapLeft: gapLeft_2};
			displayLineScreen(personLeft, $(this));
			displayLineScreen(personRight, $(this));

			checkPosition(personLeft, personRight);
			alreadyTryIt = true;
			
		}); //objectdetect
	});	

	function checkPosition(personLeft, personRight) {
		if (personLeft.line_screen_top === personRight.line_screen_top) {
			//set all to same top
			//find max top 
			//in other words the lower one
			var maxTop = 0;
			if (personLeft.face[1] < personRight.face[1]) {
				//left.top < right.top
				//lower on the right
				maxTop = document.getElementById(personRight.id).style.top;
			} else {
				maxTop = document.getElementById(personLeft.id).style.top;
			}

			$('#' + personLeft.id).css({'top': maxTop})
			$('#' + personRight.id).css({'top': maxTop});	
		}			
	}
	//end of "try it"

	var rect1, rect2;
	function displayCalculatedArea() {
		var area1 = document.getElementById('screen-size-1');
		var area2 = document.getElementById('screen-size-2');		
		var rtnArray = new Array();
		
		if (printSize.split('|')[0] === 'A4') {
			rect1 = {
					width: Math.round((A4.cmWidth * scaleX_1.value), 0), 
					height: Math.round((A4.cmHeight * scaleY_1.value), 0)
				};
			rect2 = {
					width: Math.round((A4.cmWidth * scaleX_2.value), 0), 
					height: Math.round((A4.cmHeight * scaleY_2.value),0)
				};

		} else if (printSize.split('|')[0] === 'A3') {
			rect1 = {
					width: Math.round((A3.cmWidth * scaleX_1.value), 0), 
					height: Math.round((A3.cmHeight * scaleY_1.value), 0)
				};
			rect2 = {
					width: Math.round((A3.cmWidth * scaleX_2.value), 0), 
					height: Math.round((A3.cmHeight * scaleY_2.value), 0)
				};
		}
		area1.innerHTML = 'กว้าง x ยาว (ซม.): ' + rect1.width + ' x ' + rect1.height;
		area2.innerHTML = 'กว้าง x ยาว (ซม.): ' + rect2.width + ' x ' + rect2.height;
		rtnArray.push(rect1);
		rtnArray.push(rect2);

		return rtnArray;
	}

	var actualPixel_1, actualPixel_2
	function calculateAreaPrice(rectArray) {
		// var area;
		// if (printSize.split('|')[0] === 'A4') {
		// 	area = A4.cmWidth * A4.cmHeight;
		// } else if (printSize.split('|')[0] === 'A3') {
		// 	area = A3.cmWidth * A3.cmHeight;
		// }
		var colorPixel = calculateColorPixel(document.getElementById("img-screen-1"));
		actualPixel_1 = Math.round((rectArray[0].width * rectArray[0].height) * (colorPixel / 100),  0);
		getPrice(txtScreenPrice1, actualPixel_1);

		colorPixel = calculateColorPixel(document.getElementById("img-screen-2"));
		actualPixel_2 = Math.round((rectArray[1].width * rectArray[1].height) * (colorPixel / 100),  0);
		getPrice(txtScreenPrice2, actualPixel_2);
	}

	function calculateColorPixel(img) {		
		var c = document.createElement("canvas");
		c.width = img.width;
		c.height = img.height;
		var ctx = c.getContext("2d");
		ctx.drawImage(img ,0 ,0);
		var imgData = ctx.getImageData(0 , 0, img.width, img.height);
		var res = img.width * img.height;
		console.log('resolution = ' + res);
		
		var alphaCount = 0;
		var colorCount = 0;
		for (var i=0;i<imgData.data.length;i+=4) {
			imgData.data[i];
			imgData.data[i+1];
			imgData.data[i+2];
			if (imgData.data[i+3] === 0) {
				alphaCount += 1;
			} else {
				colorCount += 1;
			}
		}

		var rtn = 100 * colorCount / res;
		console.log('alpha count = ' + alphaCount + ' % = ' + 100 * alphaCount / res);
		console.log('color count = ' + colorCount + ' % = ' + rtn);
		return rtn;
	}

	function getPrice(txt, area) {
		var rtn = 0;
		$.ajax({
	        type: "POST",
	        dataType: "json",
	        url: "data/ManageSizePrice.data.php",
	        data: {method: "getPrice", area: area}
	    })
	    .done(function(data) {
	        if (data.price) {
	            txt.innerHTML = data.price;                       
	        } else {
	            Toast.init({
	                "selector": ".alert-danger"
	            });
	            Toast.show("<strong>Error on getPrice !!!<strong> " + data);
	        }

	    })//done
	    .fail(function(data) { 
	        bootbox.dialog({
	                title: 'Fatal Error',
	                message : '<div class="alert alert-danger" role="alert"><strong>Error in getPrice !!!</strong></div>'
	        });//bootbox
	    });//fail
	}

	var line_screen_1 = document.getElementById('img-screen-1');
	var line_screen_2 = document.getElementById('img-screen-2');
	var email = document.getElementById('member-email');
	var product_image = document.getElementById('product-image');
	var txtAddress = document.getElementById('txt-address');

	var btnConfirm = document.getElementById('btn-confirm');
	btnConfirm.onclick = function() {
		var $btn = $(this).button('loading');
		var total = txtTotalPrice.innerHTML.replace(',', '');
		$.ajax({
	        type: "POST",
	        dataType: "json",
	        url: "data/ViewCart.data.php",
	        data: { method: "insert", email: email.value,
	        		address: txtAddress.value,
	        		line_screen_1: line_screen_1.src,
	        		screen_width_1: rect1.width,
	        		screen_height_1: rect1.height,
	        		color_area_1: actualPixel_1,
	        		line_screen_2: line_screen_2.src, 
	        		screen_width_2: rect2.width,
	        		screen_height_2: rect2.height,
	        		color_area_2: actualPixel_2,
	        		line_screen_price_1: parseFloat(txtScreenPrice1.innerHTML), 
	        		line_screen_price_2: parseFloat(txtScreenPrice2.innerHTML), 
	        		shirt_id_1: cboMaterial_1.value.split('|')[0], 
	        		shirt_id_2: cboMaterial_2.value.split('|')[0], 
	        		qty_1: txtQty1.value, 
	        		qty_2: txtQty2.value,
	        		product_image: product_image.src,
	        		amt: total
	        	}       
	    })
	    .done(function(data) {
	        if (data.order_id) {	       

		        setTimeout(function(){ 
		        	$btn.button('reset');
		        	setTimeout(function() {
		        		window.location = 'OrderResult.php?order_id=' + data.order_id;		   
		        	}, 300);
		        }, 2000);
		        
	        } else {
	            Toast.init({
	                "selector": ".alert-danger"
	            });
	            Toast.show("<strong>Error on Confirm !!!<strong> " + data);
	        }

	    })//done
	    .fail(function(data) { 
	        bootbox.dialog({
	                title: 'Fatal Error',
	                message : '<div class="alert alert-danger" role="alert"><strong>Error in Confirm !!!</strong></div>'
	        });//bootbox
	    });//fail

	    function viewAllItems() {
	    	//now implements code to solve issue #10
	    }
	}
    </script>
  </body>
</html>



