
/*=============================================================================
 VERIFY PASSWORD
===============================================================================*/
    var pwd = $$('[name="pwd"]');
    var repeat_pwd = $$('[name="repeat_pwd"]');
    var btn = $$(".btn-login");

    function verifyPwd(){
        if ( pwd.value == repeat_pwd.value){
            pwd.parentNode.style.border = '1px solid #ccc';
            repeat_pwd.parentNode.style.border = '1px solid #ccc';
            btn.removeAttribute("disabled");
            //btn.style.background = '#4CAF50';
            btn.style.cursor = 'pointer';

        }else{
            pwd.parentNode.style.border = '2px solid red';
            repeat_pwd.parentNode.style.border = '2px solid red';
            btn.setAttribute("disabled","");
            //btn.style.background = '#ccc';
            btn.style.cursor = 'no-drop';
        }
    }

    if ( $$('[name="repeat_pwd"]') ) {

        $$('[name="repeat_pwd"]').onkeyup=function(){
             verifyPwd();
        }

        $$('[name="pwd"]').onkeyup=function(){
            verifyPwd();
        }
    }

// /*=============================================================================
//  CAPTCHA    
// ===============================================================================*/
// changeCaptcha();
// function changeCaptcha() {

//     var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
//     // Puede incluir caracteres especiales agregándolos a la cadena anterior, // caracteres especiales como por ejemplo: += "@#?<>";
    
//     var string_length = 6; // Esta es la longitud del Captcha.
//     // ****** PRECAUCIÓN ****** Esto solo determina la cadena que producirá la función.
//     // Para que el campo Captcha sea compatible con el tamaño actualizado, // deberá cambiar el atributo maxlength en el código HTML.
//     var changeCaptcha = '';
//     for (var i=0; i<string_length; i++) {
//          var rnum = Math.floor (Math.random() * chars.length); 
//          changeCaptcha += chars.substring(rnum, rnum+1); 
//     }
//     // Parte final que cambia el valor del campo al Captcha producido.
//     var randomfield = document.getElementById('randomfield');
//     randomfield.value = changeCaptcha;
//     randomfield.readOnly = true;
//     randomfield.onmouseover= function(){randomfield.value=""};
//     randomfield.onmouseout= function(){randomfield.value=changeCaptcha};
//     randomfield.oncopy = function(){return false};
    
// }

// $("#sign-in").on('submit',function( event ){ //abrir modal captacha al dar submit en  ##sign-in

//      //se verifica que el valor del captcha puesto en index coincida con el generado. 
//     if(document.getElementById('captchaEnter').value == document.getElementById('randomfield').value ) {
//          return true ;
    
//     }else {
//         // El mensaje de alerta que se mostrará cuando el usuario ingrese un Captcha incorrecto 
//         alert('Please verify the captcha');
//     }
//     return false ;
// });

// $(".msg-btn-refresh").on('click',function( event ){ //abrir modal captacha al dar submit en  ##sign-in
//     changeCaptcha();
//     captchaEnter.value="";
// });
// $(".msg-btn-ok").on('click',function( event ){ //abrir modal captacha al dar submit en  ##sign-in
//         hide("#captcha");
//         if(document.getElementById('captchaEnter').value == document.getElementById('randomfield').value ) {
//             $(".login-captcha").html('<i class="fas fa-check-circle"></i><label for="" >Captacha</label>');
//         }else{
//             $(".login-captcha").html('<i class="fas fa-exclamation-triangle"></i><label for="" >Captacha</label>');
//         }
// });

// $(".login-captcha").on('click',function( event ){ //abrir modal captacha al dar submit en  ##sign-in
//     show("#captcha");
// });
    
/*=============================================================================
 RECOVER PASSWORD
===============================================================================*/
$("#recover_pwd").on('click',function( event ){ //abrir modal captacha al dar submit en  ##sign-in
    show("#cont_recover_pwd");
});

$(".msg-btn-close").on('click',function( event ){ //abrir modal captacha al dar submit en  ##sign-in
    hide("#cont_recover_pwd");
    res_recover_pwd.innerHTML = "";
    recover_email.value = "";
});

$(".msg-btn-send").on('click',function( event ){ //abrir modal captacha al dar submit en  ##sign-in
    res_recover_pwd.innerHTML = "...";
    var email = recover_email.value;
    $.post("login?",{'email':email, "recoverEmail":1  }
    ,'json').done(function(response){
        res_recover_pwd.innerHTML = response;
    });
});
/*=============================================================================
 Show Hide PASSWORD
===============================================================================*/
function oculPassword(){
    var value=document.getElementById("showPwd");
    if (value.type=="password") {
        value.type="text";
    }else{
        value.type="password";
    }
    

}
showEye.onclick = function(){
    showEye.style.display = "none";
    hideEye.style.display = "block";
    oculPassword();
}
hideEye.onclick = function(){
    hideEye.style.display = "none";
    showEye.style.display = "block";
    oculPassword();
}

