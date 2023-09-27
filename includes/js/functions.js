var regEx={
		Ymd:'^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$',
		blank: '^\\s*$',
		word: '^\\w+$',
		email: '^\\s*([a-zA-Z0-9_\.-])+@([a-zA-Z0-9_\.-])+\\.[a-zA-Z]{2,5}\\s*$',
		integer: '^\\d*$'
	}
	
function clientSize() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  //window.alert( 'Width = ' + myWidth );
  //window.alert( 'Height = ' + myHeight );
  return {width:myWidth,height:myHeight};
}

function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return {scrollLeft:scrOfX,scrollTop:scrOfY};
}

function in_array()
{
	switch(arguments.length)
	{
		case 2:
			if(Object.isArray(arguments[0]))
			{
				flag=false;
				for(i=0;i<arguments[0].length;i++)
				{
					if(arguments[0][i]==arguments[1])
					{
						flag=true;
						break;
					}
				}
				return flag;
			}
			else
			{
				alert('Bad arguments');	
			}
		break;
		
		default:
			alert('Bad arguments');
		break;
	}
}


function populateDate()
{
	switch(arguments.length)
	{
		case 3:
			year=arguments[0].value;
			month=arguments[1].value;
			target=arguments[2];
			switch(month)
			{
				case '1':
					no_of_days=31;
				break;
				case '2':
					no_of_days=isLeapYear(year)?29:28;
				break;
				case '3':
					no_of_days=31;
				break;
				case '4':
					no_of_days=30;
				break;
				case '5':
					no_of_days=31;
				break;
				case '6':
					no_of_days=30;
				break;
				case '7':
					no_of_days=31;
				break;
				case '8':
					no_of_days=31;
				break;
				case '9':
					no_of_days=30;
				break;
				case '10':
					no_of_days=31;
				break;
				case '11':
					no_of_days=30;
				break;
				case '12':
					no_of_days=31;
				break;
				default:
					no_of_days=31;
				break;
			}
			for(i=target.options.length-1;i>=0;i--)
			{
				target.options[i]=null;
			}
			for(i=0;i<no_of_days;i++)
			{
				target.options[i] = new Option(i+1,i+1);
			}
		break;
		
		default:
			alert('Bad arguments');
			break;
	}
}

function isLeapYear(year)
{
	flag=false;
	if(year%400==0)
	{
		flag=true;
	}
	else if(year%100==0)
	{
		flag=false;	
	}
	else if(year%4==0)
	{
		flag=true;
	}
	return flag;
}

function chkExt(str)
{
	str=str.toLowerCase();
	var pos=str.lastIndexOf(".");
	var len=str.length;
	var upper=len-pos;
	var ext=str.substring(pos,len);
	if(ext!='.pdf')
	{
		alert('Please upload .pdf only');
		return false;
	}
}

function Alert(msg)
{
	alert(msg);
}

function gotoPage(url)
{
	window.location=url;
}

function Focus(element)
{/*
	for(i=0;i<element.form.elements.length;i++)
	{
		//alert(element.form.elements[i].style.border);
		//element.form.elements[i].style.border="1px solid #6a92b8";
		type=element.form.elements[i].type;
		if((type!='submit')&&(type!='reset')&&(type!='button'))
		element.form.elements[i].className="txt_input1";
		if(type=='textarea')
		{
			element.form.elements[i].className="text_area";
		}
	}
	//element.style.border="2px solid #FF0000";
	if(element.type=="textarea")
	{
		element.className="txt_input1";
	}
	else
	{
		element.className="txt_input_error";
	}*/
	element.focus();
}

function toCenter(e)
{
	//leftPos=(document.viewport.getWidth()-e.getWidth())/2;
	clsz=clientSize();
	leftPos=(clsz.width-e.getWidth())/2;
	scrollOff=document.viewport.getScrollOffsets();
	topPos=scrollOff.top+200;
	//alert(leftPos);
	e.style.left=leftPos+"px";
	e.style.top=topPos+"px";
}

function encode64(input) {
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   do {
      chr1 = input.charCodeAt(i++);
      chr2 = input.charCodeAt(i++);
      chr3 = input.charCodeAt(i++);

      enc1 = chr1 >> 2;
      enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
      enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
      enc4 = chr3 & 63;

      if (isNaN(chr2)) {
         enc3 = enc4 = 64;
      } else if (isNaN(chr3)) {
         enc4 = 64;
      }

      output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + 
         keyStr.charAt(enc3) + keyStr.charAt(enc4);
   } while (i < input.length);
   
   return output;
}
var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
function decode64(input) {
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
   input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

   do {
      enc1 = keyStr.indexOf(input.charAt(i++));
      enc2 = keyStr.indexOf(input.charAt(i++));
      enc3 = keyStr.indexOf(input.charAt(i++));
      enc4 = keyStr.indexOf(input.charAt(i++));

      chr1 = (enc1 << 2) | (enc2 >> 4);
      chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
      chr3 = ((enc3 & 3) << 6) | enc4;

      output = output + String.fromCharCode(chr1);

      if (enc3 != 64) {
         output = output + String.fromCharCode(chr2);
      }
      if (enc4 != 64) {
         output = output + String.fromCharCode(chr3);
      }
   } while (i < input.length);

   return output;
}

function getURLParam(strParamName){
  var strReturn = "";
  var strHref = window.location.href;
  if ( strHref.indexOf("?") > -1 ){
    var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
    var aQueryString = strQueryString.split("&");
    for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
      if (
aQueryString[iParam].indexOf(strParamName.toLowerCase() + "=") > -1 ){
        var aParam = aQueryString[iParam].split("=");
        strReturn = aParam[1];
        break;
      }
    }
  }
  return unescape(strReturn);
}

function toggleDefaultValue(e,val) /* this function gets an element and if value of the element is blank then replaces it with the default value which is also passed as 2nd argu. */
{										   		   /* 3rd variable deleteVal=false means delete defalt value , true replaces the value */
	deleteVal=true;
	if(arguments.length==3)
	{
		deleteVal=arguments[2];
	}
	if(deleteVal)
	{
		if(e.value==val)
		{
			e.value='';
		}	
	}
	else
	{
		if(e.value.match(regEx.blank))
		{
			e.value=val;
		}
	}
}

function unserialize (data) {
    // http://kevin.vanzonneveld.net
    // +     original by: Arpad Ray (mailto:arpad@php.net)
    // +     improved by: Pedro Tainha (http://www.pedrotainha.com)
    // +     bugfixed by: dptr1988
    // +      revised by: d3x
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +        input by: Brett Zamir (http://brett-zamir.me)
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: Chris
    // +     improved by: James
    // %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %            note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
    // *       returns 1: ['Kevin', 'van', 'Zonneveld']
    // *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
    // *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}
 
    var error = function (type, msg, filename, line){throw new this.window[type](msg, filename, line);};
    var read_until = function (data, offset, stopchr){
        var buf = [];
        var chr = data.slice(offset, offset + 1);
        var i = 2;
        while (chr != stopchr) {
            if ((i+offset) > data.length) {
                error('Error', 'Invalid');
            }
            buf.push(chr);
            chr = data.slice(offset + (i - 1),offset + i);
            i += 1;
        }
        return [buf.length, buf.join('')];
    };
    var read_chrs = function (data, offset, length){
        var buf;
 
        buf = [];
        for (var i = 0;i < length;i++){
            var chr = data.slice(offset + (i - 1),offset + i);
            buf.push(chr);
        }
        return [buf.length, buf.join('')];
    };
    var _unserialize = function (data, offset){
        var readdata;
        var readData;
        var chrs = 0;
        var ccount;
        var stringlength;
        var keyandchrs;
        var keys;
 
        if (!offset) {offset = 0;}
        var dtype = (data.slice(offset, offset + 1)).toLowerCase();
 
        var dataoffset = offset + 2;
        var typeconvert = new Function('x', 'return x');
 
        switch (dtype){
            case 'i':
                typeconvert = function (x) {return parseInt(x, 10);};
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
            break;
            case 'b':
                typeconvert = function (x) {return parseInt(x, 10) !== 0;};
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
            break;
            case 'd':
                typeconvert = function (x) {return parseFloat(x);};
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
            break;
            case 'n':
                readdata = null;
            break;
            case 's':
                ccount = read_until(data, dataoffset, ':');
                chrs = ccount[0];
                stringlength = ccount[1];
                dataoffset += chrs + 2;
 
                readData = read_chrs(data, dataoffset+1, parseInt(stringlength, 10));
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 2;
                if (chrs != parseInt(stringlength, 10) && chrs != readdata.length){
                    error('SyntaxError', 'String length mismatch');
                }
            break;
            case 'a':
                readdata = {};
 
                keyandchrs = read_until(data, dataoffset, ':');
                chrs = keyandchrs[0];
                keys = keyandchrs[1];
                dataoffset += chrs + 2;
 
                for (var i = 0; i < parseInt(keys, 10); i++){
                    var kprops = _unserialize(data, dataoffset);
                    var kchrs = kprops[1];
                    var key = kprops[2];
                    dataoffset += kchrs;
 
                    var vprops = _unserialize(data, dataoffset);
                    var vchrs = vprops[1];
                    var value = vprops[2];
                    dataoffset += vchrs;
 
                    readdata[key] = value;
                }
 
                dataoffset += 1;
            break;
            default:
                error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
            break;
        }
        return [dtype, dataoffset - offset, typeconvert(readdata)];
    };
    
    return _unserialize((data+''), 0)[2];
}

function toggle(id)
{
	e=document.getElementById(id);
	if(e.style.display=='none')
	{
		e.style.display='';
	}
	else
	{
		e.style.display='none';
	}
}
/*
This is custom validation for a form
$().ready(function() {
	$("#login_form").validate(
		{
			rules:{
				userid:'required'
			},
			messages:{
				userid:'User Id can't be blank'
			}
		}
	);				
});
*/
function checkSelection(e,maxLength){
  element_id=$(e).attr('id');
  var selection_count=0;
  var foo = [];
  $('#'+element_id+' :selected').each(function(i, selected){
	  //foo[i] = $(selected).text();
	  selection_count++;
	  if(selection_count>maxLength)
	  {
		$(selected).attr('selected',false);
	  }
  });
}

// new //
function NameOnly(title){
	if ((/^[a-zA-Z\s]+$/).exec(title) == null) { 
		return false; 
	} else {
		return true;
	}
}

function EmailValidate(title){
	if((/^[a-zA-Z]+[a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z]+[\.][a-zA-Z]+[\.]{0,1}[a-zA-Z]+$/).exec(title)==null) {
		return false; 
	} else {
		return true;
	}
}

