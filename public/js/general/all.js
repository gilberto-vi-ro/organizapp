
/*!
 * all.js v1.0.0
 * since 18/12/2020
 * Copyright 2020 Gilberto Villarreal rodriguez
 * 				  Gil_yeung@outlook.com
 * Licensed open source
 */

/*====================================================
TOOLTIP
====================================================*/



  $(document).delegate(".tooltip_elemento " ,'mouseenter',function( e){//hover
      //var posMouse = e.pageX - innerWidth/2; 
      var textTooltip = $(this).attr("data-tooltip"); 
  			//console.log(posMouse);
        //console.log(innerWidth);

      if (textTooltip.length < 17) {
        posMouse = 3;
      }
      $(this).append('<div class="tooltip">' + textTooltip + '</div>');
      $("p> div.tooltip").css({
                                    "left": "" + -10  + "px",
                                  });
      $("p> div.tooltip").fadeIn(30);
  });


  $(document).delegate(".tooltip_elemento " ,'mouseleave',function( e){//hover
        $("p> div.tooltip").fadeOut(30).delay(30).queue(function () {
          $(this).remove();
          $(this).dequeue();
        });
  });


/*=============================================
RETURN OBJECT FUNCTION
=============================================*/
function $$(selector) {
        return document.querySelector(selector);
}

/*=============================================
SHOW HIDDEN
=============================================*/
 function show(selector, display ="block") {
         document.querySelector(selector).style.display = display;
 }
 function hide(selector) {
         document.querySelector(selector).style.display = 'none';
 }

 
/* Encode BASE64 */
function utf8_to_b64( str ) {
  return window.btoa(unescape(encodeURIComponent( str )));
}
/* Decode BASE64 */
function b64_to_utf8( str ) {
  return decodeURIComponent(escape(window.atob( str )));
}

/*converter mtime a TimeStamp */
function formatTimestamp(unix_timestamp) {
	var m = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
	var d = new Date(unix_timestamp*1000);
	return [d.getDate(),' ',m[d.getMonth()],', ',d.getFullYear()," ",
		(d.getHours() % 12 || 12),":",(d.getMinutes() < 10 ? '0' : '')+d.getMinutes(),
		" ",d.getHours() >= 12 ? 'PM' : 'AM'].join('');
}

/*converter bytes a KB ,MB, GB, TB, PB, EB */
function formatFileSize(bytes) {
	var s = ['bytes', 'KB','MB','GB','TB','PB','EB'];
	for(var pos = 0;bytes >= 1000; pos++,bytes /= 1024);
	var d = Math.round(bytes*10);
	return pos ? [parseInt(d/10),".",d%10," ",s[pos]].join('') : bytes + ' bytes';
}

/*
* dar formato a la fecha
  @param date date 
  @param string format
  */
  function dateFormat(date, format) {
    let day=date.getDate(), month=date.getMonth() + 1 ;
    let hour=date.getHours(), min=date.getMinutes(),  sec=date.getSeconds();
    const map = {
      dd: day<10 ? "0"+day : day,
      mm: month<10 ? "0"+month : month,
      yy: date.getFullYear().toString().slice(-2),
      yyyy: date.getFullYear(),
      hh: hour<10&&hour!=0? "0"+(hour>12 ? hour-12:hour==0?12:hour) : (hour>12 ? hour-12:hour==0?12:hour),
      HH: hour<10 ? "0"+hour: hour,
      ii: min<10 ? "0"+min: min,
      ss: sec<10 ? "0"+sec: sec,
      ampm: hour >= 12 ? 'pm' : 'am'
    }
  
    return format.replace(/dd|mm|yyyy|yy|hh|HH|ii|ss|ampm/gi, matched => map[matched]);
  }

/*====================================================
LOAD AJAX
====================================================*/

function loadAjax(_url, _type , _data=null, _async=false){

    let response=null;
    let res = $.ajax({
          async: _async, //true = ASynchronous, false = Synchronous
          url: _url,
          type: _type,
          datatype: "json",
          data: _data,
          cache:false,
          contentType:false,
          processData:false,
        });
    res.done(function(resp){
        //console.log(response);
        response = resp;
    });
    res.fail(function(request, status, error){
        //console.log(response);
        response = error;
    });
    //console.log(response);
    return response;
}


/* count notification */
setInterval(countNotification, 1000);
function countNotification() {
  $.get('notification?countNotification' ,'json').done(function(data) {
    //console.log(data);
    document.documentElement.style.setProperty('--notification', "'"+data+"'");
  });
  return true;
}
