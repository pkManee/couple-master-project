
(function(root){
this.pixelToCm = function(width, height, dpi){
	var obj = {width: Math.round(width * 2.54 / dpi), height: Math.round(height * 2.54 / dpi)};
	return obj;
}

this.cmToPixel = function(width, height, dpi){
	var obj = {width: Math.round(width * dpi / 2.54), height: Math.round(height * dpi / 2.54)};
	return obj;
}

this.calPortal =  function(shortLength){
	var ratio = 1.414;
	
	return Math.round(length * 1.414);
}
})(this);

//A4 = 21 x 29.7
//A3 = 29.7 x 42
//max area sie = 32x32 cm