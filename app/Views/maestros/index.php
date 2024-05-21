<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<!-- Contenido de la página -->
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de Maestros</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Botón para agregar un nuevo maestro -->
                    <div class="mb-3">
                        <a href="<?= site_url('maestros/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> <!-- Icono de Material Design Icons -->
                        </a>
                    </div>
                    <!-- Formulario de filtros -->
                    <form action="<?= site_url('maestros/index') ?>" method="get">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre Completo">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="nip" class="form-control" placeholder="NIP">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="escalafon" class="form-control" placeholder="Escalafón">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="fecha_ingreso" class="form-control" placeholder="Fecha de Ingreso">
                            </div>
                            <div class="col-md-2">
                                <select name="estado" class="form-control">
                                    <option value="">Estado</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-start">
                                <button type="submit" class="btn btn-primary me-2 btn-icon">
                                    <i class="mdi mdi-filter"></i> <!-- Icono de Material Design Icons -->
                                </button>
                                <a href="<?= site_url('maestros/index') ?>" class="btn btn-secondary btn-icon">
                                    <i class="mdi mdi-close"></i> <!-- Icono de Material Design Icons -->
                                </a>
                            </div>
                        </div>
                    </form>
                    <!-- Tabla de maestros -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>NIP</th>
                                    <th>Escalafón</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($maestros) && !empty($maestros)): ?>
                                <?php foreach ($maestros as $maestro) : ?>
                                    <tr>
                                        <td><?= isset($maestro['nombre_completo']) ? esc($maestro['nombre_completo']) : ''; ?></td>
                                        <td><?= isset($maestro['nip']) ? esc($maestro['nip']) : ''; ?></td>
                                        <td><?= isset($maestro['escalafon']) ? esc($maestro['escalafon']) : ''; ?></td>
                                        <td><?= isset($maestro['fecha_ingreso']) ? esc($maestro['fecha_ingreso']) : ''; ?></td>
                                        <td><?= isset($maestro['estado']) ? esc($maestro['estado']) : ''; ?></td>
                                        <td>
                                            <?php if (isset($maestro['idDocente'])): ?>
                                                <div class="btn-group">
                                                    <a href="<?= site_url('maestros/edit/' . esc($maestro['idDocente'])) ?>" class="btn btn-edit">
                                                        <i class="mdi mdi-pencil"></i> <!-- Icono de Material Design Icons -->
                                                    </a>
                                                    <a href="<?= site_url('maestros/delete/' . esc($maestro['idDocente'])) ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de que desea eliminar este maestro?');">
                                                        <i class="mdi mdi-delete"></i> <!-- Icono de Material Design Icons -->
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No se encontraron maestros.</td>
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
