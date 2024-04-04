<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<!-- Contenido de la página -->
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066 !important;">
                    <h4 class="header-title text-center">Listado de Maestros</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Botón para agregar un nuevo maestro -->
                    <div class="mb-3">
                        <a href="<?= site_url('maestros/create') ?>" class="btn btn-primary">Agregar Nuevo Maestro</a>
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
                                <?php foreach($maestros as $maestro): ?>
                                <tr>
                                    <td><?= $maestro->nombre_completo; ?></td>
                                    <td><?= $maestro->nip; ?></td>
                                    <td><?= $maestro->escalafon; ?></td>
                                    <td><?= $maestro->fecha_ingreso; ?></td>
                                    <td><?= $maestro->estado; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= site_url('maestros/edit/'.$maestro->idDocente) ?>" class="btn btn-warning">
                                                <i class="fa fa-pencil"></i> Editar
                                            </a>
                                            <a href="<?= site_url('maestros/delete/'.$maestro->idDocente) ?>" class="btn btn-danger">
                                                <i class="fa fa-trash"></i> Eliminar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
