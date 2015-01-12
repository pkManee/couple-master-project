var cboGender1 = document.getElementById('cbo-gender-1');
var cboShirtType1 = document.getElementById('cbo-shirt-type-1');
var cboShirtColor1 = document.getElementById('cbo-shirt-color-1');

cboShirtType1.onchange = function() { 
    getShirtColor1();    

    loadShirt(getURI1(cboShirtType1.value), cboShirtColor1.value, getURI2(cboShirtType2.value), cboShirtColor2.value); 
}

cboGender1.onchange = function() { getShirtColor1(); }
cboShirtColor1.onchange =  function() { setShirtColor(0); }

var cboGender2 = document.getElementById('cbo-gender-2');
var cboShirtType2 = document.getElementById('cbo-shirt-type-2');
var cboShirtColor2 = document.getElementById('cbo-shirt-color-2');

cboShirtType2.onchange = function() { 
    getShirtColor2();
    
    loadShirt(getURI1(cboShirtType1.value), cboShirtColor1.value, getURI2(cboShirtType2.value), cboShirtColor2.value); 
}
cboGender2.onchange = function() { getShirtColor2(); }
cboShirtColor2.onchange = function() { setShirtColor(1); }

function getURI1(shirtType) {
    var uri;
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

    return uri;
}
function getURI2(shirtType) {
    var uri;
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
    return uri;
}

function getShitType1() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "data/ManageShirtType.data.php",
        data: {method: "getAll"}       
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
            Toast.show("<strong>Error on get shirt type!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });
    });//fail
}

function getShitType2() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "data/ManageShirtType.data.php",
        data: {method: "getAll"}       
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
            Toast.show("<strong>Error on get shirt getShitType2!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in getShitType2 !!!</strong></div>'
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
            setShirtColor(0);
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
            setShirtColor(1);
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

function setShirtColor(sequenceNo) {
    if (!shirtArray) return;

    var obj = undefined;
    var shirtId = '';
    var color = undefined;
    switch (sequenceNo) {
    case 0:
        shirtId = 'shirt1';    
        color = cboShirtColor1.value;
        break;
    case 1:
        shirtId = 'shirt2';
        color = cboShirtColor2.value;
        break;
    }
    shirtArray.forEach(function(shirt) {
        if (shirt.id === shirtId) obj = shirt;
    });

    var filter = new fabric.Image.filters.Multiply({
                    color: color
                });
    obj.filters = [];
    obj.applyFilters();
    obj.filters.push(filter);
    obj.applyFilters(shirtCanvas.renderAll.bind(shirtCanvas));
    shirtCanvas.renderAll();
}

function designInit() {
    $('.selectpicker').selectpicker();
    getShitType1();
    getShitType2();
}

