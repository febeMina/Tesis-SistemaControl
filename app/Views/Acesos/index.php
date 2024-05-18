<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<!-- Contenido de la página -->
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de Tipo accesos</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>

                    <?php endif; ?>
                    <!-- Botón para agregar un nuevo tipo de permiso -->
                    <div class="mb-3">
                        <a href="<?= site_url(' ') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar <!-- Icono de Material Design Icons -->
                        </a>
                    </div>
                    <!-- Tabla de tipos de permiso -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($accesos  as $acceso) : ?>
    <tr>
        <td><?= $acceso->usuario; ?></td>
        <td><?= $acceso->idRol; ?></td>
        <td>
            <div class="btn-group">
                <a href="<?= site_url('' . $acceso->idUsuarios) ?>"class="btn btn-edit">
                    <i class="mdi mdi-pencil"></i> <!-- Icono de Material Design Icons -->
                </a>
                <!-- Agregar margen entre los botones -->
                <a href="<?= site_url('' . $acceso->idUsuarios) ?>" class="btn btn-delete">
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
