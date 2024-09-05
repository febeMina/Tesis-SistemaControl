<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Editar tipo de unidades</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <form action="<?= site_url('unidadesporcaja/update/' . $unidad['idUnidadesPorCaja']) ?>" method="post">
                        <div class="form-group" style="color: #000;">
                            <label for="tipo_unidad">Tipo de Unidad</label>
                            <input type="text" name="tipo_unidad" class="form-control" id="tipo_unidad" value="<?= $unidad['tipo_unidad'] ?>" required>
                        </div>
                        <div class="form-group" style="color: #000;">
                            <label for="unidades">Número de Unidades</label>
                            <input type="number" name="unidades" class="form-control" id="unidades" value="<?= $unidad['unidades'] ?>" required>
                        </div>
                        <div class="form-group" style="color: #000;">
                            <label for="estado">Estado</label>
                            <select name="estado" class="form-control" id="estado" required>
                                <option value="Activo" <?= $unidad['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= $unidad['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>