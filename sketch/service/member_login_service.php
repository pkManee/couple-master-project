<?php
	require("db_connect.php");

	try {
	    $dbh = dbConnect::getInstance()->dbh;
	} catch (PDOException $e) {
	    print "Error!: " . $e->getMessage() . "<br/>";
	    die();
	}

	if (empty($_POST)) return;
	
	$email = $_POST['email'];
	$password = $_POST['password'];
	$results = array();

	if(!empty($email)){
		$stmt = $dbh->prepare('select email, member_name, address from member where email = :email and password = :password');
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
		
		$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($results);	
		
	}
	/* Output header */
	header('Content-type: application/json');
	echo json_encode($results);

?>