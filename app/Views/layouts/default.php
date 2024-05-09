<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CEBD</title>
    <!-- plugins:css -->
    <?=$this->include('includes/head/_head') ?>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        <?=$this->include('includes/navbar/_navbar') ?>

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            <?=$this->include('includes/navbar/_header') ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper" style="background-color: #f0f0f0 !important;">
                             <!-- Botón para regresar -->
                    <div class="mb-3">
                        <a href="<?= previous_url() ?>" class="btn btn-secondary" style="border-radius: 10px; padding: 10px 20px;">
                            <i class="mdi mdi-arrow-left"></i> Regresar
                        </a>
                    </div>
                    
                    <?= $this->renderSection('content') ?>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <?=$this->include('includes/footer/_footer') ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- scripts -->
    <?=$this->include('includes/scripts/_scripts') ?>
</body>

</html>