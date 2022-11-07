
    <!-- barra_orizontal -->
    <header id="bar-header">

        <a href="<?= BASE_URL ?>home" ><img src="<?= BASE_URL ?>public/img/icon/logoapp.png" class="logo-bar"></a>
        <button class="btn-bar"><i class="bars"> &#9776; </i></button>
            <nav>   
                <li class="text-organizapp"><a href="<?= BASE_URL ?>home">OrganizApp </a></li>
                <ul class="menu-header">
                    <li><a id="_home" class="" href="<?= BASE_URL ?>home"><i class="fas fa-tasks"></i>Actividades</a> </li>
                    <li><a id="_folder" class="" href="<?= BASE_URL ?>folder"><i class="fas fa-folder"></i>Carpetas</a> </li>
                    <li><a id="_trash" class="" href="<?= BASE_URL ?>trash"> <i class="fas fa-recycle"></i>Papelera</a></li>
                    <li><a id="_bell" class="" href="<?= BASE_URL ?>notification"> <i class="fas fa-bell"></i> </a> </li>
                    <li> <div id="google_translate_element" class="justify-content-start" style="margin-top: -3px;"></div> </li>
                    
                    <li> <img class="icon-perfil" src="<?= $getImg; ?>"></li>
                    <div id="_perfil" class="perfil-cont-modal">
                        <div class="perfil-container-form">
                            <img class="icon-perfil-show" src="<?= $getImg; ?>">
                            <p><i class="fas fa-user"></i><?= $getName; ?></p>
                            <p><i class="fas fa-qrcode"></i><?= $getCode; ?></p>
                            <p>Su licencia Expira el: <?= session('expire_license') ?></p>
                            <div class="perfil-line-form"></div>
                            <a href="<?= BASE_URL ?>edit_profile"> <p class="perfil-txt"><i class="fas fa-user-edit"></i>Editar perfil</p></a>
                            <a href="<?= BASE_URL ?>general/logout"> <p class="perfil-txt"><i class="fas fa-sign-in-alt"></i>Cerrar sesion</p></a>
                        </div>
                    </div>
                </ul>
            </nav>
    
    </header>