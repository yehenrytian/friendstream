/***********************************************
* Ajax functions
* Created by: Ye Henry Tian
* Date: March 6, 2010
***********************************************/

function GetXmlHttpObject()
   {
   if (window.XMLHttpRequest)
      {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      return new XMLHttpRequest();
      }
   if (window.ActiveXObject)
      {
      // code for IE6, IE5
      return new ActiveXObject("Microsoft.XMLHTTP");
      }
   return null;
   }

function streamIt(status)
   {
    var msg = ""; 
	msg += "________________________________________________________\n\n"
    msg += "Do you want to stream the following happening?\n"
    msg += "________________________________________________________\n\n"
	msg += status;
	if (confirm(msg))
	   {
	   document.streamform.happening.value = status;
	   textcount(document.streamform.happening, 140, document.streamform.remain);
	   }
	else
	   {
	   document.streamform.happening.value = "What is happening?";
	   document.streamform.remain.value = 140;
	   }
   }

function stateChanged(xmlhttp, fieldId)
   {
   if (xmlhttp.readyState == 4 || xmlhttp.readyState == "complete")
      {
      hideimg(fieldId);
	  document.getElementById(fieldId).innerHTML = xmlhttp.responseText;
      }
   else
      loadimg(fieldId);
   }
   
function stateChangedList(xmlhttp, fieldId)
   {
   if (xmlhttp.readyState == 4 || xmlhttp.readyState == "complete")
      {
	  document.getElementById(fieldId).innerHTML = xmlhttp.responseText;
      }
   }
   
function stateChangedPost(xmlhttp)
   {
   if (xmlhttp.readyState == 4 || xmlhttp.readyState == "complete")
      {
	  if (document.streamform.twitter.checked)
         ajaxRefresh('twitterrefresh');
      if (document.streamform.facebook.checked)
         ajaxRefresh('fbfresh');
      if (document.streamform.buzz.checked)
         ajaxRefresh('buzzrefresh');
      if (document.streamform.weibo.checked)
         ajaxRefresh('weiborefresh');
	  //document.streamform.happening.value = "What is happening?";
	  document.getElementById('streambox').innerHTML = "<textarea name=\"happening\" onpaste=\"textcount(this, 140, this.form.remain)\" onfocus=\"if (this.value == \"What is happening?\") this.value=\"\";textcount(this, 140, this.form.remain);\" onblur=\"if (this.value == \"\") this.value=\"What is happening?\";textcount(this, 140, this.form.remain);\" onkeydown=\"textcount(this, 140, this.form.remain)\" onkeyup=\"textcount(this, 140, this.form.remain)\" class=\"tweetarea\";>What is happening?</textarea>";
	  document.streamform.remain.value = 140;
	  
	  
      }
   else
      document.getElementById('streambox').innerHTML = "<span class='notice'>Posting...</span><img src='../images/loading.gif' width='100%' height='100%'/>";
   }
   
var twittertimerId = 0;
var fbtimerId = 0;
var buzztimerId = 0;
var weibotimerId = 0;
function autoRefresh(name, value)
   {
   var timeoutPeriod = value * 60000;
   var snaId = '';
   var timerId = 0;
   switch(name)
	   {
		case 'weiboautorefresh':
		   snaId = 'weiborefresh';
		   if (timeoutPeriod == 0)
              clearInterval(weibotimerId);
           else
              weibotimerId = setInterval(function() {ajaxRefresh(snaId);}, timeoutPeriod);
		   break;
		case 'buzzautorefresh':
		   snaId = 'buzzrefresh';
		   if (timeoutPeriod == 0)
              clearInterval(buzztimerId);
           else
              buzztimerId = setInterval(function() {ajaxRefresh(snaId);}, timeoutPeriod);
		   break;
		case 'fbautorefresh':
		    snaId = 'fbrefresh';
			if (timeoutPeriod == 0)
               clearInterval(fbtimerId);
            else
               fbtimerId = setInterval(function() {ajaxRefresh(snaId);}, timeoutPeriod);
			break;
		case 'twitterautorefresh':
		    snaId = 'twitterrefresh';
			if (timeoutPeriod == 0)
               clearInterval(twittertimerId);
            else
               twittertimerId = setInterval(function() {ajaxRefresh(snaId);}, timeoutPeriod);
			break;	
	    default:
		   alert("Error: Wrong field name: " + name);
		   break;
	   }
   //alert('Auto Refresh ' + value + ' ' + snaId);
 
   //document.getElementById('weiborefresh').click();
   //$('#weiborefresh').click();
   }
   
function ajaxRefresh(snaId)
   {
   document.getElementById(snaId).onclick();   
   }
 
function loadimg(fieldId){
	var id ='';
	switch(fieldId)
	   {
		case 'twitterlist':
		   id = 'refreshTweets';
		   break;
		case 'fblist':
		   id = 'refreshUpdates';
		   break;
		case 'buzzlist':
		    id = 'refreshBuzzs';
			break;
		case 'weibolist':
		    id = 'refreshWeibos';
			break;	
	    default:
		   alert("Error Wrong fieldId!");
		   break;
	   }
   	
   document.getElementById(id).innerHTML = "<span style='font-size:bold;'>Loading...</span><img src='../images/loading.gif' width='70%' height='70%'/>";
}

function hideimg(fieldId){
	var id ='';
	switch(fieldId)
	   {
		case 'twitterlist':
		   id = 'refreshTweets';
		   break;
		case 'fblist':
		   id = 'refreshUpdates';
		   break;
		case 'buzzlist':
		    id = 'refreshBuzzs';
			break;
		case 'weibolist':
		    id = 'refreshWeibos';
			break;
	    default:
		   alert("Error Wrong fieldId!");
		   break;
	   }
   document.getElementById(id).innerHTML = "";
}

function streamAjaxPost()
   {
   var xmlhttp = GetXmlHttpObject();
   if (xmlhttp == null)
      {
      alert ("Browser does not support HTTP Request!");
      return;
      }
   
   if (!verify(document.streamform))
      return;
   
   var post = document.streamform.happening.value;
   
   var parms = "";
   
   if (document.streamform.twitter.checked)
      parms += "&twitter=true";
   if (document.streamform.facebook.checked)
      parms += "&facebook=true";
   if (document.streamform.buzz.checked)
      parms += "&buzz=true";
   if (document.streamform.weibo.checked)
      parms += "&weibo=true";

   var url = "../include/streamit.php?post=" + post;
    if (parms != '')
      url = url + parms;
   //alert ("I am here22! " + url);
   url = url + "&sid=" + Math.random();
   xmlhttp.onreadystatechange=function(){stateChangedPost(xmlhttp)}
   xmlhttp.open("GET", url, true);
   xmlhttp.send(null);
   }


function getAjaxUpdates(myFunction, parms, fieldId)
   {
   var xmlhttp = GetXmlHttpObject();
   if (xmlhttp == null)
      {
      alert ("Browser does not support HTTP Request!");
      return;
      }
   
   var url = "../include/friendstream.php?func=" + myFunction;
   if (parms != '')
      url = url + "&parms=" + parms;
   //alert ("I am here22! " + url);
   url = url + "&sid=" + Math.random();
   xmlhttp.onreadystatechange=function(){stateChanged(xmlhttp, fieldId)}
   xmlhttp.open("GET", url, true);
   xmlhttp.send(null);
   }

function getAjaxList(myFunction, parms, fieldId)
   {
   var xmlhttp = GetXmlHttpObject();
   if (xmlhttp == null)
      {
      alert ("Browser does not support HTTP Request!");
      return;
      }
   
   var url = "../include/common.funcs.php?func=" + myFunction;
   if (parms != '')
      url = url + "&parms=" + parms;
   //alert ("I am here22! " + url);
   url = url + "&sid=" + Math.random();
   xmlhttp.onreadystatechange=function(){stateChangedList(xmlhttp, fieldId)}
   xmlhttp.open("GET", url, true);
   xmlhttp.send(null);
   }

function getAjaxTitle(title, fieldId)
   {
   var xmlhttp = GetXmlHttpObject();
   if (xmlhttp == null)
      {
      alert ("Browser does not support HTTP Request!");
      return;
      }

   var url = "../include/common.funcs.php?func=echo_title";
   url = url + "&parms=" + title;
   url = url + "&sid=" + Math.random();
   xmlhttp.onreadystatechange=function(){stateChanged(xmlhttp, fieldId)}
   xmlhttp.open("GET", url, true);
   xmlhttp.send(null);
   }
   
function getAjaxVideo(myFunc, video, fieldId)
   {
   var xmlhttp = GetXmlHttpObject();
   if (xmlhttp == null)
      {
      alert ("Browser does not support HTTP Request!");
      return;
      }

   var url = "../include/common.funcs.php?func=" + myFunc;
   url = url + "&parms=" + video;
   url = url + "&sid=" + Math.random();
   xmlhttp.onreadystatechange=function(){stateChanged(xmlhttp, fieldId)}
   xmlhttp.open("GET", url, true);
   xmlhttp.send(null);
   }

function zC(imgSrc, width, hight){
 var cWidth,cHeight;
 var popUpDiv = document.getElementById('cdtest');
 
 cWidth = screen.width;
 cHeight = screen.height;
 var cObj = document.createElement("div");
 cObj.setAttribute("id","cdiv");
 cObj.style.position = "fixed";
 cObj.style.top = 0;
 cObj.style.left = 0;
 cObj.style.backgroundColor = "#0C0C0C";
// cObj.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=25,finishOpacity=75";
 cObj.style.opacity = 0.65;
 cObj.style.filter = "alpha(opacity=65)";
 cObj.style.width = cWidth +"px";
 cObj.style.height = cHeight + "px";
 cObj.style.zIndex = "1000";
 document.body.appendChild(cObj);
 var iObj = document.createElement("img");
 //var iObj = new Image();
 iObj.src = imgSrc;
 iObj.setAttribute("id","cimg");
 iObj.style.position = "fixed";
 iObj.style.textAlign = "center";
 iObj.style.zIndex = "1001";
 iObj.style.cursor = "pointer";
 iObj.style.top = "50%";
 iObj.style.left = "50%"; 
 
 var imgWidth = typeof(width) != 'undefined' ? width : 0;
 var imgHight = typeof(hight) != 'undefined' ? hight : 0;
 
 if (imgWidth != 0)
    {
    iObj.style.width = imgWidth + "px";;
	iObj.style.marginLeft = 0 - (imgWidth / 2) + "px";
	}
 else
    {
    iObj.style.marginLeft = 0 - (iObj.width / 2) + "px";
	iObj.style.width = iObj.width;
	}
 
 if (imgHight != 0)
    {
    iObj.style.height = imgHight + "px";
	iObj.style.marginTop = 0 - (imgHight / 2) + "px";
	}
 else
    {
    iObj.style.marginTop = 0 - (iObj.height / 2) + "px";
	iObj.style.height = iObj.height;
	}
 
 iObj.onclick = function(){
  document.body.removeChild(iObj);
  document.body.removeChild(cObj);
  document.body.removeChild(closeObj);
 }
 
 iObj.onLoad=function(){}
 
 document.body.appendChild(iObj);
 
 var closeObj = new Image();
 closeObj.setAttribute("id","close");
 closeObj.style.position = "fixed";
 closeObj.style.left = "50%"
 closeObj.style.top = "50%"
 closeObj.style.marginLeft = (iObj.width / 2) + "px";
 closeObj.style.marginTop = iObj.style.marginTop;
 closeObj.style.width = "32px";
 closeObj.style.height = "32px";
 //closeObj.style.marginLeft = "-220px" ;
 //closeObj.style.marginTop = -200+"px";
 closeObj.style.textAlign = "center";
 closeObj.src = "../images/close-button.jpg";
 closeObj.style.zIndex = "1001";
 closeObj.style.cursor = "pointer";
 closeObj.onclick = function(){
  document.body.removeChild(iObj);
  document.body.removeChild(cObj);
  document.body.removeChild(closeObj);
 }
 
 document.body.appendChild(closeObj); 
}

function imgpreloader(imgSrc) {
  heavyImage = new Image(); 
  heavyImage.src = imgSrc;
}

/*
page_request.onreadystatechange=function(){
   loadpage(page_request, containerid)
}*/

function verify(f)
{
  var msg = "";
  var empty_fields = "";
  var errors = "";
  var uncheckedSNA = 0;

  // Loop through the elements of the form, looking for all
  // text and textarea elements that don't have an
  //  "optional" property defined.  Then, check for fields
  // that are empty and make a list of them.
  // Also, if any of these elements have a "min" or a "max"
  // property defined, then verify that they are numbers 
  // and that they are in the right range.
  // Put together error messages for fields that are wrong.
  for(var i = 0; i < f.length; i++)
  {
     var e = f.elements[i];	 	
	 
	 if (e.type == "checkbox" && (e.name == "twitter" || e.name == "facebook" || e.name == "buzz" || e.name == "weibo"))
        {
		   if (!e.checked)
		   {
			 //alert("I am here!\n" + e.name);
			 ++uncheckedSNA;
             continue;
		   }
	    }
		
	if (e.type == "textarea" && e.name == "happening")
	   {
		   if (e.value == "What is happening?" || isblank(e.value))
		   {
			 empty_fields += "\n        " +
                           e.name;
             continue;
		   }
	    }	
	 
  } // for each character in field
  
  // For friendstream
  if (uncheckedSNA == 4)
     {
	 errors += "____________________________________________________  _  _\n\n"
     errors += "Error: You have NOT selected any SNA network yet!\n"
     errors += "Please select at least one SNA network!\n";
     errors += "____________________________________________________  _  _\n\n"
     }

  // Now, if there were any errors, then display the
  // messages, and return true to prevent the form from
  // being submitted.  Otherwise return false
  if (!empty_fields && !errors)
  {
	 return true;
  }
  
  msg  = "____________________________________________________  _  _\n\n"
  msg += "The form was not submitted because of the " +
         "following error(s).\n";
  msg += "Please correct these error(s) and re-submit.\n";
  msg += "____________________________________________________  _  _\n\n"

  if (empty_fields)
  {
     msg += "- The following required field(s) are empty:"
           + empty_fields + "\n";
     if (errors)
        msg += "\n";
  }
  msg += errors;
  alert(msg);
  return false;
}

// A utility function that returns true if a string 
// contains only whitespace characters.
function isblank(s)
{
  for(var i = 0; i < s.length; i++)
  {
     var c = s.charAt(i);
     if ((c != ' ') &&
         (c != '\n') &&
         (c != '\t'))
        return false;
  }
  return true;
}

function strChinese(str){  
  var   pattern   =   /[\u4E00-\u9FA5]/;    
  return   pattern.test(str);  
  }  

var thumbExtArray = new Array(".gif", ".jpg", ".jpeg");
var videoExtArray = new Array(".flv");

// This function used to check the textarea's characters count
function textcount(message,total,remain)
{
   var max = total;
   if (message.value.length > max) {
       message.value = message.value.substring(0,max);
       remain.value = 0;
       alert("Your text can not excess " + total + " characters! Extra characters will be truncated!");
	   return false;
      }
   else {
        remain.value = max - message.value.length;
		return true;
      }
}