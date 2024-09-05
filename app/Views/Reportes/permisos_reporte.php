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
                    <h4 class="header-title text-center">Reporte de Permisos del Personal</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Formulario de filtro -->
                    <form action="<?= site_url('/reporte') ?>" method="get" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="date" name="fecha_inicio" class="form-control" placeholder="Fecha de Inicio" value="<?= esc($filters['fecha_inicio'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="fecha_fin" class="form-control" placeholder="Fecha de Fin" value="<?= esc($filters['fecha_fin'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="<?= site_url('/reporte') ?>" class="btn btn-secondary ms-2">Limpiar</a>
                            <a href="<?= site_url('reporte-pdf/generar-reporte') ?>" class="btn btn-success">Generar Reporte</a>
                        </div>
                    </form>

                    <!-- Mostrar tabla solo si las fechas están presentes -->
                    <?php if (!empty($filters['fecha_inicio']) && !empty($filters['fecha_fin'])): ?>
                        <!-- Tabla de permisos -->
                        <div class="table-responsive">
                            <table class="table" style="color: #000;">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Fechas</th>
                                        <th>Tipos de permisos</th>
                                        <th>Días</th>
                                        <th>Horas</th>
                                        <th>Saldo Actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $permiso): ?>
                                        <?php
                                            // Calculando el número de días solicitados
                                            $fechaInicio = new DateTime($permiso['fechaInicio']);
                                            $fechaFin = new DateTime($permiso['fechaFin']);
                                            $intervalo = $fechaInicio->diff($fechaFin);
                                            $diasSolicitados = $intervalo->days; // Sumamos 1 para incluir el primer día
                                            $horasSolicitadas = $permiso['horasSolicitadas'] ?? '-';
                                            
                                            // Obtener saldo histórico de días y horas
                                            $saldoHistorialDias = $permiso['saldoHistorialDias'] ?? 0;
                                            $saldoHistorialHoras = $permiso['saldoHistorialHoras'] ?? 0;

                                            // Calculando el nuevo saldo
                                            $nuevoSaldoDias = $saldoHistorialDias - $diasSolicitados;
                                            $nuevoSaldoHoras = $saldoHistorialHoras - ($horasSolicitadas === '-' ? 0 : $horasSolicitadas);

                                            // Formatear saldo histórico en días para eliminar decimales
                                            $saldoHistorialDias = number_format($saldoHistorialDias, 0, '', '');
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($permiso['nombre_completo']) ?></strong><br>
                                                <small>NIP: <?= esc($permiso['nip']) ?></small>
                                            </td>
                                            <td>
                                                <strong>Inicio:</strong> <?= esc($permiso['fechaInicio']) ?><br>
                                                <strong>Fin:</strong> <?= esc($permiso['fechaFin']) ?>
                                            </td>
                                            <td>
                                                <?= esc($permiso['tipoPermisoNombre']) ?><br>
                                                <small>(<?= esc($permiso['cantidadDias'] ?? '0') ?> días)</small>
                                            </td>
                                            <td><?= esc($diasSolicitados) ?></td>
                                            <td><?= esc($horasSolicitadas) ?></td>
                                            <td>
                                                <ul class="list-unstyled mb-0">
                                                    <li><strong>Días:</strong> <?= esc($nuevoSaldoDias) ?></li>
                                                    <li><strong>Horas:</strong> <?= esc($nuevoSaldoHoras) ?></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Enlaces de paginación -->
                        <?php if ($pager): ?>
                            <?= $pager ?>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="alert alert-info">
                            Por favor, seleccione las fechas de inicio y fin para mostrar los resultados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
