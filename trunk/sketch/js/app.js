var areaWidth = 849;
var areaHeight = 600;
var svgNS = "http://www.w3.org/2000/svg";

var $ = function(id){return document.getElementById(id)};

var div = $('canvas-area');
div.style.width = areaWidth;
div.style.height = areaHeight;

var c = document.createElement('canvas');
c.id = 'c';
c.width = areaWidth;
c.height = areaHeight;
c.style.width = areaWidth;
c.style.height = areaHeight;
div.appendChild(c);

//var canvas = new fabric.CanvasEx('c');  // extend event
var canvas = this.__canvas = new fabric.Canvas('c');  //normal event
fabric.Object.prototype.selectable = false;
canvas.selection = false;
var PICKER = undefined;
var COLOR = '#FF0000';
var OPACITY = '1';

//init fabric painter
var painter = fabricPainter;
var isPainterOn = false;
var isMouseDownForPaint = false;
var isMouseDown = false;

//============================================================================================================
//insert svg geometry
//============================================================================================================
var btnRect = $('btn-rectangle');
btnRect.onclick = function(){         

    var rect = new fabric.Rect({
        width: 100,
        height: 100,
        top: 10,
        left: 10,
        fill: COLOR,
        selectable: true
    });
   
    canvas.add(rect);
    canvas.renderAll();
    btnSelect.onclick();
}
var btnTri = $('btn-triangle');
btnTri.onclick = function(){
    var triangle = new fabric.Triangle({
      width: 100, 
      height: 100, 
      fill: COLOR, 
      left: 10, 
      top: 10,
      selectable: true
    });

    canvas.add(triangle);
    canvas.renderAll();
    btnSelect.onclick();
}
var btnRound = $('btn-round');
btnRound.onclick = function(){
    var circle = new fabric.Circle({
      radius: 50, 
      fill: COLOR, 
      left: 10, 
      top: 10,
      selectable: true
    });

    canvas.add(circle);
    canvas.renderAll();
    btnSelect.onclick();
}
var btnStar = document.getElementById('btn-star');
btnStar.onclick = function(){
    var svg = createSVG();
    var elm = document.createElementNS(svgNS, 'polygon');
    elm.setAttributeNS(null, 'fill', COLOR);  
    elm.setAttributeNS(null, 'points', "50,5 20,99 95,39 5,39 80,99");
    svg.appendChild(elm);
    insertGeoSVG(svg);
}
var btnHeart = document.getElementById('btn-heart');
btnHeart.onclick = function(){
    var svg = createSVG();
    var elm = document.createElementNS(svgNS, 'path');
    elm.setAttributeNS(null, 'fill', COLOR);  
    elm.setAttributeNS(null, 'd', "M67.607,13.462c-7.009,0-13.433,3.238-17.607,8.674c-4.174-5.437-10.598-8.674-17.61-8.674  c-12.266,0-22.283,10.013-22.33,22.32c-0.046,13.245,6.359,21.054,11.507,27.331l1.104,1.349  c6.095,7.515,24.992,21.013,25.792,21.584c0.458,0.328,1,0.492,1.538,0.492c0.539,0,1.08-0.165,1.539-0.492  c0.8-0.571,19.697-14.069,25.792-21.584l1.103-1.349c5.147-6.277,11.553-14.086,11.507-27.331  C89.894,23.475,79.876,13.462,67.607,13.462z");
    svg.appendChild(elm);
    insertGeoSVG(svg);
}
function createSVG(){
    var svg = document.createElementNS(svgNS, 'svg');
    svg.setAttribute('xmlns', svgNS)
    svg.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    svg.setAttribute('version', '1.1');
    svg.setAttribute('width', '100px');
    svg.setAttribute('height', '100px');
    return svg;
}
function insertGeoSVG(svg){
    var group = [];
    var uri = 'data:image/svg+xml;base64,' + window.btoa(svg.outerHTML);

    fabric.loadSVGFromURL(uri,function(objects,options) {
        var loadedObjects = new fabric.Group(group);

        loadedObjects.set({
                left: 10,
                top: 10,
                width: 100,
                height: 100,
                fill: COLOR,
                selectable: true
        });

        canvas.add(loadedObjects);
        canvas.renderAll();

        },function(item, object) {
                object.set('id',item.getAttribute('id'));
                group.push(object);
    });

    btnSelect.onclick();
}
//upload file
var imageLoader = $('upload-button');
imageLoader.addEventListener('change', handleImage, false);
function handleImage(e){
    var reader = new FileReader();
    reader.onload = function(event){
       
        fabric.Image.fromURL(event.target.result, function(oImg) {
            canvas.add(oImg);
            canvas.renderAll();
            oImg.selectable = true;
        });
    }
    reader.readAsDataURL(e.target.files[0]);

    btnSelect.onclick();
}
//insert text
var btnText = $('btn-text');
btnText.onclick = function(){
    var text = new fabric.IText('Tap and Type', { 
                              fontFamily: 'arial',
                              left: 100, 
                              top: 100 ,
                              fill: COLOR,
                              selectable: true
                            })
    canvas.add(text);
    canvas.renderAll();

    btnSelect.onclick();
}
//button clone
var btnClone = $('btn-clone');
btnClone.onclick = function(){    
    removeCrop();
    var obj = canvas.getActiveObject();
    if (obj === null || obj === undefined) return;

    if (fabric.util.getKlass(obj.type).async) {
        obj.clone(function (clone) {
            clone.set({
                left: clone.get('left') + 20,
                top: clone.get('top') - 20,
                selectable: true
            });
            canvas.add(clone);
        });
    } else {
        canvas.add(obj.clone().set({left: clone.get('left') + 20, top: clone.get('top') - 20, selectable: true}));
    }
    canvas.renderAll();
}
//button flip
var btnFlip = $('btn-flip');
btnFlip.onclick = function(){
    removeCrop();
    var obj = canvas.getActiveObject();
    if (obj === null || obj === undefined) return;
    obj.set('flipX', !obj.get('flipX'));
    canvas.renderAll()
}
//switch between draw <--> select Mode
var btnSelect = $('btn-selector');
btnSelect.onclick = function(){
    removeCrop();
    isPainterOn = false;
    canvas.isDrawingMode = false;   
}

//add crop rectangle
var pos = [0, 0];

var r = c.getBoundingClientRect();
pos[0] = r.left;
pos[1] = r.top;

var mousex = 0;
var mousey = 0;
var crop = false;
var disabled = false;

var el = new fabric.Rect({   
    fill: 'transparent',
    originX: 'left',
    originY: 'top',
    stroke: '#ccc',
    strokeDashArray: [2, 2],
    opacity: 1,
    width: 1,
    height: 1
});
el.visible = false;
canvas.add(el);

//button crop event
var btnCrop = $('btn-crop');
btnCrop.onclick = function(){  
    if (!crop){  
        crop = true;
        isPainterOn = false;
        canvas.isDrawingMode = false;
        btnCrop.setAttribute('title', 'OK');
        btnCrop.className = 'geo-button icon-ok';
        return;
    }else if (crop){       
        if (!canvas.getActiveObject()){            
            removeCrop();
            canvas.renderAll();
            return;
        }

        var activeObject = canvas.getActiveObject();
        if (activeObject.type !== 'image'){          
            removeCrop();
            canvas.renderAll();
            return;
        }

        var left = el.left - activeObject.left;
        var top = el.top - activeObject.top;
        
        left *= 1 / 0.25;
        top *= 1 / 0.25;
        
        var width = el.width * 1 / 0.25;
        var height = el.height * 1 / 0.25;
        
        activeObject.clipTo = function (ctx) {
            ctx.rect(left, top, width, height);
        };
        activeObject.selectable = true;        
        removeCrop();
        canvas.renderAll();
    }
}
function removeCrop(){
    el.visible = false;
    crop = false;
    btnCrop.setAttribute('title', 'Crop');
    btnCrop.className = 'geo-button icon-crop';
}

//brush size slider
var lineWidthSlider = $('brush-size-slider');
lineWidthSlider.onchange = function(){   
    setColor();
}
lineWidthSlider.onmouseover = function(){
    this.title = this.value;
}
lineWidthSlider.onmouseup = function(){
    this.title = this.value;
}
var btnPencil = $('btn-pencil');
btnPencil.onclick = function(){
    canvas.isDrawingMode = true;
    removeCrop();
    canvas.freeDrawingBrush = new fabric[btnPencil.getAttribute('title') + 'Brush'](canvas);
    setColor();
}
var btnBrush = $('btn-brush');
btnBrush.onclick = function(){
    canvas.isDrawingMode = true;
    removeCrop();
    canvas.freeDrawingBrush = new fabric[btnBrush.getAttribute('title') + 'Brush'](canvas);
    setColor();
}

//use fabricjs-painter
var btnSpray = $('btn-spray');
btnSpray.onclick = function(data){
    canvas.isDrawingMode = false;   
    isPainterOn = true;
    removeCrop();
    setColor();
}


function setColor(){    
    var activeObject = canvas.getActiveObject();
    var theWidth = parseInt(lineWidthSlider.value, 10);
    lineWidthSlider.previousSibling.innerHTML = theWidth;
    removeCrop();

    if (canvas.freeDrawingBrush) {
        canvas.freeDrawingBrush.color = COLOR;        
        canvas.freeDrawingBrush.width = theWidth;  
        painter.brush_globals.prop('size', theWidth);       
        fabricPainter.brush_globals.prop('color', COLOR);
    }
    if (activeObject !== null && activeObject !== undefined) {        
        activeObject.set('fill', COLOR);
        canvas.renderAll();       
    return;
    }
}

//============================================================================================================
//right side panel
//============================================================================================================
var btnClear = $('clear-button');
btnClear.onclick = function(){ canvas.clear(); }

//init method
function init() { 
    // Initiate color picker widget.
    PICKER = new Color.Picker({
        size: 225,
        hueWidth: 45,
        color: "#FF0000",
        eyedropLayer: undefined,//canvas.lowerCanvasEl,
        eyedropMouseLayer: undefined, //canvas.upperCanvasEl,
        display: true,
        callback: function(rgba, state, type, self) {
            var w3 = Color.Space(rgba, "RGBA>W3");
            // sketch.style.strokeStyle = w3;            
            COLOR = w3;
            setColor();
        }
    });

    canvas.on('mouse:down', function(data){
        isMouseDown = true;
        if (isPainterOn && !canvas.isDrawingMode){
            isMouseDownForPaint = true;
        }
        if (crop){           

            el.left = data.e.pageX - pos[0];
            el.top = data.e.pageY - pos[1];           
            el.visible = true;

            mousex = data.e.pageX;
            mousey = data.e.pageY;
          
            canvas.bringToFront(el);
        }
    });

    canvas.on('mouse:move', function(data){
        if (isMouseDownForPaint){
            painter.drawGlassStorm(data); 
            return;
        }
        if (crop && isMouseDown) {
            if (data.e.pageX - mousex > 0) {
                el.width = data.e.pageX - mousex;
            }

            if (data.e.pageY - mousey > 0) {
                el.height = data.e.pageY - mousey;
            }
        }
    });

    canvas.on('mouse:up', function(data){
        isMouseDown = false;
        if (isMouseDownForPaint) isMouseDownForPaint = false;        
    });

    btnSelect.onclick();
}

// canvas.on('mouse:dblclick', function (options){
//     console.log('double click removed');
// });
document.onkeydown = onKeyDownHandler;
function onKeyDownHandler(e) {
   switch (e.keyCode) {
      case 46: // delete
         var activeObject = canvas.getActiveObject();
         if (activeObject !== null) {
            canvas.remove(activeObject);            
            canvas.renderAll();
            return;
        }
   }
};

init();