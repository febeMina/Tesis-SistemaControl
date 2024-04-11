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
                            <i class="mdi mdi-plus"></i> Agregar<!-- Icono de Material Design Icons -->
                        </a>
                    </div>
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
                                <?php foreach ($maestros as $maestro) : ?>
                                    <tr>
                                        <td><?= $maestro->nombre_completo; ?></td>
                                        <td><?= $maestro->nip; ?></td>
                                        <td><?= $maestro->escalafon; ?></td>
                                        <td><?= $maestro->fecha_ingreso; ?></td>
                                        <td><?= $maestro->estado; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('maestros/edit/' . $maestro->idDocente) ?>" class="btn btn-warning">
                                                    <i class="mdi mdi-pencil"></i> <!-- Icono de Material Design Icons -->
                                                </a>
                                                <!-- Agregar margen entre los botones -->
                                                <a href="<?= site_url('maestros/delete/' . $maestro->idDocente) ?>" class="btn btn-danger ml-1">
                                                    <i class="mdi mdi-delete"></i> <!-- Icono de Material Design Icons -->
                                                </a>
                                                <!-- Fin de la separación -->
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
