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
var isFilterMode = false;
var filters = ['blur', 'sharpen', 'emboss', 'grayscale'];
var colorThief = new ColorThief();

var div = document.getElementById('canvas-area');
div.style.width = areaWidth;
div.style.height = areaHeight;

//draw canvas
var c = document.createElement('canvas');
c.id = 'c';
// c.width = A4.pxWidth;
// c.height = A4.pxHeight;
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
s.width = areaWidth;//A4.pxWidth;
s.height = areaHeight; //A4.pxHeight;
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
    var drawTools = document.getElementById('draw-tools');
    var panelColor = document.getElementById('panel-color');    
    //to shirt mode
    if (isShirtMode){
        canvas.wrapperEl.style.zIndex = -1;
        shirtCanvas.wrapperEl.style.zIndex = 1;
        drawTools.style.display = 'none';
        panelColor.style.display = 'inline-block';
        //toggle tab page
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
        //toggle picker
        if (PICKER) { 
            PICKER.toggle(false);
            isShowPicker = false;
        }
    }else{
    //to design mode
        canvas.wrapperEl.style.zIndex = 1;
        shirtCanvas.wrapperEl.style.zIndex = -1;
        drawTools.style.display = 'inline-block';
        panelColor.style.display = 'none';
        //toggle tab page
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        //toggle picker
        if (PICKER) {
            PICKER.toggle(true);
            isShowPicker = true;
        }
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
            btnSelect.onclick();
            this.value = '';
           
        }else if (files[i].type.match(/image.*/)){
            var reader = new FileReader();
            reader.onload = function(event){
               
                fabric.Image.fromURL(event.target.result, function(oImg) {
                    oImg.scaleX = (canvas.width/2) /oImg.width;
                    oImg.scaleY = (canvas.width/2) /oImg.width;//(canvas.height/2) /oImg.height;
                    canvas.add(oImg);
                    canvas.renderAll();
                    oImg.selectable = true;
                });
            }
            reader.readAsDataURL(e.target.files[0]);
            btnSelect.onclick();
            this.value = '';
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

//button filter
var btnFilter = document.getElementById('btn-filter');
btnFilter.onclick = function() {   
    isFilterMode = !isFilterMode;
    toggleFilter();
}
function toggleFilter() {
    var divFilters = document.getElementById('panel-filter');
    var divLineWidth = document.getElementById('line-width');

    if (isFilterMode) {
        divFilters.style.display = 'inline-block';
        divLineWidth.style.display = 'none';
    } else {
        divFilters.style.display = 'none';
        divLineWidth.style.display = 'inline-block';
    }

    var obj = canvas.getActiveObject();
    if (!obj) return;

    for (var i = 0; i < filters.length; i++) {
        if (obj.filters) {
            document.getElementById(filters[i]).disabled = false;
            document.getElementById(filters[i]).checked = !!canvas.getActiveObject().filters[i];
        } else {
            document.getElementById(filters[i]).disabled = true;
            document.getElementById(filters[i]).checked = false;
        }
    }
}
function filterLoading() {  
    var obj = canvas.getActiveObject();
    if (obj === null || obj === undefined || obj.type != 'image') {         
        return false;
    }

    bootbox.dialog({
                        title: 'Pleas wait',
                        message : '<div class="alert alert-info" role="alert">Calculating ...</div>'
                    });
    return true;
}
var btnBlur = document.getElementById('blur');
btnBlur.onchange = function() {
    if (!filterLoading()) return;

    var f = fabric.Image.filters;    
    var filter = new f.Convolute({
                                  matrix: [ .1,  .1,  .1,
                                            .1,  .2,  .1,
                                            .1,  .1,  .1 ]
                                });       
    filter = this.checked && filter;    
    setTimeout(function() { applyFilter(0, filter); }, 500);
    setTimeout(function() { bootbox.hideAll(); }, 500);
}
var btnSharpen = document.getElementById('sharpen');
btnSharpen.onchange = function() {
    if (!filterLoading()) return;

    var f = fabric.Image.filters;    
    var filter = new f.Convolute({
                                    matrix: [   0, -1,  0,
                                               -1,  5, -1,
                                                0, -1,  0 ]
                                });
    filter = this.checked && filter;
    setTimeout(function() { applyFilter(1, filter); }, 500);
    setTimeout(function() { bootbox.hideAll(); }, 500);
}
var btnEmboss = document.getElementById('emboss');
btnEmboss.onchange = function() {
    if (!filterLoading()) return;

    var f = fabric.Image.filters;
    var filter = new f.Convolute({  opaque: true,
                                    matrix: [ -2, -1,  0,
                                              -1,  1,  1,
                                               0,  1,  2 ]
                                });
    filter = this.checked && filter;
    setTimeout(function() { applyFilter(2, filter); }, 500);
    setTimeout(function() { bootbox.hideAll(); }, 500);
}
var btnGrascale = document.getElementById('grayscale');
btnGrascale.onchange = function() {
    if (!filterLoading()) return;
    var f = fabric.Image.filters;
    var filter = new f.Grayscale();
    filter = this.checked && filter;
    setTimeout(function() { applyFilter(3, filter); }, 500);
    setTimeout(function() { bootbox.hideAll(); }, 500);  
}

function applyFilter(index, filter) {
    var obj = canvas.getActiveObject();
    obj.filters[index] = filter;
    obj.applyFilters(canvas.renderAll.bind(canvas));
}
function applyFilterValue(index, prop, value, obj) {
    
    if (obj.filters[index]) {
      obj.filters[index][prop] = value;
      obj.applyFilters(canvas.renderAll.bind(canvas));
    }
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
    isFilterMode = false;
    toggleFilter();   
}

var el;
var object, lastActive, object1, object2;
var cntObj = 0;
var crop = false;
//button crop event
var btnCrop = document.getElementById('btn-crop');
btnCrop.onclick = function(){  

    //start crop
    if (!crop){
        canvas.remove(el);        

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

        el.left = 10;
        el.top = 10;
        el.width = 300;
        el.height = 300;

        canvas.add(el);
        canvas.setActiveObject(el);
        canvas.isDrawingMode = false;
        crop = true;

        btnCrop.setAttribute('title', 'OK');
        btnCrop.className = 'geo-button icon-ok';
              
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

//button color picker
var isShowPicker = true;
var btnPicker = document.getElementById('btn-color-picker');
btnPicker.onclick = function() {
    isShowPicker = !isShowPicker
    if (PICKER) PICKER.toggle(isShowPicker);        
}

//button convert to paint
//use Symmetric Nearest Neighbor
var btnSnn = document.getElementById('btn-snn');
btnSnn.onclick = function() {
    if (!filterLoading()) return;

    var obj = canvas.getActiveObject();

    var box = obj, //this is rect object
            boxTop = box.top,
            boxLeft = box.left;
           
    canvas.deactivateAll();

    var dataURL = obj.toDataURL();
    var img = new Image();
    img.src = dataURL;    
    
    setTimeout(function() { 
        var convertedURL = snn(img, 3);
        fabric.Image.fromURL(convertedURL, function(oImg) {
            //canvas.clear();
            canvas.remove(obj);
            oImg.set({top: boxTop ,left: boxLeft});
            canvas.add(oImg);
            canvas.renderAll();
            oImg.selectable = true;
        });
     
        canvas.renderAll();
    },
    500);

    setTimeout(function() { bootbox.hideAll(); }, 500);
}

//button bring to front
var btnBringToFront = document.getElementById('btn-bring-to-front');
btnBringToFront.onclick = function() {
    var obj = canvas.getActiveObject();
    if (!obj) return;

    obj.bringToFront();
}

//brush size slider
var lineWidthSlider = document.getElementById('brush-size-slider');
lineWidthSlider.onchange = function(){   
    setColor();    
}
lineWidthSlider.onmouseover = function(){
    this.title = this.value;
    this.previousElementSibling.innerHTML = ':' + this.value;
}
lineWidthSlider.onmousedown = function() {
    this.title = this.value;
    this.previousElementSibling.innerHTML = ':' + this.value;
}
lineWidthSlider.onmouseup = function(){
    this.title = this.value;
}
var btnPencil = document.getElementById('btn-pencil');
btnPencil.onclick = function(){
    canvas.isDrawingMode = true;
    isFilterMode = false;
    toggleFilter();
    canvas.freeDrawingBrush = new fabric['PencilBrush'](canvas);
    setColor();
}
var btnBrush = document.getElementById('btn-brush');
btnBrush.onclick = function(){
    canvas.isDrawingMode = true;
    isFilterMode = false;
    toggleFilter();
    canvas.freeDrawingBrush = new fabric['CircleBrush'](canvas);
    setColor();
}
//use fabricjs-painter
var btnSpray = document.getElementById('btn-spray');
btnSpray.onclick = function(data){
    canvas.isDrawingMode = false;
    isFilterMode = false;
    toggleFilter();
    isPainterOn = true;
    setColor();
}

function setColor(){    
    var activeObject = canvas.getActiveObject();
    var theWidth = parseInt(lineWidthSlider.value, 10);
    lineWidthSlider.previousElementSibling.innerHTML = ':' + theWidth;    

    if (canvas.freeDrawingBrush) {
        canvas.freeDrawingBrush.color = COLOR;        
        canvas.freeDrawingBrush.width = theWidth;  
        painter.brush_globals.prop('size', theWidth);       
        fabricPainter.brush_globals.prop('color', COLOR);
    }
    if (activeObject !== null && activeObject !== undefined) {        
        activeObject.set('fill', COLOR);
        canvas.renderAll();        
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

            if (isFilterMode) {
                for (var i = 0; i < filters.length; i++) {
                    document.getElementById(filters[i]).disabled = true;
                    document.getElementById(filters[i]).checked = false;
                }
            }
            return;
        }
   }
};

var shirtArray = [];
//shirt canvas
var splitLineScreen = [];
var finalLineScreen = [];
var side_1 = {width: 190, height: 280, top: 160, left: 118, offsetTop: 0};
var side_2 = {width: 190, height: 280, top: 160, left: 545, offsetTop: 0};
var side_cal_1 = {
                    width: side_1.width, 
                    height: 0, 
                    top: side_1.top + side_1.offsetTop, 
                    left: side_1.left, 
                    right: side_1.width + side_1.left
                };
var side_cal_2 = {
                    width: side_2.width, 
                    height: 0, 
                    top: side_2.top + side_2.offsetTop, 
                    left: side_2.left, 
                    right: side_2.width + side_2.left
                };
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

function loadShirt(type_1, type_2){ 
   
    if (!isShirtMode) return;

    var txtHeight1 = parseInt(document.getElementById('txt-height-1').value);
    var txtHeight2 = parseInt(document.getElementById('txt-height-2').value);

    var offset = Math.abs(txtHeight1 - txtHeight2);
    if (txtHeight1 > txtHeight2) {
        //person on the left taller than right
        side_1.offsetTop = 0;        
        side_2.offsetTop = offset
        side_cal_1.height =  side_1.height - side_2.offsetTop;        
        side_cal_1.top = side_1.top + offset;

        side_cal_2.height = side_cal_1.height;
        side_cal_2.top = side_cal_1.top;
    } else if( txtHeight1 < txtHeight2) {
        //person on the right taller left       
        side_1.offsetTop = offset
        side_2.offsetTop = 0;
        side_cal_2.height = side_2.height - side_1.offsetTop;
        side_cal_2.top = side_2.top + offset;

        side_cal_1.height = side_cal_2.height;
        side_cal_1.top = side_cal_2.top;
    } else if (txtHeight1 === txtHeight2) {
        side_1.offsetTop = 0;
        side_2.offsetTop = 0;

        side_cal_1.height = side_1.height;
        side_cal_1.top = side_1.top;

        side_cal_2.height = side_2.height;
        side_cal_2.top = side_2.top;
    }

    if (type_1 === undefined) type_1 = roundType.type_m;
    if (type_2 === undefined) type_2 = roundType.type_w;

    shirtCanvas.clear();
    shirtArray.length = 0;
    shirtArray = [];
    finalLineScreen = [];

    var rectStroke = 'rgba(0, 0, 0, 0)'; //'rgba(128, 128, 128, 0.2)';
    //shirt 1
    //men
    if (type_1) {        
        var rectBorder = new fabric.Rect({
            width: side_1.width,
            height: side_1.height,
            top: side_1.top + side_1.offsetTop,
            left: side_1.left,
            strokeWidth: 1,
            fill: 'rgba(0, 0, 0, 0)',
            stroke: rectStroke,
            selectable: false
        });
       
        shirtCanvas.add(rectBorder);
        borderShirt1 = rectBorder;

        fabric.Image.fromURL(type_1.uri, function(oImg) {            
            oImg.set({id: 'shirt1',width: 417, height: 471, top: 30 + side_1.offsetTop, left: 3}); 
            shirtCanvas.add(oImg);
            //oImg.selectable = true;    

            shirtCanvas.sendToBack(oImg);
            shirtArray.push(oImg);
        });
        
        fabric.Image.fromURL(splitLineScreen[0], function(oImg) {        
            //oImg.set({top: side_1.top + side_1.offsetTop + 3 ,left: side_1.left + 3, scaleX: 100/oImg.width, scaleY: 141/oImg.height});
            oImg.set({top: side_1.top + side_1.offsetTop + 3 ,left: side_1.left + 3, width: 100, height: 141});

            shirtCanvas.add(oImg);            
            
            oImg.set({selectable: true, hasRotatingPoint: false, lockUniScaling: true, sideOfCanvas: 'left', goodTop: oImg.top, goodLeft: oImg.left, goodScaleX: 1, goodScaleY: 1});           
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
            top: side_2.top + side_2.offsetTop,
            left: side_2.left,
            strokeWidth: 1,
            fill: 'rgba(0, 0, 0, 0)',
            stroke: rectStroke,
            selectable: false
        });
       
        shirtCanvas.add(rectBorder2);
        borderShirt2 = rectBorder2;

        fabric.Image.fromURL(type_2.uri, function(oImg) {           
            oImg.set({id: 'shirt2', width: 417, height: 471, top: 35 + side_2.offsetTop, left: 430}); 
            shirtCanvas.add(oImg);
            //oImg.selectable = true;      

            shirtCanvas.sendToBack(oImg);
            shirtArray.push(oImg);
        });
        
        fabric.Image.fromURL(splitLineScreen[1], function(oImg) {        
            //oImg.set({top: side_2.top + side_2.offsetTop + 3 ,left: side_2.left + 3, scaleX: 100/oImg.width, scaleY: 141/oImg.height});
            oImg.set({top: side_2.top + side_2.offsetTop + 3 ,left: side_2.left + 3, width: 100, height: 141});
            shirtCanvas.add(oImg);

            oImg.set({selectable: true, hasRotatingPoint: false, lockUniScaling: true, sideOfCanvas: 'right', goodTop: oImg.top, goodLeft: oImg.left, goodScaleX: 1, goodScaleY: 1});
            shirtCanvas.bringToFront(oImg);
            shirtCanvas.renderAll();
            finalLineScreen.push(oImg);
        });
    }          
}

function scaleToFit() {    
    
    finalLineScreen.forEach(function(obj) {
        scaling(obj);       

        while (!adjustPosition(obj)) {
            console.log('call adjustPosition');
            adjustPosition(obj);            
        }

        //snap to the righ side
        if (obj.sideOfCanvas === 'left') {
            var offset = parseInt(Math.abs(obj.left + obj.currentWidth - side_cal_1.right));
            obj.set({left: obj.left + offset});
            obj.setCoords();
        } else {
            //do nothing
        }
        
    });

    shirtCanvas.renderAll();
}

function scaling(obj) {
    var side = undefined;

    if (obj.sideOfCanvas === 'left') {
        side = side_cal_1;
    } else {
        side = side_cal_2;
    }

    var scaleFactor = 0.01;
    while ((obj.currentWidth < side.width) && (obj.currentHeight < side.height)) {
        obj.set({scaleX: obj.scaleX + scaleFactor, scaleY: obj.scaleY + scaleFactor, left: side.left + 3, top: side.top + 3});
        scaleFactor += 0.01;
        obj.setCoords();
    }
    while ((obj.currentWidth > side.width) || (obj.currentHeight > side.height)) {
        scaleFactor -= 0.01;
        obj.set({scaleX: obj.scaleX + scaleFactor, scaleY: obj.scaleY + scaleFactor, left: side.left + 3, top: side.top + 3});
        obj.setCoords();
    }
}

function adjustPosition(obj) {   

    var TL, BR;
    if (obj.sideOfCanvas === 'left') {
        //bounds = borderShirt1;
        TL = new fabric.Point(side_cal_1.left, side_cal_1.top);
        BR = new fabric.Point(side_cal_1.left + side_cal_1.width, side_cal_1.top + side_cal_1.height);
    } else if (obj.sideOfCanvas === 'right') {
        //bounds = borderShirt2;
        TL = new fabric.Point(side_cal_2.left, side_cal_2.top);
        BR = new fabric.Point(side_cal_2.left + side_cal_2.width, side_cal_2.top + side_cal_2.height);
    }

    obj.setCoords();
    // if(!obj.isContainedWithinObject(bounds)){ 

    if (!obj.isContainedWithinRect(TL, BR)) {
        obj.set({ scaleX: obj.goodScaleX, scaleY: obj.goodScaleY, left: obj.goodLeft, top: obj.goodTop });
        return false;
                         
    } else {
        obj.goodTop = obj.top;
        obj.goodLeft = obj.left;
        obj.goodScaleX = obj.scaleX;
        obj.goodScaleY = obj.scaleY;
        return true;
    }  
}

function splitCanvas() {
    splitLineScreen = [];
    splitLineScreen.length = 0;

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

    canvas.on('object:selected', function(e) { 
        if (isFilterMode) {            
            for (var i = 0; i < filters.length; i++) {
                if (e.target.filters) {
                    document.getElementById(filters[i]).disabled = false;
                    document.getElementById(filters[i]).checked = !!canvas.getActiveObject().filters[i];
                } else {
                    document.getElementById(filters[i]).disabled = true;
                    document.getElementById(filters[i]).checked = false;
                }
            }
        }
    });
    canvas.on('selection:cleared', function() {
        if (isFilterMode) {           
            for (var i = 0; i < filters.length; i++) {
                document.getElementById(filters[i]).disabled = true;
            }
        }
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
                obj.setTop(obj.goodTop);
                obj.setLeft(obj.goodLeft);                
            } else {
                obj.goodTop = obj.top;
                obj.goodLeft = obj.left;
            }
        }
    });
    //set scaleing limit    
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
                obj.set({scaleX: obj.goodScaleX, scaleY: obj.goodScaleY, left: obj.goodLeft, top: obj.goodTop});
                                 
            } else {
                obj.goodTop = obj.top;
                obj.goodLeft = obj.left;
                obj.goodScaleX = obj.scaleX;
                obj.goodScaleY = obj.scaleY;
            }
        }
    });

    shirtCanvas.on('object:selected', function(e) {
        var bound;
        if (e.target.sideOfCanvas === 'left') {
            bound = borderShirt1;
        } else {
            bound = borderShirt2;
        }

        bound.set('stroke', 'rgba(128, 128, 128, 0.3)');
    });
    shirtCanvas.on('selection:cleared', function() {
        if (borderShirt1) borderShirt1.set('stroke', 'rgba(0, 0, 0, 0)');
        if (borderShirt2) borderShirt2.set('stroke', 'rgba(0, 0, 0, 0)');       
    });

    btnSelect.onclick();
    toggleMode();
   
}//init
init();

function snn(img, radius) {
var _ctx = document.createElement('canvas').getContext('2d');
var ctx = document.createElement('canvas').getContext('2d');
var imgdata;

ctx.canvas.width = img.width;
ctx.canvas.height = img.height;
ctx.drawImage(img, 0, 0);
imgdata = ctx.getImageData(0, 0, img.width, img.height);

var data = imgdata;
var srcimg = data,
    w = srcimg.width,
    h = srcimg.height,
    r = parseInt(radius),
    div = 1.0/((2*r + 1)*(2*r + 1)),
    srcdata = srcimg.data,
    dstimg = _ctx.createImageData(w, h),
    dstdata = dstimg.data,
    sumr, sumg, sumb,
    rc, gc, bc, r1, g1, b1, r2, g2, b2,
    pv, pu, xystep, uvstep, delta1, delta2,
    i, j;

for(var y=0; y<h; y++) {
    xystep = y*w;
    for(var x=0; x<w; x++) {
      i = (xystep + x) << 2;
      sumr = 0, sumg = 0, sumb = 0;
      rc = srcdata[i];
      gc = srcdata[i + 1];
      bc = srcdata[i + 2];
      for(var v=-r; v<=r; v++) {
        uvstep = w*v;
        for(var u=-r; u<=r; u++) {
          j = (uvstep + u) << 2;
          if(srcdata[i + j]) {
            r1 = srcdata[i + j];
            g1 = srcdata[i + j + 1];
            b1 = srcdata[i + j + 2];
          } else {
            r1 = srcdata[i];
            g1 = srcdata[i + 1];
            b1 = srcdata[i + 2];
          }
          if(srcdata[i - j]) {
            r2 = srcdata[i - j];
            g2 = srcdata[i - j + 1];
            b2 = srcdata[i - j + 2];
          } else {
            r2 = srcdata[i];
            g2 = srcdata[i + 1];
            b2 = srcdata[i + 2];
          }
          delta1 = Math.sqrt((rc - r1)*(rc - r1) +
                             (gc - g1)*(gc - g1) +
                             (bc - b1)*(bc - b1));
          delta2 = Math.sqrt((rc - r2)*(rc - r2) +
                             (gc - g2)*(gc - g2) +
                             (bc - b2)*(bc - b2));
          if(delta1 < delta2) {
            sumr += r1;
            sumg += g1;
            sumb += b1;
          } else {
            sumr += r2;
            sumg += g2;
            sumb += b2;
          }
        }
      }
      dstdata[i] = sumr*div;
      dstdata[i + 1] = sumg*div;
      dstdata[i + 2] = sumb*div;
      dstdata[i + 3] = 255;
    }
}
    //return dstimg;

    var c = document.createElement('canvas');
    c.width = img.width;
    c.height = img.height;
    c.getContext('2d').putImageData(dstimg, 0, 0);
   
    return c.toDataURL();
}