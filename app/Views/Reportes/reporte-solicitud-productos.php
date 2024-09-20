<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <!-- Mensajes de éxito, error o advertencia -->
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
                    <!-- Formulario de filtro por fecha de requisición -->
                    <form action="<?= site_url('solicitudproductos/reporteS') ?>" method="get" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="date" name="fecha_solicitud" class="form-control" placeholder="Fecha de Requisición" value="<?= esc($filters['fecha_solicitud'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="<?= site_url('solicitudproductos/reporteS') ?>" class="btn btn-secondary ms-2">Limpiar</a>
                            <!-- Botón para generar el PDF si hay una fecha seleccionada -->
                            <?php if (!empty($filters['fecha_solicitud'])): ?>
                                <a href="<?= site_url('reporte-pdf/generar-reporte-solicitud-productos') ?>?fecha_solicitud=<?= esc($filters['fecha_solicitud']) ?>" class="btn btn-success">Generar Reporte PDF</a>
                            <?php endif; ?>
                        </div>
                    </form>

                    <!-- Mostrar tabla solo si se selecciona una fecha -->
                    <?php if (!empty($filters['fecha_solicitud'])): ?>
                        <div class="table-responsive">
                            <table class="table" style="color: #000;">
                                <thead>
                                    <tr>
                                        <th>Fecha de Requisición</th>
                                        <th>Comida a Preparar</th>
                                        <th>Responsable de Entrega</th>
                                        <th>Responsable de Recepción</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($solicitudes as $solicitud): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime(esc($solicitud['fechaRequisicion']))) ?></td>
                                            <td><?= esc($solicitud['comidaPreparar']) ?></td>
                                            <td><?= esc($solicitud['responsableEntrega']) ?></td>
                                            <td><?= esc($solicitud['responsableRecibe']) ?></td>
                                            <td><?= esc($solicitud['estado']) ?></td>
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
                            Por favor, seleccione una fecha de requisición para mostrar los resultados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
