var areaWidth = 850;
var areaHeight = 600;
var DPI = 300;
var A3 = {
            cmWidth: 29.7, 
            cmHeight: 42, 
            pxWidth: cmToPixel(29.7, 42, DPI).width, 
            pxHeight: cmToPixel(29.7, 42, DPI).height
        };
var A4 = {
            cmWidth: 21, 
            cmHeight: 29.7, 
            pxWidth: cmToPixel(21, 29.7, DPI).width, 
            pxHeight: cmToPixel(21, 29.7, DPI).height
        };
var svgNS = "http://www.w3.org/2000/svg";
var isShirtMode = false;

var div = document.getElementById('canvas-area');
div.style.width = areaWidth;
div.style.height = areaHeight;

//draw canvas
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
canvas.wrapperEl.style.position = 'absolute';
canvas.selection = false;

//shirt canvas
var s = document.createElement('canvas');
s.id = 's';
s.width = A4.pxWidth;
s.height = A4.pxHeight;
s.style.width = areaWidth;
s.style.height = areaHeight;
s.style.position = 'absolute';
div.appendChild(s);

var shirtCanvas = new fabric.Canvas('s');
shirtCanvas.wrapperEl.style = 'absolute';
shirtCanvas.selection = false;

var PICKER = undefined;
var COLOR = '#FF0000';
var OPACITY = '1';

//init fabric painter
var painter = fabricPainter;
var isPainterOn = false;
var isMouseDownForPaint = false;
var isMouseDown = false;

//set movement limit
var screenShirt1 = undefined, screenShirt2 = undefined, 
    borderShirt1 = undefined, borderShirt2 = undefined;


// toggle mode
var btnShirt = document.getElementById('btn-shirt');
btnShirt.onclick = function(){
    isShirtMode = !isShirtMode;

    toggleMode();

    if (isShirtMode){        
        splitCanvas();
        designInit();
    }
}
var btnDesign = document.getElementById('btn-design');
btnDesign.onclick = function(){
    btnShirt.onclick();
}

function toggleMode(){

    var designShelf = document.getElementById('design-shelf');
    var brushShelf = document.getElementById('brush-image-shelf');
    var shirtShelf = document.getElementById('shirt-shelf');
    var lineWidth = document.getElementById('line-width');
    var panelColor = document.getElementById('panel-color');
    //to shirt mode
    if (isShirtMode){
        canvas.wrapperEl.style.zIndex = -1;
        shirtCanvas.wrapperEl.style.zIndex = 1;
        lineWidth.style.display = 'none';
        panelColor.style.display = 'inline-block';

        $('.nav-tabs > .active').next('li').find('a').trigger('click');
        if (PICKER) PICKER.toggle(false);
    }else{
    //to design mode
        canvas.wrapperEl.style.zIndex = 1;
        shirtCanvas.wrapperEl.style.zIndex = -1;
        lineWidth.style.display = 'inline-block';
        panelColor.style.display = 'none';
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        if (PICKER) PICKER.toggle(true);
    }
}
//============================================================================================================
//insert svg geometry
//============================================================================================================
var btnRect = document.getElementById('btn-rectangle');
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
var btnTri = document.getElementById('btn-triangle');
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
var btnRound = document.getElementById('btn-round');
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
    var uri = undefined;  
    if (svg.outerHTML){
        uri = 'data:image/svg+xml;base64,' + window.btoa(svg.outerHTML);        
    }else{
        uri = svg;       
    }

    fabric.loadSVGFromURL(uri,function(objects,options) {
        var loadedObjects = new fabric.Group(group);
        loadedObjects.set({
                left: 10,
                top: 10,
                width: loadedObjects.width,
                height: loadedObjects.height,
                selectable: true
        });      
        
        canvas.add(loadedObjects);
        canvas.renderAll();
        btnSelect.onclick();

        },function(item, object) {              
                object.set('id',item.getAttribute('id'));             
                group.push(object);
    });    
}
//upload file
var imageLoader = document.getElementById('upload-button');
imageLoader.addEventListener('change', handleImage, false);
function handleImage(e){

    var files = e.target.files;
    for (var i = 0; i < files.length; i++) {
        if (files[i].type.match(/image.svg*/)) {
            var reader = new FileReader();
            reader.onload = function (event) {
               insertGeoSVG(event.target.result);
            }
            reader.readAsDataURL(files[i]);
           
        }else if (files[i].type.match(/image.*/)){
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
    } 
}

//insert text
var btnText = document.getElementById('btn-text');
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
var btnClone = document.getElementById('btn-clone');
btnClone.onclick = function(){
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
        canvas.add(obj.clone().set({left: obj.get('left') + 20, top: obj.get('top') - 20, selectable: true}));
    }
    canvas.renderAll();
}
//button flip
var btnFlip = document.getElementById('btn-flip');
btnFlip.onclick = function(){
    var obj = canvas.getActiveObject();
    if (obj === null || obj === undefined) return;
    obj.set('flipX', !obj.get('flipX'));
    canvas.renderAll()
}
//switch between draw <--> select Mode
var btnSelect = document.getElementById('btn-selector');
btnSelect.onclick = function(){
    isPainterOn = false;
    canvas.isDrawingMode = false;   
}

var el;
var object, lastActive, object1, object2;
var cntObj = 0;
var selection_object_left = 0;
var selection_object_top = 0;
var crop = false;
//button crop event
var btnCrop = document.getElementById('btn-crop');
btnCrop.onclick = function(){  

    //start crop
    if (!crop){
        canvas.remove(el);
        if (canvas.getActiveObject()) {

            object = canvas.getActiveObject();

            if (lastActive !== object) {
                console.log('different object');
            } else {
                console.log('same object');
            }
            if (lastActive && lastActive !== object) {
                //lastActive.clipTo = null; results in clip loss
            }

            el = new fabric.Rect({
                fill: 'rgba(0,0,0,0.3)',
                originX: 'left',
                originY: 'top',
                stroke: '#ccc',
                strokeDashArray: [2, 2],
                opacity: 0,
                width: 1,
                height: 1,
                borderColor: '#36fd00',
                cornerColor: 'green',
                hasRotatingPoint: false,
                selectable: true
            });

            el.left = canvas.getActiveObject().left;
            selection_object_left = canvas.getActiveObject().left;
            selection_object_top = canvas.getActiveObject().top;
            el.top = canvas.getActiveObject().top;
            el.width = canvas.getActiveObject().width * canvas.getActiveObject().scaleX;
            el.height = canvas.getActiveObject().height * canvas.getActiveObject().scaleY;

            canvas.add(el);
            canvas.setActiveObject(el);
            crop = true;

            btnCrop.setAttribute('title', 'OK');
            btnCrop.className = 'geo-button icon-ok';
        } else {
            alert("Please select an object or layer");
            crop = false;
            btnCrop.setAttribute('title', 'Crop');
            btnCrop.className = 'geo-button icon-crop';
        }
        
    }else if (crop){ //do crop        
        btnCrop.setAttribute('title', 'Crop');
        btnCrop.className = 'geo-button icon-crop';

        var box = el, //this is rect object
            format = 'png',
            quality = '10',
            boxTop = box.top,
            boxLeft = box.left,
            cropping = {
            y: box.top,
            x: box.left,
            width: box.currentWidth,
            height: box.currentHeight            
        };       
        canvas.deactivateAll();        

        var dataURL = canvas.toDataURLWithCropping(format, cropping, quality);      
        fabric.Image.fromURL(dataURL, function(oImg) {
            canvas.clear();
            oImg.set({top: boxTop ,left: boxLeft});
            canvas.add(oImg);
            canvas.renderAll();
            oImg.selectable = true;
        });        

        crop = false;      
        canvas.renderAll();
    }
}

fabric.Canvas.prototype.toDataURLWithCropping = function (format, cropping, quality) {
  var canvasEl = this.upperCanvasEl || this.lowerCanvasEl,
    ctx = this.contextTop || this.contextContainer,
    tempCanvasEl = fabric.document.createElement('canvas'),
    tempCtx, imageData;

    if (!tempCanvasEl.getContext && typeof G_vmlCanvasManager !== 'undefined') {
        G_vmlCanvasManager.initElement(tempCanvasEl);
    }

    this.renderAll(true);

    tempCanvasEl.width = cropping.width;
    tempCanvasEl.height = cropping.height;

    imageData = ctx.getImageData(cropping.x, cropping.y, cropping.width, cropping.height);

    tempCtx = tempCanvasEl.getContext('2d');
    tempCtx.putImageData(imageData, 0, 0);

    var data = (fabric.StaticCanvas.supports('toDataURLWithQuality'))
               ? tempCanvasEl.toDataURL('image/' + format, quality)
               : tempCanvasEl.toDataURL('image/' + format);

    this.contextTop && this.clearContext(this.contextTop);
    this.renderAll();
    return data;
}

//brush size slider
var lineWidthSlider = document.getElementById('brush-size-slider');
lineWidthSlider.onchange = function(){   
    setColor();
}
lineWidthSlider.onmouseover = function(){
    this.title = this.value;
}
lineWidthSlider.onmouseup = function(){
    this.title = this.value;
}
var btnPencil = document.getElementById('btn-pencil');
btnPencil.onclick = function(){
    canvas.isDrawingMode = true;    
    canvas.freeDrawingBrush = new fabric[btnPencil.getAttribute('title') + 'Brush'](canvas);
    setColor();
}
var btnBrush = document.getElementById('btn-brush');
btnBrush.onclick = function(){
    canvas.isDrawingMode = true;
    canvas.freeDrawingBrush = new fabric[btnBrush.getAttribute('title') + 'Brush'](canvas);
    setColor();
}

//use fabricjs-painter
var btnSpray = document.getElementById('btn-spray');
btnSpray.onclick = function(data){
    canvas.isDrawingMode = false;   
    isPainterOn = true;
    setColor();
}


function setColor(){    
    var activeObject = canvas.getActiveObject();
    var theWidth = parseInt(lineWidthSlider.value, 10);
    lineWidthSlider.previousSibling.innerHTML = theWidth;    

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
//clear button
var btnClear = document.getElementById('btn-erase');
btnClear.onclick = function(){ canvas.clear(); }

//============================================================================================================
//right side panel
//============================================================================================================


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

var shirtArray = [];
//shirt canvas
var splitLineScreen = [];
var finalLineScreen = [];
var side_1 = {width: 190, height: 280,top: 160, left: 118};
var side_2 = {width: 190, height: 280,top: 160, left: 545};
var vType = {
            type_m: {uri: './img/shirts/v-white-front-m01.png'},             
            type_w: {uri: './img/shirts/v-white-front-w01.png'}
            };
var roundType = {
            type_m: {uri: './img/shirts/white-front-m01.png'}, 
            type_w: {uri: './img/shirts/white-front-w01.png'}
            };
var poloType = {
            type_m: {uri: './img/shirts/polo-white-front-m03.png'},
            type_w: {uri: './img/shirts/polo-white-front-w02.png'}
            }

function loadShirt(type_1, color1, type_2, color2){    
    
    if (!isShirtMode) return;

    if (type_1 === undefined) type_1 = roundType.type_m;
    if (color1 === undefined) color1 = COLOR;
    if (type_2 === undefined) type_2 = roundType.type_w;
    if (color2 === undefined) color2 = COLOR;
    shirtCanvas.clear();    
    shirtArray.length = 0;    
    //shirt 1
    //men
    if (type_1) {        
        var rectBorder = new fabric.Rect({
            width: side_1.width,
            height: side_1.height,
            top: side_1.top,
            left: side_1.left,
            strokeWidth: 1,
            fill: 'rgba(0, 0, 0, 0)',
            stroke: 'rgba(0, 0, 0, 0.5)',
            selectable: false
        });
       
        shirtCanvas.add(rectBorder);
        borderShirt1 = rectBorder;

        fabric.Image.fromURL(type_1.uri, function(oImg) {            
            oImg.set({id: 'shirt1',width: 417, height: 471, top: 30, left: 3}); 
            shirtCanvas.add(oImg);
            //oImg.selectable = true;    

            shirtCanvas.sendToBack(oImg);
            shirtArray.push(oImg);
        });
        fabric.Image.fromURL(splitLineScreen[0], function(oImg) {        
            oImg.set({top: side_1.top + 5 ,left: side_1.left + 5, scaleX: 100/oImg.width, scaleY: 141/oImg.height});
            shirtCanvas.add(oImg);           
            oImg.set({selectable: true, hasRotatingPoint: false, lockUniScaling: true, sideOfCanvas: 'left', goodTop: 0, goodLeft: 0});           
            shirtCanvas.bringToFront(oImg);
            shirtCanvas.renderAll();
            finalLineScreen.push(oImg);
        });
    }
    //shirt 2
    //women
    if (type_2) {
        var rectBorder2 = new fabric.Rect({
            width: side_2.width,
            height: side_2.height,
            top: side_2.top,
            left: side_2.left,
            strokeWidth: 1,
            fill: 'rgba(0, 0, 0, 0)',
            stroke: 'rgba(0, 0, 0, 0.5)',
            selectable: false
        });
       
        shirtCanvas.add(rectBorder2);
        borderShirt2 = rectBorder2;

        fabric.Image.fromURL(type_2.uri, function(oImg) {           
            oImg.set({id: 'shirt2', width: 417, height: 471, top: 35, left: 430}); 
            shirtCanvas.add(oImg);
            //oImg.selectable = true;      

            shirtCanvas.sendToBack(oImg);
            shirtArray.push(oImg);
        });
        fabric.Image.fromURL(splitLineScreen[1], function(oImg) {        
            oImg.set({top: side_2.top + 5 ,left: side_2.left + 5, scaleX: 100/oImg.width, scaleY: 141/oImg.height});
            shirtCanvas.add(oImg);
            oImg.set({selectable: true, hasRotatingPoint: false, lockUniScaling: true, sideOfCanvas: 'right', goodTop: 0, goodLeft: 0});
            shirtCanvas.bringToFront(oImg);
            shirtCanvas.renderAll();
            finalLineScreen.push(oImg);
        });
    }
}

function splitCanvas() {
    splitLineScreen.length = 0;
    finalLineScreen.length = 0;

    var format = 'png',
        quality = '10';       
    var cropping1 = {
        y: 0,
        x: 0,
        width: 425,
        height: 600
    };
    var cropping2 = {
        y: 0,
        x: 425,
        width: 425,
        height: 600
    };
  
    canvas.deactivateAll();
    splitLineScreen.push(canvas.toDataURLWithCropping(format, cropping1, quality));
    splitLineScreen.push(canvas.toDataURLWithCropping(format, cropping2, quality));
}

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

    canvas.wrapperEl.style.zIndex = 1;
    shirtCanvas.wrapperEl.style.zIndex = -1;

    canvas.on('mouse:down', function(data){
        isMouseDown = true;
        if (isPainterOn && !canvas.isDrawingMode){
            isMouseDownForPaint = true;
        }       
    });

    canvas.on('mouse:move', function(data){
        if (isMouseDownForPaint){
            painter.drawGlassStorm(data); 
            return;
        }      
    });

    canvas.on('mouse:up', function(data){
        isMouseDown = false;
        if (isMouseDownForPaint) isMouseDownForPaint = false;        
    });

    //set movement limit
    shirtCanvas.on("object:moving", function(e) {
        if (isShirtMode){
            var obj = e.target; //shirtCanvas.getActiveObject(); 
            var bounds = undefined;

            if (obj.sideOfCanvas === 'left') {
                bounds = borderShirt1;
            } else if (obj.sideOfCanvas === 'right') {
                bounds = borderShirt2;
            }

            obj.setCoords();
            if(!obj.isContainedWithinObject(bounds)){
                obj.setTop(obj.goodTop - 1);
                obj.setLeft(obj.goodLeft - 1);                
            } else {
                obj.goodTop = obj.top;
                obj.goodLeft = obj.left;
            }            
        }
    });
    //set scaleing limit
    var goodScaleX, goodScaleY
    shirtCanvas.on("object:scaling", function(e){
        if (isShirtMode){
            var obj = e.target; //shirtCanvas.getActiveObject();
            var bounds = undefined;           

            if (obj.sideOfCanvas === 'left') {
                bounds = borderShirt1;
            } else if (obj.sideOfCanvas === 'right') {
                bounds = borderShirt2;
            }

            obj.setCoords();
            if(!obj.isContainedWithinObject(bounds)){ 
                obj.set({scaleX: goodScaleX, scaleY: goodScaleY, left: obj.goodLeft, top: obj.goodTop});
                                 
            } else{
                obj.goodTop = obj.top;
                obj.goodLeft = obj.left;
                goodScaleX = obj.scaleX;
                goodScaleY = obj.scaleY;
            }
        }
    });

    btnSelect.onclick();
    toggleMode();
   
}//init
init();