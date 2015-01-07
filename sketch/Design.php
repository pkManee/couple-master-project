<!DOCTYPE html>
<?php
  require("header.php");
?>
<html lang="en">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>Couple T-Shirt Designer</title>

    <!--bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <!--sketch-->
  	<link rel="stylesheet" type="text/css" href="css/sketch.css">  	
    <link rel="stylesheet" type="text/css" href="css/Color.Picker.Classic.css">
    <link rel="stylesheet" type="text/css" href="css/iconfont.css">
    

  	<script src="js/jquery-2.1.1.min.js"></script>
    <!--bootstrap-->
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <!--sketch-->
    <script src="TinyColor/tinycolor.js"></script>
    <script src="HSBRect/HSBRect.js"></script>
  	<script src="js/fabric.js"></script>
  	<script src="js/fabric.canvasex.js"></script>    

	</head>
	<body>
  <ul class="nav nav-tabs" role="tablist" id="myTab">
    <li role="button" class="active hide"><a href="#design-shelf" aria-controls="design-shelf" role="tab" data-toggle="tab">Design</a></li>
    <li role="button" class="hide"><a href="#shirt-shelf" aria-controls="shirt-shelf" role="tab" data-toggle="tab">Shirt</a></li> 
  </ul>  
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="design-shelf">
      <div class="upper-shelf">
        <div id="brush-image-shelf">
          <button id="btn-shirt" title="Shirt view" class="geo-button icon-shirt" type="button"></button>     
          <button id="btn-pencil" title="Pencil" class="geo-button icon-pencil"></button>      
          <button id="btn-brush" title="Circle" class="geo-button icon-brush"></button>
          <button id="btn-spray" title="Glass storm" class="geo-button icon-spray"></button>
        </div>
        <div class="upper-shelf">
          <button id="btn-selector" title="Selector" class="geo-button icon-selector"></button>
          <button id="btn-text" title="Text tool" class="geo-button icon-text"></button>
          <button id="btn-rectangle" title="Rectangle [hit del button to delete]" class="geo-button icon-rect"></button>
          <button id="btn-triangle" title="Triangle [hit del button to delete]" class="geo-button icon-tri"></button>
          <button id="btn-round" title="Round [hit del button to delete]" class="geo-button icon-round"></button>
          <button id="btn-star" title="Star [hit del button to delete]" class="geo-button icon-star"></button>
          <button id="btn-heart" title="Heart [hit del button to delete]" class="geo-button icon-heart"></button>
          <span class="fakeInputContainer" style="position: relative; overflow: hidden; z-index: 0; width: 40px; height: 40px;">
            <button id="btn-open" class="geo-button icon-folder" title="Open file"></button>
            <input type="file" id="upload-button" capture="camera" name="files[]" style="position: absolute; top: 0px; z-index: 1000; font-size: 1000px; text-align: right; width: inherit; height: inherit; cursor: pointer; right: 0px; opacity: 0;">
          </span>
          <button id="btn-erase" title="Erase All" class="geo-button icon-erase"></button>
        </div>
        <div class="upper-shelf">
          <button id="btn-clone" title="Create copy" class="geo-button icon-clone"></button>
          <button id="btn-flip" title="Flip Horizontally" class="geo-button icon-flip"></button>
          <button id="btn-crop" title="Crop" class="geo-button icon-crop"></button>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="shirt-shelf">
      <div class="upper-shelf">
        <button id="btn-design" title="Shirt view" class="geo-button icon-shirt" type="button"></button>        
      </div>
      <div class="upper-shelf">
        <div class="form-group form-inline">
        <?php
          require("service/message_service.php");
          require("service/db_connect.php");

          try {
              $conn = dbConnect::getInstance();
              $dbh = $conn->dbh;
          } catch (PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
          }

          $sql = "select s.shirt_id, s.shirt_name, s.color, s.shirt_type, s.material_type, s.size_code, s.shirt_price ";
          $sql .= ",z.chest_size, z.shirt_length ";
          $sql .= "from shirts s inner join shirt_size z on s.size_code = z.size_code ";
          $sql .= "where 1 = 1 ";
          $sql .= "order by s.shirt_name asc ";
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="form-group">
          <label class="control-label" for="cbo-shirt-men">เสื้อชาย</label>
          <select class="form-control" id="cbo-shirt-men" data-toggle="tooltip" data-placement="bottom">
            <?php            
            foreach($result as $row) {
              $shirt_id = $row["shirt_id"];
              $shirt_name = $row["shirt_name"];
              $color = $row["color"];
              $shirt_type = $row["shirt_type"];
              $material_type = $row["material_type"];
              $size_code = $row["size_code"];
              $shirt_price = $row["shirt_price"];    
              $chest_size = $row["chest_size"];
              $shirt_length = $row["shirt_length"];

              echo "<option value=\"".$shirt_id."\" >".$shirt_name."</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label class="contorl-label" for="cbo-shirt-women">เสื้อหญิง</label>
          <select class="form-control" id="cbo-shirt-women" data-toggle="tooltip" data-placement="bottom">
            <?php            
            foreach($result as $row) {
              $shirt_id = $row["shirt_id"];
              $shirt_name = $row["shirt_name"];
              $color = $row["color"];
              $shirt_type = $row["shirt_type"];
              $material_type = $row["material_type"];
              $size_code = $row["size_code"];
              $shirt_price = $row["shirt_price"];       

              echo "<option value=\"".$shirt_id."\">".$shirt_name."</option>";
            }
            ?>
          </select>
        </div>
        </div>
      </div>
    </div>
  </div>
    <br/>
  <div id="canvas-area">
    <div id="the-devide"></div>
  </div>
	<div id="right-side">
      <div id="brush-shelf">        
        <span>Line width: </span>        
        <input id="brush-size-slider" type="range" min="1" max="100" value="30">
        <hr>
        <br>
        <div>
          
        </div>
      </div>      
  </div>
    <script src="./js/Event.js" type="text/javascript"></script> 
    <script src="./js/Color.Picker.Classic.js" type="text/javascript"></script>
    <script src="./js/Color.Space.js" type="text/javascript"></script>
    <script src="./js/fabricjs-painter.js" type="text/javascript"></script>
    <script src="./js/resolutionCal.js" type="text/javascript"></script>    
    <script src="./js/app.js" type="text/javascript"></script>
    
	</body>
</html>