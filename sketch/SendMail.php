<?php
require("header.php");

include "classes/class.phpmailer.php"; // include the class name
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "pk.manee@gmail.com";
$mail->Password = "1go2go3go";
$mail->SetFrom("pk.manee@gmail.com");
$mail->Subject = 'ยืนยันการสั่งซื้อ';
$mail->Body = $_POST['email_body'];
$mail->AddAddress($_SESSION['email']);

 if(!$mail->Send()){
 	header("Content-Type: application/json");	
	echo json_encode(array("result"=>$mail->ErrorInfo));
}
else{
	header("Content-Type: application/json");
	echo json_encode(array("result"=>"success"));
}
?>