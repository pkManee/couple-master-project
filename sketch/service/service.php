<?php
require_once("Rest.inc.php");
require_once("db_connect.php");
require_once("message_service.php");

class MyService extends  REST {
	public function __construct()
	{
		parent::__construct();// Init parent contructor		
	}

	//Public method for access api.
	//This method dynmically call the method based on the query string
	public function processApi()
	{
		$func = strtolower(trim(str_replace("/","",$_REQUEST['method'])));

		if((int)method_exists($this,$func) > 0)
			$this->$func();
		else
			$this->response('', 404); 
		// If the method not exist with in this class, response would be "Page not found".
	}

    function HelloWorld(){
    	if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

		$str = 'your name is '. $this->_request['yourname'];
        $this->response(json_encode(array("result"=>$str)), 200);
    }

    function memberLogin(){
    	if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

    	try {
	    	$dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		    $this->response("Error!: " . $e->getMessage() . "<br/>", 500);
		    die();
		}
		
		$email = $this->_request['email'];
		$password = $this->_request['password'];
		$results = array();

		if(!empty($email)){
			$stmt = $dbh->prepare('select email, member_name, address from member where email = :email and password = :password');
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':password', $password);
			$stmt->execute();
			
			$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		$this->response(json_encode($results), 200);
	}

	function getAmphur(){
		if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

		try {
	    	$dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		    $this->response("Error!: " . $e->getMessage() . "<br/>", 500);
		    die();
		}

		$provinceId = $this->_request['province_id'];
		$results = array();

		if (!empty($provinceId)){
			$sql = "select amphur_id, amphur_name from amphures where province_id = :province_id order by amphur_name asc";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(':province_id', $provinceId);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$this->response(json_encode($results), 200);
		}
	}//function getAmphur

	function getDistrict(){
		if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

		try {
	    	$dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		    $this->response("Error!: " . $e->getMessage() . "<br/>", 500);
		    die();
		}

		$provinceId = $this->_request["province_id"];
		$amphurId = $this->_request["amphur_id"];
		$results = array();

		if (!empty($provinceId) && !empty($amphurId)){
			$sql = "select d.district_id, d.district_name, z.zipcode from ";
			$sql .= "districts d inner join zipcodes z on d.district_code = z.district_code ";
			$sql .= "where 1 = 1 ";
			$sql .= "and d.province_id = :province_id ";			
			$sql .= "and d.amphur_id = :amphur_id ";
			$sql .= "order by d.district_name asc ";

			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(':province_id', $provinceId);
			$stmt->bindParam(':amphur_id', $amphurId);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$this->response(json_encode($results), 200);
		}
	}//function getTambol

	function getBytesFromHexString($hexdata) {
	  for($count = 0; $count < strlen($hexdata); $count+=2)
	    $bytes[] = chr(hexdec(substr($hexdata, $count, 2)));

	  return implode($bytes);
	}

	function getImageMimeType($imagedata) {	
	  $imagemimetypes = array( 
	    "jpg" => "FFD8", 
	    "png" => "89504E47", 
	    "gif" => "474946",
	    "bmp" => "424D", 
	    "tiff" => "4949",
	    "tiff" => "4D4D"
	  );

	  foreach ($imagemimetypes as $mime => $hexbytes)
	  {
	    $bytes = $this::getBytesFromHexString($hexbytes);
	    if (substr($imagedata, 0, strlen($bytes)) == $bytes)
	      return $mime;
	  }

	  return NULL;
	}

	function deleteOldFile($target_dir) {
		//delete old file

		$files = glob('../'.$target_dir.$this->_request['email'].'*.*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
		    unlink($file); // delete file
		}
	}

	function updateMember() {
		if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

		//begin upload file
		$data = $this->_request["fileToUpload"];
		$target_dir = "uploads/";
		$oldUpload = $this->_request["oldUpload"];

		if (!empty($data) && ($data != 'undefined')) {
			$this::deleteOldFile($target_dir);

			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = str_replace(' ', '+', $data);
			$data = base64_decode($data);
			$mimetype = $this::getImageMimeType($data);			
			
			//$target_file = $target_dir . basename($_FILES[$this->_request["fileToUpload"]]["name"]);			
			$photo = $target_dir . $this->_request['email'] . '.' . $mimetype;			
			file_put_contents('../' . $photo, $data);		
		} else {
			if (!empty($oldUpload)) {
				$photo = $oldUpload;
			} else {
				$photo = null;
				$this::deleteOldFile($target_dir);
			}
		}

		// $uploadOk = 1;
		// $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		// // Check if image file is a actual image or fake image		
	 //    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	 //    if($check !== false) {
	 //        //$this->response("File is an image - " . $check["mime"] . ".", 222);
	 //        $uploadOk = 1;
	 //    } else {
	 //        $this->response("File is not an image.", 500);
	 //        $uploadOk = 0;
	 //    }

		// // Check if file already exists
		// if (file_exists($target_file)) {
		//     $this->response("Sorry, file already exists.", 500);
		//     $uploadOk = 0;
		// }

		// // Check file size
		// if ($_FILES["fileToUpload"]["size"] > 500000) {
		//     $this->response("Sorry, your file is too large.", 500);
		//     $uploadOk = 0;
		// }

		// // Allow certain file formats
		// if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		// && $imageFileType != "gif" ) {
		//     $this->response("Sorry, only JPG, JPEG, PNG & GIF files are allowed.", 500);
		//     $uploadOk = 0;
		// }

		// // Check if $uploadOk is set to 0 by an error
		// if ($uploadOk == 0) {
		//     $this->response("Sorry, your file was not uploaded.", 500);
		// // if everything is ok, try to upload file
		// } else {
		//     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		//         $this->response("The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.", 200);
		//     } else {
		//         $this->response("Sorry, there was an error uploading your file.", 500);
		//     }
		// }//upload file

		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  $this->response("Error!: " . $e->getMessage() . "<br/>", 500);
		  die();
		}		

		$sql = "update member set member_name = :member_name, address = :address, password = :password ";
		$sql .= ",province_id = :province_id, province_name = :province_name ";
		$sql .= ",amphur_id = :amphur_id, amphur_name = :amphur_name ";
		$sql .= ",district_id = :district_id, district_name = :district_name ";
		$sql .= ",postcode = :postcode, height_1 = :height_1, height_2 = :height_2 ";
		$sql .= ",photo = :photo ";
		$sql .= "where email = :email";

		$stmt = $dbh->prepare($sql);

		$stmt->bindValue(":member_name", $this->_request["txtName"]);
		$stmt->bindValue(":address", $this->_request["txtAddress"]);
		$stmt->bindValue(":password", $this->_request["txtPassword"]);
		$stmt->bindValue(":province_id", doExplode($this->_request["cboProvince"])[0]);
		$stmt->bindValue(":province_name", doExplode($this->_request["cboProvince"])[1]);
		$stmt->bindValue(":amphur_id", doExplode($this->_request["cboAmphur"])[0]);
		$stmt->bindValue(":amphur_name", doExplode($this->_request["cboAmphur"])[1]);
		$stmt->bindValue(":district_id", doExplode($this->_request["cboDistrict"])[0]);
		$stmt->bindValue(":district_name", doExplode($this->_request["cboDistrict"])[1]);
		$stmt->bindValue(":postcode", $this->_request["txtPostCode"]);
		$stmt->bindValue(":photo", $photo);
		$stmt->bindValue(":height_1", $this->_request["txtHeight_1"]);
		$stmt->bindValue(":height_2", $this->_request["txtHeight_2"]);
		$stmt->bindValue(":email", $this->_request["email"]);

		if ($stmt->execute()){
			$this->response(json_encode(array("result"=>"success")), 200);
		}else{		
			$this->response(json_encode($stmt->errorInfo()), 500);
		}
	}//updateMember

	function updateMemberHeight() {
		if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  $this->response("Error!: " . $e->getMessage() . "<br/>", 500);
		  die();
		}

		$email = $this->_request['email'];
		$height_1 = $this->_request['height_1'];
		$height_2 = $this->_request['height_2'];

		$sql = "update member set height_1 = :height_1, height_2 = :height_2 ";
		$sql .= "where email = :email ";

		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(":height_1", $height_1);
		$stmt->bindValue(":height_2", $height_2);
		$stmt->bindValue(":email", $email);

		if ($stmt->execute()) {
			$this->response(json_encode(array("result"=>"success")), 200);
		}else{		
			$this->response(json_encode($stmt->errorInfo()), 500);
		}

	}

	function getShirtInfo() {
		if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  $this->response("Error!: " . $e->getMessage() . "<br/>", 500);
		  die();
		}

		$shirt_id = $this->_request["shirt_id"];

		$sql = "select s.shirt_id, s.shirt_name, s.color, s.shirt_type, s.material_type, s.size_code, s.shirt_price ";
		$sql .= ",z.chest_size, z.shirt_length ";
		$sql .= ",c.color_hex ";
		$sql .= "from shirts s inner join shirt_size z on s.size_code = z.size_code ";
		$sql .= "inner join shirt_color c on s.color = c.color ";
		$sql .= "where 1 = 1 ";

		if (!empty($shirt_id)){
			$sql .= "and s.shirt_id = :shirt_id ";
			$sql .= "order by s.shirt_name asc ";
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(":shirt_id", $shirt_id);

		} else {
			$sql .= "order by s.shirt_name asc ";
			$stmt = $dbh->prepare($sql);
		}		

		if ($stmt->execute()){
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$this->response(json_encode($results), 200);
		} else {
			$this->response(json_encode($stmt->errorInfo()), 500);
		}		
	}

	function getClipart() {
		if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  $this->response("Error!: " . $e->getMessage() . "<br/>", 500);
		  die();
		}

		$clipartType = $this->_request['clipartType'];
		$dir = '../img/clipart/' . $clipartType . '/';

       	$files = array();
        foreach (new DirectoryIterator($dir) as $fileInfo) {
            if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
            $files[] = $fileInfo->getFilename();
        }

        $this->response(json_encode($files), 200);
	}

	function updateSystemInit() {
		if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

		try {
		  $dbh = dbConnect::getInstance()->dbh;
		} catch (PDOException $e) {
		  $this->response("Error!: " . $e->getMessage() . "<br/>", 500);
		  die();
		}

		$print_format = $this->_request['print_format'];
		$width = $this->_request['width'];
		$height = $this->_request['height'];
		$vat_rate = $this->_request['vat_rate'];
		$old_print_format = $this->_request['old_print_format'];

		$sql = 'update printer set ';
		$sql .= 'print_format = :print_format, ';
		$sql .= 'width = :width, ';
		$sql .= 'height = :height, ';
		$sql .= 'vat_rate = :vat_rate ';
		$sql .= 'where print_format = :old_print_format ';

		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(":print_format", $print_format);
		$stmt->bindValue(":width", $width);
		$stmt->bindValue(":height", $height);
		$stmt->bindValue(":vat_rate", $vat_rate);
		$stmt->bindValue(":old_print_format", $old_print_format);

		if ($stmt->execute()) {
			$this->response(json_encode(array("result"=>"success")), 200);
		} else {		
			$this->response(json_encode($stmt->errorInfo()), 500);
		}
	}

}//class 

// Initiiate Library
$myservice = new MyService;
$myservice->processApi();
?>
 