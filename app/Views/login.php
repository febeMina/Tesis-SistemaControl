<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CEBD</title>
    <link rel="stylesheet" href="<?= site_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= site_url('assets/vendors/css/vendor.bundle.base.css') ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/style.css') ?>">
    <link rel="shortcut icon" href="<?= site_url('assets/images/favicon.png') ?>" />
    <!-- Estilos  -->
    <style>
        body,
        .container-scroller {
            margin: 0;
            padding: 0;
        }

        .custom-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('assets/images/img_cebd/estudiantes.jpeg');
            background-size: cover;
            background-position: center;
        }

        .custom-form {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .custom-title {
            text-align: center;
            margin-bottom: 10px;
            color: #225195;
        }

        .auth-form-btn {
            background-color: #225195;
            color: #000;
            margin-top: 20px;
            width: 100%; /* Modificado para ocupar todo el ancho */
            border-radius: 25px;
            border: 2px solid #000;
        }

        .rounded-logo {
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #225195;
        }

        .small-title {
            text-align: right;
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 0;
            color: #225195;
        }

        .form-group {
            margin-bottom: 15px;
            color: #225195;
        }

        .form-control {
            background-color: #fff;
            color: #225195;
            border: 2px solid #000;
            border-radius: 5px;
            padding: 10px;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #ff7f0f;
            outline: none;
            box-shadow: 0 0 0 2px #ff7f0f;
        }
    </style>
</head>

<body class="login-page">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="row w-100 mx-0 custom-container">
                    <div class="col-lg-4 mx-auto custom-form">
                        <div class="logo-container d-flex justify-content-center mb-3">
                            <img src="<?= site_url('assets/images/img_cebd/logo.cebd.jpg') ?>" class="rounded-logo">
                        </div>
                        <h2 class="custom-title">CENTRO ESCOLAR BARRIO LAS DELICIAS</h2>
                        <h3 class="small-title">San Salvador, Mejicanos</h3>
                        <!-- Formulario de inicio de sesión -->
                            <form action="<?= site_url('/') ?>" method="post" class="pt-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Usuario</label>
                                    <input type="text" class="form-control form-control-lg" name="username" id="exampleInputEmail1" placeholder="Usuario" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Contraseña</label>
                                    <input type="password" class="form-control form-control-lg" name="password" id="exampleInputPassword1" placeholder="Contraseña" required>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">INICIAR SESIÓN</button>
                                </div>
                            </form>

                    </div>
                </div>
        </div>
    </div>
    <!-- jquery latest version -->
    <script src="../public/assets/js/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="../public/assets/js/popper.min.js"></script>
    <script src="../public/assets/js/bootstrap.min.js"></script>
    <script src="../public/assets/js/owl.carousel.min.js"></script>
    <script src="../public/assets/js/metisMenu.min.js"></script>
    <script src="../public/assets/js/jquery.slimscroll.min.js"></script>
    <script src="../public/assets/js/jquery.slicknav.min.js"></script>

    <!-- others plugins -->
    <script src="../public/assets/js/plugins.js"></script>
    <script src="../public/assets/js/scripts.js"></script>
    <script>
        function validateForm() {
            var username = document.getElementById('exampleInputEmail1').value;
            var password = document.getElementById('exampleInputPassword1').value;
            if (username.trim() === '' || password.trim() === '') {
                alert('Por favor ingresa el usuario y la contraseña.');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
