var cboGender1 = document.getElementById('cbo-gender-1');
var cboShirtType1 = document.getElementById('cbo-shirt-type-1');
var cboShirtColor1 = document.getElementById('cbo-shirt-color-1');

var cboChange = false;
cboShirtType1.onchange = function() { 
    cboChange = true;
    getShirtColor1();    
}
cboGender1.onchange = function() { 
    cboChange = true;
    getShirtType1(); 
}
cboShirtColor1.onchange =  function() { setShirtColor(); }

var cboGender2 = document.getElementById('cbo-gender-2');
var cboShirtType2 = document.getElementById('cbo-shirt-type-2');
var cboShirtColor2 = document.getElementById('cbo-shirt-color-2');

cboShirtType2.onchange = function() {
    cboChange = false;
    getShirtColor2();
}
cboGender2.onchange = function() { 
    cboChange = false;
    getShirtColor2(); 
}
cboShirtColor2.onchange = function() { setShirtColor(); }

function getURI1(shirtType) {    
    var uri;
    if (cboGender1.value === 'M'){
        switch (shirtType) {
        case 'คอกลม':
            uri = roundType.type_m;
            break;
        case 'คอวี':
            uri = vType.type_m;
            break;
        case 'คอโปโล':
            uri = poloType.type_m;
            break;
        }
    } else {
        switch (shirtType) {
        case 'คอกลม':
            uri = roundType.type_w;
            break;
        case 'คอวี':
            uri = vType.type_w;
            break;
        case 'คอโปโล':
            uri = poloType.type_w;
            break;
        }
    }

    return uri;
}
function getURI2(shirtType) {
    var uri;
    if (cboGender2.value === 'M'){
        switch (shirtType) {
        case 'คอกลม':
            uri = roundType.type_m;
            break;
        case 'คอวี':
            uri = vType.type_m;
            break;
        case 'คอโปโล':
            uri = poloType.type_m;
            break;
        }
    } else {
        switch (shirtType) {
        case 'คอกลม':
            uri = roundType.type_w;
            break;
        case 'คอวี':
            uri = vType.type_w;
            break;
        case 'คอโปโล':
            uri = poloType.type_w;
            break;
        }
    }
    return uri;
}
function getShirtType1() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "data/ManageShirts.data.php",
        data: {method: "getShirtTypeByGender", gender: cboGender1.value}       
    })
    .done(function(data) {
        if (data) {
            var text = '';
            data.forEach(function(item){
                text += "<option value=\""+ item.shirt_type +"\" >" + item.shirt_type + "</option>";    
            });

            $(cboShirtType1).html(text).selectpicker('refresh');           
            getShirtColor1();                    
        } else {
            Toast.init({
                "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on getShirtType1!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in getShirtType1 !!!</strong></div>'
        });
    });//fail
}
function getShirtType2() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "data/ManageShirts.data.php",
        data: {method: "getShirtTypeByGender", gender: cboGender2.value}       
    })
    .done(function(data) {
        if (data) {
            var text = '';
            data.forEach(function(item){
                text += "<option value=\""+ item.shirt_type +"\" >" + item.shirt_type + "</option>";    
            });

            $(cboShirtType2).html(text).selectpicker('refresh');
            getShirtColor2();                    
        } else {
            Toast.init({
                "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on get shirt getShirtType2!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in getShirtType2 !!!</strong></div>'
        });
    });//fail
}
var shirtColor1;
function getShirtColor1() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "data/ManageShirts.data.php",
        data: {method: "getShirtColor", gender: cboGender1.value, shirt_type: cboShirtType1.value}       
    })//post
    .done(function(data) {
        if (data) {
            var text = '';
            shirtColor1 = data;
            data.forEach(function(item){
                text += "<option data-content=\"<table style='width:100%; text-aligh:left;'><tr><td style='width: 50%;'>" +item.color+ "</td><td style='width: '50%'; text-aligh: right' bgcolor='" +item.color_hex+ "'></td></tr></table>\" ";
                text +="value=\"" +item.color_hex+ "\" >" +item.color+ "</option>";
            });

            $(cboShirtColor1).html(text).selectpicker('refresh');
            shirtCanvas.clear();
            loadShirt(getURI1(cboShirtType1.value), getURI2(cboShirtType2.value)); 
            getShirtSize1(); 
        } else {
            Toast.init({
                "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on get shirt color 1!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in getShirtColor1 !!!</strong></div>'
        });
    });//fail
}
var shirtColor2;
function getShirtColor2() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "data/ManageShirts.data.php",
        data: {method: "getShirtColor", gender: cboGender2.value, shirt_type: cboShirtType2.value}       
    })//post
    .done(function(data) {
        if (data) {
            var text = '';
            shirtColor2 = data;
            data.forEach(function(item){
                text += "<option data-content=\"<table style='width:100%; text-aligh:left;'><tr><td style='width: 50%;'>" +item.color+ "</td><td style='width: '50%'; text-aligh: right' bgcolor='" +item.color_hex+ "'></td></tr></table>\" ";
                text +="value=\"" +item.color_hex+ "\" >" +item.color+ "</option>";
            });

            $(cboShirtColor2).html(text).selectpicker('refresh');
            shirtCanvas.clear();
            loadShirt(getURI1(cboShirtType1.value), getURI2(cboShirtType2.value)); 
            getShirtSize2();            
        } else {
            Toast.init({
                "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on get shirt getShirtColor2!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in getShirtColor2 !!!</strong></div>'
        });
    });//fail
}

function getShirtSize1() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "data/ManageShirts.data.php",
        data: {method: "getShirtSize", gender: cboGender1.value, shirt_type: cboShirtType1.value}       
    })//post
    .done(function(data) {
        if (data) {
            var text = '';
            data.forEach(function(item){
                text += "<option value=\"" +item.size_code+ "\" >" +item.size_code+ "</option>";
            });

            $('#cbo-shirt-size-1').html(text).selectpicker('refresh');           
            setShirtColor();
            
            //do this method from designInit() command only, skip when combobox change
            if (!cboChange) {
               getShirtType2();
            }

        } else {
            Toast.init({
                "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on getShirtSize1!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in getShirtSize1 !!!</strong></div>'
        });
    });//fail
}
function getShirtSize2() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "data/ManageShirts.data.php",
        data: {method: "getShirtSize", gender: cboGender2.value, shirt_type: cboShirtType2.value}       
    })//post
    .done(function(data) {
        if (data) {
            var text = '';
            data.forEach(function(item){
                text += "<option value=\"" +item.size_code+ "\" >" +item.size_code+ "</option>";
            });

            $('#cbo-shirt-size-2').html(text).selectpicker('refresh');
            setShirtColor();
        } else {
            Toast.init({
                "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on getShirtSize2!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in getShirtSize2 !!!</strong></div>'
        });
    });//fail
}

function setShirtColor() {
    if (!shirtArray) return;

    var obj = undefined;   
    var color = undefined;    
 
    shirtArray.forEach(function(shirt) {
        if (shirt.id === 'shirt1') {
            obj = shirt;
            color = cboShirtColor1.value;
            var filter = new fabric.Image.filters.Multiply({
                        color: color
                    });
            obj.filters = [];
            obj.applyFilters();
            obj.filters.push(filter);
            obj.applyFilters(shirtCanvas.renderAll.bind(shirtCanvas));
            shirtCanvas.renderAll();
        }
        if (shirt.id === 'shirt2'){
            obj = shirt;
            color = cboShirtColor2.value;
            var filter = new fabric.Image.filters.Multiply({
                        color: color
                    });
            obj.filters = [];
            obj.applyFilters();
            obj.filters.push(filter);
            obj.applyFilters(shirtCanvas.renderAll.bind(shirtCanvas));
            shirtCanvas.renderAll();
        }
    });
}

function designInit() {
    $('.selectpicker').selectpicker();
    cboChange = false;
    getShirtType1();    
}

var btnCal = document.getElementById('btn-calculation');
btnCal.onclick = function() {
    loadShirt(getURI1(cboShirtType1.value), getURI2(cboShirtType2.value));
    setTimeout(function() { 
        scaleToFit(); 
        Toast.init({"selector": ".alert-success"});
        Toast.show("Calculation<br><strong>Adjust position completed ...</strong>");
    }, 300);   

    setTimeout(function() { setShirtColor(); }, 300);
}

//================================================================================
//left shirt
var cboColorStyle_1 = document.getElementById('cbo-color-style-1');
cboColorStyle_1.onchange = function() {
    //display dominant color
    var img = new Image();
    img.src = splitLineScreen[0];
    var c = colorThief.getColor(img);
    if (!c) return;
    
    var dominantColor = 'rgb(' + c[0] + ',' + c[1] + ',' + c[2] + ')';
    var recommend;
    var divColor = document.getElementById('recommend-color-1');
    divColor.innerHTML = '';

    var span = document.createElement('span');
    span.className = 'span-color form-control';
    span.style.background = dominantColor;
    divColor.appendChild(span);

    switch (this.value) {       
        case 'analogous':
            recommend = tinycolor.analogous(dominantColor);
            // var span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend[1].toHexString();            
            // divColor.appendChild(span);
            // span = undefined;

            // span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend[2].toHexString();
            // divColor.appendChild(span);
            // span = undefined;

            var temp = [recommend[1].toHexString(), recommend[2].toHexString()];
            var recommend_1 = findColor(temp, shirtColor1, dominantColor, 'shirt1');
            var existingColor = undefined;

            for (var i = 0; i < recommend_1.length; i++) { 
                var nearestColor = recommend_1[i][0].colorInStore;
                if (tinycolor(nearestColor).toHexString() === tinycolor(dominantColor).toHexString()) {
                    continue;
                }
                if (!existingColor) { 
                        existingColor = nearestColor;
                    } else {
                        if (existingColor === nearestColor) break;
                    }
                var span = document.createElement('span');
                span.className = 'span-color form-control clickable';
                span.style.background = nearestColor;
                span.title = "เปลี่ยนสีเสื้อ";
                span.setAttribute('data-toggle', 'tooltip');
                span.addEventListener('click', selectColor1, false);               
                
                divColor.appendChild(span);
            }
            
            $('.clickable').tooltip();

            recommend = undefined;
            break;

        case 'triad':
            recommend = tinycolor.triad(dominantColor);
            // var span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend[1].toHexString();            
            // divColor.appendChild(span);
            // span = undefined;

            // span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend[2].toHexString();
            // divColor.appendChild(span);
            // span = undefined;
            
            var temp = [recommend[1].toHexString(), recommend[2].toHexString()];
            var recommend_1 = findColor(temp, shirtColor1, dominantColor, 'shirt1');
            var existingColor = undefined;

            for (var i = 0; i < recommend_1.length; i++) { 
                var nearestColor = recommend_1[i][0].colorInStore;
                if (tinycolor(nearestColor).toHexString() === tinycolor(dominantColor).toHexString()) {
                    continue;
                }
                if (!existingColor) { 
                        existingColor = nearestColor;
                    } else {
                        if (existingColor === nearestColor) break;
                    }
                var span = document.createElement('span');
                span.className = 'span-color form-control clickable';
                span.style.background = nearestColor;
                span.title = "เปลี่ยนสีเสื้อ";
                span.setAttribute('data-toggle', 'tooltip');
                span.addEventListener('click', selectColor1, false);
                
                divColor.appendChild(span);
            }

            $('.clickable').tooltip();

            recommend = undefined;
            break;

         case 'complementary':
            recommend = tinycolor.complement(dominantColor);

            // var span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend.toHexString();
            // divColor.appendChild(span);
            // span = undefined;

            var temp = [recommend.toHexString()];
            var recommend_1 = findColor(temp, shirtColor1, dominantColor, 'shirt1');
            var existingColor = undefined;

            for (var i = 0; i < recommend_1.length; i++) { 
                var nearestColor = recommend_1[i][0].colorInStore;
                if (tinycolor(nearestColor).toHexString() === tinycolor(dominantColor).toHexString()) {
                    continue;
                }
                if (!existingColor) { 
                        existingColor = nearestColor;
                } else {
                    if (existingColor === nearestColor) break;
                }
                var span = document.createElement('span');
                span.className = 'span-color form-control clickable';
                span.style.background = nearestColor;
                span.title = "เปลี่ยนสีเสื้อ";
                span.setAttribute('data-toggle', 'tooltip');
                span.addEventListener('click', selectColor1, false);
                
                divColor.appendChild(span);
            }

            $('.clickable').tooltip();

            recommend = undefined;
            break;

        default:
            recommend = undefined;
            divColor.innerHTML = '';
            break;
    }
}
function selectColor1() {
    var colorHex = tinycolor(this.style.background).toHexString();
    $(cboShirtColor1).selectpicker('val', colorHex);
    cboShirtColor1.onchange();
}

//==========================================================================================
//right shirt
var cboColorStyle_2 = document.getElementById('cbo-color-style-2');
cboColorStyle_2.onchange = function() {
    //display dominant color
    var img = new Image();
    img.src = splitLineScreen[0];
    var c = colorThief.getColor(img);
    if (!c) return;
    
    var dominantColor = 'rgb(' + c[0] + ',' + c[1] + ',' + c[2] + ')';
    var recommend;
    var divColor = document.getElementById('recommend-color-2');
    divColor.innerHTML = '';

    var span = document.createElement('span');
    span.className = 'span-color form-control';
    span.style.background = dominantColor;
    divColor.appendChild(span);

    switch (this.value) {       
        case 'analogous':
            recommend = tinycolor.analogous(dominantColor);
            // var span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend[1].toHexString();            
            // divColor.appendChild(span);
            // span = undefined;

            // span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend[2].toHexString();
            // divColor.appendChild(span);
            // span = undefined;

            var temp = [recommend[1].toHexString(), recommend[2].toHexString()];
            var recommend_1 = findColor(temp, shirtColor1, dominantColor, 'shirt1');
            var existingColor = undefined;

            for (var i = 0; i < recommend_1.length; i++) { 
                var nearestColor = recommend_1[i][0].colorInStore;
                if (tinycolor(nearestColor).toHexString() === tinycolor(dominantColor).toHexString()) {
                    continue;
                }
                if (!existingColor) { 
                        existingColor = nearestColor;
                    } else {
                        if (existingColor === nearestColor) break;
                    }
                var span = document.createElement('span');
                span.className = 'span-color form-control clickable';
                span.style.background = nearestColor;
                span.title = "เปลี่ยนสีเสื้อ";
                span.setAttribute('data-toggle', 'tooltip');
                span.addEventListener('click', selectColor2, false);               
                
                divColor.appendChild(span);
            }
            
            $('.clickable').tooltip();

            recommend = undefined;
            break;

        case 'triad':
            recommend = tinycolor.triad(dominantColor);
            // var span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend[1].toHexString();            
            // divColor.appendChild(span);
            // span = undefined;

            // span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend[2].toHexString();
            // divColor.appendChild(span);
            // span = undefined;
            
            var temp = [recommend[1].toHexString(), recommend[2].toHexString()];
            var recommend_1 = findColor(temp, shirtColor1, dominantColor, 'shirt1');
            var existingColor = undefined;

            for (var i = 0; i < recommend_1.length; i++) { 
                var nearestColor = recommend_1[i][0].colorInStore;
                if (tinycolor(nearestColor).toHexString() === tinycolor(dominantColor).toHexString()) {
                    continue;
                }
                if (!existingColor) { 
                        existingColor = nearestColor;
                    } else {
                        if (existingColor === nearestColor) break;
                    }
                var span = document.createElement('span');
                span.className = 'span-color form-control clickable';
                span.style.background = nearestColor;
                span.title = "เปลี่ยนสีเสื้อ";
                span.setAttribute('data-toggle', 'tooltip');
                span.addEventListener('click', selectColor2, false);
                
                divColor.appendChild(span);
            }

            $('.clickable').tooltip();

            recommend = undefined;
            break;

         case 'complementary':
            recommend = tinycolor.complement(dominantColor);

            // var span = document.createElement('span');
            // span.className = 'span-color form-control';
            // span.style.background = recommend.toHexString();
            // divColor.appendChild(span);
            // span = undefined;

            var temp = [recommend.toHexString()];
            var recommend_1 = findColor(temp, shirtColor1, dominantColor, 'shirt1');
            var existingColor = undefined;

            for (var i = 0; i < recommend_1.length; i++) { 
                var nearestColor = recommend_1[i][0].colorInStore;
                if (tinycolor(nearestColor).toHexString() === tinycolor(dominantColor).toHexString()) {
                    continue;
                }
                if (!existingColor) { 
                        existingColor = nearestColor;
                } else {
                    if (existingColor === nearestColor) break;
                }
                var span = document.createElement('span');
                span.className = 'span-color form-control clickable';
                span.style.background = nearestColor;
                span.title = "เปลี่ยนสีเสื้อ";
                span.setAttribute('data-toggle', 'tooltip');
                span.addEventListener('click', selectColor2, false);
                
                divColor.appendChild(span);
            }

            $('.clickable').tooltip();

            recommend = undefined;
            break;

        default:
            recommend = undefined;
            divColor.innerHTML = '';
            break;
    }
}
function selectColor2() {
    var colorHex = tinycolor(this.style.background).toHexString();
    $(cboShirtColor2).selectpicker('val', colorHex);
    cboShirtColor2.onchange();
}

function findColor(recommendColor, colorInStore, dominantColor, side) {
    
    var rtn = new Array();
    recommendColor.forEach(function(rec) {
        var reccomend = new Array();

        colorInStore.forEach(function(current) {
            var distance = compareColor(rec, current.color_hex);
            var colorInStore = { 
                                dominantColor: tinycolor(dominantColor).toHexString(), 
                                recommendColor: rec,
                                colorInStore: current.color_hex,
                                distance: distance,
                                side: side
                            };
            reccomend.push(colorInStore);
        });
        reccomend.sort(compareArray)
        rtn.push(reccomend);   
    });
    
    //rtn.sort(compareArray)
    return rtn;
}

function compareArray(a,b) {
  if (a.distance < b.distance)
     return -1;
  if (a.distance > b.distance)
    return 1;
  return 0;
}

function compareColor(color1, color2) {
    var a = tinycolor(color1).toRgb();
    var b = tinycolor(color2).toRgb();
    var differences = distance(a.r, b.r) + distance(a.g, b.g) + distance(a.b, b.b);
    return Math.sqrt(differences);
}
function distance(a, b) {
    return (a - b) * (a - b);
}

var cboShirtSize1 = document.getElementById('cbo-shirt-size-1');
var cboShirtSize2 = document.getElementById('cbo-shirt-size-2');
function goSave() {
    
    var dataURL = shirtCanvas.toDataURL();
    $.redirect("ViewCart.php",
                {
                    gender_1: cboGender1.value, 
                    shirt_type_1: cboShirtType1.value,
                    shirt_size_1: cboShirtSize1.value,
                    shirt_color_1: cboShirtColor1.value,
                    screen1: splitLineScreen[0],
                    gender_2: cboGender2.value,
                    shirt_type_2: cboShirtType2.value,
                    shirt_size_2: cboShirtSize2.value,
                    shirt_color_2: cboShirtColor2.value,
                    screen2: splitLineScreen[1],
                    product: dataURL
                });
}

var Toast = (function() {
    "use strict";

    var elem,
        hideHandler,
        that = {};

    that.init = function(options) {
        elem = $(options.selector);
    };

    that.show = function(text) {
        clearTimeout(hideHandler);

        elem.find("span").html(text);
        elem.delay(200).fadeIn().delay(1500).fadeOut();
    };

    return that;
}());

;(function( $ ){

    $.redirect = function( target, values, method ) {  

      method = (method && method.toUpperCase() == 'GET') ? 'GET' : 'POST';
        
      if (!values)
      {
        var obj = $.parse_url(target);
        target = obj.url;
        values = obj.params;
      }
            
      var form = $('<form>',{attr:{
        method: method,
        action: target
      }});
      
      for(var i in values)
      {
        $('<input>',{
          attr:{
            type: 'hidden',
            name: i,
            value: values[i]
          }
        }).appendTo(form);

      }
      
      $('body').append(form);
          console.log(form);
      form.submit();
    };
    
    $.parse_url = function(url)
    {
      if (url.indexOf('?') == -1)
        return { url: url, params: {} }
        
      var parts = url.split('?');
      var url = parts[0];
      var query_string = parts[1];
      
      var return_obj = {};
      var elems = query_string.split('&');
      
      var obj = {};
      
      for(var i in elems)
      {
        var elem = elems[i];
        var pair = elem.split('=');
        obj[pair[0]] = pair[1];
      }
      
      return_obj.url = url;
      return_obj.params = obj;
      
      return return_obj;    
    }   
  })( jQuery );