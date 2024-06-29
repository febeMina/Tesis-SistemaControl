<nav class="sidebar sidebar-offcanvas" id="sidebar" style="background-color: #090066 !important;">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top" style="background-color: #090066 !important;">
        <a class="sidebar-brand brand-logo" href="index.html"><img src="<?= base_url('public/assets/images/logo.svg') ?>" alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="<?= base_url('public/assets/images/logo-mini.svg') ?>" alt="logo" /></a>
    </div>
    <ul class="nav">
        
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                    <div class="count-indicator">
                        <img class="img-xs rounded-circle " src="<?= base_url('public/assets/images/faces/admin.png') ?>" alt="">
                        <span class="count bg-success"></span>
                    </div>
                    <div class="profile-name">
                        <h5 class="mb-0 font-weight-normal">
                            <?= session()->get('usuario') ?>
                        </h5>
                        <span>
                            <?= session()->get('rol') ?>
                        </span>
                    </div>
                </div>
                <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-settings text-primary"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Configuración de Cuenta</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-onepassword  text-info"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-calendar-today text-success"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                        </div>
                    </a>
                </div>
            </div>
        </li>
        
        <li class="nav-item nav-category">
            <span class="nav-link">Menu</span>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="<?= site_url('/') ?>">
                <span class="menu-icon">
                    <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-account"></i>
                </span>
                <span class="menu-title">Administracion</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a href="<?= site_url('usuario') ?>" class="nav-link">Usuarios</a></li>
                    <li class="nav-item"><a href="<?= site_url('roles') ?>" class="nav-link">Roles</a></li>
                    <li class="nav-item"><a href="<?= site_url('acceso') ?>" class="nav-link">Accesos</a></li>
                    <li class="nav-item"><a href="<?= site_url('padres') ?>" class="nav-link">Padres</a></li>
                    <li class="nav-item"><a href="<?= site_url('maestros') ?>" class="nav-link">Sector magisterial</a></li>
                    <li class="nav-item"><a href="<?= site_url('tipo_permiso') ?>" class="nav-link">Tipos de Licencias</a></li>
                </ul>
            </div>
        </li>
        <!-- Menú para el donaciones -->
        <li class="nav-item menu-items">
        <a class="nav-link" data-toggle="collapse" href="#donaciones" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-currency-usd"></i>
                </span>
                <span class="menu-title">Donaciones</span>
                <i class="menu-arrow"></i>
            </a>
              <div class="collapse" id="donaciones">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a href="<?= site_url('proyectos') ?>" class="nav-link">Proyectos</a></li>
                    <li class="nav-item"><a href="<?= site_url('donaciones') ?>" class="nav-link">Listado de Donaciones</a></li>
                </ul>
            </div>
        </li>


        <li class="nav-item menu-items">
            <a class="nav-link" href="<?= site_url('permiso_magisterial/index') ?>">
                <span class="menu-icon">
                    <i class="mdi mdi-file-document"></i>
                </span>
                <span class="menu-title">Licencias</span>
            </a>
        </li>

        <!-- Menú para el apartado de Productos -->
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#alimentacion" aria-expanded="false" aria-controls="alimentacion">
                <span class="menu-icon">
                    <i class="mdi mdi-food"></i>
                </span>
                <span class="menu-title">Alimentación</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="alimentacion">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a href="<?= site_url('tipo_proyectos') ?>" class="nav-link">Tipo de producto</a></li>
                    <li class="nav-item"><a href="<?= site_url('unidadesmedida') ?>" class="nav-link">Unidades de Medida</a></li>
                    <li class="nav-item"><a href="<?= site_url('consumo') ?>" class="nav-link">Consumo por productos</a></li>
                    <li class="nav-item"><a href="<?= site_url('solicitudproductos') ?>" class="nav-link">Requisición de productos</a></li>
                </ul>
            </div>
        </li>


        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <span class="menu-icon">
                    <i class="mdi mdi-file-document"></i>
                </span>
                <span class="menu-title">Reportes</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a href="<?= site_url('reporte') ?>" class="nav-link">Reporte de permisos</a>
                    </li>
                    <li class="nav-item"><a href="#" class="nav-link">Reporte de donaciones</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Reporte de inasistencias</a></li>
                </ul>
            </div>
        </li>
         
    </ul>
</nav>