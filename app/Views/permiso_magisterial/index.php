<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Saldo de Permiso Magisterial</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Tabla de saldos de permiso -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nombre del Maestro</th>
                                    <th>Fecha de Solicitud</th>
                                    <?php foreach ($tipos_permisos as $tipo_permiso): ?>
                                        <th><?= $tipo_permiso['nombre'] ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($saldos_docentes as $saldo): ?>
                                    <tr>
                                        <td><?= $saldo['nip'] ?></td>
                                        <td><?= $saldo['nombre_completo'] ?></td>
                                        <td><?= $saldo['fecha_solicitud'] ?></td>
                                        <?php foreach ($tipos_permisos as $tipo_permiso): ?>
                                            <?php
                                            $dias_disponibles = 0;
                                            $dias_ocupados = 0;
                                            foreach ($saldo['detalle_saldos_permiso'] as $detalle_saldo) {
                                                if ($detalle_saldo['idTipoPermiso'] == $tipo_permiso['idTipoPermiso']) {
                                                    $dias_disponibles = isset($detalle_saldo['saldo']) ? $detalle_saldo['saldo'] : 0;
                                                    $dias_ocupados = isset($detalle_saldo['dias_ocupados']) ? $detalle_saldo['dias_ocupados'] : 0;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <td>
                                                <div>Días Ocupados: <?= $dias_ocupados ?></div>
                                                <div>Días Disponibles: <?= $dias_disponibles ?></div>
                                            </td>
                                        <?php endforeach; ?>
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
