// Initialize croquis
var svgNS = "http://www.w3.org/2000/svg";
var croquis = new Croquis();
croquis.lockHistory();
croquis.setCanvasSize(1024, 600);
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

//clear & fill button ui
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

//upload file
var imageLoader = document.getElementById('upload-button');
imageLoader.addEventListener('change', handleImage, false);

function handleImage(e){
    var reader = new FileReader();
    reader.onload = function(event){
        var img = new Image();       
        img.src = event.target.result;
        img.className = 'resize-image';
        croquisDOMElement.appendChild(img);    
        resizeableImage(img);
    }
    reader.readAsDataURL(e.target.files[0]);     
}

//merge layer and image
//button onclick event
var mergeButton = document.getElementById('merge-button');
mergeButton.onclick = function (){
    var pic = new Image();
    pic = document.getElementsByClassName('resize-image');
    if (pic === undefined) return;
    
    var currentLayerIndex = croquis.getCurrentLayerIndex();
    var context = document.getElementsByClassName('croquis-layer-canvas')[currentLayerIndex].getContext('2d');

    for(var i = 0; i < pic.length; i++){
        var picRelativePosition = getRelativePosition(pic[i].x, pic[i].y);
        context.drawImage(pic[i], picRelativePosition.x, picRelativePosition.y); 
    }
    
    //delete image 
    var resizeContainer = document.getElementsByClassName('resize-container');
    while (resizeContainer.length > 0){
        var index = resizeContainer.length - 1;
        resizeContainer[index].remove();
    }

    imageLoader.value = '';
}

//brush images
var circleBrush = document.getElementById('circle-brush');
var brushImages = document.getElementsByClassName('brush-image');
var currentBrush = circleBrush;

Array.prototype.map.call(brushImages, function (brush) {
    brush.addEventListener('pointerdown', brushImagePointerDown);
});

function brushImagePointerDown(e) {
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
    //brush.setColor(color.toRgbString());

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
    //colorPickerColor.style.backgroundColor = color.toRgbString();
    //colorPickerColor.textContent = color.toHexString();
    inputHexColor.value = color.toHexString();

    var colorPickerChecker = document.getElementById('color-picker-checker');
    var rgbaColor = color.toRgb();
    colorPickerChecker.style.backgroundColor = 
    'rgba(' + rgbaColor.r + ', ' + rgbaColor.g + ', ' + rgbaColor.b + ', ' + rgbaColor.a + ')'; 
    //inputHexColor.value; //color.toHexString();
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

var backgroundCheckerImage;
(function () {
    backgroundCheckerImage = document.createElement('canvas');
    backgroundCheckerImage.width = backgroundCheckerImage.height = 20;
    var backgroundImageContext = backgroundCheckerImage.getContext('2d');
    backgroundImageContext.fillStyle = '#fff';
    backgroundImageContext.fillRect(0, 0, 20, 20);
    backgroundImageContext.fillStyle = '#ccc';
    backgroundImageContext.fillRect(0, 0, 10, 10);
    backgroundImageContext.fillRect(10, 10, 20, 20);
})();

// var colorPickerChecker = document.getElementById('color-picker-checker');
// colorPickerChecker.style.backgroundImage = 'url(' + backgroundCheckerImage.toDataURL() + ')';

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

///create rectangle svg
var btnRect = document.getElementById('btn-rectangle');
btnRect.onclick = function(){
    
    var rectElement = document.createElement(svgNS, 'rect');
    rectElement.setAttributeNS(null, 'fill', tinycolor(brush.getColor()).toHexString());
    rectElement.setAttributeNS(null, 'width', 150);
    rectElement.setAttributeNS(null, 'height', 150);
        
    var img = new Image(); 
    img.className = 'resize-image';
    img.src = svgToDataURL("image/svg+xml", undefined, rectElement);
    //img.src = svgToDataURL("image/png", undefined, svgElement);      
   
    croquisDOMElement.appendChild(img);  
    resizeableImage(img);   
}



//resizeableImage
var resizeableImage = function(image_target) {
  // Some variable and settings
  var $container,
      orig_src = new Image(),
      image_target = image_target, //$(image_target).get(0),
      event_state = {},
      constrain = false,
      min_width = 60, // Change as required
      min_height = 60,
      max_width = 800, // Change as required
      max_height = 900,
      resize_canvas = document.createElement('canvas');

  init = function(){

    // When resizing, we will always use this copy of the original as the base   
    orig_src.src=image_target.src;

    // Wrap the image with the container and add resize handles
    $(image_target).wrap('<div class="resize-container" style="top:10px; left:10px;"></div>')
    .before('<span class="resize-handle resize-handle-nw"></span>')
    .before('<span class="resize-handle resize-handle-ne"></span>')
    .after('<span class="resize-handle resize-handle-se"></span>')
    .after('<span class="resize-handle resize-handle-sw"></span>');

    // var divResizeContainer = document.createElement('div');
    // divResizeContainer.class = 'resize-container';
    // divResizeContainer.style.top = '10px';
    // divResizeContainer.style.left = '10px';

    // var handle1 = document.createElement('span');
    // var handle2 = document.createElement('span');
    // var handle3 = document.createElement('span');
    // var handle4 = document.createElement('span');

    // handle1.class = 'resize-handle resize-handle-nw';
    // divResizeContainer.appendChild(handle1);

    // handle2.class = 'resize-handle resize-handle-ne';
    // divResizeContainer.appendChild(handle2);

    // divResizeContainer.appendChild(image_target);

    // handle3.class = 'resize-handle resize-handle-se';
    // divResizeContainer.appendChild(handle3);

    // handle4.class = 'resize-handle resize-handle-sw';
    // divResizeContainer.appendChild(handle4);




      // Assign the container to a variable
    $container = $(image_target).parent('.resize-container');

    // Add events
    $container.on('mousedown touchstart', '.resize-handle', startResize);
    $container.on('mousedown touchstart', 'img', startMoving);
    $('.js-crop').on('click', crop);
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

  crop = function(){
    //Find the part of the image that is inside the crop box
    var crop_canvas,
        left = $('.overlay').offset().left - $container.offset().left,
        top =  $('.overlay').offset().top - $container.offset().top,
        width = $('.overlay').width(),
        height = $('.overlay').height();
        
    crop_canvas = document.createElement('canvas');
    crop_canvas.width = width;
    crop_canvas.height = height;
    
    crop_canvas.getContext('2d').drawImage(image_target, left, top, width, height, 0, 0, width, height);
    window.open(crop_canvas.toDataURL("image/png"));
  }

  init();
};