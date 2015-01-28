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
    
    <div class="container">
   	<?php
   	$gender_1 = $_POST["gender_1"];
    $shirt_type_1 = $_POST["shirt_type_1"];
    
	include("data/Member.class.php");
	//select user profile
	if (isset($_SESSION["email"]) && !empty($_SESSION["email"])){           
	  try {
	      $dbh = dbConnect::getInstance()->dbh;
	  } catch (PDOException $e) {
	      print "Error!: " . $e->getMessage() . "<br/>";
	      die();
	  }

	  $sql = "select email, member_name, address, province_id, amphur_id, district_id, password, postcode ";
	  $sql .= ",photo, height_1, height_2 ";
	  $sql .= "from member where email = :email ";
	  $stmt = $dbh->prepare($sql);
	  $stmt->bindValue(":email", $_SESSION["email"]);
	  if ($stmt->execute()){
	    $stmt->setFetchMode(PDO::FETCH_CLASS, "Member");
	    $member = $stmt->fetch();

	    echo "<input type=\"hidden\" id=\"hidden-amphur\" value=\"" .$member->amphur_id. "\" >";
	    echo "<input type=\"hidden\" id=\"hidden-district\" value=\"" .$member->district_id. "\" >";
	  }else{
	    echo "error -> " .$stmt->errorInfo()[2];
	  }
	}
 
   	?>
	   	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingOne">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
		          รายการสั่งซื้อ
		        </a>
		      </h4>
		    </div>
		    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		    	<form class="form-horizontal">
		    	<div class="panel-body">
					<div class="form-group">
			        	<label class="col-sm-2 control-label">ชื่อ-นามสกุล</label>
			        	<div class="col-sm-10">
			        		<p class="form-control-static"><?php echo $member->member_name; ?></p>
			        	</div>		        	
				    </div>
				    <div class="form-group">
				    	<label class="col-sm-2 control-label">อีเมล์</label>
			        	<div class="col-sm-10">
			        		<p class="form-control-static"><?php echo $member->email; ?></p>
			        	</div>
				    </div>
				    <div class="form-group">
				    	<label class="col-sm-2 control-label">ที่อยู่</label>
				    	<div class="col-sm-6">
				    		<textarea class="form-control" name="txtAddress" 
				    			style="min-height: 100px;"><?php echo $member->address; ?></textarea>
				    	</div>
				    </div>
		      			

					<button type="submit" class="btn btn-success">ยืนยันการสั่งซื้อ</button>
				</div>
				</form>
		    </div>		   
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingTwo">
		      <h4 class="panel-title">
		        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
		          ลายสกรีน
		        </a>
		      </h4>
		    </div>
		    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		      <div class="panel-body">
		        <img src="<?php echo $_POST['screen1']; ?>" class="img-rounded">
	   			<img src="<?php echo $_POST['screen2']; ?>" class="img-rounded">
		      </div>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingThree">
		      <h4 class="panel-title">
		        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
		          รูปภาพ
		        </a>
		      </h4>
		    </div>
		    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		      <div class="panel-body">
		        <img src="<?php echo $_POST['product']; ?>" class="img-rounded">
		      </div>
		    </div>
		  </div>
		</div>

    </div>
    
  </body>
</html>



