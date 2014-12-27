<?php
require_once("Rest.inc.php");
require("db_connect.php");
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
			$this->response('',404); 
		// If the method not exist with in this class, response would be "Page not found".
	}

    function abc(){
    	if($this->get_request_method() != "POST")
		{
			$this->response('',406);
		}

		$str = 'youname is '. $this->_request['youname'];
        $this->response(json_encode($str), 200);
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
			$json=json_encode($results);	
			
		}
		
		$this->response(json_encode($results), 200);
	    }

 }

// Initiiate Library
$myservice = new MyService;
$myservice->processApi();
?>
