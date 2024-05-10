<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Listado de Tipos de Producto</h4>
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
                        <a href="<?= site_url('tipo_producto/create') ?>" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Agregar Tipo de Producto
                        </a>
                    </div>
                    <!-- Tabla de tipos de producto -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tiposProducto as $tipoProducto) : ?>
                                    <tr>
                                        <td><?= $tipoProducto['nombre']; ?></td>
                                        <td><?= $tipoProducto['descripcion']; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= site_url('tipo_producto/edit/' . $tipoProducto['idtipoProducto']) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i> <!-- Icono de edición -->
                                                </a>
                                                <a href="<?= site_url('tipo_producto/delete/' . $tipoProducto['idtipoProducto']) ?>" class="btn btn-delete">
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