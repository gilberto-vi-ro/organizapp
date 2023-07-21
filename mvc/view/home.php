<?php
    session_start();
    if (!session('data_user')) {
        href(BASE_URL . "login");
        return;
    }

    include_once ROOT_PATH . "library/DB.php";
    include_once ROOT_PATH . "library/FileManager.php";
    include_once ROOT_PATH . "mvc/model/HomeModel.php";
    include_once ROOT_PATH . "mvc/controller/HomeController.php";
    $message = null;
    $section= "home";

    $HomeController = new HomeController();

    $getImg = $HomeController->getImg();
    $getName = $HomeController->getName();
    $getCode = session('id_usuario');
    

    $pathDefault =  str_replace("drive/", "", $HomeController->getPathDefault());

    if (isset($_GET['listFolder'])) {
        $HomeController->listFolder($_GET['path']);
        exit();
    } else if (isset($_GET['listTaskPending'])) {
        $r = $HomeController->listTaskPending($_GET['path'], $_GET['priority'], $_GET['search'], $_GET['range']);
        //print_r($r);
        exit();
    } else if (isset($_GET['listTaskDone'])) {
        $r = $HomeController->listTaskDone($_GET['path'], $_GET['priority'], $_GET['search'], $_GET['range']);
        //print_r($r);
        exit();
    } else if (isset($_GET['listTaskDelivered'])) {
        $r = $HomeController->listTaskDelivered($_GET['path'], $_GET['priority'], $_GET['search'], $_GET['range']);
        //print_r($r);
        exit();
    }
    else if (isset($_GET['getPathFile'])) {
        $r = $HomeController->getPathFile($_GET['idFile']);
        exit();
    }

    if (isset($_POST['addNewTask'])) {
        $HomeController->addNewTask($_POST);
        //var_dump($_POST);
        exit();
    }
    else if (isset($_POST['editTask']) ){
        $HomeController->editTask($_POST);
        //var_dump($_POST);
        //var_dump($_FILES);
        exit();
    }
    else if (isset($_POST['editStatusTask']) ){
        $HomeController->editStatusTask($_POST);
        //var_dump($_POST);
        //var_dump($_FILES);
        exit();
    }

    else if (isset($_POST['deleteTask']) ){
        $HomeController->deleteTask( $_POST['item'] );
        exit();
    }
    else if ( isset($_POST['moveTask']) ){
        $HomeController->moveTask( $_POST['newPathname'], $_POST['item'] );
        
    }
        
       


?>

<!DOCTYPE html>
<html>

<head>

    <title>OrganizApp</title>

    <meta charset="UTF-8">
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="viewport" content="width-device-width, user-scalable=no, initial-scale=1.0, maiximum-scale1.0, minimum-scale=1.0">

    <link rel="icon" href="<?= BASE_URL ?>public/img/icon/logoapp.png">

    <link rel=" stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/plugins/ka-f.fontawesome.v5.15.4.free.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/menu-vertical.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/menu-horizontal.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/home.css">
    <!----========googleTranslate ======== -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/googleTranslate.css">

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
                    <li><img class="icon-foldersearch" src="<?= BASE_URL ?>public/img/icon/foldersearch.png"></li>
                    <li><img class="icon-back" src="<?= BASE_URL ?>public/img/icon/back.png"></li>
                    <li>
                        <div id="breadcrumb" data-path="null">
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
                            <input id="id_search" type="text" class="form-control" name="bus" placeholder="Search">
                        </li>
                    </ul>
                </div>
            </div>

            <!-- filtrado de actividades por importante -->

            <div class="text-activity">
                <h3>Activities</h3>
            </div>
            <div>
                <form class="combo">
                    <select id="task_priority">
                        <option value="0"> All </option>
                        <option value="1"> Urgent </option>
                        <option value="2">Important </option>
                        <option value="3">Not urgent </option>
                    </select>
                </form>
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
        ITEM TASK
        =================================================================================-->

        <div class="myflex-padre">
            <!-- diseño de los contenedores de tareas -->
            <!-- contenedor numero 1 -->
            <div class="myflex-hijo" ondrop="drop(event,'list_task_pending')" ondragover="dragover(event)">
                <div class="cont-text-hijo">
                    <h3>Pending</h3>
                </div>
                <div id="list_task_pending" class="center-item js_list_task" >
                    <!-- tareas pendientes -->
                    <div  class="loader" ></div>
                    <!-- <div id="draggable-1" class="my-item" ondragstart="dragStart(event)" ondrag="drag(event)" draggable="true">
                        <div class="selected hidde"><i class="fas fa-check"></i></div>
                        <input type="hidden" value="0" data-this="null" class="js-data-this">

                        <p data-tooltip="tarea de prueba" class="tooltip_elemento"><span class="item-name">tarea de prueba</span></p>
                        <div class="tooltip">Texto del tooltip</div>

                        <img src="<?= BASE_URL ?>public/img/icon/task.png" class="logo-task">
                        <p class="item-expired">24/11/2021</p>
                    </div> -->
                </div>
            </div>

            <div class="myflex-hijo" ondrop="drop(event,'list_task_done')" ondragover="dragover(event)">
                <div class="cont-text-hijo">
                    <h3>Ready</h3>
                </div>
                <div class="center-item">
                    <div id="list_task_done" class="center-item js_list_task" >
                        <!-- tareas listas -->
                        <div  class="loader" ></div>
                        <!-- <div id="draggable-2" class="my-item" ondragstart="dragStart(event)" ondrag="drag(event)" draggable="true">
                            <div class="selected hidde"><i class="fas fa-check"></i></div>
                            <input type="hidden" value="0" data-this="null" class="js-data-this">

                            <p data-tooltip="tarea de prueba" class="tooltip_elemento"><span class="item-name">tarea de prueba</span></p>
                            <div class="tooltip">Texto del tooltip</div>

                            <img src="<?= BASE_URL ?>public/img/icon/task.png" class="logo-task">
                            <p class="item-expired">24/11/2021</p>
                        </div> -->
                    </div>
                </div>
            </div>

            <div class="myflex-hijo" ondrop="drop(event,'list_task_delivered')" ondragover="dragover(event)">
                <div class="cont-text-hijo">
                    <h3>Delivered</h3>
                </div>
                <div class="center-item">
                    <div id="list_task_delivered" class="center-item js_list_task" >
                        <!-- tareas entregadas -->
                        <div  class="loader" ></div>
                        <!-- <div id="draggable-3" class="my-item" ondragstart="dragStart(event)" ondrag="drag(event)" draggable="true">
                            <div class="selected hidde"><i class="fas fa-check"></i></div>
                            <input type="hidden" value="0" data-this="null" class="js-data-this">

                            <p data-tooltip="tarea de prueba" class="tooltip_elemento"><span class="item-name">tarea de prueba</span></p>
                            <div class="tooltip">Texto del tooltip</div>

                            <img src="<?= BASE_URL ?>public/img/icon/task.png" class="logo-task">
                            <p class="item-expired">24/11/2021</p>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- ===============================================================================
        ITEM FOLDER
        =================================================================================-->
        <div id="list_folder" class="cont-modal">
            <div class="myflex-padre">
                <!-- diseño de los contenedores de carpetas -->
                <div class="myflex-hijo">
                    <p class="close-modal">×</p>
                    <div id="list_item_folder" data-path="null" data-pathname="null" class="center-item">
                        <div class="my-item">
                            <input type="hidden" value="-1">
                            <i class="fas fa-folder"></i>
                            <p data-tooltip="nombre item" class="tooltip_elemento"><span class="item-name">nombre item</span></p>
                            <div class="tooltip">Texto del tooltip</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===============================================================================
        ADD NEW TASK
        =================================================================================-->
        <div id="add_task" class="cont-modal my-task">
            <form id="add_task_form" class="task-item-container-form" action="#" method="GET|POST" enctype="multipart/form-data">
                <p id="add_task_type"><img src="<?= BASE_URL ?>public/img/icon/task.png" class="logo-new-task"><span class="txt-new-task">New task</span></p>
                <div class="task-item-line-form"></div>
                <div class="task-item-cont-txt">
                    <p class="task-item-txt">Name:</p>
                    <input id="add_task_name" type="text" name="name" placeholder="Nombre" required="">
                </div>
                <div class="task-item-cont-txt">
                    <p class="task-item-txt">Deliver date:</p>
                    <input id="add_task_delivery_date" type="datetime-local" name="delivery_date" min="2022-01-01T08:30" max="2099-06-30T16:30" required="">
                    <!--value="2022-01-01T08:30" -->
                </div>
                <div class="task-item-cont-txt">
                    <p class="task-item-txt">Status:</p>
                    <select id="add_task_status" name="status" required="">
                        <option hidden="" value="">Select an option</option>
                        <option value="1">Pending </option>
                        <option value="2">Ready </option>
                        <option value="3">Delivered </option>
                    </select>
                </div>
                <div class="task-item-cont-txt">
                    <p class="task-item-txt">Description:</p>
                    <textarea id="add_task_description" name="description" placeholder="Description" required=""></textarea>
                </div>
                <div id="cont_modified" class="task-item-cont-txt">
                    <p class="task-item-txt">Priority:</p>
                    <select id="add_task_priority" name="priority" required="">
                        <option hidden="" value="">Select an option</option>
                        <option value="1"> Urgent </option>
                        <option value="2">Important </option>
                        <option value="3">Not urgent </option>
                    </select>
                </div>
                <div class="task-item-line-form"></div>

                <div class="task-item-cont-txt row">
                    <button type="button" class="task-item-button-right"> Close modal</button>
                    <button type="submit" class="task-item-button-left js_btn_add_task"> Add </button>
                </div>
            </form>
        </div>
        <!-- ===============================================================================
        EDIT TASK | SHOW TASK 
        =================================================================================-->
        <div id="edit_task" class="cont-modal my-task">
            <form id="edit_task_form" class="task-item-container-form" action="#" method="GET|POST" enctype="multipart/form-data" onSubmit="return false;">
                <p ><img src="<?= BASE_URL ?>public/img/icon/task.png" class="logo-new-task"><span class="txt-new-task">Editar Tarea</span></p>
                <div class="task-item-line-form"></div>
                <div class="task-item-cont-txt">
                    <p class="task-item-txt">Name:</p>
                    <textarea id="edit_task_name" name="name" required="">description </textarea>
                </div>
                <div id="cont_edit_task_path" class="task-item-cont-txt">
                    <p class="task-item-txt">File path:</p>
                    <textarea id="edit_task_path" required="">Default </textarea>
                </div>
                <div class="task-item-cont-txt">
                    <p class="task-item-txt">Deliver date:</p>
                    <input id="edit_task_delivery_date" type="datetime-local" name="delivery_date" min="2022-01-01T08:30" max="2099-06-30T16:30" required="">
                    <!--value="2022-01-01T08:30" -->
                </div>
                <div class="task-item-cont-txt">
                    <p class="task-item-txt">Status:</p>
                    <select id="edit_task_status" name="status" required="">
                        <option hidden="" value="">Select an option</option>
                        <option value="1">Pending </option>
                        <option value="2">Ready </option>
                        <option value="3">Delivered </option>
                    </select>
                </div>
                <div class="task-item-cont-txt">
                    <p class="task-item-txt">Description:</p>
                    <textarea id="edit_task_description" name="description" required="">Description </textarea>
                </div>
                <div class="task-item-cont-txt">
                    <p class="task-item-txt">Priority:</p>
                    <select id="edit_task_priority" name="priority" required="">
                        <option hidden="" value="">Select an option</option>
                        <option value="1"> Urgent </option>
                        <option value="2">Important </option>
                        <option value="3">Not urgent </option>
                    </select>
                </div>
                <div class="task-item-line-form"></div>
                    <div class="task-item-cont-txt">
                        <p class="task-item-txt">File:</p>
                        <label id="edit_task_name_file" type="text" >name_file</label>
                    </div>
                    <div class="task-item-cont-txt">
                        <p class="task-item-txt">Path File:</p>
                        <a id="edit_task_path_file" href="<?= BASE_URL ?>folder#" type="text" >null</a>
                    </div>
                    <div class="task-item-cont-txt">
                        <p class="task-item-txt"></p>
                        <input id="edit_task_file" type="file" name="file_data" value="null" >
                    </div>
                <div class="task-item-line-form"></div>
                <div class="task-item-cont-txt row">
                    <button type="button" class="task-item-button-right"> Close modal</button>
                    <button type="submit" class="task-item-button-left js_btn_edit_task"> Update </button>
                </div>
            </form>
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
                        <span class="title" translate="no">Home</span>
                    </a>
                </div>

                <div class="items">
                    <div id="menu_add" class="group menu-hidde">
                        <span class="fas fa-plus-circle"></span>
                        <span class="title">Add</span>
                    </div>
                </div>

                <div id="menu_edit" class="items">
                    <div class="group">
                        <span class="fas fa-edit"></span>
                        <span class="title">Edit</span>
                    </div>
                </div>

                <div id="menu_delete" class="items">
                    <div class="group">
                        <span class="fas fa-trash-alt"></span>
                        <span class="title">Delete</span>
                    </div>
                </div>

                <div id="menu_move" class="items">
                    <div class="group">
                        <span class="fas fa-cut"></span>
                        <span class="title" style="text-align:start">Move items</span>
                    </div>
                </div>

                <div id="menu_paste" class="items" style="display: none;">
                    <div class="group">
                        <span class="fas fa-paste"></span>
                        <span class="title" style="text-align:start">Move items here</span>
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
    </script>
    <script src="<?= BASE_URL ?>public/js/plugins/jquery.min.3.3.1.js"></script>
    <script src="<?= BASE_URL ?>public/js/plugins/sweetalert.min.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/all.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/menu-horizontal.js"></script>
    <script src="<?= BASE_URL ?>public/js/general/menu-vertical.js"></script>
    <script src="<?= BASE_URL ?>public/js/home.js"></script>
    <!----=====GOOGLE TRANSLATE ===== -->
	<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="<?= BASE_URL ?>public/js/googleTranslate.js"></script>
 
    

</body>

</html>