
<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reporte de Permisos Magisteriales</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('reportes/generate_report') ?>" method="post">
                        <div class="mb-3">
                            <label for="id_maestro" class="form-label">Seleccionar Maestro</label>
                            <select name="id_maestro" class="form-control">
                                <option value="">Seleccionar Maestro</option>
                                <?php foreach ($maestros as $maestro): ?>
                                    <option value="<?= $maestro['id_maestro'] ?>"><?= $maestro['nombre_completo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_tipo_permiso" class="form-label">Seleccionar Tipo de Permiso</label>
                            <select name="id_tipo_permiso" class="form-control">
                                <option value="">Seleccionar Tipo de Permiso</option>
                                <?php foreach ($tipos_permisos as $tipo_permiso): ?>
                                    <option value="<?= $tipo_permiso['id_tipo_permiso'] ?>"><?= $tipo_permiso['nombre_tipo_permiso'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Generar Reporte</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
