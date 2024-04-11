<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEBD - Corona Admin</title>
    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="<?= base_url('/public/assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/public/assets/vendors/css/vendor.bundle.base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/public/assets/css/style.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('/public/assets/images/favicon.png') ?>" />
    <!-- Enlace a la biblioteca de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('node_modules/sweetalert2/dist/sweetalert2.min.css') ?>">
    <style>
        /* Estilos para la barra lateral */
        .sidebar-menu {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 260px; /* Ancho de la barra lateral */
            background-color: #090066 !important;
            font-size: 16px; /* Tamaño de fuente para el menú */
        }

        /* Estilos para el contenido principal */
        .main-content {
            margin-left: 260px; /* Ancho de la barra lateral */
            padding: 20px; /* Espaciado interior del contenido principal */
        }

        /* Estilos para hacer que el contenido sea scrollable si es necesario */
        .main-content {
            overflow-y: auto;
            height: calc(100vh - 20px); /* Altura total de la ventana menos el padding */
        }

        /* Estilos para el texto del menú */
        .nav-link {
            color: #ffffff; /* Color del texto del menú */
            font-weight: 500; /* Peso de la fuente */
            transition: all 0.3s ease; /* Transición suave al cambiar el estilo */
        }

        /* Estilos para el icono del menú */
        .menu-icon {
            margin-right: 10px; /* Espaciado a la derecha del icono */
        }

        /* Estilos para el título del menú */
        .menu-title {
            margin-right: 10px; /* Espaciado a la derecha del título */
        }
    </style>
</head>

<body style="background-color: #1202B4;">
    <!-- sidebar menu area start -->
    <div class="sidebar-menu">
        <!-- Aquí va el código del menú lateral -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar" style="background-color: #090066 !important;">
            <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top" style="background-color: #090066 !important;">
            <a class="sidebar-brand brand-logo" href="<?= base_url('public/assets/images/logo.svg') ?>"><img src="<?= base_url('public/assets/images/logo.svg') ?>" alt="logo" /></a>
            <a class="sidebar-brand brand-logo-mini" href="<?= base_url('public/assets/images/logo-mini.svg') ?>"><img src="<?= base_url('public/assets/images/logo-mini.svg') ?>" alt="logo" /></a>    

            </div>
            <ul class="nav">
                <li class="nav-item profile">
                    <!-- Aquí va el código del perfil de usuario -->
                </li>
                <li class="nav-item nav-category">
                    <span class="nav-link">Menu</span>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="index.html">
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
                            <li class="nav-item"><a href="#" class="nav-link">Usuarios</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Roles</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Accesos</a></li>
                            <li class="nav-item"><a href="<?= site_url('padres/create') ?>" class="nav-link">Padres</a></li>
                            <li class="nav-item"><a href="<?= site_url('maestros') ?>" class="nav-link">Docentes</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Tipos de Licencias</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="pages/forms/basic_elements.html">
                        <span class="menu-icon">
                            <i class="mdi mdi-currency-usd"></i>
                        </span>
                        <span class="menu-title">Donaciones</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="pages/tables/basic-table.html">
                        <span class="menu-icon">
                            <i class="mdi mdi-file-document"></i>
                        </span>
                        <span class="menu-title">Licencias</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="pages/charts/chartjs.html">
                        <span class="menu-icon">
                            <i class="mdi mdi-food"></i>
                        </span>
                        <span class="menu-title">Alimentacion</span>
                    </a>
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
                            <li class="nav-item"><a href="#" class="nav-link">Reporte de movimiento de productos</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Reporte de donaciones</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Reporte de inasistencias</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
    <!-- sidebar menu area end -->

    <!-- main content area start -->
    <div class="main-content">
        <!-- header area start -->
        <div class="header-area">
            <!-- Aquí va el código del encabezado -->
        </div>
        <!-- header area end -->
        <!-- page title area start -->
        <!-- Aquí va el código del título de la página -->
        <!-- page title area end -->
        <div class="main-content-inner">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
    <!-- main content area end -->

    <!-- Enlaces a los archivos JavaScript -->
    <script src="<?= base_url('public/assets/vendors/js/vendor.bundle.base.js') ?>"></script>
    <!-- Scripts personalizados -->
    <script src="<?= base_url('public/assets/js/off-canvas.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/hoverable-collapse.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/misc.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/settings.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/todolist.js') ?>"></script>
    <script src="<?= base_url('node_modules/sweetalert2/dist/sweetalert2.all.min.js') ?>"></script>
    <!-- Scripts adicionales para la plantilla -->
    <!-- Por ejemplo, si hay algún script específico de la plantilla Corona Admin -->
</body>

</html>
