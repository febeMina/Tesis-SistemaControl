<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 15px; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="header-title text-center">Nuevo ingreso de producto</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form action="<?= site_url('solicitudproductos/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="fechaIngreso" style="color: #000;">Fecha de ingreso</label>
                                    <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="responsableEntrega" style="color: #000;">Responsable de entrega</label>
                                    <input type="text" class="form-control" id="responsableEntrega" name="responsableEntrega" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="responsableRecibe" style="color: #000;">Responsable de recepción</label>
                                    <input type="text" class="form-control" id="responsableRecibe" name="responsableRecibe" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="<?= previous_url() ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>