<?php 
    session_start();
    include_once ROOT_PATH."library/DB.php";
    include_once ROOT_PATH."library/FileManager.php";
    //include_once ROOT_PATH."mvc/model/ViewFileModel.php";
    include_once ROOT_PATH."mvc/controller/ViewFileController.php";
    $message=null;
    
    if ( isset($_GET['v']) )
        ViewFileController::decodePathName();
    $ViewFileController = new ViewFileController();
    $pathDefault =  str_replace("drive/", "", $ViewFileController->getPathDefault());
    $getFileManagerValues = $ViewFileController->getValues(); 
  
    if ( isset($_GET['list']) )
        $ViewFileController->listAll( $_GET['pathname'] );
    else if ( isset($_GET['listSearch']) )
        $ViewFileController->search( $_GET['pathname'], $_GET['search'] );

    else if ( isset($_GET['showFile']) )
          $ViewFileController->showFile( $_GET['pathname'], $_GET['extension'] );
    else if ( isset($_GET['download']) )
        $ViewFileController->download( $_GET['pathname'], $_GET['is_dir'] );


        

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
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/menu-horizontal.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/home.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/folder.css">
  
     <!-- ===============================================================================
     MENU HORIZONTAL
    =================================================================================-->
    <header id="bar-header">

        <a href="<?= BASE_URL ?>home" ><img src="<?= BASE_URL ?>public/img/icon/logoapp.png" class="logo-bar"></a>
            <nav>   
                <li class="text-organizapp"><a href="<?= BASE_URL ?>home">OrganizApp </a></li>
                <ul class="menu-header">
                    
                </ul>
            </nav>
    
    </header>
    
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
                  <h3 >Visualizar</h3>  
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
                    <button type="button"  class="info-item-button-fa js_btn_share"> <i class="fas fa-share"></i> </button>
                    <button type="button"  class="info-item-button-fa js_btn_download"> <i class="fas fa-download"></i> </button>
                </div>
            </div>
        </div>

      
      
           
        <!-- ===============================================================================
         ITEM
        =================================================================================-->
        <div  class="myflex-padre">
            <!-- diseño de los contenedores de tareas -->
            <div  class="myflex-hijo">
                <div id="list" data-pathname="null" class="center-item">
                    <div  id="loader" ></div>
                    <!-- <div  class="my-item" >
                        <div class="selected hidde"><i class="fas fa-check"></i></div>
                        <input type="hidden" value="-1">
                        <i class="fas fa-folder"></i>
                        <i class="fas fa-file"></i>
                        <p data-tooltip="nombre item" class="tooltip_elemento"><span class="item-name">nombre item</span></p>
                        <div class="tooltip">Texto del tooltip</div>
                    </div> -->
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
       
       
        

        

    </div>
    
    <!-- ===============================================================================
     JS
    =================================================================================-->
    <script type="text/javascript">
        var BASE_URL = "<?= BASE_URL ?>";
      
    </script>
    
    <script src="<?= BASE_URL ?>public/js/plugins/jquery.min.3.3.1.js"></script>
    <script src="<?= BASE_URL ?>public/js/plugins/sweetalert.min.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/all.js"></script>
    <script src="<?= BASE_URL ?>public/js/view_file.js"></script>

</body>

</html>