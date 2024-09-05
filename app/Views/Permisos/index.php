<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Permiso Magisterial</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= site_url('permiso_magisterial/index') ?>" method="get">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre del Maestro" value="<?= esc($filters['nombre_completo'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="nip" class="form-control" placeholder="NIP" value="<?= esc($filters['nip'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="fecha_solicitud" class="form-control" placeholder="Fecha de Solicitud" value="<?= esc($filters['fecha_solicitud'] ?? '') ?>">
                            </div>
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                                    <a href="<?= site_url('permiso_magisterial/index') ?>" class="btn btn-secondary mt-3 ms-2">Limpiar</a>
                                </div>
                                <a href="<?= site_url('permiso_magisterial/create') ?>" class="btn btn-success mt-3">Agregar Permiso</a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-sm" style="color: #000; font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nombre del Maestro</th>
                                    <th>Fecha de Solicitud</th>
                                    <th>Detalles de Permisos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historial_permisos as $permiso): ?>
                                    <tr>
                                        <td><?= esc($permiso['nip']) ?></td>
                                        <td><?= esc($permiso['nombre_completo']) ?></td>
                                        <td><?= esc($permiso['fecha_creacion']) ?></td>
                                        <td>
                                            <?php if (isset($permiso['detalle_saldos_permiso']) && is_array($permiso['detalle_saldos_permiso']) && !empty($permiso['detalle_saldos_permiso'])): ?>
                                                <table class="table table-bordered table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo Permiso</th>
                                                            <th>Fecha Inicio</th>
                                                            <th>Fecha Fin</th>
                                                            <th>Días Ocupados</th>
                                                            <th>Días Disponibles</th>
                                                            <th>Horas Ocupadas</th>
                                                            <th>Horas Disponibles</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($permiso['detalle_saldos_permiso'] as $detalle): ?>
                                                            <?php
                                                            // Calcula los días disponibles y horas disponibles
                                                            $dias_disponibles = ($detalle['cantidadDias'] ?? 0) - ($detalle['dias_ocupados'] ?? 0);
                                                            $horas_disponibles = ($detalle['cantidad_horas'] ?? 0) - ($detalle['horas_ocupadas'] ?? 0);
                                                            ?>
                                                            <tr>
                                                                <td><?= esc($detalle['nombreTipoPermiso'] ?? 'Desconocido') ?> (<?= esc($detalle['cantidadDias'] ?? 'Desconocido') ?> días)</td>
                                                                <td><?= esc($detalle['fecha_inicio'] ?? 'Desconocida') ?></td>
                                                                <td><?= esc($detalle['fecha_fin'] ?? 'Desconocida') ?></td>
                                                                <td><?= esc($detalle['dias_ocupados'] ?? 0) ?></td>
                                                                <td><?= esc($dias_disponibles) ?></td>
                                                                <td><?= esc($detalle['horas_ocupadas'] ?? 0) ?></td>
                                                                <td><?= esc($horas_disponibles) ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>

                                            <?php else: ?>
                                                <p>No hay detalles de permisos disponibles.</p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Enlaces de paginación -->
                    <div class="d-flex justify-content-center">
                        <?= $pager->links('group1', 'bootstrap_pagination') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
