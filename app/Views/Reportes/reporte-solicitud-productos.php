<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <!-- Mensaje de éxito, error o advertencia -->
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
                    <h4 class="header-title text-center">Reporte de Solicitudes de Productos</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Formulario de filtro -->
                    <form action="<?= site_url('solicitudproductos/reporteS') ?>" method="get" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="date" name="fecha_solicitud" class="form-control" placeholder="Fecha de Solicitud" value="<?= esc($filters['fecha_solicitud'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="<?= site_url('solicitudproductos/reporteS') ?>" class="btn btn-secondary ms-2">Limpiar</a>
                            <!-- Botón para generar el PDF -->
                            <?php if (!empty($filters['fecha_solicitud'])): ?>
                                <a href="<?= site_url('reporte-pdf/generar-reporte-solicitud-productos') ?>?fecha_solicitud=<?= esc($filters['fecha_solicitud']) ?>" class="btn btn-success">Generar Reporte PDF</a>

                            <?php endif; ?>
                        </div>
                    </form>

                    <!-- Mostrar tabla solo si la fecha está presente -->
                    <?php if (!empty($filters['fecha_solicitud'])): ?>
                        <!-- Tabla de solicitudes -->
                        <div class="table-responsive">
                            <table class="table" style="color: #000;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Comida a Preparar</th>
                                        <th>Responsable Entrega</th>
                                        <th>Responsable Recibir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($solicitudes as $solicitud): ?>
                                        <tr>
                                            <td><?= esc($solicitud['idSolicitudProductos']) ?></td>
                                            <td><?= esc($solicitud['Fecha_solicitud']) ?></td>
                                            <td><?= esc($solicitud['Comida_a_preparar']) ?></td>
                                            <td><?= esc($solicitud['responsable_entrega']) ?></td>
                                            <td><?= esc($solicitud['responsable_recibir']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Enlaces de paginación -->
                        <?php if ($pager): ?>
                            <?= $pager->links() ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Por favor, seleccione una fecha de solicitud para mostrar los resultados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
