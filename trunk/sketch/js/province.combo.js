var cboProvince = document.getElementById('cbo-province');
var cboAmphur = document.getElementById('cbo-amphur');
var cboDistrict = document.getElementById('cbo-district');

cboProvince.onchange = function(){
	var att1 = document.createAttribute('disabled');
 	cboDistrict.setAttributeNode(att1);

	cboAmphur.removeAttribute('disabled');
	getAmphur(this.value.split("|")[0]);
	
	setTimeout(function() { cboAmphur.onchange(); }, 200);
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
  		    option.text = amphur.amphur_name.trim();
  		    option.value = amphur.amphur_id + "|" + amphur.amphur_name.trim();
  		    cboAmphur.add(option);
        });
      }
    });
} 

cboAmphur.onchange = function(){	
	cboDistrict.removeAttribute("disabled");
	getDistrict(cboProvince.value.split("|")[0], this.value.split("|")[0]);
  setTimeout(function() { cboDistrict.onchange(); }, 200);
}

function getDistrict(province, amphur){
	$.ajax({
    	type: "POST",
     	dataType: "json",
    	url: "./service/servie",
    	data: {method: "getDistrict", province_id: province, amphur_id: amphur},
    	success: function(data){     
      //build object        
        cboDistrict.innerHTML = "";	
        data.forEach(function(tambol){
        	var option = document.createElement("option");
  		    option.text = tambol.district_name.trim();
  		    option.value = tambol.district_id +"|" + tambol.district_name.trim() + "|" + tambol.zipcode.trim();
  		    cboDistrict.add(option);
        });      
      }
    });
}

cboDistrict.onchange = function(){
  var txtPostCode = document.getElementById('txt-post-code');
  if (txtPostCode){
    txtPostCode.value = this.value.split("|")[2];
  }
}

var formAmphurId = document.getElementById('hidden-amphur');
var formDistrictId = document.getElementById('hidden-district');
function init(){
	cboProvince.onchange();

  setTimeout(function(){
    if (formAmphurId){
      setSelectedIndex(cboAmphur, formAmphurId.value);
      cboAmphur.onchange();
    }  
  }, 500);
  
  setTimeout(function(){
    if (formDistrictId){
      setSelectedIndex(cboDistrict, formDistrictId.value);
      cboDistrict.onchange();
    }  
  }, 500);
  
}

init();

function setSelectedIndex(s, valsearch)
{
  // Loop through all the items in drop down list
  for (i = 0; i< s.options.length; i++)
  { 
    if (s.options[i].value.split("|")[0] == valsearch)
    {
      // Item is found. Set its property and exit
      s.options[i].selected = true;
      break;
    }
  }
  return;
}
