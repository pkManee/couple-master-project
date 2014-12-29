var cboProvince = document.getElementById('cbo-province');
var cboAmphur = document.getElementById('cbo-amphur');
var cboTambol = document.getElementById('cbo-tambol');

cboProvince.onchange = function(){
	var att1 = document.createAttribute('disabled');
 	cboTambol.setAttributeNode(att1);

	cboAmphur.removeAttribute('disabled');
	getAmphur(this.value);
	
	setTimeout(function() { cboAmphur.onchange(); }, 1000);
}
	
function getAmphur(province){
	$.ajax({
    	type: "POST",
     	dataType: "json",
      	url: "./service/servie",
      	data: {method: "getAmphur", province_id: province},
      	success: function(data){     
      	cboAmphur.innerHTML = "";
        //build object       
        data.forEach(function(amphur){
        	var option = document.createElement("option");
		    option.text = amphur.amphur_name;
		    option.value = amphur.amphur_id;
		    cboAmphur.add(option);
        });
      }
    });
} 

cboAmphur.onchange = function(){	
	cboTambol.removeAttribute("disabled");
	getTambol(cboProvince.value, this.value);
}

function getTambol(province, amphur){
	$.ajax({
    	type: "POST",
     	dataType: "json",
      	url: "./service/servie",
      	data: {method: "getTambol", province_id: province, amphur_id: amphur},
      	success: function(data){     
        //build object        
        cboTambol.innerHTML = "";	
        data.forEach(function(tambol){
        	var option = document.createElement("option");
		    option.text = tambol.district_name;
		    option.value = tambol.district_id;
		    cboTambol.add(option);
        });
      }
    });
}

function init(){
	cboProvince.onchange();
	setTimeout(function(){ cboAmphur.onchange(); }, 1000);
}

init();