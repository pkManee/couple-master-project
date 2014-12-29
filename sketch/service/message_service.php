<?php
function messageSuccess($message, $delay, $redirectPage){
	$script = "<script type='text/JavaScript'>";
    $script .= "$( document ).ready(function() { ";
    $script .= "bootbox.dialog({";
    $script .= "title: '',";
    $script .= "message : '<div class=\"alert alert-success\" role=\"alert\">".$message."</div>' ";
    if (!empty($redirectPage)){
        $script .= ",buttons: {";
        $script .=              "success: {";
        $script .=                          "label: 'OK', ";
        $script .=                          "className: 'btn-success', ";
        $script .=                          "callback: function() { ";
        $script .=                              "window.location = 'index.php'; ";
        $script .=                          "}";
        $script .=                      "}";
        $script .=              "}";
    }
    $script .= "});";

    if (empty($redirectPage) && $delay > 0){  
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
    $script .= "message : '<div class=\"alert alert-danger\" role=\"alert\">".$message."</div>' ";
    $script .= "});"; 
	if ($delay > 0){
    	$script .= "setTimeout(function(){bootbox.hideAll()}, " .$delay. ");";
    }
    $script .= "}); ";
    $script .= "</script>";

    return $script;
}
function toastSuccess($message){
    $script = "<script type='text/JavaScript'>";
    $script .= "$( document ).ready(function() { ";

    $script .= "Toast.init({ \"selector\": \".alert-success\" });";
    $script .= "Toast.show(\"" .$message. "\");";

    $script .= "}); ";
    $script .= "</script>";

    return $script;
}
function toastFail($message){
    $script = "<script type='text/JavaScript'>";
    $script .= "$( document ).ready(function() { ";

    $script .= "Toast.init({ \"selector\": \".alert-danger\" });";
    $script .= "Toast.show(\"" .$message. "\");";

    $script .= "}); ";
    $script .= "</script>";

    return $script;
}

function doExplode($stringToExplode){
    $delimiter = "|";
    return explode($delimiter, $stringToExplode);
}
?>