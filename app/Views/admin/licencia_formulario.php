<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?= isset($licencia) ? 'Editar Licencia' : 'Crear Nueva Licencia' ?></h1>
            <form action="<?= isset($licencia) ? base_url('admin/licencias/edit/' . $licencia['id']) : base_url('admin/licencias/store') ?>" method="post">
                <div class="form-group">
                    <label for="numero">No.</label>
                    <input type="text" class="form-control" id="numero" name="numero" value="<?= isset($licencia) ? $licencia['numero'] : '' ?>">
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre de Licencia</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= isset($licencia) ? $licencia['nombre'] : '' ?>">
                </div>
                <div class="form-group">
                    <label for="dias">Días</label>
                    <input type="text" class="form-control" id="dias" name="dias" value="<?= isset($licencia) ? $licencia['dias'] : '' ?>">
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <input type="text" class="form-control" id="estado" name="estado" value="<?= isset($licencia) ? $licencia['estado'] : '' ?>">
                </div>
                <button type="submit" class="btn btn-primary"><?= isset($licencia) ? 'Actualizar' : 'Guardar' ?></button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
