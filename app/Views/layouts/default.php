<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEBD - Corona Admin</title>
    <!-- Carga de jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="public/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="public/assets/vendors/css/vendor.bundle.base.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="public/assets/css/style.css">
    <!-- Icono de la página -->
    <link rel="shortcut icon" href="public/assets/images/favicon.png" />
</head>

<body>
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <!-- Aquí va el código del menú lateral -->
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
        <!-- footer area start-->
        <footer>
            <!-- Aquí va el código del pie de página -->
        </footer>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->

    <!-- Enlaces a los archivos JavaScript -->
    <script src="public/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- Scripts personalizados -->
    <script src="public/assets/js/off-canvas.js"></script>
    <script src="public/assets/js/hoverable-collapse.js"></script>
    <script src="public/assets/js/misc.js"></script>
    <script src="public/assets/js/settings.js"></script>
    <script src="public/assets/js/todolist.js"></script>
    <!-- Scripts adicionales para la plantilla -->
    <!-- Por ejemplo, si hay algún script específico de la plantilla Corona Admin -->
</body>

</html>
