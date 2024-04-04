<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h3 class="text-center">Editar Maestro</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('maestros/update/'.$maestro->idDocente) ?>" method="post">
                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= $maestro->nombre_completo ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" value="<?= $maestro->nip ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="escalafon">Escalafón</label>
                            <input type="text" class="form-control" id="escalafon" name="escalafon" value="<?= $maestro->escalafon ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_ingreso">Fecha de Ingreso</label>
                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="<?= $maestro->fecha_ingreso ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo" <?= ($maestro->estado == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= ($maestro->estado == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualizar Maestro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
