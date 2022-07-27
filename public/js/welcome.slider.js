
var currentSlide=0;
var contSlider = $(".cont-slider .slider"); 
var itemSlide = $(".item-slide a");
activeSlide();


////////////////////////auto cambiar img/////////////////////
let timer = setInterval(function() {showSlides(false, +1);}, 3000);
$("._next").click(function(){
    showSlides(false, +1)
    clearTimeout(timer);

});

$("._prev").click(function(){
      showSlides(false,-1);
      clearTimeout(timer);
     
});

$(".cont-slider").click(function(){
        clearTimeout(timer);
       timer = setInterval(function() {showSlides(false, +1);}, 3000);
});

////////////////////////slides/////////////////////
$(".item-slide a").on("click",function(e){
  var i = $(this).index();
  showSlides(i,true);
  clearTimeout(timer);
}); 

function activeSlide(){

  var i = $(".item-slide .active").index();
  showSlides(i,false);
}

function showSlides(index = false, action = false) {
     
      var nextSlide = currentSlide;//animation


      if (index === false )
          index = currentSlide += action;
      else if(action === false)
          currentSlide=index;
      else
      {
        action = index > nextSlide? +1 : (index == nextSlide? 0 : -1);//animation
        currentSlide=index;
      }
     
      if (index >= contSlider.length) {currentSlide = 0}
      if (index < 0) {currentSlide = contSlider.length-1}
       
      for (i = 0; i < contSlider.length; i++) {
        $(contSlider[i]).css({"opacity" : "0.0","z-index" : "0"});
        if(itemSlide.length<contSlider.length)return;
        itemSlide[i].classList.remove("active");
        $(contSlider[i]).removeClass("mov-left");//animation
        $(contSlider[i]).removeClass("mov-right");//animation
        $(contSlider[i]).removeClass("a-opacity");//animation
        $(contSlider[i]).removeClass("mov-left-x2");//animation
        $(contSlider[i]).removeClass("mov-right-x2");//animation
      }
      
      $(contSlider[currentSlide]).css({"opacity" : "1","z-index" : "1"});
      itemSlide[currentSlide].classList.add("active");
      if(contSlider.length<=1) return;
      transitionSlide( currentSlide,nextSlide,action);//animation
    
  }


function transitionSlide(currentSlide,nextSlide,action ){
 
   var nextSlide = contSlider[nextSlide];
   var currentSlide = contSlider[currentSlide];

 /* $(contSlider).parent().css({"overflow": "none",
                            "overflow": "hidden",
                          });*/
  if(action === -1){
      $(nextSlide).addClass("mov-left");
      $(currentSlide).addClass("mov-left-x2");
    
  }else if(action === +1){
       $(nextSlide).addClass("mov-right");
       $(currentSlide).addClass("mov-right-x2");
  }
  else{
    $(nextSlide).addClass("a-opacity");
  }

 
 
}