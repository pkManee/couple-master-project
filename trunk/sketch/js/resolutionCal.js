
(function(root){
this.pixelToCm = function(width, height, dpi){
	var obj = {width: Math.round(width * 2.54 / dpi), height: Math.round(height * 2.54 / dpi)};
	return obj;
}

this.cmToPixel = function(width, height, dpi){
	var obj = {width: Math.round(width * dpi / 2.54), height: Math.round(height * dpi / 2.54)};
	return obj;
}
})(this);