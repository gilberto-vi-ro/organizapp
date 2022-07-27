
/*=============================================================================
 EDIT PROFILE
===============================================================================*/
   var pwd = $$('[name="new_pwd"]');
    var repeat_pwd = $$('[name="confirm_pwd"]');
    var btn = $$(".edit-perfil-btn");

    function verifyPwd(){
        if ( pwd.value == repeat_pwd.value){
            pwd.style = {'border-buttom': '1px solid #DBDCDE '};
            repeat_pwd.style = {'border-buttom': '1px solid #DBDCDE '};
            btn.removeAttribute("disabled");
            //btn.style.background = '#4CAF50';
            btn.style.cursor = 'pointer';

        }else{
            pwd.style.border = '2px solid red';
            repeat_pwd.style.border = '2px solid red';
            btn.setAttribute("disabled","");
            //btn.style.background = '#DBDCDE ';
            btn.style.cursor = 'no-drop';
        }
    }


    $$('[name="new_pwd"]').onkeyup=function(){
         verifyPwd();
    }

    $$('[name="confirm_pwd"]').onkeyup=function(){
        verifyPwd();
    }
    


/*=============================================
PREVISUALIZAR IMAGEN
=============================================*/
function readImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (img) {
            $('#user-img').attr('src', img.target.result); // Renderizamos la imagen

        }
        reader.readAsDataURL(input.files[0]);
    }
}
//console.log(img.target.result);
$("#input_file").change(function () {
    // Código a ejecutar cuando se detecta un cambio de archivo
    readImage(this);
    $('[name="img_changed"]').val("1");
});