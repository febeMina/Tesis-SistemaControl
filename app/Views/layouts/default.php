<!DOCTYPE html>
<html lang="en">

<head >
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEBD - Corona Admin</title>
    <!-- Carga de jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="<?= base_url('public/assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/vendors/css/vendor.bundle.base.css') ?>">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= base_url('public/assets/css/style.css') ?>">
    <!-- Icono de la página -->
    <link rel="shortcut icon" href="<?= base_url('public/assets/images/favicon.png') ?>" />
    <link rel="stylesheet" href="<?= base_url('node_modules/sweetalert2/dist/sweetalert2.min.css') ?>">
</head>

<body style="background-color: #1202B4;">
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <!-- Aquí va el código del menú lateral -->
            <!-- Puedes copiar el código del menú lateral de tu archivo principal -->
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <!-- Aquí va el código del encabezado -->
                <!-- Puedes copiar el código del encabezado de tu archivo principal -->
            </div>
            <!-- header area end -->
            <!-- page title area start -->
            <!-- Aquí va el código del título de la página -->
            <!-- Puedes copiar el código del título de la página de tu archivo principal -->
            <!-- page title area end -->
            <div class="main-content-inner">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer style="background-color: #f0f0f0;">
            <!-- Aquí va el código del pie de página -->
            <!-- Puedes copiar el código del pie de página de tu archivo principal -->
        </footer>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->

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
