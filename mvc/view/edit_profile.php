<?php 
    session_start();
    if(!session('data_user') ){
        href(BASE_URL."login");
        return;
    }

    include_once ROOT_PATH."library/DB.php";
    include_once ROOT_PATH."mvc/model/EditProfileModel.php";
    include_once ROOT_PATH."mvc/controller/EditProfileController.php";
    $message=null;

    $EditProfileController = new EditProfileController();
    if (isset($_POST['edit_profile']) )
        $EditProfileController->updateProfile();


    /*======================================================================
    MSG
    ========================================================================*/
    if (isset($_GET['saved_profile']) )
        $message="Datos actualizados.";
    elseif (isset($_GET['not_saved_profile'])) {
        $message="Ocurrio un error al actualizar.";
    }
  

 ?>

<!DOCTYPE html>
<html>
<head>
	<title>OrganizApp</title>
        <meta charset="UTF-8">
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="viewport" content="width-device-width, user-scalable=no, initial-scale=1.0, maiximum-scale1.0, minimum-scale=1.0">
     
        <link rel="icon"  href="<?= BASE_URL ?>public/img/icon/logoapp.png">

    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/plugins/ka-f.fontawesome.v5.15.4.free.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/msg.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/menu-horizontal.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/edit_profile.css">
    
	<!-- ===============================================================================
     MENU HORIZONTAL
    =================================================================================-->

    <!-- barra_orizontal -->
    <header id="bar-header">
        <a href="<?=  $EditProfileController->redirMyHome(); ?>" ><img src="<?= BASE_URL ?>public/img/icon/logoapp.png" class="logo-bar"></a>
        <button class="btn-bar"><i class="bars"> &#9776; </i></button>
            <nav>   
                <li class="text-organizapp"><a href="<?=  $EditProfileController->redirMyHome(); ?>">OrganizApp </a></li>
                <ul class="perfil-menu-header">
                    <li><a href="<?=  $EditProfileController->redirMyHome(); ?>"> <span class="fas fa-arrow-left"/> </a> </li>
                </ul>
            </nav>
    </header>
</head>
<body>
	<h1 class="body-txt">Editando perfil</h1>


    <div  class="edit-perfil-cont-modal" >
        <form class="edit-perfil-container-form" action="<?= BASE_URL ?>edit_profile" method="POST" enctype="multipart/form-data">
            
            <input type="text" name="edit_profile"  value="edit" hidden="">

            <input type="text" name="img_changed"  value="0" hidden="">
            <label for="input_file" class="cont-img-profile">
                <img id="user-img" class="icon-edit-perfil-show" src="<?= $EditProfileController->getImg(); ?>">
                <span  class="fas fa-camera"></span>
            </label>
            <input id="input_file" type="file" name="img"  value="hola" hidden="">

            <div class="div-cont-label-input">
                <label class="lbl">Nombre Completo:</label>
                <input type="text" name="name_c" class="input-form-trasparent" value="<?= $EditProfileController->getName(); ?>" >
            </div>
            <div class="div-cont-label-input">
                <label class="lbl" >Nueva Contraseña:</label>
                <input type="password" name="new_pwd" class="input-form-trasparent" value="default" >
            </div>
             <div class="div-cont-label-input">
                <label class="lbl" >Confirmar Contraseña:</label>
                <input type="password" name="confirm_pwd" class="input-form-trasparent" value="default" >
            </div>
          
            <div class="edit-perfil-line-form"></div>
            <button type="subnit" class="edit-perfil-btn">Guardar cambios</button>
        </form>
    </div>


    <!-- ===============================================================================
    JS
    =================================================================================-->
    <script src="<?= BASE_URL ?>public/js/plugins/jquery.min.3.3.1.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/all.js"></script>
    <!-- ===============================================================================
     MSG
    =================================================================================-->
    <?php if ($message!=null) { 
                include_once ROOT_PATH."mvc/view/general/msg.php";
                echo "<script src=".BASE_URL."public/js/general/msg.js></script>";
          }
    ?>

    <script src="<?= BASE_URL ?>public/js/edit_profile.js"></script>
</body>
</html>