var cboProvince = document.getElementById('cbo-province');
var cboAmphur = document.getElementById('cbo-amphur');
var cboDistrict = document.getElementById('cbo-district');

cboProvince.onchange = function(){
  var att1 = document.createAttribute('disabled');
  cboDistrict.setAttributeNode(att1);

  cboAmphur.removeAttribute('disabled');
  getAmphur(cboProvince.value.split("|")[0]);
  
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
          option.text = amphur.amphur_name.trim();
          option.value = amphur.amphur_id + "|" + amphur.amphur_name.trim();
          cboAmphur.add(option);
        });
        var formAmphurId = document.getElementById('hidden-amphur');
        if (formAmphurId){
          setSelectedIndex(cboAmphur, formAmphurId.value);           
        }
        cboAmphur.onchange();
      }
    });
} 

cboAmphur.onchange = function(){  
 
  cboDistrict.removeAttribute("disabled");
  getDistrict(cboProvince.value.split("|")[0], cboAmphur.value.split("|")[0]);  
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

        var formDistrictId = document.getElementById('hidden-district');
        if (formDistrictId){
          setSelectedIndex(cboDistrict, formDistrictId.value);
        }
        cboDistrict.onchange();
      }
    });
}

cboDistrict.onchange = function(){
  var txtPostCode = document.getElementById('txt-post-code');
  if (txtPostCode){
    txtPostCode.value = cboDistrict.value.split("|")[2];
  }
}

function init(){
  cboProvince.onchange();
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

