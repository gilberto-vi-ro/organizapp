<?php 
    session_start();
    if(!session('data_user') ){
        href(BASE_URL."login");
        return;
    }

    include_once ROOT_PATH."library/DB.php";
    include_once ROOT_PATH."library/FileManager.php";
    include_once ROOT_PATH."mvc/model/FolderModel.php";
    include_once ROOT_PATH."mvc/controller/FolderController.php";
    $message=null;
    $section= "folder";

    $FolderController = new FolderController();
    
    $getImg = $FolderController->getImg();
    $getName = $FolderController->getName();
    $getCode = session('id_usuario');
    $getFileManagerValues = $FolderController->getValues(); 
    $pathDefault =  str_replace("drive/", "", $FolderController->getPathDefault());

    

    if ( isset($_GET['list']) )
        $FolderController->listAll( $_GET['pathname'] );

    else if ( isset($_GET['listSearch']) )
          $FolderController->search( $_GET['pathname'], $_GET['search'] );

    else if ( isset($_GET['showFile']) )
          $FolderController->showFile( $_GET['pathname'], $_GET['extension'] );
    else if ( isset($_GET['download']) )
        $FolderController->download( $_GET['pathname'], $_GET['is_dir'] );


    if ( isset($_POST['createFolder']) )
        $FolderController->createFolder( $_POST['pathname'], $_POST['name'] );

    else if ( isset($_POST['rename']) )
        $FolderController->rename( $_POST['oldPathname'],  $_POST['newname'] );

    else if ( isset($_POST['move']) )
        $FolderController->move( $_POST['newPathname'], $_POST['item'] );

    else if ( isset($_POST['delete']) )
        $FolderController->delete( $_POST['item'] );

    else if ( isset($_POST['upload']) )
        $FolderController->upload( $_POST['pathname'], $_FILES );


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
            <div >
                <div class="busqueda">
                    <ul>
                        <li>
                            <img class="icon-search" src="<?= BASE_URL ?>public/img/icon/search.png">
                        </li>
                        <li>
                             <input id="search" type="text" class="form-control" name="bus" placeholder="Buscar" >
                        </li>

                    </ul>
                </div>
            </div>
            <!-- filtrado de actividades por importante -->
            <div class="text-activity">
                  <h3 >Carpetas</h3>  
            </div>
        </div>
      
           
          
        <!-- ===============================================================================
         ITEM
        =================================================================================-->
        <div  class="myflex-padre">
            <!-- diseño de los contenedores de tareas -->
            <div  class="myflex-hijo">
                <div id="list" data-pathname="null" class="center-item">
                    <div  class="my-item" >
                        <div class="selected hidde"><i class="fas fa-check"></i></div>
                        <input type="hidden" value="-1">
                        <i class="fas fa-folder"></i>
                        <i class="fas fa-file"></i>
                        <p data-tooltip="nombre item" class="tooltip_elemento"><span class="item-name">nombre item</span></p>
                        <div class="tooltip">Texto del tooltip</div>
                    </div>
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
                    <p class="info-item-txt">Path:</p>
                    <textarea id="path"  disabled="" class="info-item-textarea" >path/default</textarea>
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
                    <p class="info-item-txt">Modificado:</p>
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
                    <button type="button"  class="info-item-button-right"> Cerrar </button>
                    <button type="button"  class="info-item-button-left js_btn_download"> Descargar </button>
                </div>
            </div>
        </div>

         <!-- ===============================================================================
        SHOW FILE
        =================================================================================-->
        <div id="show_file" class="info-item-cont-modal">
            <div class="info-item-container-form">
                <div id="cont_file"></div>
                <div class="info-item-line-form"></div>
                <div class="info-item-cont-txt">
                    <button type="button"  class="info-item-button-right"> Cerrar </button>
                    <button id="btn_download_file" type="button"  class="info-item-button-left js_btn_download"> Descargar </button>
                </div>
            </div>
        </div>
        <!-- ===============================================================================
        ADD FOLDER
        =================================================================================-->
        <div id="add_folder" class="info-item-cont-modal">
            <div class="info-item-container-form">
                <p><i class="fas fa-folder"></i><span>Agregar Carpeta</span></p>
                <div class="info-item-line-form"></div>
                <div class="info-item-cont-txt">
                    <p class="info-item-txt">Nombre:</p>
                    <input id="input_add_name_folder" type="text" name="name-folder" value="name folder" class="info-item-input">
                </div>
                <div class="info-item-line-form"></div>
                <div class="info-item-cont-txt">
                    <button type="button"  class="info-item-button-right"> Cerrar </button>
                    <button id="btn_add_folder" type="button"  class="info-item-button-left"> Agregar </button>
                </div>
            </div>
        </div>
         <!-- ===============================================================================
        ADD FILE
        =================================================================================-->
        <div id="add_file" class="info-item-cont-modal">
            <div class="info-item-container-form">
                <p><i class="fas fa-file"></i><span>Agregar Archivo</span></p>
                <div class="info-item-line-form"></div>
                     

                <div id="file_drop_target">
                    Arrastre los archivos aquí para cargar
                    <b>or</b>
                    <input id="load_file" type="file" multiple />
                </div>
                <div id="upload_progress"></div>
               <!--  <div class="info-item-cont-txt">
                    <p class="info-item-txt">Nombre:</p>
                    <input id="input_add_name_folder" type="text" name="name-folder" value="name folder" class="info-item-input">
                </div> -->
                <div class="info-item-line-form"></div>
                <div class="info-item-cont-txt">
                    <button type="button"  class="info-item-button-right"> Cerrar </button>
                    <button id="btn_add_file" type="button"  class="info-item-button-left"> Agregar </button>
                </div>
            </div>
        </div>
         <!-- ===============================================================================
        RENAME FOLDER
        =================================================================================-->
        <div id="rename_folder" class="info-item-cont-modal">
            <div class="info-item-container-form">
                <p id="type_rename"><i class="fas fa-folder"></i><span>Renombrar Carpeta</span></p>
                <div class="info-item-line-form"></div>
                <div class="info-item-cont-txt">
                    <p class="info-item-txt">Nombre:</p>
                    <input id="input_rename_folder" type="text" name="name-folder" value="name folder" class="info-item-input">
                </div>
                <div class="info-item-line-form"></div>
                <div class="info-item-cont-txt">
                    <button type="button"  class="info-item-button-right"> Cerrar </button>
                    <button id="btn_rename_folder" type="button"  class="info-item-button-left"> Renombrar </button>
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

                <div class="items">
                    <div id="menu_add" class="group menu-hidde">
                        <span class="fas fa-plus-circle"></span>
                        <span class="title">Agregar</span>
                    </div>
                    <div id="menu_add_file" class="items-child hidde">
                        <span class="fas fa-file"></span>
                        <span class="title">file</span>
                    </div>
                    <div id="menu_add_folder" class="items-child hidde">
                        <span class="fas fa-folder" ></span>
                        <span class="title">folder</span>
                    </div>
                </div>

                <div id="menu_rename_folder" class="items">
                    <div class="group">
                        <span class="fas fa-edit"></span>
                        <span class="title">Editar</span>
                    </div>
                </div>

                <div id="menu_delete" class="items">
                    <div class="group">
                        <span class="fas fa-trash-alt"></span>
                        <span class="title">Eliminar</span>
                    </div>
                </div>

                <div id="menu_move" class="items">
                    <div class="group">
                        <span class="fas fa-cut"></span>
                        <span class="title">Mover</span>
                    </div>
                </div>

                <div id="menu_paste" class="items" style="display: none;">
                    <div class="group">
                        <span class="fas fa-paste"></span>
                        <span class="title">Mover Aqui</span>
                    </div>
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
    <script src="<?= BASE_URL ?>public/js/general/all.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/menu-horizontal.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/menu-vertical.js"></script>
    <script src="<?= BASE_URL ?>public/js/folder.js"></script>

</body>

</html>