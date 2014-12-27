<?php
function messageSuccess($message, $delay){
	$script = "<script type='text/JavaScript'>";
    $script .= "$( document ).ready(function() { ";
    $script .= "bootbox.dialog({";
    $script .= "title: '',";
    $script .= "message : '<div class=\"alert alert-success\" role=\"alert\">".$message."</div>' ";
    $script .= "});";
    if ($delay > 0){  
    	$script .= "setTimeout(function(){bootbox.hideAll()}, " .$delay. ");";
    }
    $script .= "}); ";
    $script .= "</script>";
 
    
  	return $script;
}
function messageFail($message, $delay){
	$script = "<script type='text/JavaScript'>";
    $script .= "$( document ).ready(function() { ";
    $script .= "bootbox.dialog({";
    $script .= "title: '',";
    $script .= "message : '<div class=\"alert alert-warning\" role=\"alert\">".$message."</div>' ";
    $script .= "});"; 
	if ($delay > 0){
    	$script .= "setTimeout(function(){bootbox.hideAll()}, " .$delay. ");";
    }
    $script .= "}); ";
    $script .= "</script>";

    return $script;
}
?>