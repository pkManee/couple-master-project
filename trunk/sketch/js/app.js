var areaWidth = 849;
var areaHeight = 600;
var $ = function(id){return document.getElementById(id)};
var div = $('canvas-area');
div.width = areaWidth;
div.height = areaHeight;
div.style.width = areaWidth;
div.style.height = areaHeight;

var theCanvas = document.createElement('canvas');
theCanvas.id = 'the-canvas';
theCanvas.width = areaWidth;
theCanvas.height = areaHeight;
theCanvas.style.width = areaWidth;
theCanvas.style.height = areaHeight;

div.appendChild(theCanvas);

var canvasMian = new fabric.Canvas('the-canvas');

function init() {  
  
}

var btnRect = $('btn-rectangle');
btnRect.onclick = function(){
  fabric.Object.prototype.transparentCorners = false;

  var rect = new fabric.Rect({
    width: 100,
    height: 100,
    top: 100,
    left: 100,
    fill: 'rgba(255,0,0,0.5)'
  });

  canvasMian.add(rect); 
}

init();