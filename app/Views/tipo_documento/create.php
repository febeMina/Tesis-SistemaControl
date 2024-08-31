<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066;">
                    <h4 class="header-title">Crear Nuevo Tipo de Documento</h4>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('public/tipo-documento/store') ?>" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Tipo de Documento</label>
                            <input type="text" name="nombre" class="form-control" id="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="mascara" class="form-label">Máscara</label>
                            <input type="text" name="mascara" class="form-control" id="mascara" required>
                            <small class="form-text text-muted">Ejemplo de máscara: ####-####-#### (para un formato de 4-4-4 dígitos).</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="<?= base_url('tipo-documento') ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
