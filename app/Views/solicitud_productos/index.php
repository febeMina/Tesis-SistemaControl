<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #090066; border-radius: 15px;">
                    <h3 class="text-center text-white">Listado de Solicitudes de Productos</h3>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">

                    <?php if (session()->get('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->get('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->get('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->get('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('solicitudproductos/index') ?>" method="get">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" name="producto_nombre" class="form-control" placeholder="Nombre del Producto" value="<?= isset($filters['producto_nombre']) ? esc($filters['producto_nombre']) : '' ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="producto_descripcion" class="form-control" placeholder="Descripción del Producto" value="<?= isset($filters['producto_descripcion']) ? esc($filters['producto_descripcion']) : '' ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="producto_fecha_vencimiento" class="form-control" placeholder="Fecha de Vencimiento" value="<?= isset($filters['producto_fecha_vencimiento']) ? esc($filters['producto_fecha_vencimiento']) : '' ?>">
                            </div>
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                                    <a href="<?= site_url('solicitudproductos/index') ?>" class="btn btn-secondary mt-3 ms-2">Limpiar</a>
                                </div>
                                <a href="<?= site_url('solicitudproductos/create') ?>" class="btn btn-success mt-3">Crear Nueva Solicitud</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table" style="color: #000; font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th>ID Solicitud</th>
                                    <th>Fecha de Solicitud</th>
                                    <th>Comida a Preparar</th>
                                    <th>Producto</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Cantidad</th>
                                    <th>Responsable de Entrega</th>
                                    <th>Responsable de Recibir</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitudes as $solicitud): ?>
                                    <tr>
                                        <td><?= esc($solicitud['idSolicitudProductos']) ?></td>
                                        <td><?= esc($solicitud['Fecha_solicitud']) ?></td>
                                        <td><?= isset($solicitud['Comida_a_preparar']) ? esc($solicitud['Comida_a_preparar']) : 'N/A' ?></td>
                                        <td><?= esc($solicitud['producto_nombre']) ?> (ID: <?= esc($solicitud['idProducto']) ?>)</td>
                                        <td><?= esc($solicitud['fecha_vencimiento']) ?></td>
                                        <td><?= esc($solicitud['cantidad']) ?></td>
                                        <td><?= esc($solicitud['responsable_entrega']) ?></td>
                                        <td><?= esc($solicitud['responsable_recibir']) ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Acciones">
                                                <a href="<?= site_url('solicitudproductos/edit/' . $solicitud['idSolicitudProductos']) ?>" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="<?= site_url('solicitudproductos/delete/' . $solicitud['idSolicitudProductos']) ?>" class="btn btn-delete" onclick="return confirm('¿Está seguro de eliminar esta solicitud?');">
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

<style>
    .card-header h3 {
        color: #ffffff;
    }
    .card-body {
        color: #000000;
    }
    .btn-primary {
        color: #ffffff;
    }
    .btn-success {
        color: #ffffff;
    }
    .btn-edit {
        color: #090066;
        border-radius: 5px;
        margin-right: 5px;
    }
    .btn-delete {
        color: red;
        border-radius: 5px;
    }
</style>
