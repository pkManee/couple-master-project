var cboGender1 = document.getElementById('cbo-gender-1');
var cboShirtType1 = document.getElementById('cbo-shirt-type-1');
var cboShirtColor1 = document.getElementById('cbo-shirt-color-1');

var cboChange = false;
cboShirtType1.onchange = function() { 
    cboChange = true;
    getShirtColor1();
    $(cboColorStyle_1).selectpicker('val', '');
    cboColorStyle_1.onchange();
}
cboGender1.onchange = function() { 
    cboChange = true;
    getShirtType1();
    $(cboColorStyle_1).selectpicker('val', '');
    cboColorStyle_1.onchange();
}
cboShirtColor1.onchange =  function() { setShirtColor(); }

var cboGender2 = document.getElementById('cbo-gender-2');
var cboShirtType2 = document.getElementById('cbo-shirt-type-2');
var cboShirtColor2 = document.getElementById('cbo-shirt-color-2');

cboShirtType2.onchange = function() {
    cboChange = false;
    getShirtColor2();
    $(cboColorStyle_2).selectpicker('val', '');
    cboColorStyle_2.onchange();
}
cboGender2.onchange = function() { 
    cboChange = false;
    getShirtColor2();
    $(cboColorStyle_2).selectpicker('val', '');
    cboColorStyle_2.onchange();
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
                text += "<option data-content=\"<span></span><span>"+item.color+"</span><span class='pull-right' style='width:50px;height:20px;background-color:"+item.color_hex+"'></span>\" ";
                text += "value=\"" +item.color_hex+ "\" >" +item.color_hex+ "</option>";
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
                text += "<option data-content=\"<span></span><span>"+item.color+"</span><span class='pull-right' style='width:50px;height:20px;background-color:"+item.color_hex+"'></span>\" ";
                text += "value=\"" +item.color_hex+ "\" >" +item.color_hex+ "</option>";
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

var jointColor;
$(document).ajaxStop(function () {
    jointColor = [];
    if (!shirtColor1) return;
    jointColor = shirtColor1.filter(function(val) {
      return shirtColor2.map(function(e) { return e.color_hex; }).indexOf(val.color_hex) != -1; //shirtColor2.indexOf(val) == -1;
    });
});

function getJointColor() {
    var colorType;
    if (cboColorStyle_1.value === cboColorStyle_2.value) {
        colorType = cboColorStyle_1.value;
    }

    if (colorType === '') return;

    var img = new Image();
    img.src = canvas.toDataURL();
    var c = colorThief.getColor(img);
    if (!c) return;
    
    var dominantColor = 'rgb(' + c[0] + ',' + c[1] + ',' + c[2] + ')';

    var recommend;
    var divColor = document.getElementById('recommend-color-3');
    divColor.innerHTML = '';

    var heart = document.createElement('span');
    heart.className = 'icon-heart';
    heart.style.paddingLeft = '10px';
    heart.title = 'สีที่เหมาะกับทั้งคู่';
    heart.style.color = 'rgb(255,0,0)';
    divColor.appendChild(heart);

    var span = document.createElement('span');    
    span.className = 'span-color form-control';
    span.style.background = dominantColor;
    span.title = 'โทนสีหลัก';
    divColor.appendChild(span);
   
    switch (colorType) {       
        case 'analogous':
            recommend = tinycolor.analogous(dominantColor);            

            var temp = [recommend[1].toHexString(), recommend[2].toHexString()];
            var recommend_1 = findColor(temp, jointColor, dominantColor, 'center');
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
                span.addEventListener('click', setBothColor, false);               
                
                divColor.appendChild(span);
            }
            break;

        case 'triad':
            recommend = tinycolor.triad(dominantColor);            
            
            var temp = [recommend[1].toHexString(), recommend[2].toHexString()];
            var recommend_1 = findColor(temp, jointColor, dominantColor, 'center');
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
                span.addEventListener('click', setBothColor, false);
                
                divColor.appendChild(span);
            }            
            break;

         case 'complementary':
            recommend = tinycolor.complement(dominantColor);           

            var temp = [recommend.toHexString()];
            var recommend_1 = findColor(temp, jointColor, dominantColor, 'center');
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
                span.addEventListener('click', setBothColor, false);
                
                divColor.appendChild(span);
            }
            
            break;

        default:
            recommend = undefined;
            divColor.innerHTML = '';
            break;
    }

    $('.span-color').tooltip();
    $('span.icon-heart').tooltip();
    recommend = undefined;
}

function setBothColor() {
    var colorHex = tinycolor(this.style.background).toHexString();

    $(cboShirtColor1).selectpicker('val', colorHex);
    cboShirtColor1.onchange();

    $(cboShirtColor2).selectpicker('val', colorHex);
    cboShirtColor2.onchange();
}

var btnCal = document.getElementById('btn-calculation');
btnCal.onclick = function() {
    loadShirt(getURI1(cboShirtType1.value), getURI2(cboShirtType2.value));
    setTimeout(function() { 
        scaleToFit(); 
        setTimeout(function() { setShirtColor(); }, 300);
    }, 300);
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
    span.title = 'โทนสีหลัก';
    divColor.appendChild(span);

    getJointColor();
    switch (this.value) {       
        case 'analogous':
            recommend = tinycolor.analogous(dominantColor);            

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
    img.src = splitLineScreen[1];
    var c = colorThief.getColor(img);
    if (!c) return;
    
    var dominantColor = 'rgb(' + c[0] + ',' + c[1] + ',' + c[2] + ')';
    var recommend;
    var divColor = document.getElementById('recommend-color-2');
    divColor.innerHTML = '';

    var span = document.createElement('span');
    span.className = 'span-color form-control';
    span.style.background = dominantColor;
    span.title = 'โทนสีหลัก';
    divColor.appendChild(span);

    getJointColor();
    switch (this.value) {       
        case 'analogous':
            recommend = tinycolor.analogous(dominantColor);            

            var temp = [recommend[1].toHexString(), recommend[2].toHexString()];
            var recommend_1 = findColor(temp, shirtColor2, dominantColor, 'shirt1');
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
            
            var temp = [recommend[1].toHexString(), recommend[2].toHexString()];
            var recommend_1 = findColor(temp, shirtColor2, dominantColor, 'shirt1');
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

            var temp = [recommend.toHexString()];
            var recommend_1 = findColor(temp, shirtColor2, dominantColor, 'shirt1');
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
    //update member profile first
    var height_1 = document.getElementById('txt-height-1');
    var height_2 = document.getElementById('txt-height-2');
    var email = document.getElementById('hidden-email');

    $.ajax({
        type: 'POST',
        url: './service/', 
        data: { method: 'updateMemberHeight', height_1: height_1.value, height_2: height_2.value, email: email.value }
    })
    .done(function(data){
      if (data.result === "success") {
             //capture image and redirect
            shirtCanvas.deactivateAll();
            if (borderShirt1) borderShirt1.set('stroke', 'rgba(0, 0, 0, 0)');
            if (borderShirt2) borderShirt2.set('stroke', 'rgba(0, 0, 0, 0)');   
            var dataURL = shirtCanvas.toDataURL();
            var screen1 = splitLineScreen[0]; //finalLineScreen[0].toDataURL();
            var screen2 = splitLineScreen[1]; //finalLineScreen[1].toDataURL(); 
            splitShirt();

            $.redirect("ViewCart.php",
                        {
                            gender_1: cboGender1.value, 
                            shirt_type_1: cboShirtType1.value,
                            shirt_size_1: cboShirtSize1.value,
                            shirt_color_1: cboShirtColor1.value,
                            screen1: screen1,
                            scaleX_1: finalLineScreen[0].scaleX,
                            scaleY_1: finalLineScreen[0].scaleY,
                            top_1: finalLineScreen[0].top,
                            gapLeft_1: finalLineScreen[0].left - borderShirt1.left,
                            gender_2: cboGender2.value,
                            shirt_type_2: cboShirtType2.value,
                            shirt_size_2: cboShirtSize2.value,
                            shirt_color_2: cboShirtColor2.value,
                            screen2: screen2,
                            scaleX_2: finalLineScreen[1].scaleX,
                            scaleY_2: finalLineScreen[1].scaleY,
                            top_2: finalLineScreen[1].top,
                            gapLeft_2: finalLineScreen[1].left - borderShirt2.left,
                            product: dataURL,
                            shirt_photo_1: splitShirtArray[0],
                            shirt_photo_2: splitShirtArray[1]
                        });
      } else {
        bootbox.dialog({
                title: 'Update Member Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Update member error !!!</strong></div>'
        });
      }
    })//done
    .fail(function() {
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });
    });//fail
}

var splitShirtArray;
function splitShirt() {
    splitShirtArray = [];    

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
  
    shirtCanvas.deactivateAll();
    splitShirtArray.push(shirtCanvas.toDataURLWithCropping(format, cropping1, quality));
    splitShirtArray.push(shirtCanvas.toDataURLWithCropping(format, cropping2, quality));
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