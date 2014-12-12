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
var canvas = new fabric.Canvas('c');  //normal event
this.__canvas = canvas;
var COLOR = undefined;
var OPACITY = '1';
var COLOR_RGB = undefined;
var COLOR_RGBA = undefined;

//color picker
var halfThumbRadius = 7.5;              
var sbSize = 150;                       
var colorPickerHueSlider = $('color-picker-hue-slider');
colorPickerHueSlider.value = tinycolor(COLOR).toHsv().h;

var colorPickerSb = $('color-picker-sb');
var colorOpacitySlider = $('brush-opacity-slider');
colorOpacitySlider.value = OPACITY * 100;

var colorPickerHSBRect = new HSBRect(150, 150);
colorPickerHSBRect.DOMElement.id = 'color-picker-hsbrect';
colorPickerSb.appendChild(colorPickerHSBRect.DOMElement);

var colorPickerThumb = document.createElement('div');
colorPickerThumb.id = 'color-picker-thumb';
colorPickerSb.appendChild(colorPickerThumb);

///color in hex value
var colorPickerColor = $('color-picker-color');
var inputHexColor = $('input-hex-color');
inputHexColor.onblur = function(){
    var color = tinycolor(inputHexColor.value);
    var hsvColor = color.toHsv();

    //set Hue slider
    colorPickerHueSlider.value = hsvColor.h;
    
    var s = (hsvColor.s * sbSize) - halfThumbRadius;
    var v = ((1 - hsvColor.v) * sbSize) - sbSize - halfThumbRadius;
    colorPickerThumb.style.setProperty('margin-left', s + 'px');
    colorPickerThumb.style.setProperty('margin-top', v + 'px');

    //force set color
    colorPickerHueSlider.onchange();
}

///run balck
pickColor(0, 150);

function setColor() {    
    var h = colorPickerHueSlider.value;
    var s = parseFloat(colorPickerThumb.style.getPropertyValue('margin-left'));
    var b = parseFloat(colorPickerThumb.style.getPropertyValue('margin-top'));
    s = (s + halfThumbRadius) / sbSize;
    b = 1 - ((b + halfThumbRadius + sbSize) / sbSize);
    COLOR = (tinycolor({h: h, s:s, v: b}).toRgbString());

    var a = parseFloat($('brush-opacity-slider').value /100);
    COLOR = tinycolor({h: h, s:s, v: b, a: a});    
    inputHexColor.value = COLOR.toHexString();

    var colorPickerChecker = $('color-picker-checker');
    COLOR_RGB = COLOR.toRgb();
    COLOR_RGBA = 'rgba(' + COLOR_RGB.r + ','+ COLOR_RGB.g + ',' + COLOR_RGB.b + ',' + COLOR_RGB.a + ')';
    colorPickerChecker.style.backgroundColor = COLOR_RGBA;
}

colorPickerHueSlider.onchange = function () {
    colorPickerHSBRect.hue = colorPickerHueSlider.value;
    setColor();
}

function colorPickerPointerDown(e) {
    document.addEventListener('mousemove', colorPickerPointerMove);
    colorPickerPointerMove(e);
}
function colorPickerPointerUp(e) {
    document.removeEventListener('mousemove', colorPickerPointerMove);
}
function colorPickerPointerMove(e) {
    var boundRect = colorPickerSb.getBoundingClientRect();
    var x = (e.clientX - boundRect.left);
    var y = (e.clientY - boundRect.top);
    pickColor(x, y);
}
function minmax(value, min, max) {
    return Math.min(max, Math.max(min, value));
}
function pickColor(x, y) {  
    colorPickerThumb.style.setProperty('margin-left',
        (minmax(x, 0, sbSize) - halfThumbRadius) + 'px');
    colorPickerThumb.style.setProperty('margin-top',
        (minmax(y, 0, sbSize) - (sbSize + halfThumbRadius)) + 'px');
    colorPickerThumb.style.setProperty('border-color',
        (y < sbSize * 0.5)? '#000' : '#fff');
    setColor();
}
colorOpacitySlider.onchange = function () {
    OPACITY = colorOpacitySlider.value * 0.01;
    setColor();
}
colorPickerSb.addEventListener('mousedown', colorPickerPointerDown);
document.addEventListener('mouseup', colorPickerPointerUp);

//=============================================================================================

var btnRect = $('btn-rectangle');
btnRect.onclick = function(){         

    var rect = new fabric.Rect({
        width: 100,
        height: 100,
        top: 10,
        left: 10,
        fill: COLOR_RGBA
    });
   
    canvas.add(rect);
    canvas.renderAll();
}
var btnTri = $('btn-triangle');
btnTri.onclick = function(){
    var triangle = new fabric.Triangle({
      width: 100, 
      height: 100, 
      fill: COLOR_RGBA, 
      left: 10, 
      top: 10
    });

    canvas.add(triangle);
    canvas.renderAll();
}
var btnRound = $('btn-round');
btnRound.onclick = function(){
    var circle = new fabric.Circle({
      radius: 50, 
      fill: COLOR_RGBA, 
      left: 10, 
      top: 10
    });

    canvas.add(circle);
    canvas.renderAll();
}
var btnStar = document.getElementById('btn-star');
btnStar.onclick = function(){
    var svg = createSVG();
    var elm = document.createElementNS(svgNS, 'polygon');
    elm.setAttributeNS(null, 'fill', COLOR_RGBA);  
    elm.setAttributeNS(null, 'points', "50,5 20,99 95,39 5,39 80,99");
    svg.appendChild(elm);
    insertGeoSVG(svg);
}
var btnHeart = document.getElementById('btn-heart');
btnHeart.onclick = function(){
    var svg = createSVG();
    var elm = document.createElementNS(svgNS, 'path');
    elm.setAttributeNS(null, 'fill', COLOR_RGBA);  
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
                fill: COLOR_RGBA
        });

        canvas.add(loadedObjects);
        canvas.renderAll();

        },function(item, object) {
                object.set('id',item.getAttribute('id'));
                group.push(object);
    });
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
        });
    }
    reader.readAsDataURL(e.target.files[0]);     
}

//init method
function init() { 


    
}

canvas.on('mouse:dblclick', function (options) {
    console.log('double click removed');
});

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