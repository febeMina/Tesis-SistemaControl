<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center">Editar Unidades de Medida Individual</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <?= form_open('unidadesindividuales/update/' . $unidad['idUnidades_individuales']) ?>
                    <div class="form-group">
                        <label for="unidadades_individuales" style="color: #000;">Nombre</label>
                        <input type="text" class="form-control" id="unidadades_individuales" name="unidadades_individuales" value="<?= $unidad['unidadades_individuales'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="estado" style="color: #000;">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="Activo" <?= $unidad['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="Inactivo" <?= $unidad['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>