<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de Tipos de Permiso</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
                    <div class="mb-3">
                        <a href="<?= site_url('tipo_permiso/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cantidad de Días</th>
                                    <th>Estado</th> <!-- Nuevo campo de Estado -->
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($tipos_permisos as $tipo_permiso) : ?>
                                <tr>
                                    <td><?= $tipo_permiso['nombre']; ?></td>
                                    <td><?= $tipo_permiso['cantidadDias']; ?></td>
                                    <td><?= $tipo_permiso['estado'] == 'Activo' ? 'Activo' : 'Inactivo'; ?></td>

                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= site_url('tipo_permiso/edit/' . $tipo_permiso['idTipoPermiso']) ?>" class="btn btn-edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <a href="<?= site_url('tipo_permiso/delete/' . $tipo_permiso['idTipoPermiso']) ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de eliminar este tipo de permiso?');">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
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
