
/*====================================================
ITEMS
====================================================*/
//show("#user-info-modal","flex");
var itemIndex = -1;
$('.my-item').click(function(e){
    //console.log(dataUser);
    show("#user-info-modal","flex");
    itemIndex = this.querySelector("input").value;
    var getImageUser = this.querySelector(".icon-user").getAttribute('src');
    /*
    //datos = "p="+ datosJson.data[fila] ;
    $('#span_photo').html(dataUser.itemIndex[fila][1]);
    */

    $('#user-img').attr('src', getImageUser);
    $('#id_user').val(dataUser[itemIndex].id_usuario);
    $('#type_user').val(dataUser[itemIndex].tipo==0?"Administrador":"Cliente");
    $('[name="name"]').val(dataUser[itemIndex].nombre_completo);
    $('[name="email"]').val(dataUser[itemIndex].email);
    $('[name="path"]').val(dataUser[itemIndex].path_name);
    $('[name="last_time"]').val(dataUser[itemIndex].fecha_ultima_vez);
    $('[name="create_at"]').val(dataUser[itemIndex].fecha_registro);

    //console.log(dataUser[itemIndex].fecha_registro);
            
});

let idUser=null;
$('.js-add-license').click(function(e){
            
    idUser = $('#id_user').val();
    let typeUser = $('#type_user').val(); 
    if (typeUser == "Administrador"){
        hide(".msg-btn-aceptar");
        show("#_msg","flex");
        $('.msg-lbl-txt').text("No se le puede agregar licencia a un Administrador.");
    }
    else{
        hide("#user-info-modal");
        show("#add-pago-modal","flex");
    }
         
});

$('.js-delete-user').click(function(e){    
    show("#_msg","flex");
    $('.msg-lbl-txt').text("Esta seguro que desea eliminar a:");
    $('.msg-lbl-txt').append('<p class="msg-warning-txt" >'+dataUser[itemIndex].nombre_completo+'</p>');

});

$('.js-add-pago-cancel').click(function(e){    
    hide("#add-pago-modal");  
});
$('.js-add-license-cancel').click(function(e){    
    hide("#add-license-modal");  
});

$('#form-pay').on("submit",function(e){   
    $('[name="cod_license"]').val(generateLicense());
    $(".js-add-license-continue").removeAttr('disabled');
    $('#upload_progress').empty();
    show("#add-license-modal","flex");   
});
$('#form-license').on("submit",function(e){  
    //registered license
    var fd = new FormData(document.getElementById("form-pay"));
        fd.append("addLicense", true);
        fd.append("id_user", idUser);
        fd.append("cod_license", $('[name="cod_license"]').val());
        fd.append("years_license", $('[name="years_license"]').val());
        fd.append("months_license", $('[name="months_license"]').val());
        fd.append("days_license", $('[name="days_license"]').val());
			
        $row = $('#upload_progress')
				.append( $('<div class="progress_track"><div class="progress"></div>') )
                .append( $('<span class="current_progress" />').text("0%"));
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'admin',true);
        xhr.onload = function(e) {
            $(".js-add-license-continue").attr('disabled','disabled');
            $('#upload_progress').empty();
            $('#form-pay')[0].reset();
            $('#form-license')[0].reset();
            $('#upload_progress').text(e.target.response);
            
        };
        xhr.upload.onprogress = function(e){
            if(e.lengthComputable) {
                $row.find('.current_progress').text((e.loaded/e.total*100 | 0)+'%' );
                $row.find('.progress').css('width',(e.loaded/e.total*100 | 0)+'%' );
            }
        };
        xhr.onerror = function(e,i) {
            $row.find('.current_progress').text('0%' );
            $row.find('.progress').css('width','0%' );
            console.log(e);
        };

        xhr.send(fd);
        //console.log(  xhr);

		    
});


$('#close-info-user-modal').click(function(e){
    hide("#user-info-modal");      
});
$('#close-add-pago-modal').click(function(e){
    hide("#add-pago-modal");      
});
$('#close-add-license-modal').click(function(e){
    hide("#add-license-modal");      
});


function generateLicense(string_length = 20) {

    var chars = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    var license = '';
    for (var i=0; i<string_length; i++) {
         var rnum = Math.floor (Math.random() * chars.length); 
         license += chars.substring(rnum, rnum+1); 
    }
    return license;
}


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
            hide("#user-info-modal");
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
