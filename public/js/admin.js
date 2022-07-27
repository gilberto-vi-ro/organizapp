
/*====================================================
ITEMS
====================================================*/
//show("#user-modal","flex");
var itemIndex = -1;
$('.my-item').click(function(e){
            //console.log(dataUser);
            show("#user-modal","flex");
            itemIndex = this.querySelector("input").value;
            var getImageUser = this.querySelector(".icon-user").getAttribute('src');
            /*
            //datos = "p="+ datosJson.data[fila] ;
            $('#span_photo').html(dataUser.itemIndex[fila][1]);
            */

            $('#user-id').attr('src', getImageUser);
            $('[name="name"]').val(dataUser[itemIndex].nombre_completo);
            $('[name="path"]').val(dataUser[itemIndex].path_name);
            $('[name="ultima_vez"]').val(dataUser[itemIndex].fecha_ultima_vez);
            $('[name="create_at"]').val(dataUser[itemIndex].fecha_registro);

            //console.log(dataUser[itemIndex].fecha_registro);
            
});


$('.js-delete-user').click(function(e){
            
           show("#_msg","flex");
           $('.msg-lbl-txt').text("Esta seguro que desea eliminar a:");
           $('.msg-lbl-txt').append('<p class="msg-warning-txt" >'+dataUser[itemIndex].nombre_completo+'</p>');

            
});

$('.close-user-container').click(function(e){
            hide("#user-modal");
           
            
});


/*=============================================================================
 MSG
===============================================================================*/
    //var showMsg = true;
    if (showMsg) {
        show("#_msg","flex");
        //history.pushState(null,"","?");
    }
   //var closeBtnC = document.getElementsByClassName("js-btnCancel")[0];
    $$(".msg-btn-cancel").onclick=function(){
       
        hide("#_msg");
    }

    $$(".msg-btn-aceptar").onclick=function(){
        hide("#_msg");
        history.pushState(null,"","?");
        if (itemIndex != -1) {
            var idUser=dataUser[itemIndex].id_usuario;
            window.location.href = BASE_URL+'admin?delete_user=1&id='+idUser;
        }
        
    }

/*=============================================================================
 CLOSE ALL MODAL
===============================================================================*/
    // Get the modal msg aside 
    var modalMsg = $$(".msg-cont-modal");
    var userModal = $$(".user-info-modal");
  

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {

         /*close modal msg*/
        if (event.target == modalMsg) {
            hide("#_msg");
        }

        /*close modal info user*/
        if (event.target == userModal) {
            hide("#user-modal");
        }

        /*close modal perfil*/
        //var modalPerfil = $$(".perfil-container-form");
        var modalPerfil = document.querySelector(".perfil-cont-modal");
        var btn_perfil = document.querySelector(".icon-perfil");
      
      
        if (!modalPerfil.contains(event.target)) 
        {
            if (  !btn_perfil.contains(event.target)) 
            {
                if( modalPerfil.className.indexOf('active')>=0 ){
                    modalPerfil.classList.remove("active");
                }
            }
        }  
    }
