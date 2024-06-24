<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de Proyectos Institucionales</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <!-- Botón para agregar un nuevo tipo de producto -->
                    <div class="mb-3">
                        <a href="<?= site_url('proyectos/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar Proyecto
                        </a>
                    </div>
                    <!-- Tabla de tipos de producto -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre de Proyecto</th>
                                    <th>Descripción</th>
                                    <th>Meta Economica</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proyectos as $proyect) : ?>
                                    <tr>
                                        <td><?= $proyect->nombreProyecto; ?></td>
                                        <td><?= $proyect->descripcion; ?></td>
                                        <td><?= $proyect->estado; ?></td> 
                                        <td><?= $proyect->meta; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('proyecto/edit/' . $proyect->idProyectos ) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i> <!-- Icono de edición -->
                                                </a>
                                                <a href="<?= site_url('proyecto/delete/' . $proyect->idProyectos) ?>" class="btn btn-delete">
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