// Initialize croquis
var svgNS = "http://www.w3.org/2000/svg";
var croquis = new Croquis();
var paintWidth = 849;
var paintHeight = 600;
croquis.lockHistory();
croquis.setCanvasSize(paintWidth, paintHeight); //ratio 1:1.4142
croquis.addLayer();
croquis.fillLayer('#fff');
croquis.addLayer();
croquis.selectLayer(1);
croquis.unlockHistory();

var brush = new Croquis.Brush();
brush.setSize(40);
brush.setColor('#000');
brush.setSpacing(0.2);

croquis.setTool(brush);
croquis.setToolStabilizeLevel(10);
croquis.setToolStabilizeWeight(0.5);

var croquisDOMElement = croquis.getDOMElement();
var canvasArea = document.getElementById('canvas-area');
canvasArea.appendChild(croquisDOMElement);

//resize and drag canvas
var the_canvas = document.createElement('canvas');
var w = croquis.getCanvasWidth();
var h = croquis.getCanvasHeight();
the_canvas.width =  w;
the_canvas.height = h;
the_canvas.style.setProperty('width', w + 'px');
the_canvas.style.setProperty('height', h + 'px');  
the_canvas.id = "active"; 
canvasArea.appendChild(the_canvas);
var canvasState = new CanvasState(the_canvas);

function canvasPointerDown(e) {
    setPointerEvent(e);
    var pointerPosition = getRelativePosition(e.clientX, e.clientY);
    if (pointerEventsNone)
        canvasArea.style.setProperty('cursor', 'none');
    if (e.pointerType === "pen" && e.button == 5)
        croquis.setPaintingKnockout(true);
    croquis.down(pointerPosition.x, pointerPosition.y, e.pointerType === "pen" ? e.pressure : 1);
    document.addEventListener('pointermove', canvasPointerMove);
    document.addEventListener('pointerup', canvasPointerUp);

}
function canvasPointerMove(e) {
    setPointerEvent(e);
    var pointerPosition = getRelativePosition(e.clientX, e.clientY);
    croquis.move(pointerPosition.x, pointerPosition.y, e.pointerType === "pen" ? e.pressure : 1);
}
function canvasPointerUp(e) {
    setPointerEvent(e);
    var pointerPosition = getRelativePosition(e.clientX, e.clientY);
    if (pointerEventsNone)
        canvasArea.style.setProperty('cursor', 'crosshair');
    croquis.up(pointerPosition.x, pointerPosition.y, e.pointerType === "pen" ? e.pressure : 1);
    if (e.pointerType === "pen" && e.button == 5)
        setTimeout(function() {croquis.setPaintingKnockout(selectEraserCheckbox.checked)}, 30);//timeout should be longer than 20 (knockoutTickInterval in Croquis)
    document.removeEventListener('pointermove', canvasPointerMove);
    document.removeEventListener('pointerup', canvasPointerUp);
}
function getRelativePosition(absoluteX, absoluteY) {
    var rect = croquisDOMElement.getBoundingClientRect();
    return {x: absoluteX - rect.left, y: absoluteY - rect.top};
}
croquisDOMElement.addEventListener('pointerdown', canvasPointerDown);

//clear & fill button event onclick
var clearButton = document.getElementById('clear-button');
clearButton.onclick = function () {   
    croquis.clearLayer();
}

//fill button onclick event
var fillButton = document.getElementById('fill-button');
fillButton.onclick = function () {
    var rgb = tinycolor(brush.getColor()).toRgb();
    croquis.fillLayer(tinycolor({r: rgb.r, g: rgb.g, b: rgb.b,
       a: croquis.getPaintingOpacity()}).toRgbString()); 
   
}

//button flip event
var flipHorizon = document.getElementById('btn-flip-horizon');
flipHorizon.onclick = function(){
    var currentLayerIndex = croquis.getCurrentLayerIndex();
    var currentLayer = croquis.getLayerCanvas(currentLayerIndex);    
    var context = currentLayer.getContext('2d');

    var tmpCanvas = document.createElement('canvas');
    tmpCanvas.width = paintWidth;
    tmpCanvas.height = paintHeight;
    tmpCanvas.getContext('2d').drawImage(currentLayer, 0, 0);

    croquis.clearLayer(currentLayerIndex);
    context.save(); // Save the current state
    context.scale(-1, 1); // Set scale to flip the image
    context.drawImage(tmpCanvas, paintWidth * -1, 0, paintWidth, paintHeight);
    context.restore(); // Restore the last saved state
}
var flipVertical = document.getElementById('btn-flip-vertical');
flipVertical.onclick = function(){
    var currentLayerIndex = croquis.getCurrentLayerIndex();
    var currentLayer = croquis.getLayerCanvas(currentLayerIndex);
    var context = currentLayer.getContext('2d');

    var tmpCanvas = document.createElement('canvas');
    tmpCanvas.width = paintWidth;
    tmpCanvas.height = paintHeight;
    tmpCanvas.getContext('2d').drawImage(currentLayer, 0, 0);

    croquis.clearLayer(currentLayerIndex);    
    context.save(); // Save the current state
    context.scale(1, -1); // Set scale to flip the image
    context.drawImage(tmpCanvas, 0, paintHeight * -1, paintWidth, paintHeight);
    context.restore(); // Restore the last saved state
}

//upload file
var imageLoader = document.getElementById('upload-button');
imageLoader.addEventListener('change', handleImage, false);

function handleImage(e){
    var reader = new FileReader();
    reader.onload = function(event){
        var img = new Image();       
        img.src = event.target.result;
        // img.className = 'resize-image';        
        // croquisDOMElement.appendChild(img);    
        // resizeableImage(img);
        if (img.complete){
            canvasState.addShape(new Shape(canvasState, 10, 10, img.width, img.height, 1, img));   
            switchToActive();
        }else{
            alert('invalid image !!!');
            return;
        }
    }
    reader.readAsDataURL(e.target.files[0]);     
}

//merge layer and image
//button onclick event
var btnMerge = document.getElementById('merge-button');
btnMerge.onclick = function (){  
    
    switchToPaint(); 

    setTimeout(function(){ 
    
        var currentLayer = croquis.getLayerCanvas(croquis.getCurrentLayerIndex());    
        var context = currentLayer.getContext('2d');
        var tempCanvas = document.createElement('canvas');
        tempCanvas.width = paintWidth;
        tempCanvas.height = paintHeight;        

        tempCanvas.getContext('2d').drawImage(canvasState.canvas, 0, 0);
        canvasState.clear();
        canvasState.shapes = [];

        tempCanvas.getContext('2d').drawImage(currentLayer, 0, 0);
        croquis.clearLayer();
        context.drawImage(tempCanvas, 0, 0);        
    }, 
    50);
}

function deleteResizeableImage(){  
    //delete all
    var resizeContainer = document.getElementsByClassName('resize-container');
    while (resizeContainer.length > 0){
        var index = resizeContainer.length - 1;
        resizeContainer[index].remove();
    }    
}

//brush images
var circleBrush = document.getElementById('circle-brush');
var brushImages = document.getElementsByClassName('brush-image');
var currentBrush = circleBrush;

Array.prototype.map.call(brushImages, function (brush) {
    brush.addEventListener('pointerdown', brushImagePointerDown);
});

function brushImagePointerDown(e) {
    switchToPaint();
    var image = e.currentTarget;
    currentBrush.className = 'brush-image';
    image.className = 'brush-image on';
    currentBrush = image;
    if (image == circleBrush)
        image = null;
    brush.setImage(image);
    updatePointer();
}

// checking pointer-events property support
var pointerEventsNone = document.documentElement.style.pointerEvents !== undefined;

//brush pointer
var brushPointerContainer = document.createElement('div');
brushPointerContainer.className = 'brush-pointer';

if (pointerEventsNone) {
    croquisDOMElement.addEventListener('pointerover', function () {
        croquisDOMElement.addEventListener('pointermove', croquisPointerMove);
        document.body.appendChild(brushPointerContainer);
    });
    croquisDOMElement.addEventListener('pointerout', function () {
        croquisDOMElement.removeEventListener('pointermove', croquisPointerMove);
        brushPointerContainer.parentElement.removeChild(brushPointerContainer);
    });
}

function croquisPointerMove(e) {
    if (pointerEventsNone) {
        var x = e.clientX + window.pageXOffset;
        var y = e.clientY + window.pageYOffset;
        brushPointerContainer.style.setProperty('left', x + 'px');
        brushPointerContainer.style.setProperty('top', y + 'px');
    }
}

function updatePointer() {
    if (pointerEventsNone) {
        var image = currentBrush;
        var threshold;
        if (currentBrush == circleBrush) {
            image = null;
            threshold = 0xff;
        }
        else {
            threshold = 0x30;
        }
        var brushPointer = Croquis.createBrushPointer(
            image, brush.getSize(), brush.getAngle(), threshold, true);
        brushPointer.style.setProperty('margin-left',
            '-' + (brushPointer.width * 0.5) + 'px');
        brushPointer.style.setProperty('margin-top',
            '-' + (brushPointer.height * 0.5) + 'px');
        brushPointerContainer.innerHTML = '';
        brushPointerContainer.appendChild(brushPointer);
    }
}
updatePointer();

//color picker
var halfThumbRadius = 7.5;              
var sbSize = 150;                       
var colorPickerHueSlider = document.getElementById('color-picker-hue-slider');
var colorPickerSb = document.getElementById('color-picker-sb');
var colorPickerHSBRect = new HSBRect(150, 150);
colorPickerHSBRect.DOMElement.id = 'color-picker-hsbrect';
colorPickerSb.appendChild(colorPickerHSBRect.DOMElement);
var colorPickerThumb = document.createElement('div');
colorPickerThumb.id = 'color-picker-thumb';
colorPickerSb.appendChild(colorPickerThumb);
colorPickerHueSlider.value = tinycolor(brush.getColor()).toHsv().h;

///color in hex value
var colorPickerColor = document.getElementById('color-picker-color');
var inputHexColor = document.getElementById('input-hex-color');

///input hex color on lost focus
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

///init run
pickColor(0, 150);

function setColor() {    
    var h = colorPickerHueSlider.value;
    var s = parseFloat(colorPickerThumb.style.getPropertyValue('margin-left'));
    var b = parseFloat(colorPickerThumb.style.getPropertyValue('margin-top'));
    s = (s + halfThumbRadius) / sbSize;
    b = 1 - ((b + halfThumbRadius + sbSize) / sbSize);
    brush.setColor(tinycolor({h: h, s:s, v: b}).toRgbString());
    var a = croquis.getPaintingOpacity();
    var color = tinycolor({h: h, s:s, v: b, a: a});    
    inputHexColor.value = color.toHexString();

    var colorPickerChecker = document.getElementById('color-picker-checker');
    var rgbaColor = color.toRgb();
    colorPickerChecker.style.backgroundColor = 
    'rgba(' + rgbaColor.r + ', ' + rgbaColor.g + ', ' + rgbaColor.b + ', ' + rgbaColor.a + ')';    
}

colorPickerHueSlider.onchange = function () {
    colorPickerHSBRect.hue = colorPickerHueSlider.value;
    setColor();
}

function colorPickerPointerDown(e) {
    document.addEventListener('pointermove', colorPickerPointerMove);
    colorPickerPointerMove(e);
}
function colorPickerPointerUp(e) {
    document.removeEventListener('pointermove', colorPickerPointerMove);
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
colorPickerSb.addEventListener('pointerdown', colorPickerPointerDown);
document.addEventListener('pointerup', colorPickerPointerUp);

//stabilizer shelf
var toolStabilizeLevelSlider =
    document.getElementById('tool-stabilize-level-slider');
var toolStabilizeWeightSlider =
    document.getElementById('tool-stabilize-weight-slider');
toolStabilizeLevelSlider.value = croquis.getToolStabilizeLevel();
toolStabilizeWeightSlider.value = croquis.getToolStabilizeWeight() * 100;

//brush shelf
var selectEraserCheckbox =
    document.getElementById('select-eraser-checkbox');
var brushSizeSlider = document.getElementById('brush-size-slider');
var brushOpacitySlider = document.getElementById('brush-opacity-slider');
var brushFlowSlider = document.getElementById('brush-flow-slider');
var brushSpacingSlider = document.getElementById('brush-spacing-slider');
var brushAngleSlider = document.getElementById('brush-angle-slider');
var brushRotateToDirectionCheckbox = document.getElementById('brush-rotate-to-direction-checkbox');
brushSizeSlider.value = brush.getSize();
brushOpacitySlider.value = croquis.getPaintingOpacity() * 100;
brushFlowSlider.value = brush.getFlow() * 100;
brushSpacingSlider.value = brush.getSpacing() * 100;
brushAngleSlider.value = brush.getAngle();
brushRotateToDirectionCheckbox.checked = brush.getRotateToDirection();

toolStabilizeLevelSlider.onchange = function () {
    croquis.setToolStabilizeLevel(toolStabilizeLevelSlider.value);
    toolStabilizeLevelSlider.value = croquis.getToolStabilizeLevel();
}
toolStabilizeWeightSlider.onchange = function () {
    croquis.setToolStabilizeWeight(toolStabilizeWeightSlider.value * 0.01);
    toolStabilizeWeightSlider.value = croquis.getToolStabilizeWeight() * 100;
}

selectEraserCheckbox.onchange = function () {
    croquis.setPaintingKnockout(selectEraserCheckbox.checked);
}
brushSizeSlider.onchange = function () {
    brush.setSize(brushSizeSlider.value);
    updatePointer();
}
brushOpacitySlider.onchange = function () {
    croquis.setPaintingOpacity(brushOpacitySlider.value * 0.01);
    setColor();
}
brushFlowSlider.onchange = function () {
    brush.setFlow(brushFlowSlider.value * 0.01);
}
brushSpacingSlider.onchange = function () {
    brush.setSpacing(brushSpacingSlider.value * 0.01);
}
brushAngleSlider.onchange = function () {
    brush.setAngle(brushAngleSlider.value);
    updatePointer();
}
brushRotateToDirectionCheckbox.onchange = function () {
    brush.setRotateToDirection(brushRotateToDirectionCheckbox.checked);
}

// Platform variables
var mac = navigator.platform.indexOf('Mac') >= 0;

//keyboard
document.addEventListener('keydown', documentKeyDown);
function documentKeyDown(e) {    
    if (mac ? e.metaKey : e.ctrlKey) {
        switch (e.keyCode) {
        case 89: //ctrl + y
            croquis.redo();
            break;
        case 90: //ctrl + z
            croquis[e.shiftKey ? 'redo' : 'undo']();
            break;
        }
    }else{
        if (e.keyCode === 46){
            deleteResizeableImage();
        }
    }
}

function setPointerEvent(e) {
    if (e.pointerType !== "pen" && Croquis.Tablet.pen() && Croquis.Tablet.pen().pointerType) {//it says it's not a pen but it might be a wacom pen
        e.pointerType = "pen";
        e.pressure = Croquis.Tablet.pressure();
        if (Croquis.Tablet.isEraser()) {
            Object.defineProperties(e, {
                "button": { value: 5 },
                "buttons": { value: 32 }
            });
        }
    }
}

//=================================================================================================================
///create svg image
//=================================================================================================================
function switchToActive(){
    var activeLayer = document.getElementById('active');
    if (activeLayer === null) return;
    activeLayer.style.setProperty('z-index', 2);

    var paintCanvas = document.getElementsByClassName('croquis-painting-canvas');
    paintCanvas[0].style.setProperty('z-index', 1);
}
function switchToPaint(){
    var activeLayer = document.getElementById('active');
    if (activeLayer === null) return;
    activeLayer.style.setProperty('z-index', 1);
    
    //deselect object
    var event = new MouseEvent('mousedown', {
                                'view': window,
                                'bubbles': true,
                                'cancelable': true
                              });
    activeLayer.dispatchEvent(event);

    var paintCanvas = document.getElementsByClassName('croquis-painting-canvas');
    paintCanvas[0].style.setProperty('z-index', 2);
    
}

var btnSelector = document.getElementById('btn-selector');
btnSelector.onclick = function(){
    switchToActive();
}

var btnRect = document.getElementById('btn-rectangle');
btnRect.onclick = function(){
    
    var svg = createSVG();
    var elm = document.createElementNS(svgNS, 'rect');
    elm.setAttributeNS(null, 'fill', tinycolor(brush.getColor()).toHexString());   
    elm.setAttributeNS(null, 'width', '100px');
    elm.setAttributeNS(null, 'height', '100px');
    svg.appendChild(elm);

    insertGeoSVG(svg);
}
//create triangle svg
var btnTri = document.getElementById('btn-triangle');
btnTri.onclick = function(){

    var svg = createSVG();
    var elm = document.createElementNS(svgNS, 'polygon');
    elm.setAttributeNS(null, 'fill', tinycolor(brush.getColor()).toHexString());
    elm.setAttributeNS(null, 'points', "0,100 50,0 100,100");
    svg.appendChild(elm);

    insertGeoSVG(svg);    
}
//create circle or eclipse svg
var btnRound = document.getElementById('btn-round');
btnRound.onclick = function(){
    var svg = createSVG();
    var elm = document.createElementNS(svgNS, 'circle');
    elm.setAttributeNS(null, 'fill', tinycolor(brush.getColor()).toHexString());  
    elm.setAttributeNS(null, 'cx', '50px');
    elm.setAttributeNS(null, 'cy', '50px');
    elm.setAttributeNS(null, 'r', '45px');
    svg.appendChild(elm);
    insertGeoSVG(svg);
}
//create star svg
var btnStar = document.getElementById('btn-star');
btnStar.onclick = function(){
    var svg = createSVG();
    var elm = document.createElementNS(svgNS, 'polygon');
    elm.setAttributeNS(null, 'fill', tinycolor(brush.getColor()).toHexString());  
    elm.setAttributeNS(null, 'points', "50,5 20,99 95,39 5,39 80,99");
    svg.appendChild(elm);
    insertGeoSVG(svg);
}
//create heart svg
var btnHeart = document.getElementById('btn-heart');
btnHeart.onclick = function(){
    var svg = createSVG();
    var elm = document.createElementNS(svgNS, 'path');
    elm.setAttributeNS(null, 'fill', tinycolor(brush.getColor()).toHexString());  
    elm.setAttributeNS(null, 'd', "M67.607,13.462c-7.009,0-13.433,3.238-17.607,8.674c-4.174-5.437-10.598-8.674-17.61-8.674  c-12.266,0-22.283,10.013-22.33,22.32c-0.046,13.245,6.359,21.054,11.507,27.331l1.104,1.349  c6.095,7.515,24.992,21.013,25.792,21.584c0.458,0.328,1,0.492,1.538,0.492c0.539,0,1.08-0.165,1.539-0.492  c0.8-0.571,19.697-14.069,25.792-21.584l1.103-1.349c5.147-6.277,11.553-14.086,11.507-27.331  C89.894,23.475,79.876,13.462,67.607,13.462z");
    svg.appendChild(elm);
    insertGeoSVG(svg);
}

function insertGeoSVG(svg){

    var encoded = window.btoa(svg.outerHTML);
    var img = new Image();
    img.onload = function(){
        //img.className = 'resize-image';    
        img.style.opacity = croquis.getPaintingOpacity();  
    }
    img.src = 'data:image/svg+xml;base64,' + encoded;   
    
    canvasState.addShape(new Shape(canvasState, 10, 10, img.width, img.height, img.style.opacity, img));  
   
    switchToActive();
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

//Crop function
var btnCrop = document.getElementById('btn-crop');
btnCrop.onclick = function(){

    //create layer crop
    if (btnCrop.className === "geo-button icon-crop"){

        var cropLayer = document.getElementsByClassName('resize-image');
        if (cropLayer.length > 0) return;

        var svg = createSVG();
        svg.setAttribute('width', '100px');
        svg.setAttribute('height', '142px');

        var elm = document.createElementNS(svgNS, 'rect');
        elm.setAttributeNS(null, 'fill', 'rgba(187, 201, 159, 41)');   
        elm.setAttributeNS(null, 'width', '100px');
        elm.setAttributeNS(null, 'height', '142px');

        svg.appendChild(elm);

        var encoded = window.btoa(svg.outerHTML);
        var img = new Image();
        img.onload = function(){
            img.className = 'resize-image';
            img.style.opacity = 0.40;
        }        
        img.src = 'data:image/svg+xml;base64,' + encoded;   
        
        croquisDOMElement.appendChild(img);
        resizeableImage(img);

        btnCrop.className = "geo-button icon-ok";
    } else {
   
    //Find the part of the image that is inside the crop box   
    var currentLayer = croquis.getLayerCanvas(croquis.getCurrentLayerIndex());    
    var context = currentLayer.getContext('2d'); 
    var overlay = document.getElementsByClassName('resize-image')[0];
    var relativePosition = getRelativePosition(overlay.x, overlay.y);
    var crop_canvas,
        left = relativePosition.x,
        top =  relativePosition.y, 
        width = overlay.width,
        height = overlay.height;
    
    //dispatch merge layers event
    btnMerge.onclick();
    setTimeout(function(){
            crop_canvas = document.createElement('canvas');
            crop_canvas.width = paintHeight; 
            crop_canvas.height = paintWidth;
            
            crop_canvas.getContext('2d').drawImage(currentLayer, left, top, width, height, 0, 0, paintHeight, paintWidth);
            window.open(crop_canvas.toDataURL()); 

            btnCrop.className = "geo-button icon-crop";
            deleteResizeableImage();
        }, 50);    
    }
}


//=================================================================================================================
//resizeableImage
//=================================================================================================================
var resizeableImage = function(image_target) {
  // Some variable and settings
    var $container,
          orig_src = new Image(),
          image_target = image_target, //$(image_target).get(0),
          event_state = {},
          constrain = true,
          min_width = 100, // Change as required
          min_height = 141,
          max_width = 849, // Change as required
          max_height = 600,
          resize_canvas = document.createElement('canvas');

    init = function(){

        // When resizing, we will always use this copy of the original as the base   
        orig_src.src=image_target.src;

        // Wrap the image with the container and add resize handles
        $(image_target).wrap('<div class="resize-container" style="top:5px; left:5px;"></div>')
        .before('<span class="resize-handle resize-handle-nw"></span>')
        .before('<span class="resize-handle resize-handle-ne"></span>')
        .after('<span class="resize-handle resize-handle-se"></span>')
        .after('<span class="resize-handle resize-handle-sw"></span>');

        // Assign the container to a variable
        $container = $(image_target).parent('.resize-container');

        // Add events
        $container.on('mousedown touchstart', '.resize-handle', startResize);
        $container.on('mousedown touchstart', 'img', startMoving);       
    };    

    startResize = function(e){       
        e.preventDefault();
        e.stopPropagation();
        saveEventState(e);
        $(document).on('mousemove touchmove', resizing);
        $(document).on('mouseup touchend', endResize);
    };

    endResize = function(e){
        e.preventDefault();
        $(document).off('mouseup touchend', endResize);
        $(document).off('mousemove touchmove', resizing);
    };

    saveEventState = function(e){
        // Save the initial event details and container state
        event_state.container_width = $container.width();
        event_state.container_height = $container.height();
        event_state.container_left = $container.offset().left; 
        event_state.container_top = $container.offset().top;
        event_state.mouse_x = (e.clientX || e.pageX || e.originalEvent.touches[0].clientX) + $(window).scrollLeft(); 
        event_state.mouse_y = (e.clientY || e.pageY || e.originalEvent.touches[0].clientY) + $(window).scrollTop();

        // This is a fix for mobile safari
        // For some reason it does not allow a direct copy of the touches property
        if(typeof e.originalEvent.touches !== 'undefined'){
            event_state.touches = [];
            $.each(e.originalEvent.touches, function(i, ob){
              event_state.touches[i] = {};
              event_state.touches[i].clientX = 0+ob.clientX;
              event_state.touches[i].clientY = 0+ob.clientY;
            });
        }
        event_state.evnt = e;
    };

    var mainCanvas = croquis.getLayerCanvas(croquis.getCurrentLayerIndex());
    var cx = mainCanvas.width / 2;
    var cy = mainCanvas.height / 2;
    var offsetX = mainCanvas.offsetLeft;
    var offsetY = mainCanvas.offsetTop;
    var r = 0;

  resizing = function(e){
    var mouse={},width,height,left,top,offset=$container.offset();
    mouse.x = (e.clientX || e.pageX || e.originalEvent.touches[0].clientX) + $(window).scrollLeft(); 
    mouse.y = (e.clientY || e.pageY || e.originalEvent.touches[0].clientY) + $(window).scrollTop();

    // Position image differently depending on the corner dragged and constraints
    if( $(event_state.evnt.target).hasClass('resize-handle-se') ){
          width = mouse.x - event_state.container_left;
          height = mouse.y  - event_state.container_top;
          left = event_state.container_left;
          top = event_state.container_top;
    } else if($(event_state.evnt.target).hasClass('resize-handle-sw') ){
          width = event_state.container_width - (mouse.x - event_state.container_left);
          height = mouse.y  - event_state.container_top;
          left = mouse.x;
          top = event_state.container_top;
    } else if($(event_state.evnt.target).hasClass('resize-handle-nw') ){
          width = event_state.container_width - (mouse.x - event_state.container_left);
          height = event_state.container_height - (mouse.y - event_state.container_top);
          left = mouse.x;
          top = mouse.y;
          if(constrain || e.shiftKey){
            top = mouse.y - ((width / orig_src.width * orig_src.height) - height);
          }
    } else if($(event_state.evnt.target).hasClass('resize-handle-ne') ){
          width = mouse.x - event_state.container_left;
          height = event_state.container_height - (mouse.y - event_state.container_top);
          left = event_state.container_left;
          top = mouse.y;
        if(constrain || e.shiftKey){
            top = mouse.y - ((width / orig_src.width * orig_src.height) - height);
        }      
    }
    
    // Optionally maintain aspect ratio
    if(constrain || e.shiftKey){
      height = width / orig_src.width * orig_src.height;
    }

    if(width > min_width && height > min_height && width < max_width && height < max_height){
      // To improve performance you might limit how often resizeImage() is called
      resizeImage(width, height);  
      // Without this Firefox will not re-calculate the the image dimensions until drag end
      $container.offset({'left': left, 'top': top});
    }
  }

  resizeImage = function(width, height){
    resize_canvas.width = width;
    resize_canvas.height = height;
    resize_canvas.getContext('2d').drawImage(orig_src, 0, 0, width, height);   
    $(image_target).attr('src', resize_canvas.toDataURL("image/png"));     
  };

  startMoving = function(e){
    e.preventDefault();
    e.stopPropagation();
    saveEventState(e);
    $(document).on('mousemove touchmove', moving);
    $(document).on('mouseup touchend', endMoving);
  };

  endMoving = function(e){
    e.preventDefault();
    $(document).off('mouseup touchend', endMoving);
    $(document).off('mousemove touchmove', moving);
  };

  moving = function(e){
    var  mouse={}, touches;
    e.preventDefault();
    e.stopPropagation();
    
    touches = e.originalEvent.touches;
    
    mouse.x = (e.clientX || e.pageX || touches[0].clientX) + $(window).scrollLeft(); 
    mouse.y = (e.clientY || e.pageY || touches[0].clientY) + $(window).scrollTop();
    $container.offset({
      'left': mouse.x - ( event_state.mouse_x - event_state.container_left ),
      'top': mouse.y - ( event_state.mouse_y - event_state.container_top ) 
    });
    // Watch for pinch zoom gesture while moving
    if(event_state.touches && event_state.touches.length > 1 && touches.length > 1){
      var width = event_state.container_width, height = event_state.container_height;
      var a = event_state.touches[0].clientX - event_state.touches[1].clientX;
      a = a * a; 
      var b = event_state.touches[0].clientY - event_state.touches[1].clientY;
      b = b * b; 
      var dist1 = Math.sqrt( a + b );
      
      a = e.originalEvent.touches[0].clientX - touches[1].clientX;
      a = a * a; 
      b = e.originalEvent.touches[0].clientY - touches[1].clientY;
      b = b * b; 
      var dist2 = Math.sqrt( a + b );

      var ratio = dist2 /dist1;

      width = width * ratio;
      height = height * ratio;
      // To improve performance you might limit how often resizeImage() is called
      resizeImage(width, height);
    }
  };
  init();  
};