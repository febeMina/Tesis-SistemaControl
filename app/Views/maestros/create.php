<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="mt-4">Agregar Maestro</h1>
    <div class="card mb-4">
        <div class="card-body">
            <form action="/maestros/store" method="post">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo">
                </div>
                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" class="form-control" id="nip" name="nip">
                </div>
                <div class="form-group">
                    <label for="escalafon">Escalafón</label>
                    <input type="text" class="form-control" id="escalafon" name="escalafon">
                </div>
                <div class="form-group">
                    <label for="fecha_ingreso">Fecha de Ingreso</label>
                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control" id="estado" name="estado">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
