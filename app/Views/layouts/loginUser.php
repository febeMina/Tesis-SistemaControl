<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Centro Escolar</title>
    <!-- Incluir CSS de Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir tu CSS personalizado -->
    <link href="<?= base_url('public/assets/css/loginUser.css') ?>" rel="stylesheet">
    <!-- Incluir otros archivos CSS que necesites -->
    <?=$this->include('includes/head/_head_login') ?>
</head>

<body class="login-page">
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Incluir los scripts al final del cuerpo del documento -->
    <!-- Scripts de Bootstrap (jQuery y Popper.js son necesarios para Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Incluir tu archivo de JavaScript personalizado -->
    <?=$this->include('includes/scripts/_scripts_login') ?>
</body>
</html>
