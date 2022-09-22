<?php 
    session_start();
    $type = session('data_user')["tipo"];
    if(!session('data_user') || $type != 0){
        href(BASE_URL);
        return;
    }


    include_once ROOT_PATH."library/DB.php";
    include_once ROOT_PATH."library/FileManager.php";
    include_once ROOT_PATH."mvc/model/AdminModel.php";
    include_once ROOT_PATH."mvc/controller/AdminController.php";

    $message = null;
    $section= "admin";
    $AdminController = new AdminController();

    $listUsers= $AdminController->listUsers();

    if (isset($_GET['delete_user']) && isset($_GET['id']) )
         $AdminController->deleteUser($_GET['id']);

    if (isset($_POST['addLicense']) ) {
        $AdminController->addPayAndLicense();
    }
       
    /*======================================================================
    MSG
    ========================================================================*/
    if (isset($_GET['user_dlt']) )
        $message="El usuario se elimino correctamente.";
    elseif (isset($_GET['no_user_dlt'])) {
        $message="Ocurrio un error al borrar el usuario";
    }
    elseif (isset($_GET['is_admin'])) {
        $message="Error: no se puede eliminar al admin";
    }

 ?>


<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
        <meta charset="UTF-8">
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="viewport" content="width-device-width, user-scalable=no, initial-scale=1.0, maiximum-scale1.0, minimum-scale=1.0">
     
        <link rel="icon"  href="<?= BASE_URL ?>public/img/icon/logoapp.png"">

    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/plugins/ka-f.fontawesome.v5.15.4.free.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/msg.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/menu-horizontal.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/home.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/admin.css">
    
    
	<!-- ===============================================================================
     MENU HORIZONTAL
    =================================================================================-->

    <!-- barra_orizontal -->
    <header id="bar-header">
        <a href="<?= BASE_URL ?>admin" ><img src="<?= BASE_URL ?>public/img/icon/logoapp.png" class="logo-bar"></a>
        <button class="btn-bar"><i class="bars"> &#9776; </i></button>
            <nav>   
                <li class="text-organizapp"><a href="<?= BASE_URL ?>admin">OrganizApp </a></li>
                <ul class="menu-header" >
                    <li><a id="_admin" href="<?= BASE_URL ?>admin"> Admin </a> </li>
                    <li> <img class="icon-perfil" src="<?=  $AdminController->getImg(); ?>"></li>
                    <div id="_perfil" class="perfil-cont-modal">
                        <div class="perfil-container-form">
                            <img class="icon-perfil-show" src="<?=  $AdminController->getImg(); ?>">
                            <p><i class="fas fa-user"></i><?=  $AdminController->getName(); ?></p>
                            <div class="perfil-line-form"></div>
                            <a href="<?= BASE_URL ?>edit_profile"> <p class="perfil-txt"><i class="fas fa-user-edit"></i>Editar perfil</p></a>
                            <a href="<?= BASE_URL ?>general/logout"> <p class="perfil-txt"><i class="fas fa-sign-in-alt"></i>Cerrar sesion</p></a>
                        </div>
                    </div>
                </ul>
            </nav>
    </header>

</head>
<body>



         <!-- ===============================================================================
         SECTION
        =================================================================================-->

        <div class="text-activity">
                <h3 >Usuarios</h3>  
        </div>
        <div  class="myflex-padre">
            <!-- diseño de los contenedores de tareas -->
            <!-- contenedor numero 1 -->
            <div  class="myflex-hijo">
                <div class="center-item">

                    <?php 
                    $i=0;
                    foreach  ( $listUsers  as $data):  
                
                    if ($data->img == null) 
                         $img = BASE_URL."public/img/icon/user.png"; 
                     else 
                        $img = $data->img; ?> 

                     <div  class="my-item">
                        <input type="hidden" value="<?= $i ?>">
                        <img class="icon-user" src="<?= $img ?>" >
                         <div class="active-tooltip" >
                            <p class="tooltip-content" > <?= $data->nombre_completo; ?> </p>
                            <p class="item-name"><?= $data->email; ?></p>
                        </div>
                    </div>
                    <?php $i++; endforeach; ?>
                   
                </div>
            </div>
        </div>

        <!-- ===============================================================================
         INFO USER
        =================================================================================-->

        <div id="user-info-modal" class="user-info-modal" >
            <div class="user-info-container">
                <p id="close-info-user-modal" class="close-times">&times;</p>
                <input id="id_user" type="hidden" value="0000">
                <img id="user-img" class="icon-perfil-show" src="<?= BASE_URL ?>public/img/icon/user.png">
               
                <div class="div-cont-label-input">
                    <label class="lbl">Tipo:</label>
                    <input type="text" id="type_user" class="input-form-trasparent" value="tipo" disabled="disabled">
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Nombre:</label>
                    <input type="text" name="name" class="input-form-trasparent" value="nombre" disabled="disabled">
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Email:</label>
                    <input type="text" name="email" class="input-form-trasparent" value="Email" disabled="disabled">
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Path:</label>
                    <input type="text" name="path" class="input-form-trasparent" value="path/" disabled="disabled">
                </div>
                 <div class="div-cont-label-input">
                    <label class="lbl">Ultima vez:</label>
                    <input type="text" name="last_time" class="input-form-trasparent" value="ultima vez" disabled="disabled">
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Fecha de registro:</label>
                    <input type="text" name="create_at" class="input-form-trasparent" value="registro" disabled="disabled">
                </div>
                <div class="user-line-form"></div>
                <div class="cont-btn">
                    <button type="button" class="btn js-add-license">Agregar licencia</button>
                    <button type="button" class="btn js-delete-user">Eliminar usuario</button>
                </div>
                
            </div>
        </div>

        <!-- ===============================================================================
        ADD PAGO
        =================================================================================-->
        <div id="add-pago-modal" class="user-info-modal" >
            <form id="form-pay" class="user-info-container" onsubmit="return false">
                <p id="close-add-pago-modal" class="close-times">&times;</p>
                <br>
                <label class="title-lbl-txt">Detalles del pago</label>
                <div class="user-line-form"></div>
                
                <div class="div-cont-label-input">
                    <label class="lbl">Monto:</label>
                    <input type="number" name="pay_amount" class="input-form-trasparent" placeholder="0" required>
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Metodo de Pago:</label>
                    <input type="text" name="pay_method" class="input-form-trasparent" placeholder="efectivo, targeta, online" pattern="|efectivo|targeta|online|" required>
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Estado del pago:</label>
                    <input type="text" name="pay_status" class="input-form-trasparent" placeholder="error, pendiente, completado" pattern="|error|pendiente|completado|" required>
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Descripcion:</label>
                    <input type="text" name="pay_description" class="input-form-trasparent" placeholder="......" >
                </div>
                 
                <div class="user-line-form"></div>
                <div class="cont-btn">
                    <button type="button" class="btn js-add-pago-cancel">Cancelar</button>
                    <button type="submit" class="btn js-add-pago-continue">Continuar</button>
                </div>
                
            </form>
        </div>

        <!-- ===============================================================================
        ADD LICENSE
        =================================================================================-->
        <div id="add-license-modal" class="user-info-modal" >
            <form id="form-license" class="user-info-container" onsubmit="return false">
                <p id="close-add-license-modal" class="close-times">&times;</p>
                <br>
                <label class="title-lbl-txt">Detalles de la licencia</label>
                <div class="user-line-form"></div>
                <input id="id_user" type="hidden" value="0000">
                <div class="div-cont-label-input">
                    <label class="lbl">Codigo de Licencia:</label>
                    <input type="text" name="cod_license" class="input-form-trasparent" placeholder="AAAAAAAAAAAAAA">
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Años:</label>
                    <input type="number" name="years_license" class="input-form-trasparent" value="0" min="0" max="9" required>
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Meses:</label>
                    <input type="number" name="months_license" class="input-form-trasparent" value="0" min="0" max="12" required>
                </div>
                <div class="div-cont-label-input">
                    <label class="lbl">Dias:</label>
                    <input type="number" name="days_license" class="input-form-trasparent" value="0" min="0" max="31" required>
                </div>
                 
                <div id="upload_progress"></div>
                <div class="user-line-form"></div>
                <div class="cont-btn">
                    <button type="button" class="btn js-add-license-cancel">Cancelar</button>
                    <button type="submit" class="btn js-add-license-continue">Continuar</button>
                </div>
                
            </form>
        </div>

     <!-- ===============================================================================
    MSG
    =================================================================================-->
    <?php 
        include_once ROOT_PATH."mvc/view/general/msg.php";
        if ($message!=null) { 
                    echo ' <script type="text/javascript"> var showMsg = true ; </script>';
              }else{
                    echo ' <script type="text/javascript"> var showMsg = false ; </script>';
              }
        ?>
      
    <!-- ===============================================================================
    JS
    =================================================================================-->
    <script type="text/javascript">
        var dataUser = <?=  json_encode($listUsers); ?> ;
        var BASE_URL = "<?=  BASE_URL ?>" ;
        var section = "<?= $section ?>";
    </script>
   
    <script src="<?= BASE_URL ?>public/js/plugins/jquery.min.3.3.1.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/all.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/menu-horizontal.js"></script>
	<script src="<?= BASE_URL ?>public/js/admin.js"></script>
 

</body>
</html>