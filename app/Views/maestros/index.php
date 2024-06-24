<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->has('inactive')): ?>
        <div class="alert alert-warning">
            <?= session('inactive') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Personal magisterial</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Formulario de filtros -->
                    <form action="<?= site_url('maestros/index') ?>" method="get">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre Completo">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="nip" class="form-control" placeholder="NIP">
                            </div>
                            <div class="col-md-4">
                                <select name="tipo" class="form-control">
                                    <option value="">Tipo</option>
                                    <option value="Docente">Docente</option>
                                    <option value="Administrativo">Administrativo</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Botones de acciones -->
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <div>
                                <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                                <a href="<?= site_url('maestros/index') ?>" class="btn btn-secondary mt-3 ms-2"> Limpiar</a>
                            </div>
                            <div>
                                <a href="<?= site_url('maestros/create') ?>" class="btn btn-primary">
                                    <i class="mdi mdi-plus"> Agregar</i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabla de maestros -->
                    <div class="table-responsive mt-3">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>NIP</th>
                                    <th>Escalafón</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Estado</th>
                                    <th>Tipo</th>
                                    <th>Cargo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($maestros) && !empty($maestros)): ?>
                                    <?php foreach ($maestros as $maestro) : ?>
                                        <tr>
                                            <td><?= esc($maestro['nombre_completo']) ?></td>
                                            <td><?= esc($maestro['nip']) ?></td>
                                            <td><?= esc($maestro['escalafon']) ?></td>
                                            <td><?= esc($maestro['fecha_ingreso']) ?></td>
                                            <td><?= esc($maestro['estado']) ?></td>
                                            <td><?= esc($maestro['tipo']) ?></td>
                                            <td><?= esc($maestro['cargo']) ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= site_url('maestros/edit/' . esc($maestro['idDocente'])) ?>" class="btn btn-edit">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <a href="<?= site_url('maestros/delete/' . esc($maestro['idDocente'])) ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de que desea marcar este maestro como inactivo?');">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No se encontraron maestros.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
