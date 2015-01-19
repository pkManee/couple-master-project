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

var cboColorStyle = document.getElementById('cbo-color-style');
cboColorStyle.onchange = function() {
    var colorShirt = document.getElementById('color-thief-1').style.backgroundColor;
    var recommend;
    switch (this.value) {               
       
        case 'analogous':
            recommend = tinycolor.analogous(colorShirt, 2);
            break;
        case 'triad':
        break;
         case 'complementary':            
            break;
    }

    if (recommend) {
        
    }
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