/**
 * @fileoverview msg.js
 * @version      1.0.0
 * @author       Gilberto Villarreal Rodriguez <gil_yeung@outlook.com>
 * @link         https://myproyecto.com/
 * @copyright    myproyecto.com
 * desc          ...
 * @since        18/12/2020
 * @license      License Open Source
 */ 
/*=============================================================================
 MSG
===============================================================================*/
    show('#_msg');

   //var closeBtnC = document.getElementsByClassName("js-btnCancel")[0];
    $$(".msg-btn-cancel").onclick=function(){
        _msg.style.display = "none";
    }

    $$(".msg-btn-aceptar").onclick=function(){
           _msg.style.display = "none";
          
           history.pushState(null,"","?");
    }

    /////////////////////////////////////////////////////////////
	// Get the modal msg aside 
    var modal = $$(".msg-cont-modal");
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            _msg.style.display = "none";
        }
    }
    ///////////////////////////////////////////


