<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de Unidades de Medida</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Botón para agregar una nueva unidad de medida -->
                    <div class="mb-3">
                        <a href="<?= site_url('unidadesmedida/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar Unidad de Medida
                        </a>
                    </div>
                    <!-- Tabla de unidades de medida -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Abreviatura</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unidades_medida as $unidad) : ?>
                                    <tr>
                                        <td><?= $unidad['nombre'] ?></td>
                                        <td><?= $unidad['abreviatura'] ?></td>
                                        <td><?= $unidad['descripción'] ?></td>
                                        <td><?= $unidad['estado'] ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('unidadesmedida/edit/' . $unidad['idUnidadesMedida']) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i> <!-- Icono de edición -->
                                                </a>
                                                <a href="<?= site_url('unidadesmedida/delete/' . $unidad['idUnidadesMedida']) ?>" class="btn btn-delete">
                                                    <i class="mdi mdi-delete"></i> <!-- Icono de eliminación -->
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