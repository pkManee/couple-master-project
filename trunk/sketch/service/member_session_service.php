<?php

session_start();

$_SESSION["email"] = $_POST["email"];
$_SESSION["member_name"] = $_POST["member_name"];

?>