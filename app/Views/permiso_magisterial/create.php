<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Crear Permiso Magisterial</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($validation)): ?>
                        <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                    <?php endif; ?>

                    <form action="<?= site_url('permiso_magisterial/store') ?>" method="post">
                        <div class="form-group">
                            <label for="id_maestro" style="color: #000;"><i class="fas fa-chalkboard-teacher"></i> Maestro</label>
                            <select name="id_maestro" id="id_maestro" class="form-control">
                                <?php foreach ($maestros as $maestro): ?>
                                    <option value="<?= $maestro['idDocente'] ?>"><?= $maestro['nombre_completo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_tipo_permiso" style="color: #000;"><i class="fas fa-clipboard"></i> Tipo de Permiso</label>
                            <select name="id_tipo_permiso" id="id_tipo_permiso" class="form-control">
                                <?php foreach ($tipos_permisos as $tipo_permiso): ?>
                                    <option value="<?= $tipo_permiso['idTipoPermiso'] ?>"><?= $tipo_permiso['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_inicio" style="color: #000;"><i class="fas fa-calendar-alt"></i> Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="fecha_fin" style="color: #000;"><i class="fas fa-calendar-alt"></i> Fecha de Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
