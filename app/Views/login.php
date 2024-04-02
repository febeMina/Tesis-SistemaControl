<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Corona Admin</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <!-- Estilos adicionales -->
    <style>
        body {
            background-image: url('');
            background-size: cover;
            background-position: center;
            height: 100vh;
            overflow: hidden;
        }

        .custom-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .custom-form {
            background-color: rgba(255, 255, 255, 0.7); /* Transparencia para mejorar la legibilidad de los campos */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Sombra para resaltar el formulario */
        }

        .custom-title {
            text-align: center;
            margin-bottom: 20px;
            color: #fff; /* Color del texto */
        }
    </style>
</head>
<body class="login-page">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center">
                <div class="row w-100 mx-0 custom-container">
                    <div class="col-lg-4 mx-auto custom-form">
                        <h2 class="custom-title">CENTRO ESCOLAR BARRIO LAS DELICIAS</h2>
                        <div class="brand-logo"></div>
                        <h6 class="font-weight-light">Inicia sesión para continuar.</h6>
                        <form action="<?= base_url() . route_to('login') ?>" method="post" class="pt-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Correo electrónico</label>
                                <input type="email" class="form-control form-control-lg" name="email" id="exampleInputEmail1" placeholder="Correo electrónico">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Contraseña</label>
                                <input type="password" class="form-control form-control-lg" name="password" id="exampleInputPassword1" placeholder="Contraseña">
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">INICIAR SESIÓN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jquery latest version -->
    <script src="../public/js/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="../public/js/popper.min.js"></script>
    <script src="../public/js/bootstrap.min.js"></script>
    <script src="../public/js/owl.carousel.min.js"></script>
    <script src="../public/js/metisMenu.min.js"></script>
    <script src="../public/js/jquery.slimscroll.min.js"></script>
    <script src="../public/js/jquery.slicknav.min.js"></script>

    <!-- others plugins -->
    <script src="../public/js/plugins.js"></script>
    <script src="../public/js/scripts.js"></script>
</body>
</html>
