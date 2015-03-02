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
    <link href="css/bootstrap-select.css" rel="stylesheet">
    <link href="css/image-picker.css" rel="stylesheet" type="text/css" >
    <link href="css/bootstrap-dialog.css" rel="stylesheet" type="text/css" >

    <!--sketch-->
  	<link rel="stylesheet" type="text/css" href="css/sketch.css">  	
    <link rel="stylesheet" type="text/css" href="css/Color.Picker.Classic.css">
    <link rel="stylesheet" type="text/css" href="css/iconfont.css">

  	<script src="js/jquery-2.1.1.min.js"></script>
    <!--bootstrap-->
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/bootstrapValidator.js"></script>
    <script src="js/image-picker.js"></script>
    <script src="js/bootstrap-dialog.js"></script>
    <!--sketch-->
    <script src="TinyColor/tinycolor.js"></script>
    <script src="HSBRect/HSBRect.js"></script>
  	<script src="js/fabric.js"></script>
  	<script src="js/fabric.canvasex.js"></script>
	</head>
	<body>
  <div class="alert alert-info" role="alert" style="display:none; z-index: 1000; position: absolute; left: 0px; top: 50px;">
    <span></span>
  </div>
  <ul class="nav nav-tabs" role="tablist" id="myTab">
    <li role="button" class="active hide"><a href="#design-shelf" aria-controls="design-shelf" role="tab" data-toggle="tab">Design</a></li>
    <li role="button" class="hide"><a href="#shirt-shelf" aria-controls="shirt-shelf" role="tab" data-toggle="tab">Shirt</a></li> 
  </ul>  
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="design-shelf">
      <div class="upper-shelf">
        <div id="brush-image-shelf">
          <button id="btn-shirt" title="Shirt view" class="geo-button icon-shirt" type="button" data-toggle="tooltip" data-placement="bottom"></button>
          <button id="btn-color-picker" title="Color PIcker" class="geo-button icon-color-picker" data-toggle="tooltip" data-placement="bottom"></button> 
          <button id="btn-pencil" title="Pencil" class="geo-button icon-pencil" data-toggle="tooltip" data-placement="bottom"></button>      
          <button id="btn-brush" title="Circle" class="geo-button icon-brush" data-toggle="tooltip" data-placement="bottom"></button>
          
          <div class="btn-group" data-toggle="tooltip" data-placement="right" title="Random Spray" >
            <button type="button" class="geo-button icon-spray dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></button>
            <ul class="dropdown-menu" role="menu" style="min-width: 0px !important;">
              <li><a id="btn-spray" href="#">Glass Storm</a></li>
              <li><a id="btn-diamond-cross" href="#">Diamond Cross</a></li>              
              <li><a id="btn-dna-brush" href="#">DNA Brush</a></li>              
              <li><a id="btn-star-line" href="#">Star Line</a></li>              
            </ul>
          </div>
        </div>
        <div class="btn-group upper-shelf">
          <button id="btn-selector" title="Selector" class="geo-button icon-selector" data-toggle="tooltip" data-placement="bottom"></button>
          <button id="btn-text" title="Text tool" class="geo-button icon-text" data-toggle="tooltip" data-placement="bottom"></button>

          <div class="btn-group">
            <button type="button" class="geo-button icon-rect dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></button>
            <ul class="dropdown-menu" role="menu" style="min-width: 0px !important;">
              <li><a id="btn-rectangle" href="#" title="Rectangle" class="geo-button icon-rect" data-toggle="tooltip" data-placement="right" style="width: 70px;"></a></li>
              <li><a id="btn-triangle" href="#" title="Triangle" class="geo-button icon-tri" data-toggle="tooltip" data-placement="right" style="width: 70px;"></a></li>
              <li><a id="btn-round" href="#" title="Round" class="geo-button icon-round" data-toggle="tooltip" data-placement="right" style="width: 70px;"></a></li>
              <li><a id="btn-star" href="#" title="Star" class="geo-button icon-star" data-toggle="tooltip" data-placement="right" style="width: 70px;"></a></li>
              <li><a id="btn-heart" href="#" title="Heart" class="geo-button icon-heart" data-toggle="tooltip" data-placement="right" style="width: 70px;"></a></li>
            </ul>
          </div>
          <div class="hidden">
            <button title="Rectangle [hit del button to delete]" class="geo-button icon-rect" data-toggle="tooltip" data-placement="bottom"></button>
            <button title="Triangle [hit del button to delete]" class="geo-button icon-tri" data-toggle="tooltip" data-placement="bottom"></button>
            <button title="Round [hit del button to delete]" class="geo-button icon-round" data-toggle="tooltip" data-placement="bottom"></button>
            <button title="Star [hit del button to delete]" class="geo-button icon-star" data-toggle="tooltip" data-placement="bottom"></button>
            <button title="Heart [hit del button to delete]" class="geo-button icon-heart" data-toggle="tooltip" data-placement="bottom"></button>
          </div>
          <button id="btn-erase" title="Erase All" class="geo-button icon-erase" data-toggle="tooltip" data-placement="bottom"></button>
          <div class="btn-group" data-toggle="tooltip" data-placement="right" title="Cliparts">
            <button type="button" class="geo-button icon-drawer dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#" id="btn-love">Love</a></li>
              <li><a href="#" id="btn-valentine">Valentine</a></li>
            </ul>
          </div>
          <span class="fakeInputContainer" style="position: relative; overflow: hidden; z-index: 0; width: 40px; height: 40px;" data-toggle="tooltip" data-placement="right" title="Upload File">
            <button id="btn-open" class="geo-button icon-upload"></button>
            <input type="file" id="upload-button" capture="camera" name="files[]"
                    style="position: absolute; top: 0px; z-index: 1000; font-size: 1000px; text-align: right; width: inherit; height: inherit; cursor: pointer; right: 0px; opacity: 0;">
          </span>          
        </div>
        <div class="upper-shelf">          
          <button id="btn-filter" title="Layer filters" class="geo-button icon-filter" data-toggle="tooltip" data-placement="bottom"></button>
          <button id="btn-clone" title="Create copy" class="geo-button icon-clone" data-toggle="tooltip" data-placement="bottom"></button>
          <button id="btn-flip" title="Flip Horizontally" class="geo-button icon-flip" data-toggle="tooltip" data-placement="bottom"></button>
          <button id="btn-crop" title="Crop" class="geo-button icon-crop" data-toggle="tooltip" data-placement="bottom"></button>
          <button id="btn-snn" title="Convert to water painting" class="geo-button icon-paint" data-toggle="tooltip" data-placement="bottom"></button>
          <button id="btn-bring-to-front" title="Bring to front" class="geo-button icon-up" data-toggle="tooltip" data-placement="bottom"></button>
          <button id="btn-send-to-back" title="Send to back" class="geo-button icon-down" data-toggle="tooltip" data-placement="bottom"></button>
        </div>
        <div class="upper-shelf">
          <button id="btn-download" title="Download" class="geo-button icon-download" data-toggle="tooltip" data-placement="bottom"></button>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="shirt-shelf">
      <div class="upper-shelf">
        <button id="btn-design" title="Shirt view" class="geo-button icon-shirt" type="button" data-toggle="tooltip" data-placement="bottom"></button>        
        <div class="container">
            <div class="upper-shelf" >
              <select class="form-control selectpicker" id="cbo-gender-1">
                <option value="M">ชาย</option>
                <option value="F">หญิง</option>
              </select>
            </div>
            <div class="upper-shelf">
              <select class="form-control" id="cbo-shirt-type-1" >
              </select>
            </div>
            <div class="upper-shelf" >
              <select class="form-control invisible" id="cbo-shirt-size-1" ></select>
            </div> 

            <div class="upper-shelf" style="padding-left: 150px; position: absolute;" >
              <div class="upper-shelf" >
                <select class="form-control selectpicker" id="cbo-gender-2" data-toggle="tooltip" >
                  <option value="M">ชาย</option>
                  <option value="F" selected>หญิง</option>
                </select>
              </div>
              <div class="upper-shelf">
                <select class="form-control" id="cbo-shirt-type-2">
                </select>
              </div>
              <div class="upper-shelf" >
                <select class="form-control invisible" id="cbo-shirt-size-2" ></select>
              </div>
            </div>
        </div>
      </div>  
    </div>
  </div>
  <br/>
  <?php
    include("service/message_service.php");
    include("service/db_connect.php");

    try {
        $dbh = dbConnect::getInstance()->dbh;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
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

    echo '<input type="hidden" id="print-format" value="' .$print_format. '">';

    class Member
    {
      public $email;
      public $member_name = "";
      public $address;
      public $province_id;
      public $amphur_id;
      public $district_id;
      public $password;
      public $postcode;
      public $photo;
      public $height_1 = 0;
      public $height_2 = 0;
    }

    $sql = "select email, member_name, address, province_id, amphur_id, district_id, password, postcode ";
    $sql .= ",photo, height_1, height_2 ";
    $sql .= "from member where email = :email ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":email", $_SESSION["email"]);
    if ($stmt->execute()){
      $stmt->setFetchMode(PDO::FETCH_CLASS, "Member");
      $member = $stmt->fetch();      
    }else{
      echo "error -> " .$stmt->errorInfo()[2];
    }

    echo '<input type="hidden" id="hidden-email" value="' .$member->email. '">';
    
  ?>
  <div id="canvas-area">
    <div id="the-devide"></div>
  </div>
	<div id="right-side" class="well" style="width:240px;">
    <div id="draw-tools" style="width:100%;">
      <div id="line-width" style="width:100%;">        
        <label class="control-label" for="brush-size-slider">ขนาดเส้น</label><span style="padding-left:10px;"></span>       
        <input id="brush-size-slider" class="form-control" type="range" min="1" max="100" value="30" 
        data-toggle="tooltip" data-placement="bottom" >              
      </div>
      <!-- panel-filter -->
      <div id="panel-filter" style="width:100%; display:none;"> 
        <div class="form-group">     
          <div class="btn-group" style="width:100%;">            
              <input type="checkbox" id="blur" disabled>
              <label class="control-label" for="blur">Blur</label>
          </div>
        </div>
        <div class="form-group">
          <div class="btn-group" style="width:100%;">            
              <input type="checkbox" id="sharpen" disabled>
              <label class="control-label" for="sharpen">Sharpen</label>
          </div>
        </div>
        <div class="form-group">
          <div class="btn-group" style="width:100%;">            
              <input type="checkbox" id="emboss" disabled>
              <label class="control-label" for="emboss" >Emboss</label>
          </div>
        </div>
        <div class="form-group">
          <div class="btn-group" style="width:100%;">            
              <input type="checkbox" id="grayscale" disabled>
              <label class="control-label" for="grayscale" >Grayscale</label>
          </div>
        </div>
      </div> <!-- panel-filter -->

      <!-- panel font family -->
      <div id="panel-font-family" style="width: 100%; " class="hidden">
        <div class="form-group">
          <label class="control-label" for="font-picker">ฟอนต์</label>
          <select id="font-picker" class="form-control" data-live-search="true" data-size="10">            
          </select>
        </div>
      </div> <!-- panel font family -->
    </div> <!-- draw tool -->
    <div id="panel-color" style="width: 200px;">
      <form id="member-profile-form" method="POST">
        <div class="form-group">         
          <div id="recommend-color-1" class="recommend-color"></div>
          <select class="form-control selectpicker" id="cbo-color-style-1">
            <option value="">เลือกรูปการการแนะนำสี</option>
            <option value="analogous">Analogous: สีใกล้เคียง</option>
            <option value="triad">Triad: สีไตรสัมพันธ์ (สีสามเส้า)</option>
            <option value="complementary">Complementary: สีคู่ตรงข้าม</option>            
          </select>          
        </div>       
        <div class="form-group">
          <label class="control-label" for="cbo-shirt-color-1">เสื้อด้านซ้าย</label>          
          <select class="form-control selectpicker" id="cbo-shirt-color-1"></select>
        </div>
        <div class="form-group">
          <label class="control-label" for="txt-height-1">ส่วนสูงของผู้ใส่ (ซม.)</label>          
          <input type="text" class="form-control" id="txt-height-1" name="txtHeight1"
            placeholder="ส่วนสูงของผู้ใส่ (ซม.)" value="<?php echo $member->height_1; ?>">
        </div>
        <hr>
        <div class="form-group">         
          <div id="recommend-color-2" class="recommend-color"></div>
          <select class="form-control selectpicker" id="cbo-color-style-2">
            <option value="">เลือกรูปการการแนะนำสี</option>
            <option value="analogous">Analogous: สีใกล้เคียง</option>
            <option value="triad">Triad: สีไตรสัมพันธ์ (สีสามเส้า)</option>
            <option value="complementary">Complementary: สีคู่ตรงข้าม</option>            
          </select>          
        </div>    
        <div class="form-group">
          <label class="control-label" for="cbo-shirt-color-2">เสื้อด้านขวา</label>          
          <select class="form-control selectpicker" id="cbo-shirt-color-2" ></select>
        </div>
        <div class="form-group">
          <label class="control-label" for="txt-height-2">ส่วนสูงของผู้ใส่ (ซม.)</label>     
          <input type="text" class="form-control" id="txt-height-2" name="txtHeight2" 
            placeholder="ส่วนสูงของผู้ใส่ (ซม.)" value="<?php echo $member->height_2; ?>">
        </div>

        <div class="form-group">
          <button type="button" class="geo-button icon-calculate" id="btn-calculation" 
                  title="คำนวณตำแหน่ง" data-toggle="tooltip" data-placement="bottom"></button>
          <button type="submit" class="geo-button icon-cart" id="btn-cart"
                  title="สั่งซื้อ" data-toggle="tooltip" data-placement="bottom"></button>
        </div>
      </form>
    </div>
  </div>
 <div class="hidden" id="div-image-picker">        
    <div class="center-block" style="overflow-y: auto; max-height: 500px;">
        
        <select id="popup-image">      
        </select>                   
    </div>
    <br>
    <div class="row"></div>
    <div>
        <button type="button" class="btn btn-success btn-sm" id="btn-select-image">เลือก</button>
        <button type="button" class="btn btn-success btn-sm" id="btn-close-image-picker">ปิด</button>          
    </div>
  </div>
  <script src="js/Event.js" type="text/javascript"></script> 
  <script src="js/Color.Picker.Classic.js" type="text/javascript"></script>
  <script src="js/Color.Space.js" type="text/javascript"></script>
  <script src="js/fabricjs-painter.js" type="text/javascript"></script>
  <script src="js/resolutionCal.js" type="text/javascript"></script>
  <script src="js/color-thief.js" type="text/javascript"></script>
  <script src="js/app.js" type="text/javascript"></script>
  <script src="js/Design.js" type="text/javascript"></script>
  <script src="js/detect-fonts.js" type="text/javascript"></script>

  <script type="text/javascript">
    //document ready
  $(document).ready(function() {      
    $('[data-toggle="tooltip"]').tooltip();

    $('#member-profile-form')
      .bootstrapValidator( {
        feedbackIcons: {
              valid: 'glyphicon glyphicon-ok',
              invalid: 'glyphicon glyphicon-remove',
              validating: 'glyphicon glyphicon-refresh'
        },         
        fields: {
            txtHeight1: {
                validators: {
                    numeric: {
                    message: 'กรุณาระบุเป็นตัวเลข'
                  },
                  greaterThan: {
                    value: 75,
                    inclusive: false,
                    message: 'กรุณาระบุตัวเลขที่มากกว่า 75 ซม.'
                  },
                  lessThan: {
                    value: 220,
                    inclusive: true,
                    message: 'ส่วนสูงต้องไม่เกิน 220 ซม.'
                  }
              }
            },
            txtHeight2: {
                validators: {
                    numeric: {
                    message: 'กรุณาระบุเป็นตัวเลข'
                  },
                  greaterThan: {
                    value: 75,
                    inclusive: false,
                    message: 'กรุณาระบุตัวเลขที่มากกว่า 75 ซม.'
                  },
                  lessThan: {
                    value: 220,
                    inclusive: true,
                    message: 'ส่วนสูงต้องไม่เกิน 220 ซม.'
                  }
              }
            }
          }//fields
      })//bootstrapValidator
      .on('success.form.bv', function(e){
         // Prevent form submission
          e.preventDefault();

          // Get the form instance
          var $form = $(e.target);
          
          //go process here
          //code in Design.js
          goSave();
      });//on success.form.bv

  });//document.ready 
  </script>
	</body>
</html>