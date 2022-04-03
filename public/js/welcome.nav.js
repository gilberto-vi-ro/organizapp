/*!
 * welcome.nav.js v1.0.0
 * since 18/12/2020
 * Copyright 2020 Gilberto Villarreal Rodriguez <Gil_yeung@outlook.com>
 * Licensed open source
 */

/* ==========================================
		NAV HEADER
==========================================*/
 myScroll();
/*Detectamos cuando hay desplazamiento*/
window.onscroll = function(){
	myScroll();
}
/*Detectamos cuando se redimenciona la pantalla*/
window.onresize = function(){
	myScroll();
}

function myScroll(){
	/*Obtenemos la posicion de scroll*/
	var scroll = document.documentElement.scrollTop || document.body.scrollTop;
	$(".navbar").css({"top":"0px","transition": "none"});
	$(".navbar").addClass("top-nav-collapse");
	$(".remove-divider").addClass("divider");
	if (scroll < 90 && innerWidth>750){
	        //$(".navbar").css({"top":"10px","transition": "all 0.9s"});
			$(".navbar").removeClass("top-nav-collapse");
			$(".remove-divider").removeClass("divider");
			return;
	}else if (innerWidth>750){
		$(".remove-divider").removeClass("divider");
		return;
	}
	
	//$(".navbar-default").css({"background-color":"#3A4356"});

}


$(".ref-id").click(activeNav);

function activeNav(){
 	$(".ref-id").removeClass("active");
 	this.classList.add("active");
}

function activeNavId(id){
//navs = $(".ref-id a");
  /*for (let item of navs) {
      href = $(item).attr("href")
      if( href == id )
        item.parentNode.classList.add("active");
        //console.log(item);
      else
        item.parentNode.classList.remove("active");
  }
  console.log(navs.parent());
  */
}

$(".navbar-toggle").on("click",toggleMenu);
toggleMenu();
function toggleMenu()
{  
	var btn_toggle = $(".navbar-toggle");

    if( btn_toggle.hasClass('collapsed')){
    	btn_toggle.html("&times; ");
    	btn_toggle.addClass("bold");
    }
    else{ 
    	btn_toggle.html(" &#9776; ");
    	btn_toggle.addClass('collapsed');
    	btn_toggle.removeClass("bold");
    }
}
/* ==========================================
		LOADER
   ==========================================*/

$(window).on('load', function(){
	$("#loader").delay(100).fadeOut('slow');


});

/* ==========================================
		BODY
   ==========================================*/