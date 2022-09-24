<?php 
    include_once ROOT_PATH."library/DB.php";
    include_once ROOT_PATH."mvc/model/NotificationModel.php";
    include_once ROOT_PATH."mvc/controller/NotificationController.php";
    
    session_start();
    if (!isset($_GET['sendNotificationEmail'])){
        if(!session('data_user') ){
            href(BASE_URL."login");
            return;
        }
    }else{
        if (isset($_GET['sendNotificationEmail']) )
            NotificationController::sendNotificationEmail();
    }
    
    $message=null;
    $section= "notification";


    $NotificationController = new NotificationController();

    $getImg = $NotificationController->getImg();
    $getName = $NotificationController->getName();
    $getCode = session('id_usuario'); 
    $pathDefault =  str_replace("drive/", "", $NotificationController->getPathDefault());

    if (isset($_GET['listNotification']) )
        $NotificationController->listAllNotification($_GET['range']);
    else if (isset($_GET['countNotification']) )
        $NotificationController->countNotification();
    else if (isset($_GET['seenNotification']) )
        $NotificationController->seenNotification($_GET['idNotification']);
    else if (isset($_GET['deleteNotification']) ){
        $dataItem = json_decode( $_GET['dataItem']);
        //var_dump( $dataItem );
        $NotificationController->deleteNotification(  $dataItem );
        exit;
    }
    
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
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/notification.css">
  
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
        
            <!-- filtrado de actividades por importante -->
            <div class="text-activity">
                  <h3 >Notificaciones</h3>  
            </div>
            <!-- rango de fecha -->
            <div class="date-range">
                    <ul>
                        <li>
                            <input id="date_range1" type="date" class="date-box" >
                        </li>
                        <li>
                            <input id="date_range2" type="date" class="date-box" >
                        </li>
                    </ul>
            </div>
        </div>
    
        <!-- ===============================================================================
         ITEM
        =================================================================================-->
        <div  class="myflex-padre">
            <!-- diseño de los contenedores de tareas -->
            <div  class="myflex-hijo">
                <div id="list" class="center-item">
                
                    <a href="<?= BASE_URL ?>home#" class="my-item" type="text" >
                        <div class="selected hidde" ><i class="fas fa-check"></i></div>
                        <input type="hidden" value="-1" data-this="null">
                        <i class="fas fa-bell item"></i>
                        <p class="item-name item">Usted tiene una tarea vencida</p>
                        <p class="item-name item">null </p>
                    </a>
                </div>
            </div>
        </div>

         <!-- ===============================================================================
         INFO ITEM
        =================================================================================-->
        <div id="info_item" class="info-item-cont-modal" >
            <div class="info-item-container-form">
                <p id="info_item_msg"><i class="fas fa-bell item"></i>Usted tiene una tarea que ya expiro.</p>
                <div class="info-item-line-form"></div>

                <div class="info-item-cont-txt">
                    <p class="info-item-txt">Nombre de la tarea:</p>
                    <p id="info_item_name_task">null</p>
                </div>
                <div class="info-item-cont-txt">
                    <p class="info-item-txt">Ruta:</p>
                    <a id="info_item_url" href="#">url</a>
                </div>
                <div class="info-item-cont-txt">
                    <p class="info-item-txt">Estado:</p>
                     <p id="info_item_status">null</p>
                </div>
                <div class="info-item-cont-txt">
                    <p class="info-item-txt">Prioridad:</p>
                     <p id="info_item_priority">null</p>
                </div>
                <div class="info-item-cont-txt">
                    <p class="info-item-txt">Fecha de notificacion:</p>
                    <p id="info_item_date_notification">mydate</p>
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
                <div id="menu_delete" class="items">
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
    </script>
    <script src="<?= BASE_URL ?>public/js/plugins/jquery.min.3.3.1.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/all.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/menu-vertical.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/menu-horizontal.js"></script>
    <script src="<?= BASE_URL ?>public/js/notification.js"></script>

</body>

</html>