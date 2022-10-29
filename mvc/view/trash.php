<?php 
    session_start();
    if(!session('data_user') ){
        href(BASE_URL."login");
        return;
    }

    include_once ROOT_PATH."library/DB.php";
    include_once ROOT_PATH."library/FileManager.php";
    include_once ROOT_PATH."mvc/model/TrashModel.php";
    include_once ROOT_PATH."mvc/controller/TrashController.php";
    $message=null;
    $section= "trash";

    $TrashController = new TrashController();

    $getImg = $TrashController->getImg();
    $getName = $TrashController->getName();
    $getCode = session('id_usuario');
    $getFileManagerValues = $TrashController->getValues(); 
    $pathDefault =  str_replace("drive/", "", $TrashController->getPathDefault());

    if (isset($_GET['list']) )
        $TrashController->listAllTrash($_GET['pathname']);
    else if ( isset($_GET['listSearch']) )
          $TrashController->searchTrash($_GET['pathname'], $_GET['search']);

   if (isset($_POST['restoreTrash']) )
        $TrashController->restoreTrash( $_POST['item']);

    else if (isset($_POST['deleteTrash']) )
        $TrashController->deleteTrash( $_POST['item'] );

    

 ?>

<!DOCTYPE html>
<html>
<head>
    
    <title>OrganizApp</title>
       
        <meta charset="UTF-8">
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="viewport" content="width-device-width, user-scalable=no, initial-scale=1.0, maiximum-scale1.0, minimum-scale=1.0">
     
        <link rel="icon"  href="<?= BASE_URL ?>public/img/icon/logoapp.png"">

    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/plugins/ka-f.fontawesome.v5.15.4.free.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/menu-vertical.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/menu-horizontal.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/home.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/folder.css">
  
    <!-- ===============================================================================
     MENU HORIZONTAL
    =================================================================================-->
    <?php include_once ROOT_PATH."mvc/view/general/horizontally_menu.php";?>
    
</head>
<body>
    <div class="cont-loader">
        <div class="loader"></div>
    </div>
    <div class="body">
        <!-- ===============================================================================
        BODY
        =================================================================================-->

        <div class="body-padre">
            <!-- breadcrumb -->
            <div class="text-path">
                <ul>
                    <li><img class="icon-back" src="<?= BASE_URL ?>public/img/icon/back.png"></li>
                    <li><div id="breadcrumb" >
                            <div>
                                <a href="#"></a><span> / </span><a href="#drive%2F<?= $pathDefault ?>"><?= $pathDefault ?></a><span>
                            </div>
                        </div> 
                    </li>
                </ul>
            </div>
            <!-- boton busqueda -->
            <div>
                  <div class="busqueda">
                    <ul>
                        <li>
                            <img class="icon-search" src="<?= BASE_URL ?>public/img/icon/search.png">
                        </li> 
                        <li>
                             <input id="search" type="text" class="form-control" name="bus" placeholder="Buscar">
                            
                        </li>
                    </ul>
                   </div>
            </div>
            <!-- filtrado de actividades por importante -->
            <div class="text-activity">
                  <h3 >Papelera</h3>  
            </div>
        </div>
    
        <!-- ===============================================================================
         ITEM
        =================================================================================-->
        <div  class="myflex-padre">
            <!-- diseño de los contenedores de tareas -->
            <div  class="myflex-hijo">
                <div id="list" data-pathname="null" class="center-item">
                <div  class="loader" ></div>
                    <!-- <div  class="my-item" >
                        <div class="selected hidde"><i class="fas fa-check"></i></div>
                        <input type="hidden" value="-1">
                        <i class="fas fa-folder"></i>
                        <i class="fas fa-file icon"></i>
                        <div class="active-tooltip" >
                            <p class="tooltip-content" > nombre </p>
                            <p class="item-name">nombre</p>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

         <!-- ===============================================================================
         INFO ITEM
        =================================================================================-->
        <div id="info_item" class="info-item-cont-modal" >
            <div class="info-item-container-form">
                <p id="name"><i class="fas fa-folder"></i><span>folder</span></p>
                <div class="info-item-line-form"></div>
                <div class="info-item-cont-txt">
                    <p class="info-item-txt">Tipo:</p>
                    <p id="type">Folder</p>
                </div>
                 <div class="info-item-cont-txt">
                    <p class="info-item-txt">Tamaño:</p>
                    <p id="size">20 MB</p>
                </div>
                 <div class="info-item-cont-txt">
                    <p class="info-item-txt">Contiene:</p>
                    <p id="contain">4 folders, 2 files</p>
                </div>
                <div class="info-item-cont-txt">
                    <p class="info-item-txt">Creado:</p>
                    <p id="create_at">Nov 25, 2021 12:40 AM</p>
                </div>
                <div id="cont_modified" class="info-item-cont-txt">
                    <p class="info-item-txt">Eliminado:</p>
                    <p id="modified">Nov 9, 2021 10:40 AM</p>
                </div>
                <div class="info-item-line-form"></div>
                  <div class="info-item-cont-txt">
                    <p class="info-item-txt">Atributos:</p>
                    <div class="info-item-space"></div>
                    <p>Writeable:<span id="perm_write">yes</span></p>
                    <p>Readable:<span id="perm_read">yes</span></p>
                    <p>Deleteable:<span id="perm_delete">yes</span></p>
                    <p>Executable:<span id="perm_exec">yes</span></p>
                </div>
                <div class="info-item-line-form"></div>
                <div class="info-item-cont-txt">
                    <!--button type="button"  class="info-item-button-left"> Descargar </button-->
                    <button type="button"  class="info-item-button-right"> Cerrar </button>
                </div>
            </div>
        </div>


        <!-- ===============================================================================
        MENU VERTICAL
        =================================================================================-->

        <!-- barra vertical -->
        <div id="sidemenu" class="menu-collapsed">
            <div id="header">
                <div id="menu-btn">
                    <div class="btn-hamburger"></div>
                    <div class="btn-hamburger"></div>
                    <div class="btn-hamburger"></div>
                </div>
            </div>
            <div id="menu-items">
                <div class="items">
                    <a href="<?= BASE_URL ?>home" class="group">
                        <span class="fas fa-home"></span>
                        <span class="title">Home</span>
                    </a>
                </div>

                <div id="menu_restore_trash" class="items">
                    <div class="group">
                        <span class="fas fa-trash-restore"></span>
                        <span class="title">Restaurar</span>
                    </div>
                </div>

                <div id="menu_delete_trash" class="items">
                    <div class="group">
                        <span class="fas fa-trash-alt"></span>
                        <span class="title">Eliminar</span>
                    </div>
                </div>

                <div class="items">
                    <a href="?" class="group">
                        <span class="fas fa-sync-alt"></span>
                        <span class="title">Recargar</span>
                    </a>
                </div>
            </div>
        </div>

    </div>


    
    
    <!-- ===============================================================================
    JS
    =================================================================================-->
    <script type="text/javascript">
        var BASE_URL = "<?= BASE_URL ?>";
        var section = "<?= $section ?>";
        var FileManager_values = '<?=  $getFileManagerValues ?>';
    </script>
    <script src="<?= BASE_URL ?>public/js/plugins/jquery.min.3.3.1.js"></script>
    <script src="<?= BASE_URL ?>public/js/plugins/sweetalert.min.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/all.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/menu-vertical.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/menu-horizontal.js"></script>
    <script src="<?= BASE_URL ?>public/js/trash.js"></script>

</body>

</html>