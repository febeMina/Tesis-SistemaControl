<nav class="navbar p-0 fixed-top d-flex flex-row" style="background-color: #1202B4 !important;">
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
    <a class="sidebar-brand brand-logo" href="index.html"><img src="<?= base_url('public/assets/images/log.PNG') ?>" alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="<?= base_url('public/assets/images/logo-min.PNG') ?>" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav w-100">
            <li class="nav-item w-100">
            <?php
                $fecha= date('j \d\e F \d\e Y');
                $hora = date('H:i:s'); // Formato "d/m/Y H:i:s"
                echo "Fecha: " . $fecha . " Hora: " . $hora;
                ?>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                    <div class="navbar-profile">
                       <!-- VALIDACIÓN FOTO DE PERFIL-->
                            <?php if (session()->get('rol')=="Administrador"): ?>
                                <img class="img-xs rounded-circle" src="<?=base_url('public/assets/images/faces/admin.PNG')?>" alt=""> 
                            <?php endif; ?>
                            <?php if (session()->get('rol')=="Cocina"): ?>
                                <img class="img-xs rounded-circle" src="<?=base_url('public/assets/images/faces/cocina.PNG')?>" alt=""> 
                            <?php endif; ?>

                            <?php if (session()->get('rol')=="Tesoreria"): ?>
                                <img class="img-xs rounded-circle" src="<?=base_url('public/assets/images/faces/face14.jpg')?>" alt=""> 
                            <?php endif; ?>

                            <?php if (session()->get('rol')=="Contador"): ?>
                                <img class="img-xs rounded-circle" src="<?=base_url('public/assets/images/faces/face13.jpg')?>" alt=""> 
                            <?php endif; ?>

                            <?php if (session()->get('rol')=="SubDireccion"): ?>
                                <img class="img-xs rounded-circle" src="<?=base_url('public/assets/images/faces/face12.jpg')?>" alt=""> 
                            <?php endif; ?>
                       
                         <!-- VALIDACIÓN FOTO DE PERFIL-->
                       
                        <p class="mb-0 d-none d-sm-block navbar-profile-name">
                            <?=session()->get('usuario')?>
                        </p>
                        <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                    aria-labelledby="profileDropdown">
                    <h6 class="p-3 mb-0">Perfil</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-settings text-success"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject mb-1">Ajustes</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a id="cerraSesion" href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-logout text-danger"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject mb-1">Cerrar sesión</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <p class="p-3 mb-0 text-center">Configuraciones Avanzadas</p>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
        </button>
    </div>
</nav>