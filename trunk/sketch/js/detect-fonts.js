/**
 * JavaScript code to detect available availability of a
 * particular font in a browser using JavaScript and CSS.
 *
 * Author : Lalit Patel
 * Website: http://www.lalit.org/lab/javascript-css-font-detect/
 * License: Apache Software License 2.0
 *          http://www.apache.org/licenses/LICENSE-2.0
 * Version: 0.15 (21 Sep 2009)
 *          Changed comparision font to default from sans-default-default,
 *          as in FF3.0 font of child element didn't fallback
 *          to parent element if the font is missing.
 * Version: 0.2 (04 Mar 2012)
 *          Comparing font against all the 3 generic font families ie,
 *          'monospace', 'sans-serif' and 'sans'. If it doesn't match all 3
 *          then that font is 100% not available in the system
 * Version: 0.3 (24 Mar 2012)
 *          Replaced sans with serif in the list of baseFonts
 */

/**
 * Usage: d = new Detector();
 *        d.detect('font name');
 */
var Detector = function() {
    // a font will be compared against all the three default fonts.
    // and if it doesn't match all 3 then that font is not available.
    var baseFonts = ['monospace', 'sans-serif', 'serif'];

    //we use m or w because these two characters take up the maximum width.
    // And we use a LLi so that the same matching fonts can get separated
    var testString = "mmmmmmmmmmlli";

    //we test using 72px font size, we may use any size. I guess larger the better.
    var testSize = '72px';

    var h = document.getElementsByTagName("body")[0];

    // create a SPAN in the document to get the width of the text we use to test
    var s = document.createElement("span");
    s.style.fontSize = testSize;
    s.innerHTML = testString;
    var defaultWidth = {};
    var defaultHeight = {};
    for (var index in baseFonts) {
        //get the default width for the three base fonts
        s.style.fontFamily = baseFonts[index];
        h.appendChild(s);
        defaultWidth[baseFonts[index]] = s.offsetWidth; //width for the default font
        defaultHeight[baseFonts[index]] = s.offsetHeight; //height for the defualt font
        h.removeChild(s);
    }

    function detect(font) {
        var detected = false;
        for (var index in baseFonts) {
            s.style.fontFamily = font + ',' + baseFonts[index]; // name of the font along with the base font for fallback.
            h.appendChild(s);
            var matched = (s.offsetWidth != defaultWidth[baseFonts[index]] || s.offsetHeight != defaultHeight[baseFonts[index]]);
            h.removeChild(s);
            detected = detected || matched;
        }
        return detected;
    }

    this.detect = detect;
};

var fonts = [];
var results = [];
var d = new Detector();
var goodFonts = [];
/**
 * other stuff
 */
function font_init() {
    fonts.push("cursive");
    fonts.push("monospace");
    fonts.push("serif");
    fonts.push("sans-serif");
    fonts.push("fantasy");
    fonts.push("default");
    fonts.push("Arial");
    fonts.push("Arial Black");
    fonts.push("Arial Narrow");
    fonts.push("Arial Rounded MT Bold");
    fonts.push("Bookman Old Style");
    fonts.push("Bradley Hand ITC");
    fonts.push("Century");
    fonts.push("Century Gothic");
    fonts.push("Comic Sans MS");
    fonts.push("Courier");
    fonts.push("Courier New");
    fonts.push("Georgia");
    fonts.push("Gentium");
    fonts.push("Impact");
    fonts.push("King");
    fonts.push("Lucida Console");
    fonts.push("Lalit");
    fonts.push("Modena");
    fonts.push("Monotype Corsiva");
    fonts.push("Papyrus");
    fonts.push("Tahoma");
    fonts.push("TeX");
    fonts.push("Times");
    fonts.push("Times New Roman");
    fonts.push("Trebuchet MS");
    fonts.push("Verdana");
    fonts.push("Verona");
    //thai
    fonts.push("THBaijam");
    fonts.push("THChakraPetch");
    fonts.push("THCharmofAU");
    fonts.push("THCharmonman");
    fonts.push("THFahkwang");
    fonts.push("THK2DJuly8");
    fonts.push("THKodchasal");
    fonts.push("THKoHo");
    fonts.push("THKrub");
    fonts.push("THMaliGrade6");
    fonts.push("THNiramitAS");
    fonts.push("THSarabunNew");
    fonts.push("THSrisakdi");
    fonts.push("THDRdeco");
    fonts.push("THDRjoyful");
    fonts.push("THTSklinsee");
    fonts.push("THkcnightmare");

    var table = document.getElementById('table');
    for (i = 0; i < fonts.length; i++) {
        var result = true;
        if (fonts[i].substring(0, 2) != 'TH') {
	       result = d.detect(fonts[i]);
        } 
	    if (result) {
	    	goodFonts.push(fonts[i]);
	    }
	    // row = table.insertRow(-1);
	    // col = row.insertCell(-1);
	    // col.appendChild(document.createTextNode(fonts[i]))
	    // col.style.fontFamily = fonts[i];
	    // col = row.insertCell(-1);	    
	    // col.appendChild(document.createTextNode(result));
    }

    var text = '';
    var cboFontPicker = document.getElementById('font-picker');
    for (var i = 0; i < goodFonts.length; i++) {
        if (goodFonts[i] === 'THTSklinsee') {
            text += '<option value="' + goodFonts[i] + '" style="font-family: ' + goodFonts[i] + '; font-size: 30px;">' + goodFonts[i] + '</option>'; 
        } else {
            text += '<option value="' + goodFonts[i] + '" style="font-family: ' + goodFonts[i] + ';">' + goodFonts[i] + '</option>'; 
        }        
    };
    $(cboFontPicker).html(text).selectpicker('refresh');
}

font_init();
