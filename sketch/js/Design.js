var cboGender1 = document.getElementById('cbo-gender-1');
var cboShirtType1 = document.getElementById('cbo-shirt-type-1');
var cboShirtColor1 = document.getElementById('cbo-shirt-color-1');
var cboWomen = document.getElementById('cbo-shirt-2');

cboShirtType1.onchange = function() {
    //getShirtInfo(this.value, this);
    getShirtColor();
}
cboGender1.onchange = function() {
    getShirtColor();
}

function getShirtInfo(shirtId, cbo) {    

    $.ajax({
        type: "POST",
        dataType: "json",
        url: ".data/ManageShirts.data.php/",
        data: {method: "getAllShirtsByGender", gender: shirtId}       
    })
    .done(function(data) {
        var shirt = data[0];           
        cbo.setAttribute("data-original-title", "size: " + shirt.size_code + " ขนาด: " + shirt.chest_size + "x" + shirt.shirt_length);
        $('#cbo-shirt-men').tooltip();     
    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });
    });//fail
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

            $('#cbo-shirt-type-1').html(text).selectpicker('refresh');

            getShirtColor();
        } else {
            Toast.init({
                "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on get shirt size!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });
    });//fail
}

function getShirtColor() {
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
                text += "<option data-content=\"<table style='width:100%'><tr><td style='width: 50%;'>" +item.color+ "</td><td style='width: '50%'; text-aligh: right' bgcolor='" +item.color_hex+ "'></td></tr></table>\" ";
                text +="value=\"" +item.color_hex+ "\" >" +item.color+ "</option>";
            });

            $('#cbo-shirt-color-1').html(text).selectpicker('refresh');
            setShirtColor();
        } else {
            Toast.init({
                "selector": ".alert-danger"
            });
            Toast.show("<strong>Error on get shirt size!!!<strong> " + data);
        }

    })//done
    .fail(function(data) { 
        bootbox.dialog({
                title: 'Fatal Error',
                message : '<div class="alert alert-danger" role="alert"><strong>Error in connection !!!</strong></div>'
        });
    });//fail
}

function setShirtColor() {
    if (!shirtArray) return;

    var filter = new fabric.Image.filters.Multiply({
            color: cboShirtColor1.value
        });
    shirtArray[0].filters.push(filter);
    shirtArray[0].applyFilters(shirtCanvas.renderAll.bind(shirtCanvas));

    shirtCanvas.renderAll();
}

function designInit() {
    $('.selectpicker').selectpicker();
    getShitType1();
}
//init();