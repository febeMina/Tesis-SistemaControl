<?= $this->extend('layouts/loginUser') ?>

<?= $this->section('content') ?>

<div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="row w-100 mx-0 custom-container">
                    <div class="col-lg-4 mx-auto custom-form">
                        <div class="logo-container d-flex justify-content-center mb-3">
                            <img src="<?= base_url('public/assets/images/img_cebd/logo.cebd.jpg') ?>" class="rounded-logo">
                        </div>
                        <h2 class="custom-title">CENTRO ESCOLAR BARRIO LAS DELICIAS</h2>
                        <h3 class="small-title">San Salvador, Mejicanos</h3>
                        <!-- Formulario de inicio de sesión -->
                            <form  class="pt-3">
                                <div class="form-group">
                                    <label for="username">Usuario</label>
                                    <input type="text" class="form-control form-control-lg" name="username" id="username" placeholder="Usuario" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Contraseña" required>
                                </div>
                                <div class="mt-3">
                                    <button id="loginBtn" type="button" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">INICIAR SESIÓN</button>
                                </div>
                            </form>

                    </div>
                </div>
        </div>
    </div>

<script src="<?= base_url('public/assets/js/login/form/form.js') ?>"></script>
<?= $this->endSection() ?>