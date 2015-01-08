<!DOCTYPE html>
<?php
 require("header.php");
 if (!isset($_GET["shirtid"])){
 	$_GET["shirtid"] = "";
 }
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Material Type</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">       
    <link href="css/bootstrap-select.css" rel="stylesheet">
    <link href="css/bootstrapValidator.css" rel="stylesheet">    

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrapValidator.js"></script>
    <script src="js/bootstrap-select.js"></script>
  </head>
  <body>  
    <div class="alert alert-success" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>
    <div class="alert alert-danger" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
      <span></span>
    </div>
    <input type="hidden" id="hidden-shirtid" name="isDelete" value="<?php echo $_GET["shirtid"]; ?>">

    <?php
      require("navbar.php");
      require("service/message_service.php");
      require("service/db_connect.php");

      //get data
      class Shirts
      {
        public $shirt_id = 0;
        public $shirt_name = "";
        public $color = "";
        public $shirt_type = "";
        public $material_type = "";
        public $size_code = "";
        public $shirt_price = 0;
        public $gender = "M";
      }

      try {
          $dbh = dbConnect::getInstance()->dbh;
      } catch (PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();
      }

      //select shirts
      if (isset($_GET["shirtid"]) && !empty($_GET["shirtid"])){
          $sql = "select shirt_id, shirt_name, color, shirt_type, material_type, size_code, shirt_price, gender ";
          $sql .= "from shirts ";
          $sql .= "where shirt_id = :shirt_id ";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(":shirt_id", $_GET["shirtid"]);
          if ($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Shirts");
            $shirts = $stmt->fetch();           
          }else{
            echo "error -> " .$stmt->errorInfo()[2];
          }         

      }else{
      	$shirts = new Shirts();
      }

      //get color
      $sql = "select color, color_hex from shirt_color ";
      $sql .= "order by color asc ";
      $stmt = $dbh->prepare($sql);
      if ($stmt->execute()){
        $result_color = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }else{
        echo "error -> " .$stmt->errorInfo()[2];
      }

      //get shirt type
      $sql = "select shirt_type, shirt_type_description from shirt_type ";          
      $sql .= "order by shirt_type asc ";
      $stmt = $dbh->prepare($sql);
      if ($stmt->execute()){
        $result_shirt_type = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }else{
        echo "error -> " .$stmt->errorInfo()[2];
      }

      //get material type
      $sql = "select material_type, description from material ";
      $sql .= "order by material_type asc ";
      $stmt = $dbh->prepare($sql);
      if ($stmt->execute()){
        $result_material_type = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }else{
        echo "error -> " .$stmt->errorInfo()[2];
      }
      //get size
      $sql = "select size_code, chest_size, shirt_length from shirt_size ";
      $sql .= "where gender = :gender ";
      $sql .= "order by size_code asc ";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(":gender", $shirts->gender);
      if ($stmt->execute()){
        $result_shirt_size = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }else{
        echo "error -> " .$stmt->errorInfo()[2];
      }

    ?>
    <form id="manage-shirts-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="container">
    <div class="col-xs-6 col-md-4">   
    
      <div class="form-group">        
        <label class="control-label" for="txt-shirt-name">คำอธิบายเสื้อ</label>
        <input type="text" class="form-control" id="txt-shirt-name" name="txtShirtName" 
        	     value="<?php echo $shirts->shirt_name; ?>" >        
      </div>

      <div class="form-group">        
        <label class="control-label" for="cbo-shirt-color">สีเสื้อ</label>
        <select class="form-control selectpicker" id="cbo-shirt-color" name="cboShirtColor">
          <?php
            foreach($result_color as $row) {
              if ($shirts->color == $row["color"]){
                $html ="<option data-content=\"<table style='width:100%'><tr><td style='width: 50%;'>" .$row["color"]. "</td><td style='width: '50%'; text-aligh: right' bgcolor='" .$row["color_hex"]. "'></td></tr></table>\" ";
                $html .="value=\"" .$row["color"]. "\" selected>" .$row["color"]. "</option>";
              }else{
                $html ="<option data-content=\"<table style='width:100%'><tr><td style='width: 50%;'>" .$row["color"]. "</td><td style='width: '50%'; text-aligh: right' bgcolor='" .$row["color_hex"]. "'></td></tr></table>\" ";
                $html .="value=\"" .$row["color"]. "\" >" .$row["color"]. "</option>";
              }
              echo $html;              
            }
          ?>
        </select>      
      </div>
      <div class="form-group">
        <label class="control-label" for="cbo-shirt-type">แบบเสื้อ</label>
        <select class="form-control selectpicker" id="cbo-shirt-type" name="cboShirtType">
          <?php
            foreach($result_shirt_type as $row) {
              if ($shirts->shirt_type == $row["color"]){
                $html ="<option value=\"" .$row["shirt_type"]. "\" selected>" .$row["shirt_type"]. "</option>";
              }else{
                $html = "<option value=\"" .$row["shirt_type"]. "\">" .$row["shirt_type"]. "</option>";
              }
              echo $html;
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label class="control-label" for="cbo-material-type">ประเภทผ้า</label>
        <select class="form-control selectpicker" id="cbo-material-type" name="cboMaterialType">
          <?php
            foreach($result_material_type as $row) {
              if ($shirts->material_type == $row["material_type"]){
                $html ="<option value=\"" .$row["material_type"]. "\" selected>" .$row["material_type"]. "</option>";
              }else{
                $html = "<option value=\"" .$row["material_type"]. "\">" .$row["material_type"]. "</option>";
              }
              echo $html;
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <?php        
        $active = $shirts->gender=="M"?"active":"";
        $checked = $shirts->gender=="M"?"checked":"";
       
        echo "<div class=\"btn-group\" data-toggle=\"buttons\">";
        echo    "<label class=\"btn btn-primary " .$active. " \">";
        echo      "<input type=\"radio\" id=\"radio-male\" name=\"rdoGender\" value=\"M\" " .$checked. ">สำหรับชาย";
        echo    "</label>";
        $active = $shirts->gender=="F"?"active":"";
        $checked = $shirts->gender=="F"?"checked":"";
        echo    "<label class=\"btn btn-primary ".$active." \">";
        echo      "<input type=\"radio\" id=\"radio-female\" name=\"rdoGender\" value=\"F\" " .$checked. " >สำหรับหญิง";
        echo    "</label>";
        echo "</div>";
        ?>
      </div>
      <div class="form-group">
        <label class="control-label" for="cbo-shirt-size">ขนาดเสื้อ</label>
        <select class="form-control selectpicker" id="cbo-shirt-size" name="cboShirtSize">
          <?php
            foreach($result_shirt_size as $row) {
              if ($shirts->size_code == $row["size_code"]){
                $html ="<option data-content=\"<table style='width:100%'><tr><td style='width: 20%;'>" .$row["size_code"]. "</td><td style='width: '80%'; >รอบอก " .$row["chest_size"]." x ความยาว " .$row["shirt_length"]. "</td></tr></table>\" ";
                $html .="value=\"" .$row["size_code"]. "\" selected>" .$row["size_code"]. "</option>";
              }else{
                $html ="<option data-content=\"<table style='width:100%'><tr><td style='width: 20%;'>" .$row["size_code"]. "</td><td style='width: '80%'; >รอบอก " .$row["chest_size"]." x ความยาว " .$row["shirt_length"]. "</td></tr></table>\" ";
                $html .="value=\"" .$row["size_code"]. "\" >" .$row["size_code"]. "</option>";
              }
              echo $html;              
            }
          ?>
        </select>
      </div>

      <div class="form-group">        
        <label class="control-label" for="txt-shirt-price">ราคาเสื้อ</label>
        <input type="text" class="form-control" id="txt-shirt-price" name="txtShirtPrice" 
               value="<?php echo $shirts->shirt_price; ?>" >        
      </div>

      <button type="submit" class="btn btn-primary">Save</button>
      <a role="button" class="btn btn-default" href="ListShirts.php">Cancel</a>
      <button id="btn-delete" class="btn btn-warning">Delete</button>
    </div>
    </div>
    </form>    
    <script type="text/javascript">
    
     $(document).ready(function() {
      $('.selectpicker').selectpicker();

      $('#manage-shirts-form')
          .bootstrapValidator({
              //... options ...
              feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  txtShirtName: {
                      validators: {
                          notEmpty: {
                              message: 'กรุณาระบุคำอธิบาย'
                          }
                      }
                  },
                  txtShirtPrice: {
                      validators: {
                          notEmpty:{
                            message: 'กรุณาระบุราคาเสื้อ'
                          },
                          numeric: {
                            message: 'กรุณาระบุเป็นตัวเลข'
                          },
                          greaterThan: {
                            value: 0,
                            inclusive: false,
                            message: 'กรุณาระบุตัวเลขที่มากกว่าศูนย์'
                          }
                      }
                  }
              }
            })//bootstrapValidator
          .on('success.form.bv', function(e) {
              // Prevent form submission
              e.preventDefault();

              // Get the form instance
              var $form = $(e.target);
              // Get the BootstrapValidator instance
              var bv = $form.data('bootstrapValidator');

              // Use Ajax to submit form data
              goSave($form);
          });//on success.form.bv
      });//document.ready

  function goSave($form){   
    $.ajax({
        type: 'POST',
        url: 'data/ManageShirts.data.php', 
        data: $form.serialize() + "&method=insert"
    })
    .done(function(data){
      if (data.result === "success"){
        Toast.init({
            "selector": ".alert-success"
        });
        Toast.show("<strong>Save completed!!!</strong><br/>redirecting ...");
        setTimeout(function(){ window.location = "ListShirts.php" }, 1000);
      }else{
        Toast.init({
          "selector": ".alert-danger"
        });
        Toast.show("<strong>Error on saving!!!<strong> " + data);
      }
    })
    .fail(function() {
      bootbox.dialog({
                  title: 'Fatal Error',
                  message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
      });
    });
  }

  var btnDelete = document.getElementById('btn-delete');
  var isDelete = document.getElementById('hidden-shirtid');
  btnDelete.onclick = function(){     	
  	event.preventDefault();
    if (!isDelete.value) return false;

    	bootbox.confirm({
      	title: '', 
      	message: '<div class="alert alert-info" role="alert">Are you sure to <strong>delete?</strong></div>',
      	callback: function(result) {
      		if (result) goDelete();     
      }
    }); 
  }

   function goDelete(){
   	$.ajax({
          type: 'POST',
          url: 'data/ManageShirts.data.php', 
          data: {method: 'delete', shirtId: isDelete.value}
      })
      .done(function(data){
        if (data.result === "success"){
          Toast.init({
              "selector": ".alert-success"
          });
          Toast.show("<strong>Delete completed!!!</strong><br/>redirecting ...");
          setTimeout(function(){ window.location = "ListShirts.php" }, 1000);
        }else{
          Toast.init({
            "selector": ".alert-danger"
          });
          Toast.show("<strong>Error on deleting!!!<strong> " + data);
        }
      })//done
      .fail(function() {
        bootbox.dialog({
                    title: 'Fatal Error',
                    message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });//fail
      });//ajax
   }

    var rdoMale = document.getElementById('radio-male');
    var rdoFemale = document.getElementById('radio-female');
    rdoMale.onchange = function() {
      getShirtSize('M');
    }
    rdoFemale.onchange = function() {
      getShirtSize('F');
    }
    function getShirtSize(gender) {
      $.ajax({
        type: 'POST',
        url: 'data/ManageShirtSize.data.php', 
        data: {method: 'get_shirt_size', gender: gender}
      })
      .done(function(data){
        if (data){ 
          var text = '';
          data.forEach(function(size){
            text +="<option data-content=\"<table style='width:100%'><tr><td style='width: 20%;'>" +size.size_code+ "</td><td style='width: '80%'; >รอบอก "+size.chest_size+" x ความยาว " +size.shirt_length+ "</td></tr></table>\" ";
            text +="value=\"" +size.size_code+ "\" selected>" +size.size_code+ "</option>";            
          });

          $('#cbo-shirt-size').html(text).selectpicker('refresh');

        } else {
          Toast.init({
            "selector": ".alert-danger"
          });
          Toast.show("<strong>Error on get shirt size!!!<strong> " + data);
        }
      })//done
      .fail(function(data) {
        bootbox.dialog({
                    title: 'Fatal Error',
                    message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });//fail
      });//ajax
    }
    </script>
  </body>
</html>