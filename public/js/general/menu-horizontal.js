/*====================================================
 MENU HORIZONTAL HEADER
 ====================================================*/
var menu_header = document.querySelector(".menu-header");
var btn_bar = document.querySelector('.btn-bar');


function toggleMenu()
{
    if( menu_header.className.indexOf('active')>=0 ){
        menu_header.classList.remove("active");
        btn_bar.innerHTML="<i class='bars' > &#9776; </i>";
        _perfil.classList.remove("active");
    }
    else{ 
        menu_header.className += " active";
        btn_bar.innerHTML="<i class='times' > &times; </i>";
    }
}

/* Close menu and submenu From Anywhere */
function closeAll(e) {
    
    let menuContainer = menu_header.contains(e.target);
    let btnBarContainer = (e.target.parentNode == null)? true : false; 
   
    if (!menuContainer) 
    {
        if (!btnBarContainer && !btn_bar.contains(e.target)) 
        {
            if( menu_header.className.indexOf('active')>=0 ){
                menu_header.classList.remove("active");
                btn_bar.innerHTML="<i class='bars' > &#9776; </i>";
            }
        }
    }  
}

btn_bar.addEventListener("click", toggleMenu, true);
document.addEventListener("click", closeAll, false);

/*====================================================
PERFIL
====================================================*/
   

   //var closeBtnC = document.getElementsByClassName("js-btnCancel")[0];
    $$(".icon-perfil").onclick=function(){
        if( _perfil.className.indexOf('active')>=0 ){
            _perfil.classList.remove("active");
        }
        else{ 
            _perfil.className += " active";
            
        }

    }

    /////////////////////////////////////////////////////////////
    // Get the modal msg aside 
    var modal = $$(".perfil-container-form");
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {

        var modal = document.querySelector(".perfil-cont-modal");
        var btn_perfil = document.querySelector(".icon-perfil");
      
      
        if (!modal.contains(event.target)) 
        {
            if (  !btn_perfil.contains(event.target)) 
            {
                if( modal.className.indexOf('active')>=0 ){
                    modal.classList.remove("active");
                }
            }
        }  
    }
    ///////////////////////////////////////////

/*====================================================
NOTIFICATION WITH CSS
====================================================*/

/* count notification */
//setInterval(countNotification, 1000);
countNotification();
function countNotification() {
  $.get('notification?countNotification' ,'json').done(function(data) {
    //console.log(data);
    document.documentElement.style.setProperty('--notification', "'"+data+"'");
  });
  return true;
}

/*====================================================
SELECTED SECTION
====================================================*/

    if (section == "home") //si la seccion o pagina es = a emp_vender
      document.getElementById("_home").classList.add("active-section");// busca el id en html y le agrega una clase css
    else if (section == "folder") //sino, entonces la seccion o pagina es = a emp_ventas
      document.getElementById("_folder").classList.add("active-section");// busca el id en html y le agrega una clase css
    else if (section == "trash") //sino, entonces la seccion o pagina es = a emp_ventas
      document.getElementById("_trash").classList.add("active-section");// busca el id en html y le agrega una clase css
    else if (section == "notification") //sino, entonces la seccion o pagina es = a emp_ventas
      document.getElementById("_bell").classList.add("active-section");// busca el id en html y le agrega una clase css
    else if (section == "admin") //sino, entonces la seccion o pagina es = a emp_ventas
      document.getElementById("_admin").classList.add("active-section");// busca el id en html y le agrega una clase css
