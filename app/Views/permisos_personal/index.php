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
                    <h4 class="header-title text-center">Permisos del Personal</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Formulario de filtro -->
                    <form action="<?= site_url('/permisos_personal') ?>" method="get" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="nombreCompleto" class="form-control" placeholder="Nombre del empleado" value="<?= esc($filters['nombreCompleto'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="nip" class="form-control" placeholder="NIP" value="<?= esc($filters['nip'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="fecha_solicitud" class="form-control" placeholder="Fecha de Solicitud" value="<?= esc($filters['fechaCreacion'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="<?= site_url('/permisos_personal') ?>" class="btn btn-secondary ms-2">Limpiar</a>
                            <a href="<?= site_url('permisos_personal/create') ?>" class="btn btn-success">Agregar Permiso</a>
                        </div>
                    </form>

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
                                <?php foreach ($data as $item): ?>
                                    <?php
                                        $horasDias = 6;
                                        // Calculando el número de días solicitados
                                        $fechaInicio = new DateTime($item['fechaInicio']);
                                        $fechaFin = new DateTime($item['fechaFin']);
                                        $intervalo = $fechaInicio->diff($fechaFin);
                                        $diasSolicitados = $intervalo->days + 1; // Sumamos 1 para incluir el primer día
                                        $horasSolicitadas = $item['horasSolicitadas'] ?? $diasSolicitados * $horasDias;
                                        $conversionDias = $item['horasSolicitadas'] ?? $horasDias;
                                        $diasSolicitados = ($conversionDias / $horasDias) * $diasSolicitados;
                                        // Obtener saldo histórico de días y horas
                                        $saldoHistorialDias = $item['saldoHistorialDias'] ?? 0;
                                        $saldoHistorialHoras = $item['saldoHistorialHoras'] ?? 0;

                                        // Calculando el nuevo saldo
                                        $nuevoSaldoDias = $saldoHistorialDias - $diasSolicitados;
                                        $nuevoSaldoHoras = $saldoHistorialHoras - $horasSolicitadas;

                                        // Formatear saldo histórico en días para eliminar decimales
                                        $saldoHistorialDias = number_format($saldoHistorialDias, 0, '', '');
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($item['nombreCompleto']) ?></strong><br>
                                            <small>NIP: <?= esc($item['nip']) ?></small>
                                        </td>
                                        <td>
                                            <strong>Inicio:</strong> <?= esc($item['fechaInicio']) ?><br>
                                            <strong>Fin:</strong> <?= esc($item['fechaFin']) ?>
                                        </td>
                                        <td>
                                            <?= esc($item['tipoPermisoNombre']) ?><br>
                                            <small>(<?= esc($item['cantidadDias'] ?? '0') ?> días)</small>
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
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
