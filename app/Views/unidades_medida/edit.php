<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center">Editar Unidad de Medida</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0;">
                    <?= form_open('unidadesmedida/update/' . $unidad['idUnidadesMedida']) ?>
                    <div class="form-group">
                        <label for="nombre" style="color: #000;">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $unidad['nombre'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="abreviatura" style="color: #000;">Abreviatura</label>
                        <input type="text" class="form-control" id="abreviatura" name="abreviatura" value="<?= $unidad['abreviatura'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="descripción" style="color: #000;">Descripción</label>
                        <textarea class="form-control" id="descripción" name="descripción" rows="3"><?= $unidad['descripción'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="estado" style="color: #000;">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="Activo" <?= $unidad['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="Inactivo" <?= $unidad['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>